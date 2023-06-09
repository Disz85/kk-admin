import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import AuthorSearch from './AuthorSearch';

const AuthorList = (props) => {
    return (
        <List search={AuthorSearch} {...props}>
            <Text name="id" />
            <Edit name="name" />
            <Text name="email" />
            <Delete name="delete" list />
        </List>
    );
};

export default AuthorList;
