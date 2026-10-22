import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Font utama J-Map
                sans:    ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['"Noto Serif JP"', 'Georgia', 'serif'],
            },
            colors: {
                // Palet warna J-Map
                jred:   '#C1272D',
                jcream: '#F7F1E3',
                jblack: '#1A1A1A',
            },
        },
    },

    plugins: [forms],
};
