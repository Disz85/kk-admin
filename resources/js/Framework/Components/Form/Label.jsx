import React from 'react';

const Label = ({ to, text, icon, children, hidden = false }) => {
    if (hidden) {
        return null;
    }

    return (
        <label className="d-flex m-form__label mb-0" htmlFor={ to }>
            { icon && <i className={ `a-icon -xs -colorPrimary fal fa-${icon} mr-2`}></i> }
            { text }
            { children }
        </label>
    );
};

export default Label;
