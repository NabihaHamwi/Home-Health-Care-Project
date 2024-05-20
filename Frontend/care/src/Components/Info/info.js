import React, { useState, useEffect } from 'react';
import { Form, Button, Container } from 'react-bootstrap';
import axios from 'axios'; 
import './info.css'

const PatientInfoForm = () => {
  const [patientInfo, setPatientInfo] = useState({});
  const [questions, setQuestions] = useState({});

  useEffect(() => {
    // جلب الأسئلة من الباك إند
    axios.get('http://127.0.0.1:8000/api/survey')
      .then(response => {
        setQuestions(response.data.data.questions);
        // تهيئة الحالة patientInfo بالقيم الفارغة لكل سؤال
        const initialPatientInfo = {};
        Object.keys(response.data.data.questions).forEach(key => {
          initialPatientInfo[key] = '';
        });
        setPatientInfo(initialPatientInfo);
      })
      .catch(error => console.error('Error fetching questions:', error));
  }, []);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setPatientInfo({ ...patientInfo, [name]: value });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log(patientInfo);
  };

  return (
    <Container className="my-5 info-container">
      <h2 className="text-center mb-4 tittle">استبيان السجل الطبي للمريض</h2>
      <Form onSubmit={handleSubmit}>
        {Object.entries(questions).map(([key, question]) => (
          <Form.Group controlId={key} key={key}>
            <Form.Label>{question}</Form.Label>
            <Form.Control
              type="text"
              name={key}
              value={patientInfo[key]}
              onChange={handleChange}
              required
            />
          </Form.Group>
        ))}
        <button type="submit" className="w-100 mt-3 info-btn">
          إرسال
        </button >
      </Form>
    </Container>
  );
};

export default PatientInfoForm;