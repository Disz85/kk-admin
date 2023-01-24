import React, { useContext } from 'react';
import { MessageContext } from '../Context/MessageContext';

// STYLE
import style from '../../../scss/components/message.module.scss';

const MessageBar = () => {
    const { messages, popMessage } = useContext(MessageContext);

    return (
        <div className={style.wrapper}>
            {messages.map((message) => (
                <button
                    type="button"
                    key={message.date}
                    className={style.message}
                    onClick={() => popMessage(message)}
                    onKeyDown={() => popMessage(message)}
                    data-type={message.type || 'success'}
                >
                    <strong className={style.messageTitle}>
                        {message.title}
                    </strong>
                    {message.text && <p>{message.text}</p>}
                </button>
            ))}
        </div>
    );
};

export default MessageBar;
