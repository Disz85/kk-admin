import React from 'react';
import List from '../../Framework/Pages/List/List';

const UserList = (props) => {
    return (
        <List {...props}>
            <Text name="id" />
        </List>
    );
};

export default UserList;
