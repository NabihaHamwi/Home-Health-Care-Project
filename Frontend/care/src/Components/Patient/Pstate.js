
import React, { useState, useEffect } from 'react';
import { Container, Form, Table, Button, Row, Col } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './Pstate.css';

const PatientState = ({ appointment }) => {
  const [patientData, setPatientData] = useState({
    currentDate: '',
    startTime: '',
    vitalSigns: {}
  });

  useEffect(() => {
    const serverURL = 'http://127.0.0.1:8000/api/sessions/create/1';
    const fetchData = async () => {
      try {
        const response = await fetch(serverURL);
        const data = await response.json();
        if (data.status === 'success') {
          setPatientData({
            currentDate: data.data.appointment_date,
            startTime: '',
            vitalSigns: data.data.activities_name.reduce((acc, sign) => {
              acc[sign] = { value: '', time: '' };
              return acc;
            }, {})
          });
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    fetchData();
  }, [appointment]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    const [key, subkey] = name.split('.');
    if (subkey) {
      setPatientData(prevPatientData => ({
        ...prevPatientData,
        vitalSigns: {
          ...prevPatientData.vitalSigns,
          [key]: { ...prevPatientData.vitalSigns[key], [subkey]: value }
        }
      }));
    } else {
      setPatientData(prevPatientData => ({
        ...prevPatientData,
        [name]: value
      }));
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const serverURL = 'http://127.0.0.1:8000/api/sessions';
    try {
      const response = await fetch(serverURL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          start_time: patientData.startTime,
          activities: Object.entries(patientData.vitalSigns).map(([sign, { value, time }]) => ({
            id: sign,
            value,
            time
          }))
        })
      });
      const data = await response.json();
      if (data.status === 'success') {
        console.log('Session stored successfully:', data);
      } else {
        console.error('Failed to store session:', data.message);
      }
    } catch (error) {
      console.error('Error:', error);
    }
  };

  const handleNewSession = () => {
    setPatientData(prevPatientData => ({
      ...prevPatientData,
      vitalSigns: Object.keys(prevPatientData.vitalSigns).reduce((acc, sign) => {
        acc[sign] = { value: '', time: '' };
        return acc;
      }, {})
    }));
  };

  return (
    <Container className="dashboard">
      <h2 className="text-center mb-4 title">جلسة متابعة المريض</h2>
      <Form onSubmit={handleSubmit} className="patient-form">
        <Row>
          <Col>
            <Form.Group className="mb-3">
              <Form.Label>التاريخ</Form.Label>
              <Form.Control
                type="date"
                name="currentDate"
                value={patientData.currentDate}
                onChange={handleChange}
                disabled
              />
            </Form.Group>
          </Col>
          <Col>
            <Form.Group className="mb-3">
              <Form.Label>وقت البدء</Form.Label>
              <Form.Control
                type="time"
                name="startTime"
                value={patientData.startTime}
                onChange={handleChange}
              />
            </Form.Group>
          </Col>
        </Row>
        <Table responsive="md" striped bordered hover className="text-center patient-table">
          <thead>
            <tr>
              <th>العلامة الحيوية</th>
              <th>القيمة</th>
              <th>الوقت</th>
            </tr>
          </thead>
          <tbody>
            {Object.entries(patientData.vitalSigns).map(([sign, { value, time }], index) => (
              <tr key={index}>
                <td>{sign}</td>
                <td>
                  <Form.Control
                    type="text"
                    name={`${sign}.value`}
                    value={value}
                    onChange={handleChange}
                  />
                </td>
                <td>
                  <Form.Control
                    type="time"
                    name={`${sign}.time`}
                    value={time}
                    onChange={handleChange}
                  />
                </td>
              </tr>
            ))}
          </tbody>
        </Table>
        <Button type="submit" className="mt-3 btnn">
          تحديث البيانات
        </Button>
        <Button onClick={handleNewSession} className="mt-3 ml-2 btnn">
           جلسة جديدة
        </Button>
      </Form>
    </Container>
  );
};

export default PatientState;
