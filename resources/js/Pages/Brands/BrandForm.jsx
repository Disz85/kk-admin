import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import Grid, { GridLeft, GridRight } from '../../Framework/Layouts/Grid';

const BrandForm = (props) => {
    return (
        <Form {...props}>
            <Grid>
                <GridLeft>
                    <TextInput name="title" />
                    <TextInput name="url" />
                    <TextInput name="where_to_find" />
                    <BlockStyledEditor name="description" fullFeatureSet />
                </GridLeft>
                <GridRight>
                    <Image name="image" isCropping={false} />
                </GridRight>
            </Grid>
        </Form>
    );
};

export default BrandForm;
