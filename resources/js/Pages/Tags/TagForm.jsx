import React from 'react';

// COMPONENTS
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Toggle from '../../Framework/Components/Form/Toggle';
import Grid, { GridLeft } from '../../Framework/Layouts/Grid';

const TagForm = (props) => (
    <Form {...props}>
        <Grid>
            <GridLeft>
                <TextInput name="name" />
                <TextInput name="description" />
                <Toggle name="is_highlighted" />
            </GridLeft>
        </Grid>
    </Form>
);

export default TagForm;
