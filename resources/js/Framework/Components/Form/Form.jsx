import { createNewEntityFromChildren, recursiveMap } from "../../../Helpers/recursions";
import ApplicationContext from "../../Context/ApplicationContext";
import React, { useState, useEffect, useContext, useRef } from 'react';
import useUpdateEffect from "../../../Hooks/useUpdateEffect";
import { useTranslation } from 'react-i18next';
import { useParams } from "react-router-dom";
import classes from 'classnames';
import Submit from "./Submit";
import Modal from "../Modal";
import {Button} from "../Button";
import {AuthContext} from "../../Context/AuthContext";
import {MessageContext} from "../../Context/MessageContext";


const NAVIGATION_MESSAGE = "Biztosan elhagyod az oldalt? A módosításaid így elvesznek!";

/**
 * This component is used for general Write/Edit operations
 * @param service The service layer used for communicating with the backend.
 * @param resource The current resource being edited (eg.: Recipe, Article, etc...).
 * @param history The history
 * @param immutable If the form is used for displaying data only. Will refuse to forward changes to the backend.
 * @param children The children that will receive the required props from the component
 * @param classNames Used for customizing appearance
 * @param creates If the form creates another, related resource it will redirect to this resource on successful save
 * @param props Misc data
 * @returns {null|*}
 * @constructor
 */
const Form = ({ service, resource, history, immutable = false, children, classNames = '', creates = null, ...props }) => {
    const setPageInfo = useContext(ApplicationContext);
    const { pushMessage } = useContext(MessageContext);
    const { user } = useContext(AuthContext);
    const { t } = useTranslation();
    const { id } = useParams();
    const ref = useRef(null);

    const [ state, setState ] = useState({ entity : {}, errors : {}, display : false, loading : true, changed : false, created : false, lock : null });

    const { entity, errors, loading } = state;

    const update = change => setState( oldState => ({ ...oldState, ...change }) );

    /*** MODEL LOCK CALLBACKS **/
    const passLock = () => service.passLock(state.lock, user).then(() => history.go(0));

    const back = () => history.goBack();

    const scrollToFirstError = () => {
        if (!ref.current) {
            return;
        }


        const error = ref.current.querySelector('.m-form__feedback')?.parentNode;

        if (error) {
            error.scrollIntoView();
        }
    };

    const save = () => {
        if (immutable || !state.changed || state.loading) {
            return;
        }

        update({ loading : true, changed : false, errors : {} });

        service.store(resource, entity)
            .then(result => {
                if (creates) {
                    history.push(`/${creates}/${result.id}/show`);
                    return;
                }

                update({ entity : result.data, created : !(!!entity.id) });
                pushMessage({ title : "Sikeres mentés!" });
            })
            .catch(({ response }) => {
                pushMessage({ title : "Hoppá, valami hiba történt!", type : "error" });
                update({ errors : response.data.errors || {}, changed : true });
                scrollToFirstError();
            })
            .finally( () => update({ loading : false }) );
    };

    // Callbacks
    const onSave = e => {
        e.stopPropagation();
        e.preventDefault();
        save();
    };

    const onChange = change => setState(({ entity, ...rest }) => ({ ...rest,
        entity : { ...entity, ...change},
        changed : true
    }));

    const onToggle = onChange;

    /** Runs on component mount, either loads specified entity or creates a new one, hooks into page lifecycle */
    useEffect(() => {

        setPageInfo({'title' : t(`application.edit`, { resource : t(`${resource}.resource`) })});

        if (id) {
            console.log(resource, id);
            service.find(resource, id)
                .then(entity => update({ entity: entity.data, loading : false, display : true }))
                .catch(({ response }) => {
                    if (!response) {
                        return;
                    }

                    if (response.status === 423) {
                        update({ lock : response.data, display : true, loading : false });
                    }
                });

        }

        if (!id) {
            update({ loading: false, display: true, entity : createNewEntityFromChildren(children) });
        }
    }, []);

    /** Resets state when a new entity has been created */
    useUpdateEffect(() => {
        update({ display : true, loading : false, created : false, changed : false });
    }, [id]);

    /** Scroll to first visible error if any is visible **/
    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            scrollToFirstError();
        }
    }, [errors]);

    /** Ask user to confirm navigation if any change has been made to the current entity **/
    useEffect(() => {
        window.onbeforeunload = () => true;
    });

    /** Check periodically for a lock status in case the entity lock was taken away by some other user **/
    useEffect(() => {
        const interval = setInterval(() => {
            if (state.entity && state.entity.lock) {
                service.lockStatus(state.entity.lock).catch(({ response }) => {
                    update({ lock : response.status === 423 ? response.data : null });
                });
            }
        }, 5000);

        return () => clearInterval(interval);
    }, [state.entity]);

    /** If component is not ready to display its content yet return null */
    if (!state.display) {
        return null;
    }

    /** If a new entity has been created rewrite url and flag as existing */
    if (state.created && history) {
        history.push(`/${resource}/${state.entity.id}/show`);
        update({ created : false });
    }

    const childrenWithProps = recursiveMap(children, child => {
        return React.cloneElement(child, ({ service, resource, entity, errors, onChange, onToggle }));
    });

    //after React.Fragment: <Prompt when={ state.changed } message={ NAVIGATION_MESSAGE }/>

    return (
        <React.Fragment>
            { state.lock && (
                <Modal isOpen={ !!state.lock } onRequestClose={ back }>
                    <h2>{ t(`${resource}.resource`) } zárolva <strong>{ state.lock.user.username }</strong> által</h2>
                    <div className={ 'd-flex flex-row justify-content-center border-top py-5 mt-5' }>
                        <Button className={ 'mr-5' } name={ 'passLock' } click={ passLock }/>
                        <Button name={ 'back' } click={ back }/>
                    </div>
                </Modal>
            ) }

            { !state.lock && (
                <form className={classes(`m-form mx-auto a-load ${classNames}`, { '-loading' : loading })} onSubmit={onSave} ref={ ref }>
                    <div className="row">
                        { childrenWithProps }
                    </div>
                    { !immutable && (
                        <div className={ 'row' }>
                            <div className="col-12 text-right py-4">
                                <Submit/>
                            </div>
                        </div>
                    ) }
                </form>
            ) }
        </React.Fragment>
    );
};


export default Form;
