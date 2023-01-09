import Search from "../../Framework/Components/Search";
import Text from "../../Framework/Components/Form/TextInput";
import React from 'react';

const TagSearch = props => (
    <Search {...props}>
        <Text name={ 'name' } className={ 'mb-4 mb-md-0 mr-2' }/>
    </Search>
);

export default TagSearch;
