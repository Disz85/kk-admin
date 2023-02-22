import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Panel from '../../Framework/Components/Panel';
import Flex from '../../Framework/Layouts/Flex';
import Toggle from '../../Framework/Components/Form/Toggle';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import TextArea from '../../Framework/Components/Form/TextArea';
import TagInput from '../../Framework/Components/Form/TagInput';
import AuthorInput from '../../Framework/Components/Form/AuthorInput';
import CategoryInput from '../../Framework/Components/Form/CategoryInput';
import DateTime from '../../Framework/Components/Form/DateTime';

const ArticleForm = (props) => {
    return (
        <Form {...props}>
            <Flex>
                <Panel>
                    <TextInput name="title" />
                    <TextArea name="lead" />
                    <BlockStyledEditor name="body" fullFeatureSet />
                </Panel>
                <Panel>
                    <Toggle name="is_active" />
                    <Toggle name="is_sponsored" />
                    <Toggle name="is_18_plus" />
                    <Image name="image" isCropping={false} />
                    <AuthorInput name="authors" />
                    <CategoryInput name="categories" type="article" />
                    <TagInput name="tags" />
                    <DateTime name="published_at" />
                </Panel>
            </Flex>
        </Form>
    );
};

export default ArticleForm;
