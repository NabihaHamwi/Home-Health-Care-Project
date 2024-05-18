// wtime.js
import React, { useState } from 'react';
import { Form, Button, Container, Row, Col, Table, ToggleButton } from 'react-bootstrap';
import { BsToggleOn, BsToggleOff } from 'react-icons/bs';
import 'bootstrap/dist/css/bootstrap.min.css';
import './wtime.css'; // تأكد من تواجد الملف في المسار الصحيح

const daysOfWeek = ['الخميس', 'الأربعاء', 'الثلاثاء', 'الاثنين', 'الأحد', 'السبت', 'الجمعة'];

const WorkingTime = () => {
  const [selectedDays, setSelectedDays] = useState([]);
  const [schedule, setSchedule] = useState({});
  const [availability, setAvailability] = useState({});

  const handleDayClick = (day) => {
    const newSelectedDays = selectedDays.includes(day)
      ? selectedDays.filter(d => d !== day)
      : [...selectedDays, day];
    setSelectedDays(newSelectedDays);
  };

  const handleAvailabilityToggle = (day) => {
    setAvailability(prev => ({
      ...prev,
      [day]: !prev[day]
    }));
  };

  const handleTimeChange = (day, type, value) => {
    if (!availability[day]) {
      setSchedule({ ...schedule, [day]: { ...schedule[day], [type]: value } });
    }
  };

  return (
    <Container className="time-container">
        <Row className='header-title' ><h2 >أوقات العمل</h2></Row >
      <Row className="day-selection">
        {daysOfWeek.map((day) => (
          <Col key={day} xl={1} xs={5} sm={3} md={2}>
            <Button
            
              onClick={() => handleDayClick(day)}
              className="day-button"
            >
              {day}
            </Button>
          </Col>
        ))}
      </Row>
      <Table striped bordered hover className="schedule-table">
        <thead>
          <tr>
            <th>اليوم</th>
            <th>متاح 24 ساعة</th>
            <th>بداية الوقت</th>
            <th>نهاية الوقت</th>
          </tr>
        </thead>
        <tbody>
          {selectedDays.map((day) => (
            <tr key={day}>
              <td>{day}</td>
              <td>
                <ToggleButton
                  variant="outline-secondary"
                  className="availability-toggle"
                  onClick={() => handleAvailabilityToggle(day)}
                >
                  {availability[day] ? <BsToggleOn size={25} /> : <BsToggleOff size={25} />}
                </ToggleButton>
              </td>
              <td>
                <Form.Control
                  type="time"
                  value={availability[day] ? '' : schedule[day]?.start || ''}
                  onChange={(e) => handleTimeChange(day, 'start', e.target.value)}
                  disabled={availability[day]}
                />
              </td>
              <td>
                <Form.Control
                  type="time"
                  value={availability[day] ? '' : schedule[day]?.end || ''}
                  onChange={(e) => handleTimeChange(day, 'end', e.target.value)}
                  disabled={availability[day]}
                />
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
      <Row className='time-btn'>
        <Col>
          <Button className='bttun'  type="submit">
            حفظ الأوقات
          </Button>
        </Col>
      </Row>
    </Container>
  );
};

export default WorkingTime;