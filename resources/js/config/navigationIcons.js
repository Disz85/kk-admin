import {
    faUser,
    faUsers,
    faTags,
    faNewspaper,
    faBriefcase,
    faBusinessTime,
    faArrowRightFromBracket,
    faBasketShopping,
    faFlask,
    faFlaskVial,
    faCartFlatbed,
    faBoxesStacked,
    faHands,
    faHandDots,
    faPaste,
    faUserNurse,
} from '@fortawesome/free-solid-svg-icons';

const navigationIcons = {
    users: faUser,
    authors: faUsers,
    articles: faNewspaper,
    tags: faTags,
    brands: faBriefcase,
    'brand-change-requests': faBusinessTime,
    products: faBasketShopping,
    ingredients: faFlask,
    categories: faBoxesStacked,
    'categories-article': faPaste,
    'categories-ingredient': faFlaskVial,
    'categories-product': faCartFlatbed,
    'categories-skintype': faHands,
    'categories-skinconcern': faHandDots,
    'categories-hairproblem': faUserNurse,
    logout: faArrowRightFromBracket,
};

export default navigationIcons;
