import React, { useState, useEffect } from 'react';
import './appointment.css';
import axios from 'axios';

const Schedule = ({ healthcareProviderId }) => {
  const [selectedSlot, setSelectedSlot] = useState(null);
  const [date, setDate] = useState(''); // تاريخ اليوم
  const [duration, setDuration] = useState(1); // المدة
  const [serviceType, setServiceType] = useState(''); // نوع الخدمة
  const [patientLocation, setPatientLocation] = useState(''); // مكان المريض
  const [availableSlots, setAvailableSlots] = useState({});
  const [bookedSlots, setBookedSlots] = useState({});

  useEffect(() => {
    axios.get(`http://127.0.0.1:8000/api/careprovidersworktimes/3`)
      .then(response => {
        if (response.data.status === "success") {
          const workTimes = response.data.data;
          const slots = {};
          workTimes.forEach(({ day_name, start_time, end_time }) => {
            const startHour = parseInt(start_time.split(':')[0], 10);
            const endHour = parseInt(end_time.split(':')[0], 10);
            if (!slots[day_name]) {
              slots[day_name] = [];
            }
            for (let hour = startHour; hour < endHour; hour++) {
              slots[day_name].push(hour);
            }
          });
          setAvailableSlots(slots);
        } else {
          console.error('Unexpected response:', response.data);
        }
      })
      .catch(error => console.error('There was an error!', error));
      axios.get(`http://127.0.0.1:8000/api/reserved-appointments/1/1`) // يمكنك تغيير الرقم 1 للأسبوع الذي تريده
      .then(response => {
        if (response.data.status === "success") {
          const reservedAppointments = response.data.data;
          const newBookedSlots = {};
          reservedAppointments.forEach(appointment => {
            const day = new Date(appointment.appointment_date).getDay();
            const startHour = parseInt(appointment.appointment_start_at.split(':')[0], 10);
            const durationHours = parseInt(appointment.appointment_duration.split(':')[0], 10);
            for (let hour = startHour; hour < startHour + durationHours; hour++) {
              newBookedSlots[`${daysOfWeek[day]}${hour}`] = true;
            }
          });
          setBookedSlots(newBookedSlots);
        } else {
          console.error('Unexpected response:', response.data);
        }
      })
      .catch(error => console.error('Error fetching reserved appointments:', error));
  }, [healthcareProviderId]);


  const daysOfWeek = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];

  const handleSlotClick = (day_name, hour) => {
    if (availableSlots[day_name] && availableSlots[day_name].includes(hour)) {
      setSelectedSlot({ day: day_name, hour });
    } else {
      alert('هذا الوقت غير متاح للحجز.');
    }
  };

  const handleBookingConfirmation = () => {
    if (selectedSlot && date && duration && serviceType && patientLocation) {
      console.log(`تم حجز الموعد: ${date} اليوم: ${selectedSlot.day} الساعة: ${selectedSlot.hour} لمدة: ${duration} ساعة/ساعات، نوع الخدمة: ${serviceType}، مكان المريض: ${patientLocation}`);
      // هنا يمكنك إضافة الكود لحفظ الحجز في النظام
      setBookedSlots(prev => ({ ...prev, [`${selectedSlot.day}${selectedSlot.hour}`]: true }));
      // إعادة تعيين الحقول
      setSelectedSlot(null);
      setDate('');
      setDuration(1);
      setServiceType('');
      setPatientLocation('');
      
    } else {
      alert('يرجى ملء جميع الحقول لتأكيد الحجز.');
    }
  };

  return (
    <div className="schedule-container all">
      <table className="schedule-table">
        <tbody>
          <tr>
            <td className="time-header">الساعة</td>
            {daysOfWeek.map(day_name => (
              <td key={day_name} className="day-header">{day_name}</td>
            ))}
          </tr>
          {[...Array(24).keys()].map(hour => (
            <tr key={hour}>
              <td className="hour">{hour}:00</td>
              {daysOfWeek.map(day_name => (
                <td
                  key={`${day_name}-${hour}`}
                  className={`slot ${availableSlots[day_name] && availableSlots[day_name].includes(hour) ? 'available' : 'unavailable'} ${bookedSlots[`${day_name}${hour}`] ? 'booked' : ''}`}
                  onClick={() => handleSlotClick(day_name, hour)}
                  style={{ backgroundColor: availableSlots[day_name] && availableSlots[day_name].includes(hour) ? 'blue' : 'transparent' }}
                >
                  {bookedSlots[`${day_name}${hour}`] ? 'محجوز' : (availableSlots[day_name] && availableSlots[day_name].includes(hour) ? 'حجز' : 'لا يوجد')}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
      {selectedSlot && (<div className="booking-form">
          <h3>حجز موعد</h3>
          <input
            type="date"
            value={date}
            onChange={e => setDate(e.target.value)}
            placeholder="تاريخ اليوم"
          />
          <p>اليوم: {selectedSlot.day}, الساعة: {selectedSlot.hour}:00</p>
          <input
            type="number"
            min="1"
            max="24"
            value={duration}
            onChange={e => setDuration(e.target.value)}
            placeholder="المدة بالساعات"
          />
          <input
            type="text"
            value={serviceType}
            onChange={e => setServiceType(e.target.value)}
            placeholder="نوع الخدمة"
          />
          <input
            type="text"
            value={patientLocation}
            onChange={e => setPatientLocation(e.target.value)}
            placeholder="مكان المريض"
          />
          <button onClick={handleBookingConfirmation}>تأكيد الحجز</button>
          <button onClick={() => setSelectedSlot(null)}>إلغاء</button>
        </div>
      )}
    </div>
  );
};

export default Schedule;