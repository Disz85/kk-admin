import React from 'react';

import Form from '../../Framework/Components/Form/Form';
import Panel from '../../Framework/Components/Panel';
import TextInput from '../../Framework/Components/Form/TextInput';

const AuthorForm = (props) => (
    <Form {...props}>
        <Panel>
            <TextInput name="name" />
            <TextInput name="description" />
        </Panel>
    </Form>
);

export default AuthorForm;
