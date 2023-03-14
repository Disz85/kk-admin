import React, { useEffect, useRef } from 'react';
import PropTypes from 'prop-types';

import EditorJS from '@editorjs/editorjs';

// TRANSLATIONS
import { useTranslation } from 'react-i18next';

// COMPONENTS
import Header from '@editorjs/header';
import List from '@editorjs/list';
import Underline from '@editorjs/underline';
import Quote from '@editorjs/quote';
import FramedText from './EditorTools/FramedText';
import translation from '../../../Translations/editor';
import Image from './EditorTools/Image';
import Gallery from './EditorTools/Gallery';
import Label from './Label';
import Error from './Error';

// STYLES
import formStyle from '../../../../scss/components/form.module.scss';
import style from '../../../../scss/components/editor.module.scss';

import '../../../../scss/external/editor.scss';

const BlockStyledEditor = ({
    entity,
    name,
    onChange,
    resource,
    errors,
    fullFeatureSet = false,
}) => {
    const editorInstance = useRef(null);

    const holderId = 'editor-holder';

    const { t } = useTranslation();

    const change = (data) => {
        if (data.blocks.length > 0) {
            onChange({ [name]: data });
        } else {
            onChange({ [name]: null });
        }
    };

    const getTools = () => {
        const baseTools = {
            header: {
                class: Header,
                config: {
                    placeholder: t('editor.headerPlaceholder'),
                    levels: [2, 3],
                    defaultLevel: 2,
                },
                inlineToolbar: true,
            },
            list: List,
            underline: Underline,
        };

        const extraTools = {
            framed: FramedText,
            quote: {
                class: Quote,
                config: {
                    quotePlaceholder: t('editor.Quote'),
                    captionPlaceholder: t('editor.Author'),
                },
            },
            image: {
                class: Image,
                config: { resource },
            },
            gallery: {
                class: Gallery,
                config: { resource },
            },
        };

        if (fullFeatureSet) {
            return { ...baseTools, ...extraTools };
        }

        return baseTools;
    };

    const initEditor = (content) => {
        const editor = new EditorJS({
            holder: holderId,
            onReady: () => {
                editorInstance.current = editor;
            },
            onChange: () => {
                editor.save().then((data) => change(data));
            },
            data: content,
            placeholder: t('editor.placeholder'),
            tools: getTools(),
            minHeight: 0,
            i18n: {
                messages: translation.messages,
            },
        });
    };

    useEffect(() => {
        if (editorInstance.current === null) {
            initEditor(entity[name]);
            editorInstance.current = true;
        }
    }, []);

    return (
        <>
            <Label
                className={formStyle.label}
                to={holderId}
                text={t(`${resource}.${name}`)}
            />
            <div className={style.editorWrapper}>
                <div id={holderId} />
                {Object.hasOwnProperty.call(errors, name) && (
                    <Error message={errors[name][0]} />
                )}
            </div>
        </>
    );
};

BlockStyledEditor.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.shape({
        id: PropTypes.number,
    }).isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of errors
     */
    errors: PropTypes.object.isRequired,
    /**
     * Type of fullFeatureSet
     */
    fullFeatureSet: PropTypes.bool,
};

BlockStyledEditor.defaultProps = {
    fullFeatureSet: false,
};

export default BlockStyledEditor;
