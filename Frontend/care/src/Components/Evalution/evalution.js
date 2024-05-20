// // في مجلد Component/Evaluation.js
// import React from 'react';
// import { Form, Button, Container, Row, Col, InputGroup, FormControl, Card } from 'react-bootstrap';
// import 'bootstrap/dist/css/bootstrap.min.css';
// import './evalution.css'; // تأكد من إنشاء هذا الملف وتخصيص الأنماط
// import { useState } from 'react';
// const Evaluation = () => {
//   const [feedback, setFeedback] = useState('');
//   const [rating, setRating] = useState(0);

//   const handleSubmit = (event) => {
//     event.preventDefault();
//     // هنا يمكنك إضافة الكود لإرسال التقييم والملاحظات إلى الخادم أو قاعدة البيانات
//     console.log('Rating: ${rating}, Feedback: ${feedback}');
//   };

//   return (
//     <Container className="evaluation-container">
//       <Row className="justify-content-md-center">
//         <Col md={8}>
//           <Card>
//             <Card.Body>
//               <Card.Title className="text-center mb-4">تقييم الخدمة</Card.Title>
//               <Form onSubmit={handleSubmit}>
//                 <Form.Group>
//                   <Form.Label>التقييم:</Form.Label>
//                   <InputGroup>
//                     <InputGroup.Prepend>
//                       <Button variant="outline-secondary" onClick={() => setRating(rating > 0 ? rating - 1 : 0)}>-</Button>
//                     </InputGroup.Prepend>
//                     <FormControl value={rating} readOnly />
//                     <InputGroup.Append>
//                       <Button variant="outline-secondary" onClick={() => setRating(rating < 5 ? rating + 1 : 5)}>+</Button>
//                     </InputGroup.Append>
//                   </InputGroup>
//                 </Form.Group>
//                 <Form.Group>
//                   <Form.Label>الملاحظات:</Form.Label>
//                   <Form.Control as="textarea" rows={3} value={feedback} onChange={(e) => setFeedback(e.target.value)} />
//                 </Form.Group>
//                 <div className="text-center">
//                   <Button variant="primary" type="submit">إرسال</Button>
//                 </div>
//               </Form>
//             </Card.Body>
//           </Card>
//         </Col>
//       </Row>
//     </Container>
//   );
// };

// export default Evaluation;