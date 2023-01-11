import React, { useState, useContext, useEffect } from 'react';
import PropTypes from 'prop-types';

import { useKeycloak } from '@react-keycloak/web';

import { HttpContext } from './HttpContext';

export const AuthContext = React.createContext({ user: null });

export const AuthProvider = ({ children }) => {
    // CONTEXTS
    const { http } = useContext(HttpContext);
    const { keycloak } = useKeycloak();

    // STATES
    const [auth, setAuth] = useState({
        user: null,
        authenticating: true,
        roles: [],
        permissions: [],
        authorized: false,
    });
    const { user, permissions, roles, authenticating, authorized } = auth;

    const logout = () => {
        http.logout().then(() => {
            window.location.replace(
                keycloak.createLogoutUrl({
                    redirectUri: import.meta.env.APP_URL,
                }),
            );
        });
    };

    const hasPermission = (permission) => {
        return (
            user['super-admin'] ||
            permissions.filter(({ name }) => name === permission).length === 0
        );
    };

    useEffect(() => {
        if (keycloak.authenticated) {
            http.xsrf().then(() => {
                http.login(keycloak.token)
                    .then(({ data }) => {
                        const { permissions, roles, ...user } = data;
                        setAuth((currentAuth) => ({
                            ...currentAuth,
                            permissions,
                            roles,
                            user,
                            authorized: true,
                        }));
                    })
                    .finally(() => {
                        setAuth((currentAuth) => ({
                            ...currentAuth,
                            authenticating: false,
                        }));
                    });
            });
        }
    }, [keycloak.authenticated]);

    if (authenticating) {
        <div>Loading...</div>;
    }

    if (!authorized) {
        return <div>Nope dude!</div>;
    }

    return (
        <AuthContext.Provider
            value={{ user, permissions, roles, logout, hasPermission }}
        >
            {children}
        </AuthContext.Provider>
    );
};

AuthProvider.propTypes = {
    /**
     * Type of resources
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};
