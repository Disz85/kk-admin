import React from 'react';

// STYLE
import style from '../../../../scss/components/buttons/button.module.scss';

const Submit = () => {
    return (
        <button className={ style.submit } type="submit">
            <span>Mentés</span>
        </button>
    );
};

export default Submit;
