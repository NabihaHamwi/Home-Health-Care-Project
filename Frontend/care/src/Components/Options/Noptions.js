// Noptions.js
import React, { useState, useEffect } from 'react';
import { Card, ListGroup, Image } from 'react-bootstrap';
import { useSearchParams } from 'react-router-dom';
import { ErrorBoundary } from 'react-error-boundary';
import './Noptions.css'; // استيراد ملف الستايل

const Noptions = () => {
  const [searchParams] = useSearchParams();
  const [providers, setProviders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const queryParams = searchParams.toString();
    fetch(`http://127.0.0.1:8000/api/search/result?${queryParams}`)
      .then(response => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data && data.status === 200 && data.data) {
          setProviders(data.data);
        } else {
          throw new Error('No providers found');
        }
      })
      .catch(error => {
        setError(`Error: ${error.message}`);
      })
      .finally(() => {
        setLoading(false);
      });
  }, [searchParams]);

  if (loading) return <div className="loading">جار التحميل...</div>;
  if (error) return <div className="error">حدث خطأ: {error}</div>;

  return (
    <ErrorBoundary FallbackComponent={() => <div className="error">حدث خطأ أثناء التحميل</div>}>
    <h2 className='title all '>مقدمي الرعاية المطابقين للمواصفات</h2>
      <div className="containers container all">
      
        {providers?.length > 0 ? (
          providers.map(provider => (
            <Card className="options-card" key={provider.id}>
              <Card.Header as="h5">{provider.first_name} {provider.last_name}</Card.Header>
              <ListGroup variant="flush">
                <ListGroup.Item>
                  <Image src={provider.image} roundedCircle className="provider-image" />
                </ListGroup.Item>
                <ListGroup.Item>العمر: {provider.age}</ListGroup.Item>
                <ListGroup.Item>الخبرة: {provider.experience} سنوات</ListGroup.Item>
                <ListGroup.Item>الخدمة: {provider.services.map(service => service.name).join(', ')}</ListGroup.Item>
                {/* ... وهكذا لبقية الخصائص */}
              </ListGroup>
            </Card>
          ))
        ) : (
          <div className="no-providers">لم يتم العثور على مقدمي الرعاية</div>
        )}
      </div>
    </ErrorBoundary>
  );
};

export default Noptions;