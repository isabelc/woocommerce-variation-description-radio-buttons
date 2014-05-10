=== WooCommerce Variation Description Radio Buttons ===
Contributors: isabel104,DesignLoud
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=me%40isabelcastillo%2ecom
Tags: variations, woocommerce, variable products, variation descriptions, radio, radio buttons
Requires at least: 3.7
Tested up to: 3.9.1
Stable Tag: 0.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Change WooCommerce variations into radio buttons and adds descriptions to variations.

== Description ==

This is a simple and light-weight plugin that once installed and activated will convert your Woocommerce variations from a drop down menu to radio buttons.  Allow your consumers to see all of your variations without having to access your drop down menu.

It also adds a "Variation Description" field. You fill the field in the backend, and it will show on the frontend on the product page.

It stops the forced display of "SKU". The SKU for variable products will only be displayed if you enter a SKU.

See the [documentation](http://isabelcastillo.com/docs/category/woocommerce-variation-description-radio-buttons)



**Credits**

This is a modified version of a [gist by kloon](https://gist.github.com/kloon/4228021), which i joined with the plugin [WooCommerce Radio Buttons](http://wordpress.org/plugins/woocommerce-radio-buttons/) by DesignLoud.

== Installation ==

1. Upload the `.zip` file through the "Plugins --> Add New --> Upload" in WordPress.

2. Enter a Variation Description for each variation.

3. Also set a default variation to be selected in your edit product page.

== Frequently Asked Questions ==

= How to display 'add to cart' and selected variation automatically =
Open your WP Dashboard and click on products, then click on one of your variation products and scroll down to product data.  Under the tab 'variations' select a default variation (towards the bottom) and click update.

= I have downloaded your plugin and activated but I still do not get any radio buttons =
This is typically caused by a theme or plugin conflicting.  Try activating the default Twenty Fourteen WordPress theme.  If that doesnt work then go through and deactivate your plugins and then reactivate one at a time to find the conflict.

== Changelog ==
= 0.5.4 = 
* Tweak: do singleton class.
* Maintenance: inline small CSS to increase page load speed.

= 0.5.3 =
* Initial release.