<?php
/**
 * Plugin Name: WooCommerce Product Archive Customiser
 * Plugin URI: https://wordpress.org/plugins/woocommerce-product-archive-customiser/
 * Version: 1.0.2
 * Description: Allows you to customise WooCommerce product archives. Change the number of product columns and the number of products displayed per page. Toggle the display of core elements and enable some that are not included in WooCommerce core such as stock levels and product categories.
 * Author: jameskoster
 * Tested up to: 4.6.1
 * Author URI: http://jameskoster.co.uk
 * Text Domain: woocommerce-product-archive-customiser
 * Domain Path: /languages/

 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package woocommerce_product_archive_customiser
 */

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {

	/**
	 * Localisation
	 */
	load_plugin_textdomain( 'woocommerce-product-archive-customiser', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/**
	 * PAC class
	 */
	if ( ! class_exists( 'WC_pac' ) ) {

		/**
		 * The Product Archive Customiser class
		 */
		final class WC_pac {

			/**
			 * The version number.
			 *
			 * @var     string
			 * @access  public
			 */
			public $version;

			/**
			 * The constructor!
			 */
			public function __construct() {
				$this->version = '1.0.1';

				add_action( 'wp_enqueue_scripts', array( $this, 'wc_pac_styles' ) );
				add_action( 'init', array( $this, 'wc_pac_setup' ) );
				add_action( 'wp', array( $this, 'wc_pac_fire_customisations' ) );
				add_action( 'wp', array( $this, 'wc_pac_columns' ) );
				add_filter( 'loop_shop_per_page', array( $this, 'woocommerce_pac_products_per_page' ), 20 );

				// Upgrade script.
				if ( version_compare( $this->version, '1.0.0' ) >= 0 ) {
					add_action( 'plugins_loaded', array( $this, 'update_settings' ) );
				}
			}

			/**
			 * Checks if any settings from <1.0.0 exist.
			 * If they do, run the importer and delete the old settings.
			 *
			 * @return void
			 */
			public function update_settings() {
				$old_setting = get_option( 'wc_pac_columns' );

				if ( $old_setting ) {
					$this->import_existing_settings();
					$this->unset_old_settings();
				}
			}

			/**
			 * Import existing settings
			 *
			 * @return void
			 */
			public function import_existing_settings() {
				$old_columns           = get_option( 'wc_pac_columns' );
				$old_products_per_page = get_option( 'wc_pac_products_per_page' );
				$old_product_count     = get_option( 'wc_pac_product_count' );
				$old_product_sorting   = get_option( 'wc_pac_product_sorting' );
				$old_sale_flash        = get_option( 'wc_pac_sale_flash' );
				$old_add_to_cart       = get_option( 'wc_pac_add_to_cart' );
				$old_thumbnail         = get_option( 'wc_pac_thumbnail' );
				$old_price             = get_option( 'wc_pac_price' );
				$old_ratings           = get_option( 'wc_pac_rating' );
				$old_new_badge         = get_option( 'wc_pac_new_badge' );
				$old_categories        = get_option( 'wc_pac_categories' );
				$old_stock             = get_option( 'wc_pac_stock' );
				$old_newness           = get_option( 'wc_pac_newness' );

				if ( $old_columns ) {
					set_theme_mod( 'wc_pac_columns', $old_columns );
				}

				if ( $old_products_per_page ) {
					set_theme_mod( 'wc_pac_products_per_page', $old_products_per_page );
				}

				if ( 'yes' === $old_product_count ) {
					set_theme_mod( 'wc_pac_product_count', true );
				} else {
					set_theme_mod( 'wc_pac_product_count', false );
				}

				if ( 'yes' === $old_product_sorting ) {
					set_theme_mod( 'wc_pac_product_sorting', true );
				} else {
					set_theme_mod( 'wc_pac_product_sorting', false );
				}

				if ( 'yes' === $old_sale_flash ) {
					set_theme_mod( 'wc_pac_sale_flash', true );
				} else {
					set_theme_mod( 'wc_pac_sale_flash', false );
				}

				if ( 'yes' === $old_add_to_cart ) {
					set_theme_mod( 'wc_pac_add_to_cart', true );
				} else {
					set_theme_mod( 'wc_pac_add_to_cart', false );
				}

				if ( 'yes' === $old_thumbnail ) {
					set_theme_mod( 'wc_pac_thumbnail', true );
				} else {
					set_theme_mod( 'wc_pac_thumbnail', false );
				}

				if ( 'yes' === $old_price ) {
					set_theme_mod( 'wc_pac_price', true );
				} else {
					set_theme_mod( 'wc_pac_price', false );
				}

				if ( 'yes' === $old_ratings ) {
					set_theme_mod( 'wc_pac_rating', true );
				} else {
					set_theme_mod( 'wc_pac_rating', false );
				}

				if ( 'yes' === $old_new_badge ) {
					set_theme_mod( 'wc_pac_new_badge', true );
				} else {
					set_theme_mod( 'wc_pac_new_badge', false );
				}

				if ( 'yes' === $old_categories ) {
					set_theme_mod( 'wc_pac_categories', true );
				} else {
					set_theme_mod( 'wc_pac_categories', false );
				}

				if ( 'yes' === $old_stock ) {
					set_theme_mod( 'wc_pac_stock', true );
				} else {
					set_theme_mod( 'wc_pac_stock', false );
				}

				if ( $old_newness ) {
					set_theme_mod( 'wc_pac_newness', $old_newness );
				}
			}

			/**
			 * Unset the old settings
			 *
			 * @return void
			 */
			public function unset_old_settings() {
				delete_option( 'wc_pac_columns' );
				delete_option( 'wc_pac_products_per_page' );
				delete_option( 'wc_pac_product_perpage' );
				delete_option( 'wc_pac_product_count' );
				delete_option( 'wc_pac_product_sorting' );
				delete_option( 'wc_pac_sale_flash' );
				delete_option( 'wc_pac_add_to_cart' );
				delete_option( 'wc_pac_thumbnail' );
				delete_option( 'wc_pac_price' );
				delete_option( 'wc_pac_rating' );
				delete_option( 'wc_pac_new_badge' );
				delete_option( 'wc_pac_categories' );
				delete_option( 'wc_pac_stock' );
				delete_option( 'wc_pac_newness' );
			}

			/**
			 * Product Archive Customiser setup
			 *
			 * @return void
			 */
			public function wc_pac_setup() {
				add_action( 'customize_register', array( $this, 'wc_pac_customize_register' ) );
			}

			/**
			 * Add settings to the Customizer
			 *
			 * @param  array $wp_customize the Customiser settings object.
			 * @return void
			 */
			public function wc_pac_customize_register( $wp_customize ) {
				$wp_customize->add_section( 'wc_pac' , array(
					'title'    => __( 'Product Archives', 'woocommerce-product-archive-customiser' ),
					'priority' => 30,
				) );

				/**
				 * Product Columns
				 */
				$wp_customize->add_setting( 'wc_pac_columns' , array(
					'default'           => '4',
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_choices' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_columns', array(
					'label'    => __( 'Product columns', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_columns',
					'type'     => 'select',
					'choices'  => array(
									'2' => '2',
									'3' => '3',
									'4' => '4',
									'5' => '5',
					),
				) ) );

				/**
				 * Products Per Page
				 */
				$wp_customize->add_setting( 'wc_pac_products_per_page' , array(
					'default'           => '10',
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_choices' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_products_per_page', array(
					'label'    => __( 'Products per page', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_products_per_page',
					'type'     => 'select',
					'choices'  => array(
									'2'  => '2',
									'3'  => '3',
									'4'  => '4',
									'5'  => '5',
									'6'  => '6',
									'7'  => '7',
									'8'  => '8',
									'9'  => '9',
									'10' => '10',
									'11' => '11',
									'12' => '12',
									'13' => '13',
									'14' => '14',
									'15' => '15',
									'16' => '16',
									'17' => '17',
									'18' => '18',
									'19' => '19',
									'20' => '20',
									'21' => '21',
									'22' => '22',
									'23' => '23',
									'24' => '24',
					),
				) ) );

				/**
				 * Display - product count
				 */
				$wp_customize->add_setting( 'wc_pac_product_count' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_product_count', array(
					'label'    => __( 'Display product count', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_product_count',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - product sorting
				 */
				$wp_customize->add_setting( 'wc_pac_product_sorting' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_product_sorting', array(
					'label'    => __( 'Display product sorting', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_product_sorting',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - product sale flashes
				 */
				$wp_customize->add_setting( 'wc_pac_sale_flash' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_sale_flash', array(
					'label'    => __( 'Display sale flashes', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_sale_flash',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - add to cart buttons
				 */
				$wp_customize->add_setting( 'wc_pac_add_to_cart' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_add_to_cart', array(
					'label'    => __( 'Display add to cart buttons', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_add_to_cart',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - product image
				 */
				$wp_customize->add_setting( 'wc_pac_thumbnail' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_thumbnail', array(
					'label'    => __( 'Display product image', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_thumbnail',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - price
				 */
				$wp_customize->add_setting( 'wc_pac_price' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_price', array(
					'label'    => __( 'Display prices', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_price',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - ratings
				 */
				$wp_customize->add_setting( 'wc_pac_rating' , array(
					'default'           => true,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_rating', array(
					'label'    => __( 'Display ratings', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_rating',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - new badge
				 */
				$wp_customize->add_setting( 'wc_pac_new_badge' , array(
					'default'           => false,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_new_badge', array(
					'label'    => __( 'Display new badge', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_new_badge',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Products Per Page
				 */
				$wp_customize->add_setting( 'wc_pac_newness' , array(
					'default'           => '7',
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_choices' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_newness', array(
					'label'           => __( 'Display the "New" badge for how many days', 'woocommerce-product-archive-customiser' ),
					'section'         => 'wc_pac',
					'settings'        => 'wc_pac_newness',
					'type'            => 'select',
					'choices'         => array(
											'7'  => '7',
											'14' => '14',
											'21' => '21',
											'28' => '28',
					),
					'active_callback' => array( $this, 'is_new_badge_enabled' ),
				) ) );

				/**
				 * Display - categories
				 */
				$wp_customize->add_setting( 'wc_pac_categories' , array(
					'default'           => false,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_categories', array(
					'label'    => __( 'Display categories', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_categories',
					'type'     => 'checkbox',
				) ) );

				/**
				 * Display - stock
				 */
				$wp_customize->add_setting( 'wc_pac_stock' , array(
					'default'           => false,
					'transport'         => 'refresh',
					'sanitize_callback' => array( $this, 'wc_pac_sanitize_checkbox' ),
				) );

				$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'wc_pac_stock', array(
					'label'    => __( 'Display stock', 'woocommerce-product-archive-customiser' ),
					'section'  => 'wc_pac',
					'settings' => 'wc_pac_stock',
					'type'     => 'checkbox',
				) ) );
			}

			/**
			 * Checkbox sanitization callback.
			 *
			 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
			 * as a boolean value, either TRUE or FALSE.
			 *
			 * @param bool $checked Whether the checkbox is checked.
			 * @return bool Whether the checkbox is checked.
			 */
			public function wc_pac_sanitize_checkbox( $checked ) {
				return ( ( isset( $checked ) && true == $checked ) ? true : false );
			}

			/**
			 * Sanitizes choices (selects / radios)
			 * Checks that the input matches one of the available choices
			 *
			 * @param array $input the available choices.
			 * @param array $setting the setting object.
			 */
			public function wc_pac_sanitize_choices( $input, $setting ) {
				// Ensure input is a slug.
				$input = sanitize_key( $input );

				// Get list of choices from the control associated with the setting.
				$choices = $setting->manager->get_control( $setting->id )->choices;

				// If the input is a valid key, return it; otherwise, return the default.
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

			/**
			 * New badge callback
			 *
			 * @param array $control the Customizer controls.
			 * @return bool
			 */
			public function is_new_badge_enabled( $control ) {
				return $control->manager->get_setting( 'wc_pac_new_badge' )->value() === true ? true : false;
			}

			/**
			 * Enqueue styles
			 *
			 * @return void
			 */
			function wc_pac_styles() {
				wp_enqueue_style( 'pac-styles', plugins_url( '/assets/css/pac.css', __FILE__ ) );
				wp_enqueue_style( 'pac-layout-styles', plugins_url( '/assets/css/layout.css', __FILE__ ), '', '', 'only screen and (min-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', $breakpoint = '768px' ) . ')' );
			}

			/**
			 * Action our customisations
			 *
			 * @return void
			 */
			function wc_pac_fire_customisations() {
				// Sale flash.
				if ( get_theme_mod( 'wc_pac_sale_flash', false ) === false ) {
					remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
				}

				// Result Count.
				if ( get_theme_mod( 'wc_pac_product_count', true ) === false ) {
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
				}

				// Product Ordering.
				if ( get_theme_mod( 'wc_pac_product_sorting', true ) === false ) {
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				}

				// Add to cart button.
				if ( get_theme_mod( 'wc_pac_add_to_cart', true ) === false ) {
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				}

				// Thumbnail.
				if ( get_theme_mod( 'wc_pac_thumbnail', true ) === false ) {
					remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
				}

				// Price.
				if ( get_theme_mod( 'wc_pac_price', true ) === false ) {
					remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
				}

				// Rating.
				if ( get_theme_mod( 'wc_pac_rating', true ) === false ) {
					remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
				}

				// New Badge.
				if ( get_theme_mod( 'wc_pac_new_badge', false ) === true ) {
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_pac_show_product_loop_new_badge' ), 30 );
				}

				// Stock.
				if ( get_theme_mod( 'wc_pac_stock', false ) === true ) {
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'woocommerce_pac_show_product_stock' ), 30 );
				}

				// Categories.
				if ( get_theme_mod( 'wc_pac_categories', false ) === true ) {
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'woocommerce_pac_show_product_categories' ), 30 );
				}
			}

			/**
			 * Set the product columns
			 *
			 * @return void
			 */
			function wc_pac_columns() {
				// Product columns.
				if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
					add_filter( 'body_class', array( $this, 'woocommerce_pac_columns' ) );
					add_filter( 'loop_shop_columns', array( $this, 'woocommerce_pac_products_row' ) );
				}
			}

			/**
			 * Get the products per page setting and set a cookie
			 *
			 * @return string per page cookie
			 */
			function woocommerce_pac_products_per_page() {
				$per_page = get_theme_mod( 'wc_pac_products_per_page', 10 );

				return $per_page;
			}

			/**
			 * Product columns class
			 *
			 * @param  array $classes current body classes.
			 * @return array          new body classes
			 */
			function woocommerce_pac_columns( $classes ) {
				$columns   = get_theme_mod( 'wc_pac_columns', 4 );
				$classes[] = 'product-columns-' . $columns;
				return $classes;
			}

			/**
			 * Return the desired products per row
			 *
			 * @return int product columns
			 */
			function woocommerce_pac_products_row() {
				$columns = get_theme_mod( 'wc_pac_columns', 4 );

				return $columns;
			}

			/**
			 * Display the new badge
			 *
			 * @return void
			 */
			function woocommerce_pac_show_product_loop_new_badge() {
				$postdate 		= get_the_time( 'Y-m-d' );			 // Post date.
				$postdatestamp 	= strtotime( $postdate );			 // Timestamped post date.
				$newness 		= get_theme_mod( 'wc_pac_newness', 7 ); // Newness in days as defined by option.

				// If the product was published within the newness time frame display the new badge.
				if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {
					echo '<p class="wc-new-badge"><span>' . esc_attr__( 'New', 'woocommerce-product-archive-customiser' ) . '</span></p>';
				}
			}

			/**
			 * Display the product categories
			 *
			 * @return void
			 */
			function woocommerce_pac_show_product_categories() {
				global $post;
				$terms_as_links = get_the_term_list( $post->ID, 'product_cat', '', ', ', '' );
				echo '<p class="categories"><small>' . wp_kses_post( $terms_as_links ) . '</small></p>';
			}

			/**
			 * Display the product stock
			 *
			 * @return void
			 */
			function woocommerce_pac_show_product_stock() {
				global $product;
				$stock = $product->get_total_stock();
				if ( ! $product->is_in_stock() ) {
					echo '<p class="stock out-of-stock"><small>' . esc_attr__( 'Out of stock', 'woocommerce-product-archive-customiser' ) . '</small></p>';
				} elseif ( $stock > 1 ) {
					echo '<p class="stock in-stock"><small>' . sprintf( esc_attr__( '%s in stock', 'woocommerce-product-archive-customiser' ), esc_attr( $stock ) ) . '</small></p>';
				}
			}
		}

		$wc_pac = new WC_pac();
	}
}
