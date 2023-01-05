import { Link } from "react-router-dom";
import classNames from 'classnames';
import React from 'react';

const getPageNumbers = total => [...Array(Math.ceil(total / 25)).keys()].map( v => v + 1);

const filterPageNumbers = (numbers, current, last) => numbers.filter( number =>
    number === 1 || number === last || (number >= current - 3 && number <= current + 3)
);

const mapPageNumbers = (numbers, current, resource) => numbers.map(number => {
    const item = classNames('m-pagination__item page-item', {'-active': current === number});
    const link = classNames('m-pagination__link', {'-active': current === number});

    return (
        <li key={number} className={ item }>
            <Link className={ link } to={`/${resource}/page/${number}${window.location.search}`}>
                {number}
            </Link>
        </li>
    );
});

const Arrow = ({ resource, to, enabled, direction }) => {
    const arrow = <i className={ `fal fa-angle-${direction}` }></i>;
    const classes = classNames('m-pagination__link -arrow', { 'disabled' : !enabled });

    if (enabled) {
        return <Link className={ classes } to={ `/${resource}/page/${to}${window.location.search}` }>{ arrow }</Link>;
    }

    return <span className={ classes }>{ arrow }</span>;
};

const Paginator = ({ pagination, ...props }) => {
    const { total, current, last } = pagination;
    const { resource } = props;

    const items = mapPageNumbers(filterPageNumbers(getPageNumbers(total), current, last), current, resource);

    return (
        <ul className="m-pagination pagination mt-3" role="navigation">
            <li className={ 'm-pagination__item page-item' }>
                <Arrow direction={ 'left' } to={ current - 1 } enabled={ current > 1 } {...props}/>
            </li>
            { items }
            <li className="m-pagination__item page-item">
                <Arrow direction={ 'right' } to={ current + 1 } enabled={ current !== last } {...props}/>
            </li>
        </ul>
    );
};

export default Paginator;
