import React from 'react';

const AdminDashboard = () => {
  return (
    <div>
      <h2>Admin Dashboard</h2>
      <div className="card">
        <h3>Admin Panel</h3>
        <p>Welcome to the admin dashboard. Here you can manage:</p>
        <ul>
          <li>View all bookings</li>
          <li>View all orders</li>
          <li>Manage rooms</li>
          <li>Manage users</li>
        </ul>
        <p><em>Additional admin features can be implemented here.</em></p>
      </div>
    </div>
  );
};

export default AdminDashboard;