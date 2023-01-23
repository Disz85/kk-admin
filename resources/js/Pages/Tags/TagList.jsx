import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import TagSearch from './TagSearch';

const TagList = (props) => {
    return (
        <List search={TagSearch} {...props}>
            <Text name="id" />
            <Edit name="name" />
            <Delete name="delete" list />
        </List>
    );
};

export default TagList;
