import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import ProductSearch from './ProductSearch';
import Boolean from '../../Framework/Components/Boolean';

const ProductList = (props) => {
    return (
        <List search={ProductSearch} {...props}>
            <Text name="id" />
            <Edit name="name" />
            <Text name="price" />
            <Text name="size" />
            <Boolean name="is_sponsored" />
            <Boolean name="is_18_plus" />
            <Boolean name="is_active" />
            <Text name="published_at" />
            <Delete name="delete" list />
        </List>
    );
};

export default ProductList;
