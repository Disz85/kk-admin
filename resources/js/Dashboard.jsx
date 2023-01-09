import React from 'react';
// PAGE
import Admin from './Framework/Pages/Admin';
// RESOURCES
import Resource from './Framework/Resource';
// ROUTES
import { logoutRoutes } from './Routes/web';
// PAGES
import UserList from './Pages/Users/UserList';
import TagList from './Pages/Tags/TagList';
import TagForm from './Pages/Tags/TagForm';
import CategoryList from './Pages/Categories/CategoryList';

const Dashboard = () => {
    return (
        <Admin>
            <Resource name="users" list={ UserList }/>
            <Resource name="tags" list={ TagList } form={ TagForm }/>
            <Resource name="categories" list={ CategoryList }/>
            <Resource
                name="logout"
                routes={ logoutRoutes }
                requiresPermission={ false }
            />
        </Admin>
    );
};

export default Dashboard;
