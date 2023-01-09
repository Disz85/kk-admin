import Field from "./Field";
import style from '../../../../scss/components/form.module.scss';

const TextInput = ({ onChange, entity, regex = null, ...props }) => {
    const change = (e) =>
        onChange({
            [props.name]: regex
                ? e.target.value.replace(regex, '')
                : e.target.value,
        });

    return (
        <div className={ style.formGroup }>
            <Field {...props}>
                {(hasError, attributes) => (
                    <input
                        className={ style.formTextInput }
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
