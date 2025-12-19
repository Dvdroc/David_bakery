import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const slots = {};

slots['2025-11-25'] = { remaining: 5, is_closed: false };
slots['2025-11-26'] = { remaining: 0, is_closed: true };

// Fungsi render kalender
function renderCalendar(date) {
    const year = date.getFullYear();
    const month = date.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    let html = '';

    for (let i = 0; i < firstDay; i++) html += '<div></div>';

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${month+1}-${day}`;
        const slot = slots[dateStr];
        let statusText = 'Kosong';

        if (slot) {
            statusText = slot.is_closed ? 'Ditutup' : 'Sisa ' + slot.remaining;
        }

        html += `<div>${day} - ${statusText}</div>`;
    }

    document.getElementById('calendarDays').innerHTML = html;
}

// contoh panggil
renderCalendar(new Date());