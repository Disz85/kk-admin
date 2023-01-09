import React, {useState, useEffect, useRef, Fragment} from "react";
import classNames from 'classnames';

const Panel = ({ iconClass, title, children, ...props }) => {
    const content = useRef(null);
    const [ active, setActive ] = useState(false);
    const [ height, setHeight ] = useState({ height: 0, overflow: 'hidden' });

    const toggleAccordion = () => setActive(active => !active);
    useEffect(() => {
        setHeight(active ? { height: 0, overflow: 'hidden' } : { height : 100 + '%' , overflow : 'visible'});
    }, [active]);

    const buttonClassName = classNames(['m-button -transparentColor m-accordion', { "-active" : active }]);
    const iconClassName = classNames(['m-accordion__icon a-icon -colorPrimary -ms fal fa-chevron-up', { '-rotate' : active }]);

    return (
        <div className={ "m-card p-5 mb-6 d-flex flex-column position-relative w-100" }>
            <div className={ 'd-flex justify-content-between align-items-center' }>

                { title &&
                    <Fragment>
                        <div className={"m-accordion__title d-flex align-items-center"}>
                            <i className={ `a-icon -sm -colorPrimary fal ${ iconClass } mr-3` }></i>
                            <h2 className={ "mb-0" }>{ title }</h2>
                        </div>
                        <button type={ "button" } className={ buttonClassName } onClick={ toggleAccordion }>
                            <i className={ iconClassName }></i>
                        </button>
                    </Fragment>
                }

            </div>
            <div className={ "m-accordion__content" } ref={ content } style={ height }>
                { React.Children.map(children, child => React.cloneElement(child, props)) }
            </div>
        </div>
    );
};

export default Panel;
