import Field from "./Field";

const TextInput = ({ onChange, entity, regex = null, ...props }) => {
    const change = (e) =>
        onChange({
            [props.name]: regex
                ? e.target.value.replace(regex, '')
                : e.target.value,
        });

    return (
        <div>
            <Field {...props}>
                {(hasError, attributes) => (
                    <input
                        type={'text'}
                        onChange={change}
                        value={entity[props.name] || ''}
                        autoComplete={'off'}
                        {...attributes}
                    />
                )}
            </Field>
        </div>
    );
};

export default TextInput;
