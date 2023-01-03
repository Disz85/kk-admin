import React from "react";

const  Submit = () => {
    return (
        <button type={ 'submit' } className={ 'm-button -add' }>
            <span className={ 'm-button__text' }>Mentés</span>
            <i className={ "far fa-save d-inline-block" }></i>
        </button>
    );
};

export default Submit;
