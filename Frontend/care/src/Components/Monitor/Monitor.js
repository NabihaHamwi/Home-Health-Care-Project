import React, { useEffect, useState } from 'react';
import { Container, Card, ListGroup } from 'react-bootstrap';
import { FaHeartbeat, FaLungs, FaTint, FaBrain } from 'react-icons/fa';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Monitor.css';

const Monitor = () => {
  const [patientData, setPatientData] = useState(null);
  const [sessionStartTime, setSessionStartTime] = useState(null);

  useEffect(() => {
  
    fetch('http://127.0.0.1:8000/api/sessions/1')
      .then(response => response.json())
      .then(data => {
        setPatientData(data.data.activities);
        setSessionStartTime(data.data.session_start_time);
      })
      .catch(error => console.error(error));
  }, []);

  const todayDate = new Date().toLocaleDateString('ar-EG', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });

  return (
    <Container className="monitor">
      <Card className="text-center">
        <Card.Header as="h2" className="title">لوحة متابعة المريض  <h4>{todayDate} {sessionStartTime && ` بداية الجلسة ${sessionStartTime}`}</h4> </Card.Header>
        <ListGroup variant="flush">
          {patientData && patientData.map((activity, index) => (
            <ListGroup.Item key={index}>
              <FaTint className="icon" /> {activity.name}: <span className="value">{activity.value}</span> <span className="time">({activity.time})</span>
            </ListGroup.Item>
          ))}
        </ListGroup>
      </Card>
    </Container>
  );
};

export default Monitor;
