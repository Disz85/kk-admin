import TextInput from './TextInput';

export default (element) => {
    if (element.props && element.props.defaultValue != null) {
        return element.props.defaultValue;
    }

    switch (element.type) {
        case TextInput:
            return '';
        default:
            return '';
    }
};
