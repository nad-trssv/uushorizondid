<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    public function getRootWithChildren($locale)
    {
        return Category::with([
                'translations' => function ($query) use ($locale) {
                    $query->where('locale', $locale);
                },
                'childrenRecursive' => function ($query) use ($locale) {
                    $query->orderBy('order')
                        ->with(['translations' => function ($q) use ($locale) {
                            $q->where('locale', $locale);
                        }]);
                }
            ])
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
    }

    public function findWithTranslations($id, $locale)
    {
        return Category::with(['children.translations' => function ($query) use ($locale) {
                        $query->where('locale', $locale);
                    }, 'translations' => function ($query) use ($locale) {
                        $query->where('locale', $locale);
                    }])
                    ->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $category = Category::create([
                'parent_id' => $data['parent_id'] ?? null,
                'order' => $data['order'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (isset($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    $category->translations()->updateOrCreate(
                        ['locale' => $translation['locale']],
                        [
                            'name' => $translation['name'],
                            'description' => $translation['description'] ?? null,
                        ]
                    );
                }
            }

            return $category;
        });
    }
}
