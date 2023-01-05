import { childrenOnly } from "../../../Helpers/recursions";
import React from 'react';

const optimize = (prev, next) => next.optimized && prev.item === next.item && prev.errors === next.errors;

const Item = React.memo(({ item, elements, onChange, remove, separated = false, step = false, ...props }) => {

    const separatedStyle = separated ? '-bordered p-4 mb-6' : '';
    const stepStyle = step ? '-step p-4' : '';

    return (
        <li key={ item.key } className={ `m-list__item ${separatedStyle} ${stepStyle}`}>
            { childrenOnly(elements).map(child => React.cloneElement(child, {
                ...props,
                key: child.props.name,
                entity: item,
                onChange: onChange(item),
                remove: remove(item),
            })) }
        </li>
    )}, optimize);

const Iterator = ({ collection, children, path, ordered = false, separated, optimized = true, step, listClassName = '', ...props }) => {

    const List = ordered ? 'ol' : 'ul';

    return collection.length > 0 && (
        <List className={ `m-list ${listClassName}` }>
            { collection.map( (item, index) =>
                <Item step={ step }
                      separated={ separated }
                      key={ item.key || index }
                      index={ index }
                      item={ item }
                      elements={ children }
                      optimized={ optimized }
                      path={ path + "." + index }
                      {...props}/>
            ) }
        </List>
    );
};

export default Iterator;
