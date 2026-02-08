
import { Calendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin from '@fullcalendar/interaction'
import ruLocale from '@fullcalendar/core/locales/ru'

const injectBrandStyles = () => {
  const css = `
  :root {
    --fc-border-color: #e9ecef;
    --fc-page-bg-color: #fff;
    --fc-today-bg-color: rgba(125,166,64,0.08);
  }

  .fc .fc-toolbar-title { font-weight: 800; color: #111; }
  .fc .fc-button { border-radius: 10px; }
  .fc .fc-daygrid-event { border-radius: 10px; padding: 6px 10px; background: #f1f5f9; overflow: hidden; }
  .fc-custom-event { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; overflow: hidden; }
  .fc-event-title { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 100%; color: #111; }

  .event-status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .event-status-dot--open { background: var(--primary, #7DA640); }
  .event-status-dot--closed { background: #9CA3AF; }
  .event-status-dot--past { background: #94A3B8; }
  .event-status-dot--full { background: #facc15; }

  @media (max-width: 767.98px) {
    .fc-dayGridMonth-view .fc-event-title,
    .fc-dayGridMonth-view .fc-event-meta {
      display: none !important;
    }

    .fc-dayGridMonth-view .fc-custom-event {
      justify-content: center;
      padding: 4px;
    }

    .fc-dayGridMonth-view .event-status-dot {
      width: 12px;
      height: 12px;
    }
  }

  @media (max-width: 575px) {
    .fc .fc-toolbar-title { font-size: 1.05rem; }
    .fc .fc-button { font-size: .85rem; padding: .35rem .5rem; }
    .fc-header-toolbar { flex-wrap: wrap; gap: 6px; justify-content: center; }
  }
  `;
  const style = document.createElement('style');
  style.innerHTML = css;
  document.head.appendChild(style);
};

document.addEventListener('DOMContentLoaded', () => {
  injectBrandStyles();

  const el = document.getElementById('eventsCalendar');
  if (!el) return;

  const initialView = window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth';

  const calendar = new Calendar(el, {
    plugins: [dayGridPlugin, listPlugin, interactionPlugin],
    locale: ruLocale,
    firstDay: 1,
    timeZone: 'local',
    height: 'auto',
    stickyHeaderDates: true,
    dayMaxEvents: 3,
    displayEventEnd: true,
    navLinks: true,

    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,listWeek'
    },

    buttonText: {
      today: 'Сегодня',
      month: 'Месяц',
      list: 'Список'
    },

    initialView,
    events: document.querySelector('meta[name="calendar-feed"]')?.content || '/events/feed',

    eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

    eventClick(info) {
      info.jsEvent.preventDefault();
      const { title, extendedProps, url, id } = info.event;

      document.getElementById('eventTitle').textContent = title;

      const formattedDate = info.event.start.toLocaleDateString('ru-RU', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
      const formattedTime = info.event.start.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' }) +
        (info.event.end ? ' - ' + info.event.end.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' }) : '');

      const createModalRow = (icon, label, value) => {
        return `
          <div class="d-flex align-items-start gap-2 mb-2">
            <i class="fas fa-${icon} text-primary fs-5 mt-1"></i>
            <div>
              <div class="fw-semibold">${label}</div>
              <div class="text-muted small">${value}</div>
            </div>
          </div>`;
      };

      const details = [
        createModalRow('calendar-day', 'Дата', formattedDate),
        createModalRow('clock', 'Время', formattedTime),
        extendedProps.location && createModalRow('map-marker-alt', 'Место', extendedProps.location),
        createModalRow('euro-sign', 'Стоимость', extendedProps.price || 'Бесплатно'),
        extendedProps.description && createModalRow('align-left', 'Описание', extendedProps.description)
      ].filter(Boolean).join('');

      document.getElementById('eventDetails').innerHTML = details;
      document.getElementById('detailBtn').href = url || '#';
      document.getElementById('detailBtn').target = '_blank';
      document.getElementById('registerEventId').value = id || '';

      const registerBtn = document.getElementById('registerBtn');
      const contactBtn = document.getElementById('contactBtn');
      const detailBtn = document.getElementById('detailBtn');

      registerBtn.style.display = 'none';
      contactBtn.style.display = 'none';
      detailBtn.style.display = 'inline-block';

      const status = extendedProps.statusClass;

      if (status === 'open') {
        registerBtn.style.display = 'inline-block';
        registerBtn.onclick = () => {
          const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));
          registerModal.show();
        };
      } else if (['closed', 'past', 'full'].includes(status)) {
        contactBtn.style.display = 'inline-block';
        contactBtn.onclick = () => {
          window.open('/contact', '_blank');
        };
      }

      const eventModal = new bootstrap.Modal(document.getElementById('eventModal'));
      eventModal.show();
    },

    eventContent(info) {
      const { title, extendedProps } = info.event;
      const statusClass = extendedProps.statusClass || 'open';

      const wrapper = document.createElement('div');
      wrapper.className = 'fc-custom-event';

      const dotEl = document.createElement('span');
      dotEl.className = `event-status-dot event-status-dot--${statusClass}`;
      wrapper.appendChild(dotEl);

      const contentEl = document.createElement('div');
      contentEl.className = 'fc-event-content';

      const titleEl = document.createElement('div');
      titleEl.className = 'fc-event-title';
      titleEl.textContent = title;
      contentEl.appendChild(titleEl);

      wrapper.appendChild(contentEl);

      return { domNodes: [wrapper] };
    },

    windowResize(arg) {
      const newView = window.innerWidth < 768 ? 'listWeek' : 'dayGridMonth';
      if (newView !== calendar.view.type) {
        calendar.changeView(newView);
      }
    },
  });

  calendar.render();
});