import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./vendor/wire-elements/modal/resources/views/*.blade.php",
    ],
    safelist: [
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ["sm", "md", "lg", "xl", "2xl"],
        },
    ],
    theme: {
        extend: {
            fontFamily: {
                tome: ["TomeSans", ...defaultTheme.fontFamily.sans],
                colors: {
                    newOrder: "#FFF9EC",
                    workingOrder: "#E9EFF5",
                    doneOrder: "#EFF7E9",
                    cancelledOrder: "#FEF0F5",
                },
            },
            colors: {
                primary: "#DC0814",
                secondary: "#222222",
            },
        },
    },

    plugins: [forms],
};
