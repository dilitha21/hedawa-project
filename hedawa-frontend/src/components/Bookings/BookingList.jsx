import React, { useState, useEffect } from 'react';
import { useAuth } from '../../context/AuthContext';
import { getUserBookings, deleteBooking } from '../../services/api';

const BookingList = () => {
  const { user } = useAuth();
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchBookings();
  }, []);

  const fetchBookings = async () => {
    setLoading(true);
    try {
      const response = await getUserBookings(user.id);
      setBookings(response.data);
    } catch (error) {
      setError('Failed to fetch bookings');
    }
    setLoading(false);
  };

  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to cancel this booking?')) {
      try {
        await deleteBooking(id);
        fetchBookings(); // Refresh the list
      } catch (error) {
        setError('Failed to cancel booking');
      }
    }
  };

  if (loading) return <div>Loading bookings...</div>;

  return (
    <div className="card">
      <h3>My Bookings</h3>
      {error && <div className="error">{error}</div>}
      {bookings.length === 0 ? (
        <p>No bookings found.</p>
      ) : (
        <table className="table">
          <thead>
            <tr>
              <th>Room Type</th>
              <th>Start Date</th>
              <th>End Date</th>
              <th>Price</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            {bookings.map(booking => (
              <tr key={booking.id}>
                <td>{booking.type}</td>
                <td>{new Date(booking.start_date).toLocaleDateString()}</td>
                <td>{new Date(booking.end_date).toLocaleDateString()}</td>
                <td>${booking.price}</td>
                <td>
                  <button
                    className="btn btn-danger"
                    onClick={() => handleDelete(booking.id)}
                  >
                    Cancel
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default BookingList;