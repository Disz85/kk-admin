import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';

const UserList = (props) => {
    return (
        <List {...props}>
            <Text name={ 'id' } />
            <Text name={ 'name' } />
            <Text name={ 'description' } />
        </List>
    );
};

export default UserList;
