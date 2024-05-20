import React, { useState } from 'react';
import './appointment.css';

const Schedule = () => {
  const [selectedSlot, setSelectedSlot] = useState(null);
  const [duration, setDuration] = useState(1); // الافتراضي هو ساعة واحدة

  const availableSlots = {
    'الأحد': [9, 10, 11, 14, 15],
    'الإثنين': [10, 11, 12, 16, 17],
    // ... يمكنك إضافة باقي الأيام والأوقات المتاحة
  };

  const bookedSlots = {}; // يمكنك إضافة الأوقات المحجوزة هنا

  const daysOfWeek = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

  const handleSlotClick = (day, hour) => {
    setSelectedSlot({ day, hour });
  };

  const handleBookingConfirmation = () => {
    if (selectedSlot && duration) {
      console.log(` حجز الموعد: ${selectedSlot.day} الساعة ${selectedSlot.hour} لمدة ${duration} ساعة/ساعات`);
      // هنا يمكنك إضافة الكود لحفظ الحجز في النظام
      bookedSlots[selectedSlot.day + selectedSlot.hour] = true;
      setSelectedSlot(null);
      setDuration(1);
    }
  };

  return (
    <div className="schedule-container">
      <table className="schedule-table">
        <tbody>
          <tr>
            <td className="time-header">الساعة</td>
            {daysOfWeek.map(day => (
              <td key={day} className="day-header">{day}</td>
            ))}
          </tr>
          {[...Array(24).keys()].map(hour => (
            <tr key={hour}>
              <td className="hour">{hour}:00</td>
              {daysOfWeek.map(day => (
                <td
                  key={day + hour}
                  className={`slot ${availableSlots[day] && availableSlots[day].includes(hour) ? 'available' : ''} ${bookedSlots[day + hour] ? 'booked' : '' }`}
                  onClick={() => handleSlotClick(day, hour)}
                >
                  {bookedSlots[day + hour] ? 'محجوز' : 'حجز'}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
      {selectedSlot && (
        <div className="booking-form">
          <h3>حجز موعد</h3>
          <p>اليوم: {selectedSlot.day}, الساعة: {selectedSlot.hour}:00</p>
          <input
            type="number"
            min="1"
            max="12"
            placeholder="المدة بالساعات"
            value={duration}
            onChange={e => setDuration(e.target.value)}
          />
          <button onClick={handleBookingConfirmation}>تأكيد الحجز</button>
          <button onClick={() => setSelectedSlot(null)}>إلغاء</button>
        </div>
      )}
    </div>
  );
};

export default Schedule;