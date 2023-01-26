import React from 'react';

// COMPONENTS
import Form from '../../Framework/Components/Form/Form';
import Panel from '../../Framework/Components/Panel';
import TextInput from '../../Framework/Components/Form/TextInput';
import Toggle from '../../Framework/Components/Form/Toggle';

const TagForm = (props) => (
    <Form {...props}>
        <Panel className="md:w-2/4">
            <TextInput name="name" />
            <TextInput name="description" />
            <Toggle name="is_highlighted" />
        </Panel>
    </Form>
);

export default TagForm;
