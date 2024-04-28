
import './show.css'
import React from 'react';
import { Card, Button } from 'react-bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { FaUserNurse, FaUserMd, FaUserFriends } from 'react-icons/fa'; // استيراد أيقونات FontAwesome



const services = [
  {
    title: 'ممرض',
    description: 'استمتع براحة البال مع خدمات التمريض المنزلية المتميزة.',
    icon: <FaUserNurse size={50} />
  },
  {
    title: 'معالج فيزيائي',
    description: 'تجاوز حدود الشفاء مع معالجينا الفيزيائيين الخبراء.',
    icon: <FaUserMd size={50} />
  },
  {
    title: 'مرافق صحي',
    description: 'اكتشف الراحة والأمان مع مرافقينا الصحيين المتفانين.',
    icon: <FaUserFriends size={50} />
  }
];

const ShowServices = () => {
  return (
    <div className='Services' id='po'>
        <h2  >الخدمات التي نوفرها</h2>
    <div className="services-layout">
        
      {/* <div className="services-intro">
        <h5>Services</h5>
        <h2 className="services-header">نوفر لكم خدمات متنوعة من الرعاية الصحية.</h2>
        <p className="services-description">خدماتنا الطبية تشمل مجموعة واسعة من التخصصات لتلبية جميع احتياجاتك الصحية.</p>
      </div> */}
      <div className="service-cards">
        {services.map((service, index) => (
          <Card key={index} className="service-card">
            <div className="icon-container">{service.icon}</div>
            <Card.Title className="service-title">{service.title}</Card.Title>
            <Card.Text className="service-description">{service.description}</Card.Text>
            <Button variant="primary" className="service-button ">حجز الموعد</Button>
          </Card>
        ))}
      </div>
    </div>
    </div>
  );
};

export default ShowServices;