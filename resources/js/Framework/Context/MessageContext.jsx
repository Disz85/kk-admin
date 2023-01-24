import React, { useState } from 'react';
import PropTypes from 'prop-types';

import useInterval from '../../Hooks/useInterval';

export const MessageContext = React.createContext({ messages: [] });

const MessageProvider = ({ children }) => {
    const [messages, setMessages] = useState([]);

    const pushMessage = (message) =>
        setMessages((old) => [...old, { ...message, date: Date.now() }]);

    const popMessage = (message) =>
        setMessages((old) => old.filter((msg) => msg !== message));

    useInterval(() => {
        if (messages.length > 0) {
            setMessages((old) =>
                old.filter((message) => (Date.now() - message.date) / 1000 < 3),
            );
        }
    }, 1000);

    return (
        <MessageContext.Provider value={{ messages, pushMessage, popMessage }}>
            {children}
        </MessageContext.Provider>
    );
};

export default MessageProvider;

MessageProvider.propTypes = {
    /**
     * Type of children
     */
    children: PropTypes.oneOfType([
        PropTypes.arrayOf(PropTypes.node),
        PropTypes.node,
    ]),
};

MessageProvider.defaultProps = {
    children: null,
};
