import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import IngredientSearch from './IngredientSearch';

const IngredientList = (props) => {
    return (
        <List search={IngredientSearch} {...props}>
            <Text name="id" />
            <Edit name="name" />
            <Text name="ewg_data" />
            <Text name="ewg_score" />
            <Text name="ewg_score_max" />
            <Text name="comedogen_index" />
            <Delete name="delete" list />
        </List>
    );
};

export default IngredientList;
