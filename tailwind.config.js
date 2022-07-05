const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './node_modules/@protonemedia/inertiajs-tables-laravel-query-builder/**/*.{js,vue}',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                micro: '.65rem'
            },
            colors: {
                slate: {
                    200: '#f3f6fd',
                    600: '#38496e',
                    700: '#1f2c48',
                    800: '#19233a'
                },
                slablu: {
                    200: '#c5d1eb',
                    300: '#b6c6e9',
                    400: '#adbbd9',
                    500: '#8498c3',
                    580: '#637193',
                    600: '#576991',
                    700: '#394b72',
                    800: '#203052'
                }
            },
        },
    },
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
