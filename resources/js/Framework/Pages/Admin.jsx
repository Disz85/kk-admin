import React, { useState, useContext } from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import ApplicationRoute from '../Routes/ApplicationRoutes';
// CONTEXTS
import ApplicationContext from '../Context/ApplicationContext';
import { HttpContext } from '../Context/HttpContext';
import { AuthContext } from '../Context/AuthContext';
// HELPER FUNCTIONS
import { permitted, listable } from '../../Helpers/listing';
// COLLECTIONS
import registerResources from '../../Collections/registerResources';
// LAYOUTS
import Header from '../Layouts/Header';
import Main from '../Layouts/Main';
import Aside from '../Layouts/Aside';
import Navigation from '../Layouts/Navigation/Navigation';
// PAGES
import NotFound from './NotFound';
import Home from './Home';

const Admin = ({ children }) => {
    // CONTEXTS
    const { http } = useContext(HttpContext);
    const { hasPermission } = useContext(AuthContext);

    // STATES
    const [pageInfo, setPageInfo] = useState({});
    const [resources] = useState(registerResources(children));

    return (
        <ApplicationContext.Provider value={setPageInfo}>
            <BrowserRouter>
                <Header>Header</Header>
                <Aside>
                    <Navigation
                        items={listable(permitted(resources, hasPermission))}
                    />
                </Aside>

                <Main description={pageInfo}>
                    <Routes>
                        <Route exact key="/" path="/" element={<Home />} />
                        {permitted(resources, hasPermission).map((resource) => (
                            <Route
                                exact
                                key={resource.path}
                                path={resource.path}
                                element={
                                    <ApplicationRoute
                                        component={resource.component}
                                        resource={resource.name}
                                        service={http}
                                    />
                                }
                            />
                        ))}
                        <Route key="404" path="*" element={<NotFound />} />
                    </Routes>
                </Main>
            </BrowserRouter>
        </ApplicationContext.Provider>
    );
};

Admin.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

export default Admin;
