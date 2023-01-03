import { useTranslation } from "react-i18next";
import React, { useState } from "react";
import { generate } from "shortid";
import Label from "./Label";
import Error from "./Error";

const Field = ({
                   name,
                   resource,
                   children,
                   errors,
                   path = null,
                   disabled = false,
                   icon = null,
                   unlabeled = false,
                   recursionLevel = null,
               }) => {
    const [id] = useState(generate());
    const { t } = useTranslation();

    // Fully Qualified Path
    const FQP = `${path ? path + "." + name : name}`;
    const LOCALE = `${FQP}${recursionLevel ? "-" + recursionLevel : ""}`;

    const placeholder = t(
        `${resource}.${LOCALE}`.replace(new RegExp("[.][0-9]+", "g"), "")
    );

    const notIn = (errors) =>
        Object.entries(errors)
            .filter(([key, value]) => new RegExp(`${FQP}.[0-9]+.id`).test(key))
            .map(([key, error]) => error);

    const error = errors
        ? errors[FQP + ".id"] || errors[FQP] || notIn(errors)[0] || ""
        : "";

    const attributes = { id, name, disabled, placeholder };

    const hasError = !!error;

    return (
        <React.Fragment>
            {!unlabeled && <Label to={id} text={placeholder} icon={icon} />}
            {children(hasError, attributes)}
            {errors && <Error message={error} />}
        </React.Fragment>
    );
};

export default Field;
