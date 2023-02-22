import React, { useState, useEffect, useContext, useRef } from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { useParams } from 'react-router-dom';
// TRANSLATION
import { useTranslation } from 'react-i18next';
// CONFIG
import navigationIcons from '../../../config/navigationIcons';
// HOOKS
import useUpdateEffect from '../../../Hooks/useUpdateEffect';
// CONTEXTS
import { AuthContext } from '../../Context/AuthContext';
import { MessageContext } from '../../Context/MessageContext';
import ApplicationContext from '../../Context/ApplicationContext';
// HELPERS
import {
    createNewEntityFromChildren,
    recursiveMap,
} from '../../../Helpers/recursions';
// COMPONENTS
import Submit from './Submit';
import Modal from '../Modal';
import Button from '../Buttons/Button';

// STYLES
import style from '../../../../scss/components/form.module.scss';

// const NAVIGATION_MESSAGE =
//     'Biztosan elhagyod az oldalt? A módosításaid így elvesznek!';

/**
 * This component is used for general Write/Edit operations
 * @param service The service layer used for communicating with the backend.
 * @param resource The current resource being edited (eg.: Recipe, Article, etc...).
 * @param history The history
 * @param immutable If the form is used for displaying data only. Will refuse to forward changes to the backend.
 * @param children The children that will receive the required props from the component
 * @param creates If the form creates another, related resource it will redirect to this resource on successful save
 * @param props Misc data
 * @returns {null|*}
 * @constructor
 */
const Form = ({
    service,
    resource,
    history,
    children,
    immutable = false,
    creates = null,
}) => {
    const setPageInfo = useContext(ApplicationContext);
    const { pushMessage } = useContext(MessageContext);
    const { user } = useContext(AuthContext);
    const { t } = useTranslation();
    const { id } = useParams();
    const ref = useRef(null);

    const [state, setState] = useState({
        entity: {},
        errors: {},
        display: false,
        loading: true,
        changed: false,
        created: false,
        lock: null,
    });

    const { entity, errors, loading } = state;

    const update = (change) => {
        setState((oldState) => ({ ...oldState, ...change }));
    };

    // MODEL LOCK CALLBACKS
    const passLock = () =>
        service.passLock(state.lock, user).then(() => history.go(0));

    const back = () => history.goBack();

    const scrollToFirstError = () => {
        if (!ref.current) {
            return;
        }

        const error = ref.current.querySelector(
            `.${style.isInvalid}`,
        )?.parentNode;

        if (error) {
            error.scrollIntoView();
            window.scrollBy(0, -70);
        }
    };

    const save = () => {
        if (immutable || !state.changed || loading) {
            return;
        }

        update({ loading: true, changed: false, errors: {} });

        service
            .store(resource, entity)
            .then((result) => {
                if (creates) {
                    history.push(`/${creates}/${result.id}/show`);
                    return;
                }

                update({ entity: result.data, created: !entity.id });
                pushMessage({ title: 'Sikeres mentés!' });
            })
            .catch(({ response }) => {
                pushMessage({
                    title: 'A szerkesztés meghiúsult!',
                    type: 'error',
                });

                update({
                    errors: (response && response.data.errors) || {},
                    changed: true,
                });
                scrollToFirstError();
            })
            .finally(() => update({ loading: false }));
    };

    // Callbacks
    const onSave = (e) => {
        e.stopPropagation();
        e.preventDefault();
        save();
    };

    const onChange = (change) => {
        setState(({ entity, ...rest }) => ({
            ...rest,
            entity: { ...entity, ...change },
            changed: true,
        }));
    };

    const onToggle = onChange;

    /**
     * Runs on component mount, either loads specified entity or creates a new one, hooks into page lifecycle
     */
    useEffect(() => {
        setPageInfo({
            title: t(`application.edit`, {
                resource: t(`${resource}.resource`),
            }),
            icon: navigationIcons[resource],
        });

        if (id) {
            service
                .find(resource, id)
                .then((data) => {
                    update({
                        entity: data.data,
                        loading: false,
                        display: true,
                    });
                })
                .catch(({ response }) => {
                    if (!response) {
                        return;
                    }

                    if (response.status === 423) {
                        update({
                            lock: response.data,
                            display: true,
                            loading: false,
                        });
                    }
                });
        }

        if (!id) {
            update({
                loading: false,
                display: true,
                entity: createNewEntityFromChildren(children),
            });
        }
    }, []);
    /**
     * Resets state when a new entity has been created
     */
    useUpdateEffect(() => {
        update({
            display: true,
            loading: false,
            created: false,
            changed: false,
        });
    }, [id]);

    /** Scroll to first visible error if any is visible
     *
     */
    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            scrollToFirstError();
        }
    }, [errors]);

    /** Ask user to confirm navigation if any change has been made to the current entity
     *
     */
    useEffect(() => {
        window.onbeforeunload = () => true;
    });

    /**
     * Check periodically for a lock status in case the entity lock was taken away by some other user
     */
    useEffect(() => {
        const interval = setInterval(() => {
            if (state.entity && state.entity.lock) {
                service.lockStatus(state.entity.lock).catch(({ response }) => {
                    update({
                        lock: response.status === 423 ? response.data : null,
                    });
                });
            }
        }, 5000);

        return () => clearInterval(interval);
    }, [state.entity]);

    /**
     * If component is not ready to display its content yet return null
     */
    if (!state.display) {
        return null;
    }

    /**
     * If a new entity has been created rewrite url and flag as existing
     */
    if (state.created && history) {
        history.push(`/${resource}/${state.entity.id}/show`);
        update({ created: false });
    }
    const childrenWithProps = recursiveMap(children, (child) => {
        return React.cloneElement(child, {
            service,
            resource,
            entity,
            errors,
            onChange,
            onToggle,
        });
    });

    // after React.Fragment: <Prompt when={ state.changed } message={ NAVIGATION_MESSAGE }/>
    return (
        <>
            {state.lock && (
                <Modal isOpen={!!state.lock} onRequestClose={back}>
                    <h2>
                        {t(`${resource}.resource`)} zárolva
                        <strong>{state.lock.user.username}</strong> által
                    </h2>
                    <div>
                        <Button name="passLock" click={passLock} />
                        <Button name="back" click={back} />
                    </div>
                </Modal>
            )}

            {!state.lock && (
                <form onSubmit={onSave} ref={ref}>
                    <div>{childrenWithProps}</div>
                    {!immutable && <Submit />}
                </form>
            )}
        </>
    );
};

export default Form;

Form.propTypes = {
    /**
     * Type of service
     */
    service: PropTypes.object.isRequired,
    /**
     * Type of resource
     */
    resource: PropTypes.object.isRequired,
    /**
     * Type of history
     */
    history: PropTypes.object.isRequired,
    /**
     * Type of immutable
     */
    immutable: PropTypes.bool,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
    /**
     * Type of creates
     */
    creates: PropTypes.string,
};

Form.defaultProps = {
    immutable: false,
    creates: null,
};
