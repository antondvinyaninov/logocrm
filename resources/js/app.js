import './bootstrap';

import Alpine from 'alpinejs';
import scheduleCalendar from './schedule-calendar';

window.Alpine = Alpine;

Alpine.data('scheduleCalendar', scheduleCalendar);

Alpine.start();
