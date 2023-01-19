import React from 'react';
import style from '../../../../scss/components/image.module.scss';

const ImagePlaceholder = () => {
    return (
        <div className={ style.placeholder }>
            <h2 className={ style.placeholderTitle }>Még nem töltöttél fel képet</h2>
        </div>
    );
};

export default ImagePlaceholder;
