import { default as BaseModal } from "react-modal";
import React from 'react';

const ModalStyle = {
    overlay : {
        background : "rgba(0, 0, 0, .8)",
        overflowY : "auto",
        zIndex : 100000,
    },
    content : {
        textAlign : 'center',
        margin: "2px auto 0",
        border: "none",
        padding: 15,
        maxWidth : 800,
        bottom: 'initial',
    }
};

const Modal = ({ isOpen, onRequestClose, children, ...props }) => (
    <BaseModal ariaHideApp={ false } style={ ModalStyle } isOpen={ isOpen } onRequestClose={ onRequestClose } {...props}>
        { children }
    </BaseModal>
);

export default Modal;
