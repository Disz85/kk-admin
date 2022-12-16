import Keycloak from 'keycloak-js';

export default new Keycloak(`${window.location.origin}/keycloak.json`);
