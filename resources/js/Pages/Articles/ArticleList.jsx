import React from 'react';
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import Edit from '../../Framework/Components/Buttons/Edit';
import Delete from '../../Framework/Components/Buttons/Delete';
import ArticleSearch from './ArticleSearch';
import Boolean from '../../Framework/Components/Boolean';

const ArticleList = (props) => {
    return (
        <List search={ArticleSearch} {...props}>
            <Text name="id" />
            <Edit name="title" />
            <Boolean name="is_active" />
            <Boolean name="is_sponsored" />
            <Boolean name="is_18_plus" />
            <Text name="published_at" />
            <Delete name="delete" list />
        </List>
    );
};

export default ArticleList;
