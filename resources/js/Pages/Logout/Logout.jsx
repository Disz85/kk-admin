import { useContext } from 'react';
import { AuthContext } from '../../Framework/Context/AuthContext';

const Logout = () => {
    const { logout } = useContext(AuthContext);

    logout();

    return null;
};

export default Logout;
