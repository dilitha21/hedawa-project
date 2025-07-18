import React, { useState } from 'react';
import { useAuth } from '../../context/AuthContext';
import { createOrder } from '../../services/api';

const OrderForm = () => {
  const { user } = useAuth();
  const [formData, setFormData] = useState({
    item: '',
    quantity: 1,
    address: ''
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  const menuItems = [
    'Rice & Curry',
    'Kottu Roti',
    'Fried Rice',
    'Noodles',
    'Hoppers',
    'String Hoppers',
    'Biriyani',
    'Deviled Chicken',
    'Fish Curry',
    'Dhal Curry'
  ];

  const handleChange = (e) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    setSuccess('');

    try {
      await createOrder(user.id, formData.item, formData.quantity, formData.address);
      setSuccess('Order placed successfully!');
      setFormData({ item: '', quantity: 1, address: '' });
    } catch (error) {
      setError(error.response?.data?.message || 'Failed to place order');
    }
    
    setLoading(false);
  };

  return (
    <div className="card">
      <h3>Order Food</h3>
      {error && <div className="error">{error}</div>}
      {success && <div className="success">{success}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Food Item:</label>
          <select
            name="item"
            value={formData.item}
            onChange={handleChange}
            required
          >
            <option value="">Select a food item</option>
            {menuItems.map(item => (
              <option key={item} value={item}>{item}</option>
            ))}
          </select>
        </div>
        <div className="form-group">
          <label>Quantity:</label>
          <input
            type="number"
            name="quantity"
            value={formData.quantity}
            onChange={handleChange}
            min="1"
            required
          />
        </div>
        <div className="form-group">
          <label>Delivery Address:</label>
          <textarea
            name="address"
            value={formData.address}
            onChange={handleChange}
            rows="3"
            required
          />
        </div>
        <button type="submit" className="btn btn-primary" disabled={loading}>
          {loading ? 'Placing Order...' : 'Place Order'}
        </button>
      </form>
    </div>
  );
};

export default OrderForm;