import React from 'react';

// COMPONENTS
import Search from '../../Framework/Components/Search';
import Text from '../../Framework/Components/Form/TextInput';

const CategorySearch = (props) => (
    <Search {...props}>
        <Text name="name" />
    </Search>
);

export default CategorySearch;
