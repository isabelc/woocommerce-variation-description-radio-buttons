<?php 
/*
Plugin Name: WooCommerce Variation Description Radio Buttons
Plugin URI: http://isabelcastillo.com/free-plugins/woocommerce-variation-description-radio-buttons
Description: Change WooCommerce variations into radio buttons and adds descriptions to variations.
Version: 1.1-beta3
Author: Isabel Castillo
Author URI: http://isabelcastillo.com
License: GPL2
Text Domain: woo-vdrb
Domain Path: lang

Copyright 2014 - 2015 Isabel Castillo

WooCommerce Variation Description Radio Buttons is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

WooCommerce Variation Description Radio Buttons is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WooCommerce Variation Description Radio Buttons; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/
class Woo_Variation_Description_Radio_Buttons{

	private static $instance = null;
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	private function __construct() {
		add_filter( 'woocommerce_locate_template', array( $this, 'wooradio_woocommerce_locate_template' ), 10, 3 ); 
		add_action( 'wp_enqueue_scripts', array( $this, 'register_woo_radio_button_scripts' ) );
		add_action( 'wp_head', array( $this, 'inline_css' )); 
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variable_fields' ), 10, 3 );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'variable_fields_process' ), 10, 1 );

	}

	public function woovdrb_plugin_path() { 
		return untrailingslashit( plugin_dir_path( __FILE__ ) ); 
	}

	/**
	* Use our template.
	*/
	public function wooradio_woocommerce_locate_template( $template, $template_name, $template_path ) { 
		global $woocommerce; 
		$_template = $template; 
		if ( ! $template_path ) $template_path = $woocommerce->template_url; 
			$plugin_path  = $this->woovdrb_plugin_path() . '/woocommerce/'; 
		// Look within passed path within the theme - this is priority 
		$template = locate_template( 
			array( 
			$template_path . $template_name, 
			$template_name 
			) 
		);
		// Modification: Get the template from this plugin, if it exists 
		if ( ! $template && file_exists( $plugin_path . $template_name ) ) 
			$template = $plugin_path . $template_name; 
		// Use default template 
		if ( ! $template ) 
			$template = $_template;
		// Return what we found 
		return $template; 
	} 
	
	/**
	* Use our cart variation script.
	*/

	public function register_woo_radio_button_scripts () {
		wp_deregister_script( 'wc-add-to-cart-variation' ); 
		wp_dequeue_script( 'wc-add-to-cart-variation' ); 

		$suffix = '.min.js';
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$suffix = '.js';
		}

		wp_register_script( 'wc-add-to-cart-variation', plugins_url( 'woocommerce/assets/js/frontend/add-to-cart-variation' . $suffix, __FILE__ ), array( 'jquery'), false, true ); 


		if ( is_product() ) {
			wp_enqueue_script('wc-add-to-cart-variation'); 
		}
	} 
	/**
	* Inline small CSS to increase page load speed
	* @since 0.5.4
	*/
	public function inline_css() {
		?><style>.wvdrb-one-third,.wvdrb-two-thirds{float:left;margin:20px 0 10px}.wvdrb-one-third{width:31%;clear:left;}.wvdrb-two-thirds{width:65%}.variations fieldset{padding:1em;border:0}.woocommerce div.product form.cart .variations {width:100%}.single_variation .amount{font-weight:700}@media (max-width:768px){.wvdrb-one-third,.wvdrb-two-thirds{float:none;margin:20px 0 10px;width:100%}}</style><?php
	}
	
	/**
	* Add varation description field to backend.
	*/
	public function variable_fields( $loop, $variation_data, $variation ) {

		if ( empty( $variation ) ) {
			return;
		}

		// check if this attribute is a taxonomy (Global attribute)
		$prefix = 'attribute_';
		$attribute = '';
		foreach ( $variation_data as $key => $value ) {
			if ( substr( $key, 0, 10 ) == $prefix ) {
				// get the attribute 
				$attribute = substr( $key, 10 );

			}
		}

		// Do not show description field for Global Attributes
		// since they have desc field in Products -> Attributes
		if ( ! taxonomy_exists( $attribute ) ) {

			add_action( 'woocommerce_product_after_variable_attributes_js', array( $this, 'variable_fields_js' ) );
			?>	
			<tr>
				<td>
				<?php
				if ( isset( $variation->ID ) ) {
					woocommerce_wp_text_input( 
						array( 
							'id'          => '_isa_woo_variation_desc['.$loop.']', 
							'label'       => __( 'Variation Description', 'woo-vdrb' ), 
							'desc_tip'    => 'true',
							'description' => __( 'Enter a description for this variation.', 'woo-vdrb' ),
							'value'       => get_post_meta( $variation->ID, '_isa_woo_variation_desc', true )
					) );
				}
				?>
				</td>
			</tr>
		<?php
		}
	}
	
	/**
	* JS for variation description field.
	*/

	public function variable_fields_js() {
	?>
		<tr>
			<td>
			<?php 
			woocommerce_wp_text_input( 
				array( 
					'id'          => '_isa_woo_variation_desc[ + loop + ]', 
					'label'       => __( 'Variation Description', 'woo-vdrb' ), 
					'desc_tip'    => 'true',
					'description' => __( 'Enter a description for this variation.', 'woo-vdrb' ),
					'value'       => ''
				)
			);
			?>
			</td>
		</tr>
	<?php
	}

	/**
	* Save varation description values
	* @since 0.3
	*/
	
	public function variable_fields_process( $post_id ) {
		if (isset( $_POST['variable_sku'] ) ) :
			$variable_sku = $_POST['variable_sku'];
			$variable_post_id = $_POST['variable_post_id'];
			$variable_description_field = isset( $_POST['_isa_woo_variation_desc'] ) ? $_POST['_isa_woo_variation_desc'] : '';
			for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
				$variation_id_pre = isset( $variable_post_id[$i] ) ? $variable_post_id[$i] : '';
				$variation_id = (int) $variation_id_pre;
				if ( isset( $variable_description_field[$i] ) ) {
					update_post_meta( $variation_id, '_isa_woo_variation_desc', stripslashes( $variable_description_field[$i] ) );
				}
			endfor;
		endif;
	}
} // end class
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$woo_variation_description_radio_buttons = Woo_Variation_Description_Radio_Buttons::get_instance();
}
?>