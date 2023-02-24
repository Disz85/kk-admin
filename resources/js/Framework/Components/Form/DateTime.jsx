import React, { useState } from 'react';
import PropTypes from 'prop-types';

// DATE
import DatePicker from 'react-datepicker';
import { format } from 'date-fns';
import hu from 'date-fns/locale/hu';

import useUpdateEffect from '../../../Hooks/useUpdateEffect';

// COMPONENTS
import Field from './Field';

// STYLES
import '../../../../scss/external/datepicker.scss';

const DateTime = ({ entity, onChange, ...props }) => {
    const { name, readOnly } = props;

    const [date, setDate] = useState(
        entity[name] ? new Date(entity[name]) : null,
    );

    const change = (currentDate) => {
        setDate(currentDate);
    };

    useUpdateEffect(() => {
        onChange({ [name]: date ? format(date, 'yyyy-MM-dd HH:mm:ss') : null });
    }, [date]);

    return (
        <Field {...props}>
            {(attributes) => (
                <DatePicker
                    onChange={change}
                    selected={date}
                    minDate={new Date()}
                    dateFormat="yyyy-MM-dd HH:mm"
                    showTimeSelect
                    timeFormat="HH:mm"
                    timeIntervals={10}
                    locale={hu}
                    className="react-datepicker__input"
                    disabled={readOnly}
                    {...attributes}
                />
            )}
        </Field>
    );
};

export default DateTime;

DateTime.propTypes = {
    /**
     * Type of entity
     */
    entity: PropTypes.object.isRequired,
    /**
     * Type of onChange
     */
    onChange: PropTypes.func.isRequired,
    /**
     * Type of name
     */
    name: PropTypes.string.isRequired,
    /**
     * Type of readOnly
     */
    readOnly: PropTypes.bool,
};

DateTime.defaultProps = {
    readOnly: false,
};
