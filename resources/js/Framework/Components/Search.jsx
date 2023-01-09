import { createNewEntityFromChildren, recursiveMap } from "../../Helpers/recursions";
import React, { useState, useEffect } from 'react';
import { Button } from "./Button";
import { stringify, parse } from 'qs';
import Panel from "./Panel";
import classes from 'classnames';

const Search = ({ entity, onChange, children, ...props }) => {
    /*** IMMUTABLE STATE ***/
    const [ fields ] = useState(createNewEntityFromChildren(children));

    /*** MUTABLE STATE ***/
    const [ params, setParams ] = useState({ ...fields, ...parse(window.location.search.replace('?', '')) });

    /*** CALLBACKS ***/
    const update = change => {
        setParams(params => ({ ...params, ...change }));
    };

    // On params change update the query string
    useEffect(() => {
        const query = stringify(params, { filter : (prefix, value) => {
                if ((value && value.id) || null) {
                    return value.id;
                }

                if (typeof value === "boolean" && value) {
                    return 1;
                }

                if (!value) {
                    return;
                }

                return value;
            }});

        window.history.replaceState({ at : Date.now() }, '', "?" + query);
    }, [params]);

    // On query params change notify parent
    useEffect(() => {
        onChange({ ...fields, ...parse(window.location.search.replace('?', '')) });
    }, [params]);

    return (
        <Panel title={ "Szűrő" } iconClass={ 'fal fa-filter' }>
            <form className={ classes(`m-form mx-auto`) }>
                <div className= {"d-flex flex-column justify-content-between" }>
                    { recursiveMap(children, child =>
                        React.cloneElement(child, ({ ...props, errors : {}, entity : params, onChange : update }))
                    ) }
                    <div className="row justify-content-end">
                        <Button className={ '-circle -md' } name={ 'reset' } icon={ 'trash-alt' } click={ () => setParams(fields) } unlabeled/>
                    </div>
                </div>
            </form>
        </Panel>
    );
};


export default Search;
