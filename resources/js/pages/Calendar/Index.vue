<template>
  <div class="calendar-container">
    <!-- Header -->
    <div class="calendar-header">
      <h1 class="calendar-title">{{ $t('msg.events_calendar_title') || 'Календарь мероприятий' }}</h1>
      <div class="calendar-controls">
        <button
          class="toggle-weekends"
          @click="toggleWeekends"
          :class="{ active: calendarOptions.weekends }"
        >
          <i class="fas fa-calendar-week mr-2"></i>
          {{ $t('msg.toggle_weekends') || 'Показывать выходные' }}
        </button>
      </div>
    </div>

    <!-- Calendar -->
    <div class="calendar-wrapper p-4">
      <FullCalendar
        ref="fullCalendar"
        class="custom-calendar"
        :options="calendarOptions"
      >
        <template #eventContent="{ event }">
          <div class="event-content">
            <div class="event-text">
              <div
                class="event-title"
                :style="{ backgroundColor: (event.extendedProps.eventColor || defaultEventColor) + '20' }"
              >
                {{ event.title }}
              </div>

              <div class="event-location" v-if="event.extendedProps.location">
                <i class="fas fa-map-marker-alt mr-1 text-xs"></i>
                {{ event.extendedProps.location }}
              </div>

              <div class="event-time">
                <i class="fas fa-clock mr-1 text-xs"></i>
                {{ formatTime(event.start) }} — {{ formatTime(event.end) }}
              </div>

              <div class="event-meta">
                <span class="pill" :style="{ color: metaColor(event), borderColor: metaColor(event) }">
                  <i class="fas" :class="event.extendedProps.is_registration_open ? 'fa-door-open' : 'fa-lock'"></i>
                  {{ event.extendedProps.is_registration_open ? ($t('msg.open_reg') || 'Регистрация открыта') : ($t('msg.closed_reg') || 'Регистрация закрыта') }}
                </span>
                <span class="pill" v-if="event.extendedProps.max_participants">
                  <i class="fas fa-user-friends"></i>
                  {{ event.extendedProps.confirmed_participants_count || 0 }} / {{ event.extendedProps.max_participants }}
                </span>
                <span class="pill" v-if="Number.isFinite(event.extendedProps.price)">
                  € {{ formatPrice(event.extendedProps.price) }}
                </span>
              </div>
            </div>
          </div>
        </template>
      </FullCalendar>
    </div>

    <!-- Today's Events -->
    <div v-if="todaysEvents.length" class="todays-appointments">
      <h3 class="section-title">
        <i class="fas fa-calendar-day mr-2 text-indigo-600"></i>
        {{ $t('msg.todays_events') || 'Сегодня' }}
      </h3>

      <!-- Past today -->
      <div v-if="pastToday.length" class="appointment-group">
        <h4 class="group-title">
          <i class="fas fa-check-circle mr-2 text-gray-500"></i>
          {{ $t('msg.past_events') || 'Прошедшие сегодня' }}
        </h4>
        <div class="appointment-list">
          <div
            v-for="(ev, i) in showAllPast ? pastToday : pastToday.slice(0,2)"
            :key="'p-'+i"
            class="appointment-item bg-gray-50"
          >
            <div class="flex items-center">
              <i class="fas fa-check text-green-500 mr-3"></i>
              <div>
                <div class="font-medium">{{ ev.title }}</div>
                <div class="text-sm text-gray-600">
                  <i class="fas fa-clock mr-1"></i>
                  {{ formatTime(ev.start) }} — {{ formatTime(ev.end) }}
                </div>
              </div>
            </div>
            <div class="text-sm mt-1">
              <span
                class="inline-block px-2 py-1 rounded-full text-xs font-medium"
                :style="{ backgroundColor: (ev.extendedProps.eventColor || defaultEventColor) + '20', color: ev.extendedProps.eventColor || defaultEventColor }"
              >
                {{ ev.extendedProps.location || $t('msg.no_location') || 'Локация не указана' }}
              </span>
            </div>
          </div>

          <div
            v-if="pastToday.length > 2 && !showAllPast"
            class="more-items"
            @click="showAllPast = true"
          >
            + {{ pastToday.length - 2 }} {{ $t('msg.more') || 'ещё' }}
          </div>
          <div v-if="showAllPast" class="more-items" @click="showAllPast = false">
            {{ $t('msg.show_less') || 'Свернуть' }}
          </div>
        </div>
      </div>

      <!-- Upcoming today -->
      <div v-if="upcomingToday.length" class="appointment-group">
        <h4 class="group-title">
          <i class="fas fa-clock mr-2 text-indigo-600"></i>
          {{ $t('msg.upcoming_events') || 'Предстоящие сегодня' }}
        </h4>
        <div class="appointment-list">
          <div
            v-for="(ev, i) in showAllUpcoming ? upcomingToday : upcomingToday.slice(0,2)"
            :key="'u-'+i"
            class="appointment-item"
            :class="{'bg-indigo-50 border-l-4 border-indigo-600': i === 0, 'bg-gray-50': i !== 0}"
          >
            <div class="flex items-center">
              <i class="fas fa-calendar-check text-indigo-600 mr-3"></i>
              <div>
                <div class="font-medium">{{ ev.title }}</div>
                <div class="text-sm text-gray-600">
                  <i class="fas fa-clock mr-1"></i>
                  {{ formatTime(ev.start) }} — {{ formatTime(ev.end) }}
                </div>
              </div>
            </div>
            <div class="text-sm mt-1">
              <span
                class="inline-block px-2 py-1 rounded-full text-xs font-medium"
                :style="{ backgroundColor: (ev.extendedProps.eventColor || defaultEventColor) + '20', color: ev.extendedProps.eventColor || defaultEventColor }"
              >
                {{ ev.extendedProps.location || $t('msg.no_location') || 'Локация не указана' }}
              </span>
            </div>
          </div>

          <div v-if="upcomingToday.length > 2 && !showAllUpcoming" class="more-items" @click="showAllUpcoming = true">
            + {{ upcomingToday.length - 2 }} {{ $t('msg.more') || 'ещё' }}
          </div>
          <div v-if="showAllUpcoming" class="more-items" @click="showAllUpcoming = false">
            {{ $t('msg.show_less') || 'Свернуть' }}
          </div>
        </div>
      </div>
    </div>

    <!-- Stats -->
    <div class="calendar-stats">
      <div class="stat-card">
        <div class="stat-icon bg-indigo-100 text-indigo-600">
          <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stat-value">{{ stats.today }}</div>
        <div class="stat-label">{{ $t('msg.stats.today') || 'Сегодня' }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-indigo-100 text-indigo-600">
          <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-value">{{ stats.this_week }}</div>
        <div class="stat-label">{{ $t('msg.stats.this_week') || 'Эта неделя' }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-indigo-100 text-indigo-600">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-value">{{ stats.this_month }}</div>
        <div class="stat-label">{{ $t('msg.stats.this_month') || 'Этот месяц' }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-icon bg-indigo-100 text-indigo-600">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stat-value">{{ stats.total }}</div>
        <div class="stat-label">{{ $t('msg.stats.total') || 'Всего' }}</div>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="selectedEvent" class="modal-overlay" @click.self="closeModal">
      <div class="modal-content">
      <img
        :src="'/storage/' + selectedEvent.extendedProps.image || '/storage/placeholders/600x400.svg'"
        alt="Event Image"
        @error="event => event.target.src = '/storage/placeholders/600x400.svg'"
        class="modal-image"
      />
        <button class="modal-close" @click="closeModal">&times;</button>
        <h2>{{ selectedEvent.title }}</h2>

        <div class="modal-section">
          <h3>{{ $t('msg.modal.event_info') || 'О мероприятии' }}</h3>
          <div class="info-row">
            <span class="info-label">{{ $t('msg.modal.date') || 'Дата' }}:</span>
            <span>{{ formatDate(selectedEvent.start) }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">{{ $t('msg.modal.time') || 'Время' }}:</span>
            <span>{{ formatTime(selectedEvent.start) }} — {{ formatTime(selectedEvent.end) }}</span>
          </div>
          <div class="info-row" v-if="selectedEvent.extendedProps.location">
            <span class="info-label">{{ $t('msg.modal.location') || 'Локация' }}:</span>
            <span>{{ selectedEvent.extendedProps.location }}</span>
          </div>
          <div class="info-row" v-if="Number.isFinite(selectedEvent.extendedProps.price)">
            <span class="info-label">{{ $t('msg.modal.price') || 'Цена' }}:</span>
            <span>€ {{ formatPrice(selectedEvent.extendedProps.price) }}</span>
          </div>
          <div class="info-row" v-if="selectedEvent.extendedProps.max_participants">
            <span class="info-label">{{ $t('msg.modal.participants') || 'Участники' }}:</span>
            <span>
              {{ selectedEvent.extendedProps.confirmed_participants_count || 0 }} / {{ selectedEvent.extendedProps.max_participants }}
            </span>
          </div>
          <div class="info-row" v-if="selectedEvent.extendedProps.registration_deadline">
            <span class="info-label">{{ $t('msg.modal.deadline') || 'Дедлайн регистрации' }}:</span>
            <span>{{ formatDate(parseISOToLocal(selectedEvent.extendedProps.registration_deadline)) }}</span>
          </div>
        </div>

        <div class="modal-section" v-if="selectedEvent.extendedProps.short_description || selectedEvent.extendedProps.full_description">
          <h3>{{ $t('msg.modal.description') || 'Описание' }}</h3>
          <p>{{ selectedEvent.extendedProps.full_description || selectedEvent.extendedProps.short_description }}</p>
        </div>

        <div class="modal-actions">
          <button class="btn btn-edit" @click="goEdit(selectedEvent)">{{ $t('msg.modal.edit') || 'Редактировать' }}</button>
          <button class="btn btn-decline" @click="closeModal">{{ $t('msg.modal.close') || 'Закрыть' }}</button>
        </div>
      </div>
    </div>

    <!-- Month Picker Modal -->
    <div v-if="showMonthPickerModal" class="month-picker-modal" @click.self="showMonthPickerModal = false">
      <div class="modal-content">
        <div class="modal-header">
          <h3>{{ $t('msg.label.select_month') || 'Выберите месяц' }}</h3>
          <button @click="showMonthPickerModal = false">&times;</button>
        </div>
        <div class="months-grid">
          <button
            v-for="(month, index) in monthsList"
            :key="index"
            @click="selectMonth(index)"
            :class="{ active: index === currentMonth }"
          >
            {{ month }}
          </button>
        </div>
        <div class="year-selector">
          <button @click="changeYear(-1)">&lt;</button>
          <span>{{ currentYear }}</span>
          <button @click="changeYear(1)">&gt;</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import multiMonthPlugin from '@fullcalendar/multimonth';

import { format } from 'date-fns';
import { enUS, ru, et } from 'date-fns/locale';

// локали date-fns
const locales = { en: enUS, ru: ru, et: et };

// твой базовый TZ (можешь подменить из настроек приложения)
const DEFAULT_TZ = 'Europe/Tallinn';

export default {
  name: 'EventCalendarIndex',
  components: { FullCalendar },
  data() {
    return {
      selectedEvent: null,
      showAllPast: false,
      showAllUpcoming: false,
      defaultEventColor: '#4f46e5', // индиго по-умолчанию
      stats: { today: 0, this_week: 0, this_month: 0, total: 0 },
      showMonthPickerModal: false,
      currentMonth: 0,
      currentYear: new Date().getFullYear(),
      monthsList: [],
      calendarOptions: {
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false, meridiem: false },
        dayHeaderFormat: { weekday: 'short', day: 'numeric', month: 'short', omitCommas: true },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin, multiMonthPlugin],
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev title today next',
          center: '',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek,multiMonthYear'
        },
        views: {
          dayGridMonth: {
            dayHeaderFormat: { weekday: 'short' }            // Пн, Вт, Ср...
          },
        },
        buttonText: {
          today: this.$t('msg.buttons.today'),
          month: this.$t('msg.buttons.month'),
          week: this.$t('msg.buttons.week'),
          day: this.$t('msg.buttons.day'),
          list: this.$t('msg.buttons.list'),
          multiMonthYear: this.$t('msg.buttons.year')
        },
        editable: false,
        selectable: false,
        selectMirror: false,
        dayMaxEvents: 3,
        weekends: true,
        events: [],
        eventClick: this.handleEventClick,
        locale: this.$i18n.locale,
        firstDay: 1,
        nowIndicator: true,
        // таймслоты для недельного/дневного вида (под твои часы работы)
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        datesSet: () => {
          this.updateStats();
          const api = this.$refs.fullCalendar?.getApi();
          if (api) api.setOption('now', new Date());
        }
      }
    };
  },
  computed: {
    rawEvents() {
      return this.$store.getters['events/lists'] || [];
    },
    // Все евенты FullCalendar, рассчитанные из стора
    mappedEvents() {
      const colorByStatus = (status) => (status === 'published' ? '#16a34a' : '#f59e0b'); // зелёный/жёлтый
      return (this.rawEvents || []).map(ev => {
        // ISO в API имеют Z (UTC) — создаём Date и показываем локально
        const start = this.parseISOToLocal(ev.start_time);
        const end = this.parseISOToLocal(ev.end_time);
        // на карточке — локализованный заголовок, если он уже дан в EventResource
        const title = ev.title || this.pickTranslationTitle(ev);

        return {
          id: ev.id,
          title: title || ev.slug || `#${ev.id}`,
          start,
          end,
          allDay: false,
          extendedProps: {
            ...ev,
            eventColor: colorByStatus(ev.status),
            location: ev.location,
            is_registration_open: ev.is_registration_open,
            registration_deadline: ev.registration_deadline
          }
        };
      });
    },
    todaysEvents() {
      const todayStr = new Date().toDateString();
      return this.mappedEvents.filter(e => e.start && e.start.toDateString() === todayStr);
    },
    pastToday() {
      const now = new Date();
      return this.todaysEvents
        .filter(e => e.end && e.end < now)
        .sort((a, b) => b.end - a.end);
    },
    upcomingToday() {
      const now = new Date();
      return this.todaysEvents
        .filter(e => e.start && e.start >= now)
        .sort((a, b) => a.start - b.start);
    }
  },
  watch: {
    rawEvents: {
      immediate: true,
      handler() {
        this.calendarOptions.events = this.mappedEvents;
        this.$nextTick(() => this.updateStats());
      }
    },
    '$i18n.locale'(newLocale) {
      this.updateCalendarLocale(newLocale);
    }
  },
  mounted() {
    this.fetchEvents();
    this.updateCalendarLocale(this.$i18n.locale);
    this.startTimeUpdater();
    this.$nextTick(() => {
      if (this.mappedEvents.length) this.updateStats();
    });
    this.setupTitleClickHandler();
  },
  beforeUnmount() {
    if (this.timeUpdaterInterval) clearInterval(this.timeUpdaterInterval);
  },
  methods: {
    async fetchEvents() {
      // подаём минимальные параметры – можно расширить пагинацией/фильтрами при желании
      await this.$store.dispatch('events/getAll', { lang: this.$i18n.locale });
    },

    // ---------- TZ / форматирование ----------
    parseISOToLocal(iso) {
      if (!iso) return null;
      // new Date(iso) корректно трактует Z как UTC и вернёт локальную дату/время
      const d = new Date(iso);
      return Number.isNaN(d.getTime()) ? null : d;
    },
    formatDate(dateObj) {
      if (!dateObj) return '—';
      try {
        return format(dateObj, 'PPP', { locale: locales[this.$i18n.locale] });
      } catch {
        return dateObj.toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: '2-digit', timeZone: DEFAULT_TZ });
      }
    },
    formatTime(dateObj) {
      if (!dateObj) return '—';
      try {
        return format(dateObj, 'HH:mm', { locale: locales[this.$i18n.locale] });
      } catch {
        return new Intl.DateTimeFormat(undefined, { hour: '2-digit', minute: '2-digit', hour12: false, timeZone: DEFAULT_TZ }).format(dateObj);
      }
    },
    formatPrice(val) {
      const n = Number(val);
      return Number.isFinite(n) ? n.toFixed(2) : '0.00';
    },

    // ---------- UI helpers ----------
    handleEventClick(info) {
      this.selectedEvent = info.event;
    },
    closeModal() {
      this.selectedEvent = null;
    },
    toggleWeekends() {
      this.calendarOptions.weekends = !this.calendarOptions.weekends;
      this.$refs.fullCalendar?.getApi()?.updateSize();
    },
    updateCalendarLocale(locale) {
      const api = this.$refs.fullCalendar?.getApi();
      if (!api) return;
      api.setOption('locale', locale);
      api.setOption('buttonText', {
        today: this.$t('msg.buttons.today'),
        month: this.$t('msg.buttons.month'),
        week: this.$t('msg.buttons.week'),
        day: this.$t('msg.buttons.day'),
        list: this.$t('msg.buttons.list'),
        multiMonthYear: this.$t('msg.buttons.year')
      });
    },
    updateStats() {
      const api = this.$refs.fullCalendar?.getApi();
      if (!api) return;

      const today = new Date();
      const startOfDay = new Date(today.getFullYear(), today.getMonth(), today.getDate());
      const endOfDay = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 23, 59, 59);

      // понедельник — первый день недели
      const day = (today.getDay() + 6) % 7; // 0..6, где 0 — понедельник
      const startOfWeek = new Date(today);
      startOfWeek.setDate(today.getDate() - day);
      startOfWeek.setHours(0, 0, 0, 0);

      const endOfWeek = new Date(startOfWeek);
      endOfWeek.setDate(startOfWeek.getDate() + 6);
      endOfWeek.setHours(23, 59, 59, 999);

      const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1, 0, 0, 0);
      const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0, 23, 59, 59);

      const all = api.getEvents();

      this.stats.today = all.filter(e => e.start && e.start >= startOfDay && e.start <= endOfDay).length;
      this.stats.this_week = all.filter(e => e.start && e.start >= startOfWeek && e.start <= endOfWeek).length;
      this.stats.this_month = all.filter(e => e.start && e.start >= startOfMonth && e.start <= endOfMonth).length;
      this.stats.total = all.length;
    },
    startTimeUpdater() {
      this.timeUpdaterInterval = setInterval(() => {
        const api = this.$refs.fullCalendar?.getApi();
        if (api) api.setOption('now', new Date());
      }, 60000);
    },
    setupTitleClickHandler() {
      // кликабельный заголовок календаря
      this.$nextTick(() => {
        const titleElement = document.querySelector('.fc-toolbar-title');
        if (titleElement) {
          titleElement.style.cursor = 'pointer';
          titleElement.addEventListener('click', this.openMonthPicker);
        }
      });
    },
    openMonthPicker() {
      const api = this.$refs.fullCalendar.getApi();
      const d = api.getDate();
      this.currentMonth = d.getMonth();
      this.currentYear = d.getFullYear();
      this.updateMonthsList();
      this.showMonthPickerModal = true;
    },
    updateMonthsList() {
      this.monthsList = [...Array(12).keys()].map(m =>
        new Date(this.currentYear, m).toLocaleString(this.$i18n.locale, { month: 'long' })
      );
    },
    selectMonth(monthIndex) {
      const api = this.$refs.fullCalendar.getApi();
      api.gotoDate(new Date(this.currentYear, monthIndex, 1));
      this.showMonthPickerModal = false;
    },
    changeYear(delta) {
      this.currentYear += delta;
      this.updateMonthsList();
    },

    // визуальщина
    metaColor(event) {
      return event.extendedProps.is_registration_open ? '#16a34a' : '#ef4444';
    },

    // выбор заголовка из массива translations (если вдруг title не проброшен на корень)
    pickTranslationTitle(ev) {
      const lang = this.$i18n.locale;
      const trs = Array.isArray(ev.translations) ? ev.translations : [];
      let t = trs.find(x => x.language === lang && x.title);
      if (t?.title) return t.title;
      t = trs.find(x => x.default && x.title);
      if (t?.title) return t.title;
      t = trs.find(x => x.title);
      return t?.title || null;
    },

    goEdit(fcEvent) {
      const id = fcEvent.id || fcEvent.extendedProps?.id;
      if (id) this.$router.push({ name: 'admin.events.edit', params: { id } });
      this.closeModal();
    }
  }
};
</script>

<style scoped>
/* Каркас */
.calendar-container { display: flex; flex-direction: column; gap: 16px; }
.calendar-header { display: flex; justify-content: space-between; align-items: center; gap: 12px; }
.calendar-title { font-size: 20px; font-weight: 700; color: #1f2937; }
.calendar-controls { display: flex; gap: 8px; }
.toggle-weekends { display: inline-flex; align-items: center; gap: 8px; padding: 8px 12px; border-radius: 10px; border: 1px solid #e5e7eb; background: #fff; font-weight: 600; color: #374151; transition: .2s; }
.toggle-weekends.active { border-color: #4f46e5; color: #4f46e5; background: #eef2ff; }
.calendar-wrapper { background: #fff; border-radius: 12px; border: 1px solid #e5e7eb; }

/* Ивенты (месяц) */
.fc-dayGridMonth-view .fc-daygrid-event { margin: 1px 2px; padding: 3px 4px; font-size: 12px; line-height: 1.3; border-radius: 4px; border-left: 3px solid; background-color: rgba(255,255,255,0.95); box-shadow: 0 1px 2px rgba(0,0,0,.05); }
.fc-dayGridMonth-view .event-content { display: flex; flex-direction: column; gap: 2px; }
.fc-dayGridMonth-view .event-title { font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 2px 6px; font-size: 11px; border-radius: 12px; }
.fc-dayGridMonth-view .event-location, .fc-dayGridMonth-view .event-time { font-size: 10px; color: #555; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.event-meta { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 2px; }
.pill { display: inline-flex; align-items: center; gap: 6px; font-size: 10px; padding: 2px 6px; border: 1px solid #e5e7eb; border-radius: 999px; }

/* Таймгрид: компактнее */
.fc-timeGridWeek-view .fc-event-main,
.fc-timeGridDay-view .fc-event-main { padding: 2px 4px; }
.fc-timeGridWeek-view .event-content,
.fc-timeGridDay-view .event-content { display: block; }
.fc-timeGridWeek-view .event-title { display: none; }
.fc-timeGridWeek-view .event-time,
.fc-timeGridDay-view .event-time { display: none !important; }

/* link "+X more" */
.fc .fc-daygrid-more-link { font-size: 11px; color: #4f46e5; font-weight: 500; padding: 1px 4px; border-radius: 3px; display: inline-block; background-color: rgba(238,242,255,.9); cursor: pointer; width: calc(100% - 4px); box-sizing: border-box; text-align: center; }
.fc .fc-daygrid-day-bottom { position: absolute; bottom: 0; left: 0; right: 0; padding: 0 2px 2px; text-align: center !important; }

/* Статистика */
.calendar-stats { display: grid; grid-template-columns: repeat(1,minmax(0,1fr)); gap: 12px; margin-top: 12px; }
@media (min-width: 640px){ .calendar-stats { grid-template-columns: repeat(2,1fr); } }
@media (min-width: 1024px){ .calendar-stats { grid-template-columns: repeat(4,1fr); } }
.stat-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px; display: grid; gap: 6px; }
.stat-icon { width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; }
.stat-value { font-size: 22px; font-weight: 700; color: #111827; }
.stat-label { font-size: 12px; color: #6b7280; }

/* Today lists */
.todays-appointments { margin-top: 8px; }
.section-title { font-weight: 700; color: #111827; display: flex; align-items: center; }
.appointment-group { margin-top: 10px; }
.group-title { font-weight: 600; color: #374151; margin-bottom: 6px; display: flex; align-items: center; }
.appointment-list { display: grid; gap: 8px; }
.appointment-item { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; }
.more-items { cursor: pointer; color: #4f46e5; font-weight: 600; margin-top: 6px; }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.4); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 50; }
.modal-content { background: #fff; border-radius: 14px; padding: 18px; width: 100%; max-width: 680px; position: relative;max-height: 80vh; overflow-y: scroll; }
.modal-content img { width: 100%; border-radius: 10px; margin-bottom: 12px; height: 230px;  object-fit: cover; object-position: center; }
.modal-close { position: absolute; right: 10px; top: 8px; font-size: 22px; color: #6b7280; }
.modal-section { margin-top: 10px; }
.modal-section h3 { font-weight: 700; color: #1f2937; margin-bottom: 6px; }
.info-row { display: grid; grid-template-columns: 160px 1fr; gap: 6px; margin-bottom: 6px; font-size: 14px; }
.info-label { color: #6b7280; }
.modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 12px; }
.btn { padding: 8px 12px; border-radius: 10px; font-weight: 600; border: 1px solid #e5e7eb; }
.btn-edit { background: #eef2ff; color: #4f46e5; }
.btn-decline { background: #fee2e2; color: #b91c1c; }

/* Month picker modal */
.month-picker-modal { position: fixed; inset: 0; background: rgba(0,0,0,.35); display: flex; align-items: center; justify-content: center; padding: 16px; z-index: 50; }
.month-picker-modal .modal-content { background: #fff; border-radius: 14px; padding: 16px; width: 100%; max-width: 520px; }
.modal-header { display: flex; align-items: center; justify-content: space-between; }
.months-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: 10px; }
.months-grid button { padding: 8px 10px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; }
.months-grid button.active { border-color: #4f46e5; background: #eef2ff; color: #4f46e5; }
.year-selector { display: flex; align-items: center; justify-content: center; gap: 12px; font-weight: 700; margin-top: 10px; }

/* Адаптив */
@media (max-width: 768px) {
  .fc .fc-daygrid-day-frame { min-height: 80px; }
  .fc-dayGridMonth-view .fc-daygrid-event { font-size: 11px; padding: 2px 3px; }
}
@media (max-width: 480px) {
  .fc .fc-daygrid-day-frame { min-height: 60px; }
  .fc-dayGridMonth-view .fc-daygrid-event { font-size: 10px; line-height: 1.2; }
}
.fc-listWeek-view .event-time { display: none !important; }
</style>
