import { Link } from 'react-router-dom';
import React from 'react';

const Edit = ({ resource, entity, name }) => (
    <Link to={ `/${resource}/${entity.id}/show` } className={ 'a-link -default -primaryColor' }>
        { entity[name] }
    </Link>
);

export default Edit;
