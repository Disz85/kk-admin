import React from 'react';

// Components
import Form from '../../Framework/Components/Form/Form';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';
import Grid, { GridLeft } from '../../Framework/Layouts/Grid';

const AuthorForm = (props) => {
    return (
        <Form {...props}>
            <Grid>
                <GridLeft>
                    <TextInput name="title" />
                    <TextInput name="name" />
                    <Image name="image" isCropping={false} />
                    <TextInput name="email" />
                    <TextInput name="description" />
                </GridLeft>
            </Grid>
        </Form>
    );
};

export default AuthorForm;
