import React from 'react';

// Components
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import BlockStyledEditor from '../../Framework/Components/Form/BlockStyledEditor';
import Grid, { GridLeft, GridRight } from '../../Framework/Layouts/Grid';
import ApproveForm from '../../Framework/Components/Form/ApproveForm';

const BrandChangeRequestForm = (props) => {
    return (
        <ApproveForm creates="brands" {...props}>
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
        </ApproveForm>
    );
};

export default BrandChangeRequestForm;
