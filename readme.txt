=== WooCommerce Variation Description Radio Buttons ===
Contributors: isabel104
Tags: variations, woocommerce, variable products, variation descriptions, radio, radio buttons
Requires at least: 4.2
Tested up to: 4.3
Stable Tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Change WooCommerce variations into radio buttons and adds descriptions to variations.

== Description ==

This is a simple and light-weight plugin that once installed and activated will convert your Woocommerce variations from a drop down menu to radio buttons.  Allow your consumers to see all of your variations without having to access your drop down menu.

It also adds a "Variation Description" field. You fill the field in the backend, and it will show on the frontend on the product page.

It stops the forced display of "SKU". The SKU for variable products will only be displayed if you enter a SKU.

See the [documentation](https://isabelcastillo.com/docs/woocommerce-variation-description-radio-buttons-plugin)


**Credits**

This is a modified version of a [gist by kloon](https://gist.github.com/kloon/4228021), which i joined with the plugin [WooCommerce Radio Buttons](http://wordpress.org/plugins/woocommerce-radio-buttons/) by DesignLoud.

== Installation ==

1. Upload the `.zip` file through the "Plugins --> Add New --> Upload" in WordPress.

2. Enter a Variation Description for each variation.

3. Also set a default variation to be selected in your edit product page.


== Changelog ==
= 1.1 =
* Fix - Updated for WooCommerce 2.4+. 

= 1.0 = 
* Fix - Added compatibility with Global Attributes which are added in Products -> Attributes.
* Tweak - Updated the .js. 
* Tweak - Updated the variable.php template.

= 0.9 =
* Fix - Variation description field values were blank in the admin. So, if you update a product, the blank values were being saved, and in effect erasing the variation descriptions. Thanks to circularone for the report.
* Tweak - Load unminified .js if SCRIPT_DEBUG is true.

= 0.8 =
* Tweak - Removed 2 PHP notices for undefined variables.

= 0.7 = 
* Tweak - Add formatted, unminified js.
* Tweak - Remove unused single-product.js file.

= 0.6 =
* Fix - Align radio buttons better with CSS .
* Tweak - Show variation name label above radion buttons.

= 0.5.4 = 
* Tweak: do singleton class.
* Maintenance: inline small CSS to increase page load speed.

= 0.5.3 =
* Initial release.
