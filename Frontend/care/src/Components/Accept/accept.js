import React, { useState } from 'react';
import { Button, Container, Table } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './accept.css'

const AcceptanceInterface = ({ requests }) => {
  const [acceptedRequests, setAcceptedRequests] = useState([]);
  const [rejectedRequests, setRejectedRequests] = useState([]);

  const handleAccept = (requestId) => {
    setAcceptedRequests([...acceptedRequests, requestId]);
    // يمكن هنا إضافة كود لإرسال القبول إلى الخادم
  };

  const handleReject = (requestId) => {
    setRejectedRequests([...rejectedRequests, requestId]);
    // يمكن هنا إضافة كود لإرسال الرفض إلى الخادم
  };

  return (
    <Container>
    <Table striped bordered hover>
      <thead>
        <tr>
          <th>#</th>
          <th>اسم المريض</th>
          <th>التاريخ</th>
          <th>الوقت</th>
          <th>الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        {requests.map((request, index) => (
          <tr key={request.id}>
            <td>{index + 1}</td>
            <td>{request.patientName}</td>
            <td>{request.date}</td>
            <td>{request.time}</td>
            <td>
              <button className='button-accept' variant="success" onClick={() => handleAccept(request.id)}>
                قبول
              </button>
              {' '}
              <button className='button-reject' variant="danger" onClick={() => handleReject(request.id)}>
                رفض
              </button>
            </td>
          </tr>
        ))}
      </tbody>
    </Table>
    </Container>
  );
};

export default AcceptanceInterface;