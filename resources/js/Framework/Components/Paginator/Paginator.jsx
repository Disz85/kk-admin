import React from 'react';
import PropTypes from 'prop-types';

import { Link } from 'react-router-dom';

import style from '../../../../scss/components/paginator/paginator.module.scss';

// COMPONENTS
import Arrow from './Arrow';
import List from '../List/List';

const getPageNumbers = (total) =>
    [...Array(Math.ceil(total / 25)).keys()].map((v) => v + 1);

const filterPageNumbers = (numbers, current, last) =>
    numbers.filter(
        (number) =>
            number === 1 ||
            number === last ||
            (number >= current - 3 && number <= current + 3),
    );

const mapPageNumbers = (numbers, current, resource) =>
    numbers.map((number) => {
        return (
            <li key={number}>
                <Link
                    to={`/${resource}/page/${number}${window.location.search}`}
                >
                    {number}
                </Link>
            </li>
        );
    });

const Paginator = ({ pagination, ...props }) => {
    const { total, current, last } = pagination;
    const { resource } = props;

    const items = mapPageNumbers(
        filterPageNumbers(getPageNumbers(total), current, last),
        current,
        resource,
    );

    return (
        <List className={style.list} role="navigation">
            <li className={style.item}>
                <Arrow to={current - 1} enabled={current > 1} {...props} />
            </li>
            {items}
            <li className={style.item}>
                <Arrow
                    to={current + 1}
                    enabled={current !== last}
                    isRight
                    {...props}
                />
            </li>
        </List>
    );
};

export default Paginator;

Paginator.propTypes = {
    /**
     * Type of resource
     */
    resource: PropTypes.string.isRequired,
    /**
     * Type of pagination
     */
    pagination: PropTypes.shape({
        total: PropTypes.number.isRequired,
        current: PropTypes.number.isRequired,
        last: PropTypes.number.isRequired,
    }).isRequired,
};
