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
                tome: ['TomeSans', ...defaultTheme.fontFamily.sans],
            colors: {
                newOrder: '#FFF9EC',
                workingOrder: '#E9EFF5',
                doneOrder: '#EFF7E9',
                cancelledOrder: '#FEF0F5'
                },
            },
            colors: {
                primary: '#DC0814', 
                secondary: '#222222', 
             
            },
        },
        safelist: [
            'bg-[#FFF9EC]',
            'bg-[#E9EFF5]',
            'bg-red-500',
            'bg-green-200',
            // Add other dynamic classes here
          ],
    },

    plugins: [forms],
};
