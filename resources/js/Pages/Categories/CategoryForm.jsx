import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import Toggle from '../../Framework/Components/Form/Toggle';
import CategoryParentInput from '../../Framework/Components/Form/CategoryParentInput';
import Grid, { GridLeft } from '../../Framework/Layouts/Grid';

const CategoryForm = (props) => {
    return (
        <Form {...props}>
            <Grid>
                <GridLeft>
                    <TextInput name="name" />
                    <BlockStyledEditor name="description" fullFeatureSet />
                    <CategoryParentInput />
                    <Toggle name="is_archived" />
                </GridLeft>
            </Grid>
        </Form>
    );
};

export default CategoryForm;
