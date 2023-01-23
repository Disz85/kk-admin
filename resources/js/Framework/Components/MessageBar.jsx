import { MessageContext } from "../Context/MessageContext";
import React, { useContext } from 'react';

//STYLE
import style from '../../../scss/components/message.module.scss';

const MessageBar = () => {
    const { messages, popMessage } = useContext(MessageContext);

    return (
        <div className={style.wrapper}>
            { messages.map( (message) => (
                <div key={ message.date }
                     className={style.message}
                     onClick={ () => popMessage(message) }
                     data-type={ message.type || 'success' }
                >
                    <strong className={style.messageTitle}>{ message.title }</strong>
                    { message.text && <p>{ message.text }</p> }
                </div>
            )) }
        </div>
    );
};

export default MessageBar;
