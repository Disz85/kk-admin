import React, { useState, useRef } from 'react';
import Modal from 'react-modal';
import MediaService from '../../../Services/MediaService';
import useUpdateEffect from '../../../Hooks/useUpdateEffect';
import ImageCropper from './ImageCropper';
import { cropParams, sanitizeCropValues } from '../../../Helpers/imageCropper';
import Error from '../Form/Error';
import Button from '../../Components/Buttons/Button';
import Flex from '../../Layouts/Flex';
import ImagePlaceholder from './ImagePlaceholder';

// STYLES
import style from '../../../../scss/components/image.module.scss';

// ICONS
import { faUpload, faCrop } from '@fortawesome/free-solid-svg-icons';

const ModalStyle = {
    overlay: {
        background: 'rgba(0, 0, 0, .8)',
        overflowY: 'auto',
        zIndex: 100000,
    },
    content: {
        margin: '0 auto',
        border: 'none',
        padding: 10,
        maxWidth: 800,
        bottom: 'initial',
    },
};

const Image = ({
    name,
    resource,
    entity,
    onChange,
    errors,
    isCropping = true,
    isDetachable = false,
}) => {
    const input = useRef(null);

    const [media, setMedia] = useState(entity[name]);
    const [cropping, setCropping] = useState(false);
    const pickImage = () => input.current.click();

    const upload = (e) => {
        MediaService.upload(e.target.files[0], resource).then(({ data }) => {
            setMedia((old) => ({ ...old, ...data }));
        });
    };

    const detachImage = () => {
        setMedia(null);
    };

    const toggleCropping = () =>
        setCropping((currentCropping) => !currentCropping);

    const applyCropping = ({ width, height, x, y }) =>
        setMedia((old) => ({
            ...old,
            ...sanitizeCropValues({ width, height, x, y }),
        }));

    const { path, id, type, ...crop } = media || {};

    useUpdateEffect(() => {
        setCropping(false);

        let change = { [name]: null };

        if (media) {
            change = {
                [name]: {
                    id: media.id,
                    width: media.width,
                    height: media.height,
                    x: media.x,
                    y: media.y,
                },
            };
        }

        onChange(change);
    }, [media]);

    return (
        <>
            <input
                type="file"
                name={name}
                ref={input}
                onChange={upload}
                style={{ display: 'none' }}
                accept={'image/*'}
            />
            {media ? (
                <div className="m-picture">
                    <img
                        className="m-picture__coverImage"
                        src={cropParams(
                            `${import.meta.env.VITE_IMAGE_URL}/${path}`,
                            crop,
                        )}
                        alt=""
                    />
                    <Modal
                        isOpen={cropping}
                        onRequestClose={toggleCropping}
                        style={ModalStyle}
                        ariaHideApp={false}
                    >
                        <ImageCropper
                            media={media}
                            crop={crop}
                            applyCropping={applyCropping}
                        />
                    </Modal>
                    {isCropping && (
                        <Button
                            name="crop"
                            icon={faCrop}
                            click={toggleCropping}
                            type="button"
                            unlabeled
                        />
                    )}
                </div>
            ) : (
                <ImagePlaceholder />
            )}
            <Flex
                classNames={style.imageBtnWrapper}
                justifyContent="center"
                wraps="wrap"
            >
                <Button
                    className={style.uploadBtn}
                    name="upload"
                    text="Képfeltöltés"
                    icon={faUpload}
                    click={pickImage}
                    type="button"
                />
                {isDetachable && (
                    <Button
                        className={style.resetBtn}
                        name="reset"
                        text="Alaphelyzet"
                        click={detachImage}
                        type="button"
                    />
                )}
            </Flex>
            {Object.hasOwnProperty.call(errors, `${name}.id`) && (
                <Error message={errors[`${name}.id`][0]} />
            )}
        </>
    );
};

export default Image;
