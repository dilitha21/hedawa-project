import axios from 'axios';

const API_BASE_URL = 'http://localhost:5000';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
});

// Add token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Auth API
export const login = (email, password, role) => {
  return api.post('/api/login', { email, password, role });
};

export const register = (name, email, password, role) => {
  return api.post('/api/register', { name, email, password, role });
};

// Rooms API
export const getRooms = () => {
  return api.get('/api/rooms');
};

// Bookings API
export const createBooking = (room_id, start_date, end_date) => {
  return api.post('/api/bookings', { room_id, start_date, end_date });
};

export const getUserBookings = () => {
  return api.get('/api/bookings');
};

export const deleteBooking = (id) => {
  return api.delete(`/api/bookings/${id}`);
};

// Orders API
export const createOrder = (item, quantity, address) => {
  return api.post('/api/orders', { item, quantity, address });
};

export const getUserOrders = () => {
  return api.get('/api/orders');
};

export const deleteOrder = (id) => {
  return api.delete(`/api/orders/${id}`);
};

export default api;