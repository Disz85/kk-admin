import React, { useRef, useState } from 'react';
import { Cropper } from 'react-cropper';

import 'cropperjs/dist/cropper.css';
import Flex from '../../Layouts/Flex';
import Button from '../../Components/Buttons/Button';

const ImageCropper = ({ media, crop, applyCropping }) => {
    const cropperRef = useRef(null);

    const [cropData, setCropData] = useState(crop);

    const onCrop = ({ detail }) => {
        const { width, height, x, y } = detail;
        setCropData({ width, height, x, y });
    };

    const apply = () => applyCropping(cropData);

    const close = () => applyCropping(crop);

    return (
        <>
            <Cropper
                ref={cropperRef}
                src={`${import.meta.env.VITE_IMAGE_URL}/${media.path}`}
                data={cropData}
                guides
                crop={onCrop}
            />

            <Flex classNames="m-picture__btnWrapper" justifyContent="evenly">
                <Button name="upload" text="Vágás" icon="cut" click={apply} />
                <Button name="upload" text="Mégse" icon="ban" click={close} />
            </Flex>
        </>
    );
};

export default ImageCropper;
