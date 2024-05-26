import React, { useState, useEffect } from 'react';
import { Container, Table, Button } from 'react-bootstrap';
import axios from 'axios';
import './accept.css';

const AcceptanceInterface = ({ provider }) => {
  const [requests, setRequests] = useState([]); // Holds pending requests
  const [acceptedRequests, setAcceptedRequests] = useState([]);
  const [rejectedRequests, setRejectedRequests] = useState([]);

  useEffect(() => {
    const fetchPendingRequests = async () => {
      try {
        const response = await axios.get('http://127.0.0.1:8000/api/pending-appointments/2');
        if (response.data && Array.isArray(response.data.data)) {
          setRequests(response.data.data);
        } else {
          console.error('Expected an object with a data property containing an array, but got:', response.data);
          setRequests([]); // Set to  empty array if data is not available
        }
      } catch (error) {
        console.error('Error fetching pending requests:', error);
      }
    };

    fetchPendingRequests();
  }, [provider]);

  const handleAccept = (requestId) => {
    setAcceptedRequests((prevAccepted) => [...prevAccepted, requestId]);
    // Send acceptance to the server
  };

  const handleReject = (requestId) => {
    setRejectedRequests((prevRejected) => [...prevRejected, requestId]);
    // Send rejection to the server
  };

  return (
    <Container>
      <h2>واجهة الطلبات المعلقة</h2>
      <Table striped bordered hover>
        <thead>
          <tr>
            <th>#</th>
            <th>Patient Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {Array.isArray(requests) ? requests.map((request, index) => (
            <tr key={request.appointment_id}> {/* Use a unique key */}
              <td>{index + 1}</td>
              <td>{request.patient_name}</td>
              <td>{request.appointment_date}</td>
              <td>{request.appointment_start_at}</td>
              <td>
                <Button className='button-accept' variant="success" onClick={() => handleAccept(request.appointment_id)}>
                 قبول
                </Button>
                {' '}
                <Button className='button-reject' variant="danger" onClick={() => handleReject(request.appointment_id)}>
                  رفض 
                </Button>
              </td>
            </tr>
          )) : null}
        </tbody>
      </Table>
    </Container>
  );
};

export default AcceptanceInterface;
