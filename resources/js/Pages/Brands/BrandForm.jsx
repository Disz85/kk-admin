import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Panel from '../../Framework/Components/Panel';
import Flex from '../../Framework/Layouts/Flex';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';

const BrandForm = (props) => {
    return (
        <Form {...props}>
            <Flex>
                <Panel>
                    <TextInput name="title" />
                    <TextInput name="url" />
                    <TextInput name="where_to_find" />
                    <Image name="image" isCropping={false} />
                </Panel>
                <Panel>
                    <BlockStyledEditor name="description" fullFeatureSet />
                </Panel>
            </Flex>
        </Form>
    );
};

export default BrandForm;
