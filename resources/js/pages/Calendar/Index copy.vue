<template>
    <div class="calendar-container">
      <div class="calendar-header">
        <h1 class="calendar-title">{{ $t('msg.title') }}</h1>
        <div class="calendar-controls">
          <button 
            class="toggle-weekends" 
            @click="toggleWeekends"
            :class="{ active: calendarOptions.weekends }"
          >
            <i class="fas fa-calendar-week mr-2"></i>
            {{ $t('msg.toggle_weekends') }}
          </button>
        </div>
      </div>
  
      <div class="calendar-wrapper p-4">
        <FullCalendar 
          ref="fullCalendar"
          class="custom-calendar"
          :options="calendarOptions"
        >
          <template v-slot:eventContent="arg">
            <div class="event-content">
              <div class="event-text">
                <div 
                  class="event-title" 
                  :style="{backgroundColor: arg.event.extendedProps.eventColor + '20'}"
                >{{ arg.event.title }}</div>
                <div class="event-client">
                  <i class="fas fa-user mr-1 text-xs"></i>
                  {{ arg.event.extendedProps.client_name }}
                </div>
                <div class="event-time">
                  <i class="fas fa-clock mr-1 text-xs"></i>
                  {{ formatTime(arg.event.start) }} - {{ formatTime(arg.event.end) }}
                </div>
              </div>
            </div>
          </template>
        </FullCalendar>
      </div>
  
      <!-- Today's Appointments Section -->
      <div v-if="todaysAppointments.length > 0" class="todays-appointments">
        <h3 class="section-title">
          <i class="fas fa-calendar-day mr-2 text-indigo-600"></i>
          {{ $t('msg.todays_appointments') }}
        </h3>
        
        <!-- Past Appointments -->
        <div v-if="pastAppointments.length > 0" class="appointment-group">
          <h4 class="group-title">
            <i class="fas fa-check-circle mr-2 text-gray-500"></i>
            {{ $t('msg.past_appointments') }}
          </h4>
          <div class="appointment-list">
            <div 
                v-for="(appointment, index) in showAllPast ? pastAppointments : pastAppointments.slice(0, 2)" 
                :key="'past-'+index"
                class="appointment-item bg-gray-50"
            >
              <div class="flex items-center">
                <i class="fas fa-check text-green-500 mr-3"></i>
                <div>
                  <div class="font-medium">{{ appointment.client_name }} {{ appointment.client_lastname }}</div>
                  <div class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    {{ formatTime(parseAppointmentTime(appointment.appointment_start)) }} - 
                    {{ formatTime(parseAppointmentTime(appointment.appointment_end)) }}
                  </div>
                </div>
              </div>
              <div class="text-sm mt-1">
                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                      :style="{ backgroundColor: appointment.service.eventColor + '20', color: appointment.service.eventColor }">
                  {{ appointment.service.name }}
                </span>
              </div>
            </div>
            <div 
                v-if="pastAppointments.length > 2 && !showAllPast" 
                class="more-items" 
                @click="showAllPast = true"
                >
                + {{ pastAppointments.length - 2 }} {{ $t('msg.more') }}
            </div>
            <div 
                v-if="showAllPast" 
                class="more-items" 
                @click="showAllPast = false"
                >
                {{ $t('msg.show_less') }}
            </div>
          </div>
        </div>
        
        <!-- Upcoming Appointments -->
        <div v-if="upcomingAppointments.length > 0" class="appointment-group">
          <h4 class="group-title">
            <i class="fas fa-clock mr-2 text-indigo-600"></i>
            {{ $t('msg.upcoming_appointments') }}
          </h4>
          <div class="appointment-list">
            <div 
                v-for="(appointment, index) in showAllUpcoming ? upcomingAppointments : upcomingAppointments.slice(0, 2)" 
                :key="'upcoming-'+index"
                class="appointment-item"
                :class="{'bg-indigo-50 border-l-4 border-indigo-600': index === 0, 'bg-gray-50': index !== 0}"
            >
              <div class="flex items-center">
                <i class="fas fa-calendar-check text-indigo-600 mr-3"></i>
                <div>
                  <div class="font-medium">{{ appointment.client_name }} {{ appointment.client_lastname }}</div>
                  <div class="text-sm text-gray-600">
                    <i class="fas fa-clock mr-1"></i>
                    {{ formatTime(parseAppointmentTime(appointment.appointment_start)) }} - 
                    {{ formatTime(parseAppointmentTime(appointment.appointment_end)) }}
                  </div>
                </div>
              </div>
              <div class="text-sm mt-1">
                <span class="inline-block px-2 py-1 rounded-full text-xs font-medium" 
                      :style="{ backgroundColor: appointment.service.eventColor + '20', color: appointment.service.eventColor }">
                  {{ appointment.service.name }}
                </span>
              </div>
            </div>
            <div 
                v-if="upcomingAppointments.length > 2 && !showAllUpcoming" 
                class="more-items" 
                @click="showAllUpcoming = true"
                >
                + {{ upcomingAppointments.length - 2 }} {{ $t('msg.more') }}
            </div>
            <div 
                v-if="showAllUpcoming" 
                class="more-items" 
                @click="showAllUpcoming = false"
                >
                {{ $t('msg.show_less') }}
            </div>
          </div>
        </div>
      </div>
  
      <!-- Statistics Section -->
      <div class="calendar-stats">
        <div class="stat-card">
          <div class="stat-icon bg-indigo-100 text-indigo-600">
            <i class="fas fa-calendar-day"></i>
          </div>
          <div class="stat-value">{{ stats.today }}</div>
          <div class="stat-label">{{ $t('msg.stats.today') }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon bg-indigo-100 text-indigo-600">
            <i class="fas fa-calendar-week"></i>
          </div>
          <div class="stat-value">{{ stats.this_week }}</div>
          <div class="stat-label">{{ $t('msg.stats.this_week') }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon bg-indigo-100 text-indigo-600">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="stat-value">{{ stats.this_month }}</div>
          <div class="stat-label">{{ $t('msg.stats.this_month') }}</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon bg-indigo-100 text-indigo-600">
            <i class="fas fa-calendar-check"></i>
          </div>
          <div class="stat-value">{{ stats.total }}</div>
          <div class="stat-label">{{ $t('msg.stats.total') }}</div>
        </div>
      </div>
  
      <!-- Event Details Modal -->
      <div v-if="selectedEvent" class="modal-overlay" @click.self="closeModal">
        <div class="modal-content">
          <button class="modal-close" @click="closeModal">&times;</button>
          <h2>{{ selectedEvent.title }}</h2>
          
          <div class="modal-section">
            <h3>{{ $t('msg.modal.client_info') }}</h3>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.name') }}:</span>
              <span>{{ selectedEvent.extendedProps.client_name }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.phone') }}:</span>
              <span>{{ selectedEvent.extendedProps.client_phone }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.email') }}:</span>
              <span>{{ selectedEvent.extendedProps.client_email }}</span>
            </div>
          </div>
          
          <div class="modal-section">
            <h3>{{ $t('msg.modal.appointment_info') }}</h3>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.date') }}:</span>
              <span>{{ formatDate(selectedEvent.start) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.time') }}:</span>
              <span>{{ formatTime(selectedEvent.start) }} - {{ formatTime(selectedEvent.end) }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.service') }}:</span>
              <span>{{ selectedEvent.extendedProps.service.name }}</span>
            </div>
            <div class="info-row">
              <span class="info-label">{{ $t('msg.modal.price') }}:</span>
              <span>{{ selectedEvent.extendedProps.price }} €</span>
            </div>
          </div>
          
          <div class="modal-section">
            <h3>{{ $t('msg.modal.description') }}</h3>
            <p>{{ selectedEvent.extendedProps.description }}</p>
          </div>
          
          <div class="modal-actions">
            <button class="btn btn-edit">{{ $t('msg.modal.edit') }}</button>
            <button class="btn btn-decline">{{ $t('msg.modal.decline') }}</button>
            <button class="btn btn-confirm">{{ $t('msg.modal.confirm') }}</button>
          </div>
        </div>
      </div>

      <!-- Month Choose Modal -->
      <div v-if="showMonthPickerModal" class="month-picker-modal" @click.self="showMonthPickerModal = false">
        <div class="modal-content">
          <div class="modal-header">
            <h3>{{ $t('msg.label.select_month') }}</h3>
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

    const locales = {
        en: enUS,
        ru: ru,
        et: et
    };

    export default {
        name: 'CalendarIndex',
        components: {
            FullCalendar
        },
        data() {
            return {
                selectedEvent: null,
                showAllPast: false,
                showAllUpcoming: false,
                stats: {
                    today: 0,
                    this_week: 0,
                    this_month: 0,
                    total: 0
                },
                showMonthPickerModal: false,
                currentMonth: 0,
                currentYear: new Date().getFullYear(),
                monthsList: [],
                calendarOptions: {
                    slotLabelFormat: {
                      hour: '2-digit',
                      minute: '2-digit',
                      hour12: false,
                      meridiem: false
                    },
                    dayHeaderFormat: {
                      weekday: 'short',
                      day: 'numeric',
                      month: 'short',
                      omitCommas: true
                    },
                    eventTimeFormat: {
                      hour: '2-digit',
                      minute: '2-digit',
                      hour12: false
                    },
                    plugins: [ 
                        dayGridPlugin, 
                        timeGridPlugin, 
                        listPlugin, 
                        interactionPlugin,
                        multiMonthPlugin
                    ],
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                      left: 'prev title today next',
                      center: '',
                      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek,multiMonthYear'
                    },
                    buttonText: {
                        today: this.$t('msg.buttons.today'),
                        month: this.$t('msg.buttons.month'),
                        week: this.$t('msg.buttons.week'),
                        day: this.$t('msg.buttons.day'),
                        list: this.$t('msg.buttons.list'),
                        multiMonthYear: this.$t('msg.buttons.year')
                    },
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: 3,
                    weekends: true,
                    events: [],
                    eventClick: this.handleEventClick,
                    datesSet: this.updateStats,
                    locale: this.$i18n.locale,
                    firstDay: 1,
                    dayMaxEventRows: false, // Обработчик клика на "+X more"
                    nowIndicator: true, // Включаем индикатор текущего времени
                    slotMinTime: '08:00:00',
                    slotMaxTime: '20:00:00',
                    datesSet: (dateInfo) => {
                      this.updateStats(); 
                      if (dateInfo.view.type === 'timeGridDay' || dateInfo.view.type === 'timeGridWeek') {
                        dateInfo.view.calendar.setOption('now', new Date());
                      }
                    },
                }
            }
        },
        computed: {
            appointments() {
                return this.$store.getters['appointments/lists'] || [];
            },
            todaysAppointments() {
                const today = new Date().toISOString().split('T')[0];
                return this.appointments.filter(app => {
                    const [day, month, year] = app.appointment_date.split('-');
                    return `${year}-${month}-${day}` === today;
                });
            },
            pastAppointments() {
                const now = new Date();
                return this.todaysAppointments.filter(app => {
                    const endTime = this.parseAppointmentTime(app.appointment_end);
                    return new Date(endTime) < now;
                }).sort((a, b) => {
                    return new Date(this.parseAppointmentTime(b.appointment_end)) - 
                        new Date(this.parseAppointmentTime(a.appointment_end));
                });
            },
            upcomingAppointments() {
                const now = new Date();
                return this.todaysAppointments.filter(app => {
                    const startTime = this.parseAppointmentTime(app.appointment_start);
                    return new Date(startTime) >= now;
                }).sort((a, b) => {
                    return new Date(this.parseAppointmentTime(a.appointment_start)) - 
                        new Date(this.parseAppointmentTime(b.appointment_start));
                });
            }
        },
        watch: {
            appointments: {
                immediate: true,
                handler(appointments) {
                    if (appointments && appointments.length) {
                        this.calendarOptions.events = appointments.map(appointment => {
                            const [day, month, year] = appointment.appointment_date.split('-');
                            const dateStr = `${year}-${month}-${day}`;
                            
                            const start = this.combineDateTime(dateStr, appointment.appointment_start);
                            const end = this.combineDateTime(dateStr, appointment.appointment_end);
                            
                            return {
                                title: `${appointment.service.name}`,
                                client_name: appointment.client_name + ' ' + appointment.client_lastname,
                                start: start,
                                end: end,
                                extendedProps: {
                                    ...appointment,
                                    eventColor: appointment.service.eventColor
                                },
                                allDay: false
                            };
                        });
                        this.$nextTick(() => this.updateStats());
                    }
                }
            },
            '$i18n.locale'(newLocale) {
                this.updateCalendarLocale(newLocale);
            },
            'calendarOptions.initialView'(newView) {
              if (newView === 'timeGridDay' || newView === 'timeGridWeek') {
                const calendarApi = this.$refs.fullCalendar?.getApi();
                if (calendarApi) {
                  calendarApi.setOption('now', new Date());
                }
              }
            }
        },
        mounted() {
            this.getAppointments();
            this.updateCalendarLocale(this.$i18n.locale);
            this.startTimeUpdater();
            this.$nextTick(() => {
                if (this.appointments.length) {
                    this.updateStats();
                }
            });
            this.setupTitleClickHandler();
        },
        beforeUnmount() {
          if (this.timeUpdaterInterval) {
            clearInterval(this.timeUpdaterInterval);
          }
        },
        methods: {
          parseAppointmentTime(timeStr) {
              const today = new Date().toISOString().split('T')[0];
              return `${today}T${timeStr}:00`;
          },
          getAppointments() {
              this.$store.dispatch('appointments/lists');
          },
          combineDateTime(dateStr, timeStr) {
              return `${dateStr}T${timeStr}:00`;
          },  
          handleEventClick(info) {
              this.selectedEvent = info.event;
          },
          closeModal() {
              this.selectedEvent = null;
          },
          toggleWeekends() {
              this.calendarOptions.weekends = !this.calendarOptions.weekends;
              this.$refs.fullCalendar.getApi().updateSize();
          },
          updateCalendarLocale(locale) {
              const calendarApi = this.$refs.fullCalendar?.getApi();
              if (calendarApi) {
                  calendarApi.setOption('locale', locale);
                  calendarApi.setOption('buttonText', {
                  today: this.$t('msg.buttons.today'),
                  month: this.$t('msg.buttons.month'),
                  week: this.$t('msg.buttons.week'),
                  day: this.$t('msg.buttons.day'),
                  list: this.$t('msg.buttons.list'),
                  multiMonthYear: this.$t('msg.buttons.year')
                });
              }
          },
          updateStats() {
              if (!this.$refs.fullCalendar) return;
              const calendarApi = this.$refs.fullCalendar.getApi();
              const today = calendarApi.getDate();
              const startOfWeek = new Date(today);
              startOfWeek.setDate(today.getDate() - today.getDay());
              const endOfWeek = new Date(today);
              endOfWeek.setDate(today.getDate() + (6 - today.getDay()));
              
              const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
              const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
              
              const allEvents = calendarApi.getEvents();
              
              this.stats.today = allEvents.filter(event => 
                  event.start && event.start.toDateString() === today.toDateString()
              ).length;
              
              this.stats.this_week = allEvents.filter(event => 
                  event.start && event.start >= startOfWeek && event.start <= endOfWeek
              ).length;
              
              this.stats.this_month = allEvents.filter(event => 
                  event.start && event.start >= startOfMonth && event.start <= endOfMonth
              ).length;
              
              this.stats.total = allEvents.length;
          },
          formatDate(date) {
              return format(date, 'PPP', { locale: locales[this.$i18n.locale] });
          },
          formatTime(date) {
              return format(date, 'HH:mm', { locale: locales[this.$i18n.locale] });
          },
          startTimeUpdater() {
            this.timeUpdaterInterval = setInterval(() => {
              const calendarApi = this.$refs.fullCalendar?.getApi();
              if (calendarApi) {
                calendarApi.setOption('now', new Date());
              }
            }, 60000);
          },
          setupTitleClickHandler() {
            const titleElement = document.querySelector('.fc-toolbar-title');
            
            if (titleElement) {
              titleElement.style.cursor = 'pointer';
              titleElement.addEventListener('click', this.openMonthPicker);
            }
          },
          showMonthPicker(calendarApi) {
            const currentDate = calendarApi.getDate();
            const month = currentDate.getMonth();
            const year = currentDate.getFullYear();
            
            // Создаем список месяцев
            const months = [...Array(12).keys()].map(m => 
              new Date(year, m).toLocaleString(this.$i18n.locale, { month: 'long' })
            );
            
            const selectedMonth = prompt(
              `${this.$t('msg.select_month')} (1-12):\n` + 
              months.map((m, i) => `${i+1}. ${m}`).join('\n'),
              month + 1
            );
            
            if (selectedMonth) {
              const monthIndex = parseInt(selectedMonth) - 1;
              calendarApi.gotoDate(new Date(year, monthIndex, 1));
            }
          },
          openMonthPicker() {
            const calendarApi = this.$refs.fullCalendar.getApi();
            const currentDate = calendarApi.getDate();
            this.currentMonth = currentDate.getMonth();
            this.currentYear = currentDate.getFullYear();
            this.updateMonthsList();
            this.showMonthPickerModal = true;
          },
          updateMonthsList() {
            this.monthsList = [...Array(12).keys()].map(m => 
              new Date(this.currentYear, m).toLocaleString(this.$i18n.locale, { month: 'long' })
            );
          },
          selectMonth(monthIndex) {
            const calendarApi = this.$refs.fullCalendar.getApi();
            calendarApi.gotoDate(new Date(this.currentYear, monthIndex, 1));
            this.showMonthPickerModal = false;
          },
          changeYear(delta) {
            this.currentYear += delta;
            this.updateMonthsList();
          }
        }
    };
</script>

<style scoped>
.fc .fc-daygrid-day-frame {
  min-height: 100px;
  position: relative;
  overflow: hidden;
  padding-bottom: 20px;
}

/* Стили для месячного вида (полное отображение) */
.fc-dayGridMonth-view .fc-daygrid-event {
  margin: 1px 2px;
  padding: 3px 4px;
  font-size: 12px;
  line-height: 1.3;
  border-radius: 4px;
  border-left: 3px solid;
  background-color: rgba(255, 255, 255, 0.95);
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.fc-dayGridMonth-view .event-content {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.fc-dayGridMonth-view .event-title {
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  padding: 2px 6px;
  font-size: 11px;
  border-radius: 12px;
}

.fc-dayGridMonth-view .event-client,
.fc-dayGridMonth-view .event-time {
  font-size: 10px;
  color: #555;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

/* Стили для недельного и дневного вида (упрощенное отображение) */
.fc-timeGridWeek-view .fc-event-main,
.fc-timeGridDay-view .fc-event-main {
  padding: 2px 4px;
}
.fc-timeGridWeek-view .fc-event-main i{
  display: none;
  visibility: hidden;
}
.fc-timeGridWeek-view .fc-event-title,
.fc-timeGridDay-view .fc-event-title {
  font-size: 12px;
  font-weight: 500;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: block;
}
.fc-timeGridDay-view .event-text {
  display: flex;
  gap: 10px;
  height: 100%;
  align-items: center;
}
.fc-timeGridDay-view .event-content {
  height: 100%;
}

.fc-timeGridWeek-view .event-content,
.fc-timeGridDay-view .event-content {
  display: block;
}
.fc-timeGridWeek-view .event-title {
  display: none;
  visibility: hidden;
}
.fc-timeGridWeek-view .event-time,
.fc-timeGridDay-view .event-time{
  display: none !important;
  color: #222 !important;
}
.fc-timeGridWeek-view .event-client,
.fc-timeGridDay-view .event-client,
.fc-timeGridWeek-view .event-text,
.fc-timeGridDay-view .event-text {
  color: #222 !important;
}

/* Общие стили для всех видов */
.fc .fc-daygrid-event:hover {
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  z-index: 1;
}

.fc .fc-daygrid-more-link {
  font-size: 11px;
  color: #4f46e5;
  font-weight: 500;
  padding: 1px 4px;
  border-radius: 3px;
  display: inline-block;
  background-color: rgba(238, 242, 255, 0.9);
  cursor: pointer;
  width: calc(100% - 4px);
  box-sizing: border-box;
  text-align: center;
}

.fc .fc-daygrid-day-bottom {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 0 2px 2px;
  text-align: center !important;
}

/* Адаптивные стили */
@media (max-width: 768px) {
  .fc .fc-daygrid-day-frame {
    min-height: 80px;
  }
  
  .fc-dayGridMonth-view .fc-daygrid-event {
    font-size: 11px;
    padding: 2px 3px;
  }
  
  .fc-timeGridWeek-view .fc-event-title,
  .fc-timeGridDay-view .fc-event-title {
    font-size: 11px;
  }
}

@media (max-width: 480px) {
  .fc .fc-daygrid-day-frame {
    min-height: 60px;
  }
  
  .fc-dayGridMonth-view .fc-daygrid-event {
    font-size: 10px;
    line-height: 1.2;
  }
  
  .fc-timeGridWeek-view .fc-event-title,
  .fc-timeGridDay-view .fc-event-title {
    font-size: 10px;
  }
}
.fc-listWeek-view .event-time {
  display: none !important;
}
</style>