import React from 'react';

//Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Panel from "../../Framework/Components/Panel";

const AuthorForm = (props) => {
    return (
        <Form {...props}>
            <div className="flex basis-8">
                <Panel>
                    <TextInput name="title" />
                    <TextInput name="name" />
                    <TextInput name="email" />
                    <TextInput name="description" />
                </Panel>
                <Panel>
                    <Image name="image" isCropping={false} />
                </Panel>
            </div>
        </Form>
    );
};

export default AuthorForm;
