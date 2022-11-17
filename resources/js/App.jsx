import React, { StrictMode } from 'react';
import ReactDOM from 'react-dom/client';

import Cms from './Cms';

const domContainer = document.getElementById('application-root');
const root = ReactDOM.createRoot(domContainer);

if (root) {
    root.render(
        <StrictMode>
            <Cms />
        </StrictMode>,
    );
}
