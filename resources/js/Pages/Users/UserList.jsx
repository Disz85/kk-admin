import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';

const UserList = (props) => {
    return (
        <List {...props} readonly>
            <Text name="id" />
            <Text name="lastname" />
            <Text name="firstname" />
            <Text name="email" />
            <Text name="username" />
            <Text name="birth_year" />
            <Text name="skin_type" />
            <Text name="skin_concern" />
        </List>
    );
};

export default UserList;
