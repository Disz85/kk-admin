import React from 'react';
import PropTypes from 'prop-types';
// ANIMATION
// TRANSLATION
import { useTranslation } from 'react-i18next';
// ICONS
import { faHome } from '@fortawesome/free-solid-svg-icons';
// CONFIG
import navigationIcons from '../../../config/navigationIcons';
// COMPONENTS
import NavItem from './NavItem';

// STYLES
import style from '../../../../scss/layouts/navigation.module.scss';
import NavigationGroup from './NavigationGroup';

const Navigation = ({ items, action }) => {
    // Contexts
    const { t } = useTranslation();

    return (
        <nav className={style.menu}>
            <ul className={`${style.list} ${action ? style.listClosed : ''}`}>
                <NavItem
                    key="home"
                    title={t('application.home')}
                    path="/"
                    action={action}
                    icon={faHome}
                />
                {items
                    .filter((item) => {
                        return !item.group || (item.group && item.groupParent);
                    })
                    .map(({ name, group, groupParent, path }) => {
                        if (!groupParent) {
                            return (
                                <NavItem
                                    key={name}
                                    title={t(`${group ?? name}.${name}`)}
                                    path={path}
                                    action={action}
                                    icon={navigationIcons[name]}
                                    group={group}
                                    isParent={groupParent}
                                />
                            );
                        }

                        // GROUP NAVIGATION
                        return (
                            <NavigationGroup
                                key={name}
                                name={name}
                                action={action}
                                items={items.filter((item) => {
                                    return (
                                        item.group === group &&
                                        !item.groupParent
                                    );
                                })}
                            />
                        );
                    })}
            </ul>
        </nav>
    );
};

Navigation.propTypes = {
    /**
     * Type of resources
     */
    items: PropTypes.arrayOf(
        PropTypes.shape({
            name: PropTypes.string,
            path: PropTypes.string,
        }),
    ).isRequired,
    /**
     * Type of action
     */
    action: PropTypes.bool.isRequired,
};

export default Navigation;
