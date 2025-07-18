import React from 'react';
import Header from './Header';
import { useAuth } from '../../context/AuthContext';

const Layout = ({ children }) => {
  const { user } = useAuth();

  return (
    <div>
      {user && <Header />}
      <main className="container">
        {children}
      </main>
    </div>
  );
};

export default Layout;