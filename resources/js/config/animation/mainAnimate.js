import navigationAnimate from './navigationAnimate';

const mainAnimate = {
    open: {
        marginLeft: navigationAnimate.closed.width,
        width: `calc(100vw - ${navigationAnimate.closed.width} - 1rem)`,
    },
    closed: {
        width: `calc(100vw - ${navigationAnimate.open.width} - 1rem)`,
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
