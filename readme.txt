=== WooCommerce Product Archive Customiser ===
Contributors: jameskoster
Tags: woocommerce, ecommerce, products
Requires at least: 4.4
Tested up to: 4.6.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Customise the appearance of product archives in WooCommerce.

== Description ==

Allows you to customise WooCommerce product archives. Change the number of product columns and the number of products displayed per page. Toggle the display of core elements and enable some that are not included in WooCommerce core such as stock levels and product categories.

Please feel free to contribute on <a href="https://github.com/jameskoster/woocommerce-product-archive-customiser">github</a>.

== Installation ==

1. Upload `woocommerce-product-archive-customiser` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the options in the 'Product Archives' section of the WordPress Customizer.
3. Done!

== Frequently Asked Questions ==

= One / Some of the options don't work. What gives? =

If your theme has been integrated with WooCommerce it is possibly already adding or removing some of the archive compontents. If so the plugin options may be overwritten by the theme regardless of the settings you choose.

= The categories / new badge / stock don't look very good. Help! =
It's possible you will need to add a little css to your theme / child theme to tidy up these elements.

= I want to style the "new" badge, stock notices and categories myself, how do I remove the default styles? =

There are only a couple of styles applied to these components. Although not best practise it's probably safe to just overwrite these with your own css. However, if you want to do it properly (and cut out a http request), add the following code to the functions.php file in your theme / child theme to dequeue the css:

`
add_action( 'wp_enqueue_scripts', 'remove_pac_styles', 30 );
function remove_pac_styles() {
	wp_dequeue_style( 'pac-styles' );
}
`

== Screenshots ==

1. The Product Archive Customiser Settings.
2. An example product archive with everything enabled.

== Changelog ==

= 1.0.3 - 13/10/2016 =
* Fix - Column layout
* Fix - Stock quantities of 1 are now displayed.

= 1.0.2 - 06/10/2016 =
* Fix - Specify a default for Customizer settings.
* Fix - More explicit css for column layout.

= 1.0.1 - 29/04/2016 =
* Fix - Products per page setting.

= 1.0.0 - 28/04/2016 =
* New - All settings have been moved to the Customizer. Old settings should be automatically imported.
* Tweak - Complete code tidy / removal of unused assets.
* Tweak - Removed 'products per page' dropdown feature. There are various plugins on .org that do this, install one :)

= 0.5.1 - 13/03/2016 =
* Tweak - Change formatting of "in stock" message to use php formatting instead of concatenation.

= 0.5.0 - 30/01/2015 =
* Tweak - CSS classes for new badge / categories moved to parent element. Props emirpprime
* Tweak - Fixed potential 'page not found' errors when using the products per page selector feature. Props billras

= 0.4.0 - 13/06/2014 =
* Fix - Rating option
* Localization - Added .pot.

= 0.3.0  - 04/02/2014 =
* WooCommerce 2.1 compatibility.
* Fixed a notice when using the products per page selector feature.

= 0.2.0  - 28/11/2013 =
* Added option for user to choose how many products to display per page. Kudos @spmlucas.

= 0.1.1  - 10/06/2013 =
* Product column settings now affect product categories & tags
* Product categories display inline for better theme compatibility
* Stripped object pass by reference

= 0.1 =
Initial release.