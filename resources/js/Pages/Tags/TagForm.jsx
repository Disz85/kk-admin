import Form from "../../Framework/Components/Form/Form";
import Panel from "../../Framework/Components/Panel";
import TextInput from "../../Framework/Components/Form/TextInput";
import React from "react";

const TagForm = (props) => (
    <Form {...props}>
        <Panel>
            <TextInput name={ 'name' }/>
            <TextInput name={ 'description' }/>
        </Panel>
    </Form>
);

export default TagForm;
