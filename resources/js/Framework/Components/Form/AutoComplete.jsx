import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { AsyncTypeahead } from 'react-bootstrap-typeahead';
import { useTranslation } from 'react-i18next';
import useUpdateEffect from '../../../Hooks/useUpdateEffect';

// COMPONENTS
import Field from './Field';

// STYLES
import style from '../../../../scss/components/form.module.scss';
import '../../../../scss/external/select.scss';

const AutoComplete = ({
    entity,
    onChange,
    placeholder,
    searchBy,
    service,
    reference,
    isMultiple = false,
    ...props
}) => {
    const { name } = props;
    const { t } = useTranslation();

    const [state, setState] = useState({
        items: [],
        selected: entity[name]
            ? (Array.isArray(entity[name]) && entity[name]) || [entity[name]]
            : [],
        isLoading: false,
    });

    const update = (change) =>
        setState((oldState) => ({ ...oldState, ...change }));

    const onSearch = (query) => {
        update({ isLoading: true });

        service
            .autocomplete(reference, {
                [searchBy]: query,
                withoutId: entity.id,
            })
            .then((result) => {
                const items = [...result.data];

                update({ items, isLoading: false });
            });
    };

    const onSelectionChange = (selection) => {
        update({ selected: selection });
    };

    useUpdateEffect(() => {
        if (isMultiple) {
            onChange({ [name]: state.selected });
        } else {
            onChange({ [name]: state.selected[0] || null });
        }
    }, [state.selected]);

    return (
        <div className={style.formGroup}>
            <Field {...props}>
                {(attributes) => {
                    const { hasError, ...attr } = attributes;

                    return (
                        <AsyncTypeahead
                            id={`${entity.name}.${name}-select`}
                            className={`${style.autocomplete} -${name}`}
                            isLoading={state.isLoading}
                            onSearch={onSearch}
                            promptText={placeholder}
                            minLength={3}
                            multiple={isMultiple}
                            labelKey={searchBy}
                            options={state.items}
                            selected={state.selected}
                            flip
                            emptyLabel={t('application.noResults')}
                            searchText={t('application.search')}
                            onChange={onSelectionChange}
                            inputProps={{
                                id: attributes.id,
                                className: `${style.textArea} ${
                                    hasError ? style.isInvalid : ''
                                }`,
                            }}
                            {...attr}
                        />
                    );
                }}
            </Field>
        </div>
    );
};

export default AutoComplete;

AutoComplete.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of placeholder
     */
    placeholder: PropTypes.string.isRequired,
    /**
     * Type of searchBy
     */
    searchBy: PropTypes.string.isRequired,
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of reference
     */
    reference: PropTypes.string.isRequired,
    /**
     * Type of isMultiple
     */
    isMultiple: PropTypes.bool.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
};
