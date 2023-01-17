import navigationAnimate from './navigationAnimate';

const mainAnimate = {
    open: {
        marginLeft: navigationAnimate.closed.width,
        width: `calc(100vw - ${navigationAnimate.closed.width})`,
    },
    closed: {
        width: `calc(100vw - ${navigationAnimate.open.width})`,
        marginLeft: navigationAnimate.open.width,
        transition: {
            type: 'tween',
        },
    },
    exit: {
        x: '-100vw',
    },
};

export default mainAnimate;
