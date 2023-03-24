import React, { useState, useEffect, useContext, useRef } from 'react';
import PropTypes from 'prop-types';
// ROUTES
import { useParams, useNavigate } from 'react-router-dom';
// TRANSLATION
import { useTranslation } from 'react-i18next';
// CONFIG
import navigationIcons from '../../../config/navigationIcons';
// HOOKS
import useUpdateEffect from '../../../Hooks/useUpdateEffect';
// CONTEXTS
import { MessageContext } from '../../Context/MessageContext';
import ApplicationContext from '../../Context/ApplicationContext';
// HELPERS
import { recursiveMap } from '../../../Helpers/recursions';

// STYLES
import style from '../../../../scss/components/form.module.scss';

// BUTTONS
import Approve from '../Buttons/Approve';
import Reject from '../Buttons/Reject';

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
const ApproveForm = ({
    service,
    resource,
    history,
    children,
    immutable = false,
    creates = null,
}) => {
    const setPageInfo = useContext(ApplicationContext);
    const { pushMessage } = useContext(MessageContext);
    const navigate = useNavigate();
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
    });

    const { entity, errors, loading } = state;

    const update = (change) => {
        setState((oldState) => ({ ...oldState, ...change }));
    };

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

    const approve = () => {
        service
            .approve(resource, entity, id)
            .then((result) => {
                if (creates) {
                    update({ loading: false, changed: false });
                    pushMessage({ title: t('application.approved') });
                    setTimeout(() => {
                        navigate(`/${creates}/${result.data.id}/show`);
                    }, 2000);
                }
            })
            .catch(({ response }) => {
                pushMessage({
                    title: t('application.approvalFailed'),
                    type: 'error',
                });

                update({
                    errors: (response && response.data.errors) || {},
                });
                scrollToFirstError();
            })
            .finally(() => {
                update({ loading: false });
            });
    };

    const save = () => {
        if (immutable || loading) {
            return;
        }

        update({ loading: true, errors: {} });

        if (state.changed) {
            service
                .store(resource, entity, id)
                .then((result) => {
                    update({ entity: result.data, created: !entity.id });
                    approve();
                })
                .catch(({ response }) => {
                    pushMessage({
                        title: t('application.saveFailed'),
                        type: 'error',
                    });

                    update({
                        errors: (response && response.data.errors) || {},
                    });
                    scrollToFirstError();
                });
        } else {
            approve();
        }
    };

    const reject = () => {
        service
            .reject(resource, entity, id)
            .then(() => {
                if (creates) {
                    pushMessage({ title: t('application.rejected') });
                    setTimeout(() => {
                        navigate(`/${resource}`);
                    }, 2000);
                }
            })
            .catch(({ response }) => {
                pushMessage({
                    title: t('application.rejectionFailed'),
                    type: 'error',
                });

                update({
                    errors: (response && response.data.errors) || {},
                });
                scrollToFirstError();
            })
            .finally(() => {
                update({ loading: false });
            });
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
                            display: true,
                            loading: false,
                        });
                    }
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
            changed: true,
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
        <form onSubmit={onSave} ref={ref}>
            <div>{childrenWithProps}</div>
            {!immutable && <Approve />}
            {!immutable && <Reject remove={reject} entity={entity} />}
        </form>
    );
};

export default ApproveForm;

ApproveForm.propTypes = {
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

ApproveForm.defaultProps = {
    immutable: false,
    creates: null,
};
