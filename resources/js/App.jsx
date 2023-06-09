import React from 'react';
import ReactDOM from 'react-dom/client';

import { ReactKeycloakProvider } from '@react-keycloak/web';
import { HttpProvider } from './Framework/Context/HttpContext';
import { AuthProvider } from './Framework/Context/AuthContext';

import ApplicationService from './Services/ApplicationService';

import keycloak from './Adapters/keycloak';
import ssoConfig from './config/ssoConfig';

import Dashboard from './Dashboard';

import './i18n';

const domContainer = document.getElementById('application-root');
const root = ReactDOM.createRoot(domContainer);

if (root) {
    root.render(
        <ReactKeycloakProvider authClient={keycloak} initOptions={ssoConfig}>
            <HttpProvider http={ApplicationService}>
                <AuthProvider>
                    <Dashboard />
                </AuthProvider>
            </HttpProvider>
        </ReactKeycloakProvider>,
    );
}
