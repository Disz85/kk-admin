import React from 'react';
import PropTypes from 'prop-types';

import BaseModal from 'react-modal';

const Modal = ({ isOpen, onRequestClose, children, ...props }) => (
    <BaseModal
        ariaHideApp={false}
        isOpen={isOpen}
        onRequestClose={onRequestClose}
        {...props}
    >
        {children}
    </BaseModal>
);

export default Modal;

Modal.propTypes = {
    /**
     * Type of isOpen
     */
    isOpen: PropTypes.bool.isRequired,
    /**
     * Type of onRequestClose
     */
    onRequestClose: PropTypes.func.isRequired,
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]).isRequired,

    props: PropTypes.array,
};

Modal.defaultProps = {
    props: [],
};
