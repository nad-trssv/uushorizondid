<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\EventSeo;
use App\Models\EventSeoTranslation;
use App\Repositories\EventRepository;
use Illuminate\Support\Facades\DB;

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
            // Создаем основное событие
            $event = Event::create($data);
            
            // Создаем переводы
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
            $event->update($data);

            // Обновляем переводы
            if (isset($data['translations'])) {
                foreach ($data['translations'] as $translation) {
                    EventTranslation::updateOrCreate(
                        ['event_id' => $event->id, 'language_id' => $translation['language_id']],
                        $translation
                    );
                }
            }

            // Обновляем SEO
            if (isset($data['seo'])) {
                $eventSeo = EventSeo::firstOrCreate(['event_id' => $event->id]);
                
                foreach ($data['seo'] as $seoTranslation) {
                    EventSeoTranslation::updateOrCreate(
                        ['event_seo_id' => $eventSeo->id, 'language_id' => $seoTranslation['language_id']],
                        $seoTranslation
                    );
                }
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