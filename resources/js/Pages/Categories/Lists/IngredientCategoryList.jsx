import React from 'react';

// COMPONENTS
import List from '../../../Framework/Pages/List/List';
import Text from '../../../Framework/Components/Text';
import Edit from '../../../Framework/Components/Buttons/Edit';
import CategorySearch from '../CategorySearch';
import Delete from '../../../Framework/Components/Buttons/Delete';

const IngredientCategoryList = (props) => {
    return (
        <List search={CategorySearch} {...props}>
            <Text name="id" />
            <Edit name="name" resource="categories" />
            <Text name="slug" />
            <Delete name="delete" list />
        </List>
    );
};

export default IngredientCategoryList;
