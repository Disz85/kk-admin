/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.jsx',
        './resources/**/*.scss',
    ],
    theme: {
        colors: {
            light: {
                snow: '#ffffff',
                mist: '#e0e1dd',
                cloud: '#dee9f1',
                lemon: '#ffed8b',
                candy: '#eed4d4',
                orange: '#f5b77f',
                yoghurt: '#dfd2d3',
                mint: '#cfdcc2',
                leaf: '#a9cbab',
                sea: '#94caec',
                purple: '#bbbbee',
                storm: '#8b94b7',
                grey: '#f8f9fa',
            },
            medium: {
                linen: '#bebaae',
                mustard: '#e7d66f',
                rosy: '#f0a8a6',
                pumpkin: '#f5886a',
                beige: '#d0a69f',
                balaton: '#8db0a2',
                forest: '#648766',
                sky: '#80b5d7',
                levander: '#7676aa',
                blackberry: '#595f77',
                grey: '#f0f0f0',
            },
            dark: {
                autumn: '#ad9e7c',
                calendula: '#f3c131',
                punch: '#f08f8d',
                brick: '#d86a4c',
                berry: '#a88586',
                spinach: '#6c9584',
                pine: '#244b26',
                blue: '#5a93b7',
                plum: '#51518e',
                night: '#292847',
                grey: '#666666',
            },
        },
        fontFamily: {
            primary: ['Maven Pro', 'sans-serif'],
            nav: ['Maven Pro', 'sans-serif'],
            button: ['Maven Pro', 'sans-serif'],
        },
        extend: {
            strokeWidth: {
                hamburger: '0.4rem',
            },
            transitionProperty: {
                'stroke-dasharray': 'stroke-dasharray',
                'stroke-dashoffset': 'stroke-dashoffset',
            },
            margin: {
                100: '25rem',
            },
            width: {
                100: '25rem',
            },
        },
    },
    plugins: [],
};
