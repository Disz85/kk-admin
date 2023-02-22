import React from 'react';

import Search from '../../Framework/Components/Search';
import Text from '../../Framework/Components/Form/TextInput';

const ArticleSearch = (props) => (
    <Search {...props}>
        <Text name="title" />
    </Search>
);

export default ArticleSearch;
