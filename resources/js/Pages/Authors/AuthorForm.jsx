import React from 'react';

//Components
import Form from '../../Framework/Components/Form/Form';
import Panel from '../../Framework/Components/Panel';
import TextInput from '../../Framework/Components/Form/TextInput';
import Image from '../../Framework/Components/Image/Image';

//Styles
import style from '../../../scss/components/form.module.scss';

const AuthorForm = (props) => {
    return (
        <Form {...props}>
            <Panel className={style.panel} title="Szerző adatai">
                <TextInput name="title" />
                <TextInput name="name" />
                <TextInput name="email" />
                <Image name="image" isCropping={false} />
                <TextInput name="description" />
            </Panel>
        </Form>
    );
};

export default AuthorForm;
