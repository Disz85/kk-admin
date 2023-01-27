import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Panel from '../../Framework/Components/Panel';
import Flex from '../../Framework/Layouts/Flex';
import Toggle from '../../Framework/Components/Form/Toggle';

const BrandForm = (props) => {
    return (
        <Form {...props}>
            <Flex classNames="flex basis-8">
                <Panel>
                    <TextInput name="title" />
                    <TextInput name="url" />
                    <TextInput name="where_to_find" />
                    <TextInput name="description" />
                    <Toggle name="approved" />
                </Panel>
                <Panel>
                    <Image name="image" isCropping={false} />
                </Panel>
            </Flex>
        </Form>
    );
};

export default BrandForm;
