// hedawa-backend/index.js
const express = require('express');
const cors = require('cors');
const mysql = require('mysql2/promise');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
require('dotenv').config();

const app = express();
const port = process.env.PORT || 5000;
const JWT_SECRET = process.env.JWT_SECRET || 'your_jwt_secret';

app.use(cors());
app.use(express.json());

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'hedawa',
  port: process.env.DB_PORT || 3307,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

(async () => {
  try {
    const connection = await pool.getConnection();
    console.log(' Connected to MySQL DB');
    connection.release();
  } catch (err) {
    console.error('DB connection error:', err);
  }
})();

app.get('/', (req, res) => {
  res.send('Hedawa Restaurant backend running');
});

// User Registration
app.post('/api/register', async (req, res) => {
  const { name, email, password, role } = req.body;
  if (!name || !email || !password || !role) return res.status(400).json({ message: 'Missing fields' });

  try {
    const [existing] = await pool.query('SELECT id FROM users WHERE email = ?', [email]);
    if (existing.length) return res.status(409).json({ message: 'User already exists' });

    const hashedPassword = await bcrypt.hash(password, 10);
    await pool.query('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)', [name, email, hashedPassword, role]);

    res.status(201).json({ message: 'User registered' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Server error' });
  }
});

// User Login
app.post('/api/login', async (req, res) => {
  const { email, password, role } = req.body;
  if (!email || !password || !role) return res.status(400).json({ message: 'Missing fields' });

  try {
    const [users] = await pool.query('SELECT * FROM users WHERE email = ? AND role = ?', [email, role]);
    if (!users.length) return res.status(401).json({ message: 'Invalid credentials' });

    const user = users[0];
    const match = await bcrypt.compare(password, user.password);
    if (!match) return res.status(401).json({ message: 'Invalid credentials' });

    const token = jwt.sign({ id: user.id, role: user.role }, JWT_SECRET, { expiresIn: '1h' });
    res.json({ token, user: { id: user.id, name: user.name, email: user.email, role: user.role } });
  } catch (err) {
    console.error(err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get all rooms
app.get('/api/rooms', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT * FROM rooms');
    res.json(rows);
  } catch (err) {
    console.error('Error fetching rooms:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Add booking
app.post('/api/bookings', async (req, res) => {
  const { user_id, room_id, start_date, end_date } = req.body;
  if (!user_id || !room_id || !start_date || !end_date) return res.status(400).json({ message: 'Missing fields' });

  try {
    await pool.query('INSERT INTO bookings (user_id, room_id, start_date, end_date) VALUES (?, ?, ?, ?)', [user_id, room_id, start_date, end_date]);
    res.status(201).json({ message: 'Booking created' });
  } catch (err) {
    console.error('Booking error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get user bookings
app.get('/api/bookings', async (req, res) => {
  const { user_id } = req.query;
  if (!user_id) return res.status(400).json({ message: 'user_id is required' });

  try {
    const [rows] = await pool.query(`
      SELECT b.id, b.start_date, b.end_date, r.type, r.price
      FROM bookings b
      JOIN rooms r ON b.room_id = r.id
      WHERE b.user_id = ?`, [user_id]);
    res.json(rows);
  } catch (err) {
    console.error('Fetch bookings error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Delete booking
app.delete('/api/bookings/:id', async (req, res) => {
  const { id } = req.params;
  try {
    await pool.query('DELETE FROM bookings WHERE id = ?', [id]);
    res.json({ message: 'Booking deleted' });
  } catch (err) {
    console.error('Delete booking error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Add food order
app.post('/api/orders', async (req, res) => {
  const { user_id, item, quantity, address } = req.body;
  if (!user_id || !item || !quantity || !address) return res.status(400).json({ message: 'Missing fields' });

  try {
    await pool.query('INSERT INTO orders (user_id, item, quantity, address) VALUES (?, ?, ?, ?)', [user_id, item, quantity, address]);
    res.status(201).json({ message: 'Order placed' });
  } catch (err) {
    console.error('Order error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Get user orders
app.get('/api/orders', async (req, res) => {
  const { user_id } = req.query;
  if (!user_id) return res.status(400).json({ message: 'user_id is required' });

  try {
    const [rows] = await pool.query('SELECT * FROM orders WHERE user_id = ?', [user_id]);
    res.json(rows);
  } catch (err) {
    console.error('Fetch orders error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

// Delete order
app.delete('/api/orders/:id', async (req, res) => {
  const { id } = req.params;
  try {
    await pool.query('DELETE FROM orders WHERE id = ?', [id]);
    res.json({ message: 'Order deleted' });
  } catch (err) {
    console.error('Delete order error:', err);
    res.status(500).json({ message: 'Server error' });
  }
});

app.listen(port, () => {
  console.log(` Server running on http://localhost:${port}`);
});