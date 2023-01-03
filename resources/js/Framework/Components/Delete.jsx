import { Button } from "./Button";
import React from 'react';

const Delete = ({ entity, remove, circle = false, ...props }) => (
    <Button className={ `${circle ? '-circle -md' : ''}` } style={ 'link' } click={ () => remove(entity) } icon={ 'trash-alt' } {...props} unlabeled/>
);

export default Delete;
