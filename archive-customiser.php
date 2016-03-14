<?php
/*
Plugin Name: WooCommerce Product Archive Customiser
Plugin URI: http://jameskoster.co.uk/tag/product-archive-customiser/
Version: 0.5.1
Description: Allows you to customise WooCommerce product archives. Change the number of product columns and the number of products displayed per page. Toggle the display of core elements and enable some that are not included in WooCommerce core such as stock levels and product categories.
Author: jameskoster
Tested up to: 3.9.1
Author URI: http://jameskoster.co.uk
Text Domain: woocommerce-product-archive-customiser
Domain Path: /languages/

	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * Localisation
	 **/
	load_plugin_textdomain( 'woocommerce-product-archive-customiser', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/**
	 * PAC class
	 **/
	if ( ! class_exists( 'WC_pac' ) ) {

		class WC_pac {

			public function __construct() {
				add_action( 'wp_enqueue_scripts', array( $this, 'wc_pac_styles' ) );

				// Init settings
				$this->settings = array(
					array(
						'name' 		=> __( 'Product Archives', 'woocommerce-product-archive-customiser' ),
						'desc'		=> __( 'Toggle the display of various components on product archives and change product layout.', 'woocommerce-product-archive-customiser' ),
						'type' 		=> 'title',
						'id' 		=> 'wc_pac_options'
					),
					array(
						'title' 	=> __( 'Product Columns', 'woocommerce-product-archive-customiser' ),
						'desc'		=> __( 'The number of columns that products are arranged in to on archives', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_columns',
						'default'	=> '4',
						'type' 		=> 'select',
						'options' 	=> array(
							'2'  	=> __( '2', 'woocommerce-product-archive-customiser' ),
							'3' 	=> __( '3', 'woocommerce-product-archive-customiser' ),
							'4' 	=> __( '4', 'woocommerce-product-archive-customiser' ),
							'5' 	=> __( '5', 'woocommerce-product-archive-customiser' )
						)
					),
					array(
						'title'		=> __( 'Products per page', 'woocommerce-product-archive-customiser' ),
						'desc' 		=> __( 'The number of products displayed per page', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_products_per_page',
						'default'	=> '10',
						'type' 		=> 'number',
					),
					array(
						'name' 		=> __( 'Display', 'woocommerce-product-archive-customiser' ),
						'desc' 		=> __( 'Product Count', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_product_count',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Per Page Dropdown', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_product_perpage',
						'type' 		=> 'checkbox',
						'default'	=> 'no'
					),
					array(
						'desc' 		=> __( 'Product Sorting', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_product_sorting',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Sale Flashes', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_sale_flash',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Add to cart buttons', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_add_to_cart',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Thumbnails', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_thumbnail',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Prices', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_price',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Ratings', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_rating',
						'type' 		=> 'checkbox',
						'default'	=> 'yes'
					),
					array(
						'desc' 		=> __( 'Product categories', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_categories',
						'type' 		=> 'checkbox',
						'default'	=> 'no'
					),
					array(
						'desc' 		=> __( 'Stock', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_stock',
						'type' 		=> 'checkbox',
						'default'	=> 'no'
					),
					array(
						'desc' 		=> __( '"New" badges', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_new_badge',
						'type' 		=> 'checkbox',
						'default'	=> 'no'
					),
					array(
						'desc' 		=> __( 'Display the "New" badge for how many days?', 'woocommerce-product-archive-customiser' ),
						'id' 		=> 'wc_pac_newness',
						'type' 		=> 'number',
						'default'	=> '30'
					),
					array( 'type' => 'sectionend', 'id' => 'wc_pac_options' ),
				);


				// Default options
				add_option( 'wc_pac_columns', '4' );
				add_option( 'wc_pac_products_per_page', '10' );
				add_option( 'wc_pac_product_perpage', 'no' );
				add_option( 'wc_pac_product_count', 'yes' );
				add_option( 'wc_pac_product_sorting', 'yes' );
				add_option( 'wc_pac_sale_flash', 'yes' );
				add_option( 'wc_pac_add_to_cart', 'yes' );
				add_option( 'wc_pac_thumbnail', 'yes' );
				add_option( 'wc_pac_price', 'yes' );
				add_option( 'wc_pac_rating', 'yes' );
				add_option( 'wc_pac_new_badge', 'no' );
				add_option( 'wc_pac_categories', 'no' );
				add_option( 'wc_pac_stock', 'no' );
				add_option( 'wc_pac_newness', '30' );


				// Admin
				add_action( 'woocommerce_settings_catalog_options_after', array( $this, 'admin_settings' ), 20 );
				add_action( 'woocommerce_update_options_catalog', array( $this, 'save_admin_settings' ) ); // < 2.1
				add_action( 'woocommerce_update_options_products', array( $this, 'save_admin_settings' ) ); // 2.1 +
				add_action( 'admin_enqueue_scripts', array( $this, 'wc_pac_admin_scripts' ) );
				add_action( 'init', array( $this, 'wc_pac_fire_customisations' ) );
				add_action( 'wp', array( $this, 'wc_pac_columns' ) ); // This doesn't work when hooked into init :(

			}


	        /*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/

			// Load the settings
			function admin_settings() {
				woocommerce_admin_fields( $this->settings );
			}


			// Save the settings
			function save_admin_settings() {
				woocommerce_update_options( $this->settings );
			}

			// Admin scripts
			function wc_pac_admin_scripts() {
				$screen       = get_current_screen();
			    $wc_screen_id = strtolower( __( 'WooCommerce', 'woocommerce' ) );

			    // WooCommerce admin pages
			    if ( in_array( $screen->id, apply_filters( 'woocommerce_screen_ids', array( 'toplevel_page_' . $wc_screen_id, $wc_screen_id . '_page_woocommerce_settings' ) ) ) ) {

			    	wp_enqueue_script( 'wc-pac-script', plugins_url( '/assets/js/script.min.js', __FILE__ ) );

			    }
			}

			// Setup styles
			function wc_pac_styles() {
				wp_enqueue_style( 'pac-styles', plugins_url( '/assets/css/pac.css', __FILE__ ) );
				wp_enqueue_style( 'pac-layout-styles', plugins_url( '/assets/css/layout.css', __FILE__ ), '', '', 'only screen and (min-width: ' . apply_filters( 'woocommerce_style_smallscreen_breakpoint', $breakpoint = '768px' ) . ')' );
			}

			// Fire customisations!
			function wc_pac_fire_customisations() {

				// Products per page
				add_filter( 'loop_shop_per_page', array( $this, 'woocommerce_pac_products_per_page' ), 20 );

				// Per Page Dropdown
				if ( get_option( 'wc_pac_product_perpage' ) == 'yes' ) {
					add_action( 'woocommerce_before_shop_loop', array( $this, 'woocommerce_pac_show_product_perpage' ), 30 );
				}

				// Sale flash
				if ( get_option( 'wc_pac_sale_flash' ) == 'no' ) {
					remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
				}

				// Result Count
				if ( get_option( 'wc_pac_product_count' ) == 'no' ) {
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
				}

				// Product Ordering
				if ( get_option( 'wc_pac_product_sorting' ) == 'no' ) {
					remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				}

				// Add to cart button
				if ( get_option( 'wc_pac_add_to_cart' ) == 'no' ) {
					remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				}

				// Thumbnail
				if ( get_option( 'wc_pac_thumbnail' ) == 'no' ) {
					remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
				}

				// Price
				if ( get_option( 'wc_pac_price' ) == 'no' ) {
					remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
				}

				// Rating
				if ( get_option( 'wc_pac_rating' ) == 'no' ) {
					remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
				}

				// New Badge
				if ( get_option( 'wc_pac_new_badge' ) == 'yes' ) {
					add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'woocommerce_pac_show_product_loop_new_badge' ), 30 );
				}

				// Stock
				if ( get_option( 'wc_pac_stock' ) == 'yes' ) {
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'woocommerce_pac_show_product_stock' ), 30 );
				}

				// Categories
				if ( get_option( 'wc_pac_categories' ) == 'yes' ) {
					add_action( 'woocommerce_after_shop_loop_item', array( $this, 'woocommerce_pac_show_product_categories' ), 30 );
				}
			}

			// Product columns
			function wc_pac_columns() {
				// Product columns
				if ( is_shop() || is_product_category() || is_product_tag() ) {
					add_filter( 'body_class', array( $this, 'woocommerce_pac_columns' ) );
					add_filter( 'loop_shop_columns', array( $this, 'woocommerce_pac_products_row' ) );
				}
			}


			/*-----------------------------------------------------------------------------------*/
			/* Frontend Functions */
			/*-----------------------------------------------------------------------------------*/

			// Products per page
			function woocommerce_pac_products_per_page() {
				$per_page 	= get_option( 'wc_pac_products_per_page' );
				if ( isset( $_COOKIE['per_page'] ) ) {
					$per_page = $_COOKIE['per_page'];
				}
				if ( isset( $_POST['per_page'] ) ) {
					setcookie( 'per_page', $_POST['per_page'], time()+1209600, '/' );
					$per_page = $_POST['per_page'];
				}
				return $per_page;
			}

			// Per Page Dropdown
			function woocommerce_pac_show_product_perpage() {
				$per_page = get_option( 'wc_pac_products_per_page' );

				if ( isset( $_REQUEST['per_page'] ) ) {
					$woo_per_page = $_REQUEST['per_page'];
				} elseif ( ! isset( $_REQUEST['per_page'] ) && isset( $_COOKIE['per_page'] ) ) {
					$woo_per_page = $_COOKIE['per_page'];
				} else {
					$woo_per_page = $per_page;
				}

				// set action URL
				if ( is_shop() ) {
					$url = get_permalink( wc_get_page_id( 'shop' ) );
				} elseif ( is_product_category() ) {
					global $wp_query;
					$cat = $wp_query->get_queried_object();
					$url = get_term_link( $cat );
				} elseif ( is_product_tag() ) {
					global $wp_query;
					$tag = $wp_query->get_queried_object();
					$url = get_term_link( $tag );
				}

				// add querystring to URL if set
				if ( $_SERVER['QUERY_STRING'] != '' ) {
					$url .= '?' . $_SERVER['QUERY_STRING'];
				}

				?>
				<form class="woocommerce-ordering" method="post" action="<?php echo $url; ?>">
					<select name="per_page" class="per_page" onchange="this.form.submit()">
						<?php
							$x = 1;
							while ( $x <= 5 ) {
								$value 		= $per_page * $x;
								$selected 	= selected( $woo_per_page, $value, false );
								$label 		= __( "{$value} per page", 'woocommerce-product-archive-customiser' );
								echo "<option value='{$value}' {$selected}>{$label}</option>";
								$x++;
							}
						?>
					</select>
				</form>
				<?php
			}

			// Product columns
			function woocommerce_pac_columns( $classes ) {
				$columns 	= get_option( 'wc_pac_columns' );
				$classes[] 	= 'product-columns-' . $columns;
				return $classes;
			}

			function woocommerce_pac_products_row() {
				$columns 	= get_option( 'wc_pac_columns' );
				return $columns;
			}

			// Display the new badge
			function woocommerce_pac_show_product_loop_new_badge() {
				$postdate 		= get_the_time( 'Y-m-d' );			// Post date
				$postdatestamp 	= strtotime( $postdate );			// Timestamped post date
				$newness 		= get_option( 'wc_pac_newness' ); 	// Newness in days as defined by option

				if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge
					echo '<p class="wc-new-badge"><span>' . __( 'New', 'woocommerce-product-archive-customiser' ) . '</span></p>';
				}
			}

			function woocommerce_pac_show_product_categories() {
				global $post;
				$terms_as_links = get_the_term_list( $post->ID, 'product_cat', '', ', ', '' );
				echo '<p class="categories"><small>' . $terms_as_links . '</small></p>';
			}

			function woocommerce_pac_show_product_stock() {
				global $product;
				$stock = $product->get_total_stock();
			 	if ( ! $product->is_in_stock() ) {
			 		echo '<p class="stock out-of-stock"><small>' . __( 'Out of stock', 'woocommerce' ) . '</small></p>';
			 	} elseif ( $stock > 1 ) {
			 		echo '<p class="stock in-stock"><small>' . sprintf( __( '%s in stock', 'woocommerce' ), $stock ) . '</small></p>';
			 	}
			}
		}

		$WC_pac = new WC_pac();
	}
}