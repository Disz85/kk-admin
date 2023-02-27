import React from 'react';

import Search from '../../Framework/Components/Search';
import Text from '../../Framework/Components/Form/TextInput';

const IngredientSearch = (props) => (
    <Search {...props}>
        <Text name="name" />
    </Search>
);

export default IngredientSearch;
