import React, { useState, useEffect } from 'react';
import { useAuth } from '../../context/AuthContext';
import { getRooms, createBooking } from '../../services/api';

const BookingForm = () => {
  const { user } = useAuth();
  const [rooms, setRooms] = useState([]);
  const [formData, setFormData] = useState({
    room_id: '',
    start_date: '',
    end_date: ''
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    fetchRooms();
  }, []);

  const fetchRooms = async () => {
    try {
      const response = await getRooms();
      setRooms(response.data);
    } catch (error) {
      setError('Failed to fetch rooms');
    }
  };

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
      await createBooking(user.id, formData.room_id, formData.start_date, formData.end_date);
      setSuccess('Booking created successfully!');
      setFormData({ room_id: '', start_date: '', end_date: '' });
    } catch (error) {
      setError(error.response?.data?.message || 'Failed to create booking');
    }
    
    setLoading(false);
  };

  return (
    <div className="card">
      <h3>Book a Room</h3>
      {error && <div className="error">{error}</div>}
      {success && <div className="success">{success}</div>}
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label>Room:</label>
          <select
            name="room_id"
            value={formData.room_id}
            onChange={handleChange}
            required
          >
            <option value="">Select a room</option>
            {rooms.map(room => (
              <option key={room.id} value={room.id}>
                {room.type} - ${room.price}/night
              </option>
            ))}
          </select>
        </div>
        <div className="form-group">
          <label>Start Date:</label>
          <input
            type="date"
            name="start_date"
            value={formData.start_date}
            onChange={handleChange}
            required
          />
        </div>
        <div className="form-group">
          <label>End Date:</label>
          <input
            type="date"
            name="end_date"
            value={formData.end_date}
            onChange={handleChange}
            required
          />
        </div>
        <button type="submit" className="btn btn-primary" disabled={loading}>
          {loading ? 'Booking...' : 'Book Room'}
        </button>
      </form>
    </div>
  );
};

export default BookingForm;