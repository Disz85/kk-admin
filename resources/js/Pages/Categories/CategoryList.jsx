import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';

const CategoryList = (props) => {
    return (
        <List {...props} readonly>
            <Text name="id" />
            <Text name="name" />
            <Text name="slug" />
            <Text name="type" />
            <Text name="description" />
        </List>
    );
};

export default CategoryList;
