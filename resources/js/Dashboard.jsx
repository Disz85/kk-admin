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
import AuthorList from './Pages/Authors/AuthorList';
import AuthorForm from './Pages/Authors/AuthorForm';
import BrandList from './Pages/Brands/BrandList';
import BrandForm from './Pages/Brands/BrandForm';

const Dashboard = () => {
    return (
        <Admin>
            <Resource name="users" list={UserList} />
            <Resource name="authors" list={AuthorList} form={AuthorForm} />
            <Resource name="brands" list={BrandList} form={BrandForm} />
            <Resource name="tags" list={TagList} form={TagForm} />
            <Resource
                name="logout"
                routes={logoutRoutes}
                requiresPermission={false}
            />
        </Admin>
    );
};

export default Dashboard;
