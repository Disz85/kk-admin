import React from 'react';
import PropTypes from 'prop-types';

// ICONS
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

// STYLES
import style from '../../../scss/components/panel.module.scss';

const Panel = ({ title, icon, className, children }) => {
    return (
        <div className={`${style.panelWrapper} ${className}`}>
            <div className={style.panelHeader}>
                {icon && <FontAwesomeIcon icon={icon} />}
                {title && <h2>{title}</h2>}
            </div>
            <div className={style.panelBody}>
                {React.Children.map(children, (child) =>
                    React.cloneElement(child),
                )}
            </div>
        </div>
    );
};

export default Panel;

Panel.propTypes = {
    /**
     * Type of title
     */
    title: PropTypes.string,
    /**
     * Type of icon
     */
    icon: PropTypes.string,
    /**
     * Type of className
     */
    className: PropTypes.string,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,
};

Panel.defaultProps = {
    title: false,
    icon: null,
    className: '',
};
