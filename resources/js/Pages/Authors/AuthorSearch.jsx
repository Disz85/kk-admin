import React from 'react';

import Search from '../../Framework/Components/Search';
import Text from '../../Framework/Components/Form/TextInput';

const AuthorSearch = (props) => (
    <Search {...props}>
        <Text name="name" />
        <Text name="email" />
    </Search>
);

export default AuthorSearch;
