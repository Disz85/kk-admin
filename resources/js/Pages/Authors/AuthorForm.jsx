import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Panel from '../../Framework/Components/Panel';
import Flex from '../../Framework/Layouts/Flex';

const AuthorForm = (props) => {
    return (
        <Form {...props}>
            <Flex>
                <Panel>
                    <TextInput name="title" />
                    <TextInput name="name" />
                    <TextInput name="email" />
                    <TextInput name="description" />
                </Panel>
                <Panel>
                    <Image name="image" isCropping={false} />
                </Panel>
            </Flex>
        </Form>
    );
};

export default AuthorForm;
