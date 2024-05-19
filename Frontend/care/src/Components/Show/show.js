import React, { useState, useEffect } from 'react';
import { Card, Button } from 'react-bootstrap';
import './show.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FaUserNurse, FaUserMd, FaUserFriends } from 'react-icons/fa'; 
import { Link } from "react-router-dom";


const ShowServices = () => {
  const [services, setServices] = useState([]); // حالة لتخزين الخدمات

  useEffect(() => {
    // جلب البيانات من الـ API
    fetch('http://127.0.0.1:8000/api/services')
      .then(response => response.json())
      .then(data => {
        if (data.status === 200) {
          //  الخدمات مع الأيقونات المقابلة
          const updatedServices = data.data.map(service => {
            switch (service.name) {
              case 'تمريض منزلي':
                return { ...service, icon: <FaUserNurse size={50} /> };
              case 'علاج فيزيائي':
                return { ...service, icon: <FaUserMd size={50} /> };
              case 'مرافق صحّي':
                return { ...service, icon: <FaUserFriends size={50} /> };
              default:
                return service; 
            }
          });
          setServices(updatedServices);
        } else {
          console.error('Error:', data.msg);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }, []);

  return (
    <div className='Services' id='po'>
      <h2>الخدمات التي نوفرها</h2>
      <div className="services-layout">
        <div className="service-cards">
          {services.map((service, index) => (
            <Card key={index} className="service-card">
              <div className="icon-container">{service.icon}</div>
              <Card.Title className="service-title">{service.name}</Card.Title>
              <Card.Text className="service-description">{service.description}</Card.Text>
              <Link to="/nstate">
                <Button className="service-button">حجز الموعد</Button>
              </Link>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ShowServices;