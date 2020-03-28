const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.react("resources/js/app.js", "public/js").sass(
    "resources/sass/app.scss",
    "public/css"
);

mix.js("resources/js/orders-feed.js", "public/js")
	.js("resources/js/bids.js", "public/js")
	.js("resources/js/view-contact.js", "public/js")
	.js("resources/js/orders.js", "public/js")
	.js("resources/js/user-setup.js", "public/js")
	.js("resources/js/firebase-messaging-sw.js", "public")
	.version();
