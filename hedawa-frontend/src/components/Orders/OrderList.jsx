// hedawa-frontend/src/components/Orders/OrderList.jsx
import React, { useState, useEffect } from 'react';
import { useAuth } from '../../context/AuthContext';
import { getUserOrders, deleteOrder } from '../../services/api';

const OrderList = () => {
  const [orders, setOrders] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  // const { user } = useAuth(); // Not needed for fetching orders

  useEffect(() => {
    fetchOrders();
    // eslint-disable-next-line
  }, []);

  const fetchOrders = async () => {
    try {
      setLoading(true);
      const response = await getUserOrders();
      setOrders(response.data);
    } catch (err) {
      setError('Failed to fetch orders');
      console.error('Error fetching orders:', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDeleteOrder = async (orderId) => {
    if (window.confirm('Are you sure you want to delete this order?')) {
      try {
        await deleteOrder(orderId);
        setOrders(orders.filter(order => order.id !== orderId));
      } catch (err) {
        setError('Failed to delete order');
        console.error('Error deleting order:', err);
      }
    }
  };

  if (loading) {
    return (
      <div className="order-list">
        <h2>My Orders</h2>
        <div className="loading">Loading orders...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="order-list">
        <h2>My Orders</h2>
        <div className="error">{error}</div>
      </div>
    );
  }

  return (
    <div className="order-list">
      <h2>My Orders</h2>
      {orders.length === 0 ? (
        <div className="no-orders">
          <p>No orders found. Place your first order!</p>
        </div>
      ) : (
        <div className="orders-grid">
          {orders.map(order => (
            <div key={order.id} className="order-card">
              <div className="order-header">
                <h3>Order #{order.id}</h3>
                <button 
                  onClick={() => handleDeleteOrder(order.id)}
                  className="delete-btn"
                  title="Delete Order"
                >
                  ×
                </button>
              </div>
              <div className="order-details">
                <p><strong>Item:</strong> {order.item}</p>
                <p><strong>Quantity:</strong> {order.quantity}</p>
                <p><strong>Address:</strong> {order.address}</p>
                <p><strong>Order Date:</strong> {new Date(order.created_at).toLocaleDateString()}</p>
              </div>
            </div>
          ))}
        </div>
      )}
      
      <style jsx>{`
        .order-list {
          max-width: 1200px;
          margin: 0 auto;
          padding: 20px;
        }

        .order-list h2 {
          color: #333;
          margin-bottom: 20px;
          text-align: center;
        }

        .loading, .error {
          text-align: center;
          padding: 20px;
          font-size: 16px;
        }

        .error {
          color: #dc3545;
          background-color: #f8d7da;
          border: 1px solid #f5c6cb;
          border-radius: 4px;
        }

        .no-orders {
          text-align: center;
          padding: 40px;
          color: #666;
        }

        .orders-grid {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
          gap: 20px;
          margin-top: 20px;
        }

        .order-card {
          background: #fff;
          border: 1px solid #ddd;
          border-radius: 8px;
          padding: 20px;
          box-shadow: 0 2px 4px rgba(0,0,0,0.1);
          transition: transform 0.2s;
        }

        .order-card:hover {
          transform: translateY(-2px);
          box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .order-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 15px;
        }

        .order-header h3 {
          margin: 0;
          color: #333;
        }

        .delete-btn {
          background: #dc3545;
          color: white;
          border: none;
          border-radius: 50%;
          width: 30px;
          height: 30px;
          cursor: pointer;
          font-size: 18px;
          line-height: 1;
          transition: background-color 0.2s;
        }

        .delete-btn:hover {
          background: #c82333;
        }

        .order-details p {
          margin: 8px 0;
          color: #555;
        }

        .order-details strong {
          color: #333;
        }

        @media (max-width: 768px) {
          .orders-grid {
            grid-template-columns: 1fr;
          }
          
          .order-list {
            padding: 15px;
          }
        }
      `}</style>
    </div>
  );
};

export default OrderList;