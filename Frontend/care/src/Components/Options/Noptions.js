import React, { useState, useEffect } from 'react';
import axios from 'axios';
import './Noptions.css';
import { useLocation, Link } from 'react-router-dom';

const Noptions = () => {
  const [providers, setProviders] = useState([]);
  const location = useLocation();

  useEffect(() => {
    // استخدم location.search للحصول على المعاملات من الرابط
    axios.get(`http://127.0.0.1:8000/api/search/result${location.search}`)
      .then(response => {
        // تأكد من أن response.data هو مصفوفة
        if (Array.isArray(response.data)) {
          // تحديث الحالة بالبيانات المسترجعة
          setProviders(response.data);
        } else {
          // إذا لم تكن مصفوفة، يمكنك تعيين قيمة افتراضية أو إظهار رسالة خطأ
          console.error('Expected an array of providers, but got:', response.data);
        }
      })
      .catch(error => {
        console.error('There was an error fetching the providers:', error);
      });
  }, [location.search]);

  // إضافة دالة لإرسال البيانات إلى الخادم
  const sendToDatabase = (data) => {
    axios.get('http://127.0.0.1:8000/api/search/result', data)
      .then(response => {
        console.log('Data sent successfully:', response);
      })
      .catch(error => {
        console.error('Error sending data:', error);
      });
  };

  // استدعاء sendToDatabase عند الحاجة لإرسال البيانات
  // على سبيل المثال، يمكن استدعاؤها عند النقر على زر الحجز

  return (
    <div className="providers-container">
      {providers.map(provider => (
        <div key={provider.id} className="provider-card">
          <img src={provider.image || 'default-image.png'} alt={provider.name} className="provider-image" />
          <div className="provider-info">
            <h3 className="provider-name">{provider.name}</h3>
            <p className="provider-experience">{provider.experience} سنوات خبرة</p>
            <Link to={`/booking/${provider.id}`}>
              <button className="booking-button" onClick={() => sendToDatabase(provider)}>احجز الآن</button>
            </Link>
          </div>
        </div>
      ))}
    </div>
  );
};

export default Noptions;