import React, { useState, useEffect } from 'react';
import { Container, Table, Spinner, Button } from 'react-bootstrap';
import axios from 'axios';
import './accept.css';

const AcceptanceInterface = ({ provider }) => {
  const [requests, setRequests] = useState([]);
  const [appointmentDetails, setAppointmentDetails] = useState(null);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    const fetchPendingRequests = async () => {
      try {
        setLoading(true);
        const response = await axios.get(`http://127.0.0.1:8000/api/pending-appointments/3`);
        if (response.data && Array.isArray(response.data.data)) {
          setRequests(response.data.data);
        } else {
          console.error('Expected an object with a data property containing an array, but got:', response.data);
          setRequests([]);
        }
      } catch (error) {
        console.error('Error fetching pending requests:', error);
      } finally {
        setLoading(false);
      }
    };

    fetchPendingRequests();
  }, [provider]);

  const handleAccept = async (appointmentId, groupId) => {
    try {
      setLoading(true);
      const response = await axios.put(`http://127.0.0.1:8000/api/set_appointment_status/${appointmentId}/${groupId ? '/' + groupId : ''}`, {
        status: true
      });
      if (response.data.status === 'success') {
        console.log('Appointment accepted:', response.data);
        // تحديث الواجهة هنا إذا لزم الأمر
      } else {
        console.error('Failed to accept appointment:', response.data);
      }
    } catch (error) {
      console.error('Error sending acceptance:', error);
    } finally {
      setLoading(false);
    }
  };


  const handleReject = async (appointmentId, groupId) => {
    try {
      setLoading(true);
      const response = await axios.put(`http://127.0.0.1:8000/api/set_appointment_status/${appointmentId}/${groupId ? '/' + groupId : ''}`, {
        status: false
      });
      if (response.data.status === 'success') {
        console.log('Appointment rejected:', response.data);
        // تحديث الواجهة هنا إذا لزم الأمر
      } else {
        console.error('Failed to reject appointment:', response.data);
      }
    } catch (error) {
      console.error('Error sending rejection:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleDetails = async (appointmentId, groupId) => {
    const groupParam = groupId ? `/${groupId}` : '';
    try {
      setLoading(true);
      const response = await axios.get(`http://127.0.0.1:8000/api/pending-appointment-details/3/2`);
      if (response.data && response.data.data) {
        setAppointmentDetails(response.data.data[0]);
      } else {
        console.error('Expected an object with a data property, but got:', response.data);
        setAppointmentDetails(null);
      }
    } catch (error) {
      console.error('Error fetching appointment details:', error);
      setAppointmentDetails(null);
    } finally {
      setLoading(false);
    }
  };

  return (
    <Container className='all'>
      <h2 className='title'>واجهة الطلبات لمقدم الرعاية</h2>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>#</th>
            <th>اسم المريض</th>
            <th>نوع الخدمة</th>
            {/* <th> تاريخ الموعد </th> */}
            <th>الإجراءات</th>
          </tr>
        </thead>
        <tbody>
          {requests.map((request, index) => (
            <tr key={request.appointment_id}>
              <td>{index + 1}</td>
              <td>{request.patient_name}</td>
              <td>{request.service_name}</td>
              {/* <td>{request.appointment_date}</td> */}
              <td>
                <button className='btn success' variant='success' onClick={() => handleAccept(request.appointment_id)}>
                  قبول
                </button>
                {' '}
                <button className='btn success' variant='danger' onClick={() => handleReject(request.appointment_id)}>
                  رفض
                </button>
                {' '}
                <button className='btn info' variant='info' onClick={() => handleDetails(request.appointment_id, request.group_id)}>
                  التفاصيل
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </Table>
      {loading && <Spinner animation='border' variant='primary' />}
      {appointmentDetails && (
        <div className='appointment-details'>
          <h3>تفاصيل الموعد:</h3>
          <p>التاريخ: {appointmentDetails.appointment_date}</p>
          <p>البداية: {appointmentDetails.appointment_start_at}</p>
          <p>المدة: {appointmentDetails.appointment_duration}</p>
          <p>نوع الخدمة: {appointmentDetails.service_name}</p>
        </div>
      )}
    </Container>
  );
};

export default AcceptanceInterface;