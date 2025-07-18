import React, { useState } from 'react';
import BookingForm from '../Bookings/BookingForm';
import BookingList from '../Bookings/BookingList';
import OrderForm from '../Orders/OrderForm';
import OrderList from '../Orders/OrderList';

const CustomerDashboard = () => {
  const [activeTab, setActiveTab] = useState('bookings');

  return (
    <div>
      <h2>Customer Dashboard</h2>
      <div style={{ marginBottom: '20px' }}>
        <button
          className={`btn ${activeTab === 'bookings' ? 'btn-primary' : 'btn-secondary'}`}
          onClick={() => setActiveTab('bookings')}
          style={{ marginRight: '10px' }}
        >
          Room Bookings
        </button>
        <button
          className={`btn ${activeTab === 'orders' ? 'btn-primary' : 'btn-secondary'}`}
          onClick={() => setActiveTab('orders')}
        >
          Food Orders
        </button>
      </div>

      {activeTab === 'bookings' && (
        <div className="dashboard-grid">
          <BookingForm />
          <BookingList />
        </div>
      )}

      {activeTab === 'orders' && (
        <div className="dashboard-grid">
          <OrderForm />
          <OrderList />
        </div>
      )}
    </div>
  );
};

export default CustomerDashboard;