const navigationAnimate = {
    open: {
        paddingTop: '0',
        width: '28rem',
        transition: {
            type: 'tween',
            when: 'beforeChildren',
            staggerChildren: 0.3,
        },
    },
    closed: {
        paddingTop: '1.5rem',
        width: '7rem',
        backgroundColor: '#292847',
        transition: {
            type: 'tween',
            when: 'afterChildren',
        },
    },
    listItem: {
        hover: {
            scale: 1.03,
            transition: {
                type: 'spring',
                stiffness: 400,
                damping: 10,
            },
        },
    },
    text: {
        open: {
            opacity: 1,
            letterSpacing: '.1rem',
        },
        closed: {
            opacity: 0,
            letterSpacing: '-1rem',
            transition: {
                type: 'tween',
            },
        },
    },
    icon: {
        open: {
            fontSize: '1.5rem',
        },
        closed: {
            fontSize: '2.5rem',
        },
    },
};

export default navigationAnimate;
