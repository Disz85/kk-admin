import React from 'react';
import PropTypes from 'prop-types';

const Text = ({ entity, name, isParagraph = false, ...props }) => {
    const Paragraph = isParagraph ? 'p' : 'span';

    return entity && <Paragraph {...props}>{entity?.[name]}</Paragraph>;
};

export default Text;

Text.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of name
     */
    isParagraph: PropTypes.bool,
};

Text.defaultProps = {
    isParagraph: false,
};
