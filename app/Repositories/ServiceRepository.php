<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Service;
use App\Models\ServiceMaster;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ServiceRepository
{
    public function listPaginated($request)
    {
        $query = Service::query();

        // 🔍 Фильтрация по локали
        if (!empty($request['locale'])) {
            $locale = $request['locale'];
            $query->with(['translations' => function ($q) use ($locale) {
                $q->where('locale', $locale);
            }]);
        }

        // 🔍 Фильтрация по имени
        if (!empty($request['name'])) {
            $query->whereHas('translations', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request['name'] . '%');
            });
        }

        // 🔍 Фильтрация по статусу
        if (isset($request['status']) && $request['status'] !== '') {
            $query->where('status', $request['status']);
        }

        // 🔃 Сортировка
        $sortField = $request['sort_field'] ?? 'id';
        $sortOrder = $request['sort_order'] ?? 'desc';

        // Защита от SQL-инъекций — разрешаем сортировку только по определённым полям
        $allowedSortFields = ['id', 'price', 'status', 'created_at'];
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortOrder);
        }

        // 📄 Пагинация
        $perPage = $request['perPage'] ?? 10;

        return $query->paginate($perPage);
    }

    public function getAll($locale): \Illuminate\Database\Eloquent\Collection
    {
        $data = Service::with(['translations' => function ($query) use ($locale) {
            $query->where('locale', $locale);
        }])->orderByDesc('id')->get();
        return $data;
    }

    public function listFiltered($locale, $request): \Illuminate\Pagination\LengthAwarePaginator
    {
        $query = Service::with(['translations' => function ($query) use ($locale) {
            $query->whereIn('locale', [$locale, 'en'])
                  ->orderByRaw("FIELD(locale, '{$locale}', 'en')")
                  ->limit(1);
        }]);

        // ===== Фильтры =====

        // Статус
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        // Категория
        if ($request->has('category_id')) {
            $categoryId = $request->input('category_id');
            // Если это родительская категория, ищем все дочерние
            $category = Category::with('children')->find($categoryId);
            if ($category) {
                $allCategoryIds = [$category->id];
                if ($category->children) {
                    $allCategoryIds = array_merge($allCategoryIds, $category->children->pluck('id')->toArray());
                }
                $query->whereIn('category_id', $allCategoryIds);
            }
        }

        // Поиск по имени
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('translations', function ($q) use ($name, $locale) {
                $q->where('locale', $locale)
                ->where('name', 'like', '%' . $name . '%');
            });
        }

        // Фильтр по фиксированному времени
        if ($request->has('has_fixed_time')) {
            $hasFixedTime = $request->input('has_fixed_time');
            $query->where('has_fixed_time', $hasFixedTime);
        }

        // ===== Сортировка =====
        $sortField = $request->input('sortField', 'id'); // По умолчанию сортируем по ID
        $sortOrder = $request->input('sortOrder', 'desc'); // desc или asc

        if (in_array($sortOrder, ['asc', 'desc'])) {
            // Защита от SQL-инъекций: разрешить сортировку только по известным полям
            $allowedSortFields = ['id', 'price', 'created_at', 'name'];
            if (in_array($sortField, $allowedSortFields)) {
                if ($sortField === 'name') {
                    // Сортировка по имени из translations
                    $query->join('service_translations as st', 'st.service_id', '=', 'services.id')
                        ->where('st.locale', $locale)
                        ->orderBy('st.name', $sortOrder)
                        ->select('services.*');
                } else {
                    $query->orderBy($sortField, $sortOrder);
                }
            }
        }

        // ===== Пагинация =====
        $perPage = $request->input('pageSize', 10);
        $page = $request->input('page', 1);

        $services = $query->paginate($perPage, ['*'], 'page', $page);
        return $services;
    }


    public function find($id, $locale)
    {
        return Service::with(['translations' => function ($query) use ($locale) {
            $query->whereIn('locale', [$locale, 'en'])
                  ->orderByRaw("FIELD(locale, '{$locale}', 'en')")
                  ->limit(1);
        }, 'translationsAll', 'serviceMasters'])->findOrFail($id);
    }
    public function getStat()
    {
        $stats = [];

        $stats['total_count'] = Service::count();
        $stats['active_count'] = Service::where('status', 1)->count();
        $stats['inactive_count'] = Service::where('status', 0)->count();

        $stats['has_fixed_time_count'] = Service::where('has_fixed_time', 1)->count();
        $stats['without_fixed_time_count'] = Service::where('has_fixed_time', 0)->count();

        // Цена
        $stats['price_min'] = Service::min('price');
        $stats['price_max'] = Service::max('price');
        $stats['price_avg'] = (int) Service::avg('price');

        // Длительность
        $stats['duration_min'] = Service::min('duration_minutes');
        $stats['duration_max'] = Service::max('duration_minutes');
        $stats['duration_avg'] = (int) Service::avg('duration_minutes');

        // Дополнительно: распределение по времени (например, до обеда / после обеда)
        $stats['morning_count'] = Service::whereTime('time_from', '<', '12:00')->count();
        $stats['afternoon_count'] = Service::whereTime('time_from', '>=', '12:00')->count();

        return $stats;
    }

    public function create(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            $service = Service::create($data);
            if (isset($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    $service->translations()->updateOrCreate(
                        ['locale' => $translation['locale']],
                        [
                            'name' => $translation['name'],
                            'short_description' => $translation['short_description'] ?? null,
                            'full_description' => $translation['full_description'] ?? null
                        ]
                    );
                }
            }
            return $service;
        });
    }

    public function update(Service $service, array $data): Service
    {
        return DB::transaction(function () use ($service, $data) {
            $service->update($data);
            if (isset($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    $service->translations()->updateOrCreate(
                        ['locale' => $translation['locale']],
                        [
                            'name' => $translation['name'],
                            'short_description' => $translation['short_description'] ?? null,
                            'full_description' => $translation['full_description'] ?? null
                        ]
                    );
                }
            }
            return $service;
        });
    }

    public function toggleStatus(Service $service): Service
    {
        return DB::transaction(function () use ($service) {
            $service->status = !$service->status;
            $service->save();
            return $service;
        });
    }
    public function delete(Service $service): bool
    {
        return DB::transaction(function () use ($service) {
            $service->translations()->delete();
            $service->serviceMasters()->delete();
            return $service->delete();
        });
    }

    public function addMaster(Service $service, int $masterId): Service
    {
        return DB::transaction(function () use ($service, $masterId) {
            if (!$service->serviceMasters()->where('user_id', $masterId)->exists()) {
                $service->serviceMasters()->create(['user_id' => $masterId]);
            }
            return $service;
        });
    }

    public function removeMaster(Service $service, int $masterId): Service
    {
        return DB::transaction(function() use ($service, $masterId) {
            try {
                $serviceMaster = ServiceMaster::where('service_id', $service->id)
                    ->where('user_id', $masterId)
                    ->firstOrFail();
    
                $serviceMaster->delete();
    
                return $service->fresh(['serviceMasters']); // Возвращаем обновленную услугу с мастерами
            } catch (ModelNotFoundException $e) {
                throw new \Exception('Master is not associated with this service', 404);
            }
        });
    }
}
