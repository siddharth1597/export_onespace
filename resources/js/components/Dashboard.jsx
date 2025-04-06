import React from 'react';
import Home from './Home';

import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';

const Dashboard = () => {

    return (
        <Router>
            <Routes>
                <Route path="/home" element={<Home />} />
            </Routes>
        </Router>
    );
}

export default Dashboard;
