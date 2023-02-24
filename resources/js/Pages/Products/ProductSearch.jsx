import React from 'react';

import Search from '../../Framework/Components/Search';
import Text from '../../Framework/Components/Form/TextInput';

const ProductSearch = (props) => (
    <Search {...props}>
        <Text name="name" />
    </Search>
);

export default ProductSearch;
