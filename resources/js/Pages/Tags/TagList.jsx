import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Edit';
import Delete from '../../Framework/Components/Delete';

const UserList = (props) => {
    return (
        <List {...props}>
            <Text name={ 'id' } />
            <Edit name={ 'name' } />
            <Text name={ 'description' } />
            <Delete list name={ 'delete' } unlabeled/>
        </List>
    );
};

export default UserList;
