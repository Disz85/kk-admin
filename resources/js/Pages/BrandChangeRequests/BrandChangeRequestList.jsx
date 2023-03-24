import React from 'react';
import PropTypes from 'prop-types';

// COMPONENTS
import List from '../../Framework/Pages/List/List';
import Text from '../../Framework/Components/Text';
import EditChange from '../../Framework/Components/Buttons/EditChange';

const BrandChangeRequestList = (props) => {
    return (
        <List {...props} readonly>
            <Text name="id" />
            <EditChange name="title" />
        </List>
    );
};

export default BrandChangeRequestList;

BrandChangeRequestList.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object,
};

BrandChangeRequestList.defaultProps = {
    entity: {},
};
