<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\EventSeo;
use App\Models\EventSeoTranslation;
use App\Models\Language;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class EventService
{
    protected $eventRepository;

    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getAll($request)
    {
        return $this->eventRepository->getAll($request);
    }

    public function getActivated($request)
    {
        return $this->eventRepository->getActivated($request);
    }

    public function getCalendarEvents($request)
    {
        return $this->eventRepository->getCalendarEvents($request);
    }

    public function getById($id)
    {
        return $this->eventRepository->getById($id);
    }

    public function getStat()
    {
        return $this->eventRepository->getStat();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $defaultLangId = Language::where('is_default', true)->first()->id ?? 1;
            $defaultTranslation = collect($data['translations'])->firstWhere('language_id', $defaultLangId);
            $defaultTitle = $defaultTranslation['title'] ?? null;

            if ($data['slug'] === null) {
                $data['slug'] = Str::slug($defaultTitle ?? '');
            }
            if (Event::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $data['slug'] . '-' . mt_rand(100000, 999999);
            }
            if (isset($data['status']) && $data['status'] === 'published') {
                $data['published_at'] = now();
            }
            $event = Event::create($data);
            
            if (isset($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    EventTranslation::create(array_merge($translation, ['event_id' => $event->id]));
                }
            }

            // Создаем SEO
            if (isset($data['seo'])) {
                $eventSeo = EventSeo::create(['event_id' => $event->id]);
                
                foreach ($data['seo'] as $seoTranslation) {
                    EventSeoTranslation::create(array_merge($seoTranslation, ['event_seo_id' => $eventSeo->id]));
                }
            }

            return $event->load(['translations', 'seo.translations', 'gallery']);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $event = Event::findOrFail($id);

            // 1) Обновляем только поля самой таблицы events
            $eventFields = Arr::only($data, [
                'slug', 'status', 'image',
                'max_participants', 'price',
                'start_time', 'end_time', 'registration_deadline',
                'updated_at',
                // 'published_at' — если обновляешь
            ]);
            if (isset($data['status']) && $data['status'] === 'published') {
                $eventFields['published_at'] = now();
            }
            $event->update($eventFields);

            // 2) Переводы
            if (!empty($data['translations']) && is_array($data['translations'])) {
                foreach ($data['translations'] as $t) {
                    // ключи
                    $keys = [
                        'event_id'    => $event->id,
                        'language_id' => $t['language_id'],
                    ];
                    // значения (без идентификаторов)
                    $values = Arr::only($t, [
                        'title',
                        'short_description',
                        'full_description',
                        'location',
                        'requirements',
                        'included',
                    ]);

                    EventTranslation::updateOrCreate($keys, $values);
                }

                // (опционально) удалить переводы, которых нет в запросе:
                // $keepIds = collect($data['translations'])->pluck('language_id')->all();
                // EventTranslation::where('event_id', $event->id)
                //    ->whereNotIn('language_id', $keepIds)->delete();
            }

            // 3) SEO + переводы SEO
            if (!empty($data['seo']) && is_array($data['seo'])) {
                $eventSeo = EventSeo::firstOrCreate(['event_id' => $event->id]);

                foreach ($data['seo'] as $s) {
                    $keys = [
                        'event_seo_id' => $eventSeo->id,
                        'language_id'  => $s['language_id'],
                    ];
                    $values = Arr::only($s, [
                        'meta_title',
                        'meta_description',
                        'meta_keywords',
                    ]);

                    EventSeoTranslation::updateOrCreate($keys, $values);
                }

                // (опционально) sync как выше
            }
            

            return $event->load(['translations', 'seo.translations', 'gallery']);
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $event = Event::findOrFail($id);
            $event->delete();
            return true;
        });
    }
}