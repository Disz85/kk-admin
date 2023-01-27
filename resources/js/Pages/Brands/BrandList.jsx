import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import BrandSearch from './BrandSearch';

const BrandList = (props) => {
    return (
        <List search={BrandSearch} {...props}>
            <Text name="id" />
            <Edit name="title" />
            <Text name="url" />
            <Text name="where_to_find" />
            <Delete name="delete" list />
        </List>
    );
};

export default BrandList;
