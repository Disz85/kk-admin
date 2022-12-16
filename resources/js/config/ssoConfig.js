const ssoConfig = {
    onLoad: 'login-required',
    silentCheckSsoRedirectUri: `${window.location.origin}/silent-check-sso`,
    promiseType: 'native',
};

export default ssoConfig;
