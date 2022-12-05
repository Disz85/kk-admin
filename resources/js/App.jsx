import React, { StrictMode } from 'react';
import ReactDOM from 'react-dom/client';

import Dashboard from './Dashboard';

const domContainer = document.getElementById('application-root');
const root = ReactDOM.createRoot(domContainer);

if (root) {
    root.render(
        <StrictMode>
            <Dashboard />
        </StrictMode>,
    );
}
