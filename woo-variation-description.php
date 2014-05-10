<?php 
/*
Plugin Name: WooCommerce Variation Description Radio Buttons
Plugin URI: http://isabelcastillo.com/docs/category/woocommerce-variation-description-radio-buttons
Description: Change WooCommerce variations into radio buttons and adds descriptions to variations.
Version: 0.5.4
Author: Isabel Castillo
Author URI: http://isabelcastillo.com
License: GPL2
Text Domain: woo-vdrb
Domain Path: lang

Copyright 2014 Isabel Castillo

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
		// @test removeadd_action( 'wp_footer', array( $this, 'register_woo_radio_button_scripts' )); 
		add_action( 'woocommerce_product_after_variable_attributes', array( $this, 'variable_fields' ), 10, 2 );
		add_action( 'woocommerce_product_after_variable_attributes_js', array( $this, 'variable_fields_js' ) );
		add_action( 'woocommerce_process_product_meta_variable', array( $this, 'variable_fields_process' ), 10, 1 );
	}

	/**
	* @since 0.3
	*/
	public function woovdrb_plugin_path() { 
		// gets the absolute path to this plugin directory 
		return untrailingslashit( plugin_dir_path( __FILE__ ) ); 
	}

	/**
	* Use our template.
	* @since 0.3
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
	* @since 0.3
	*/

	public function register_woo_radio_button_scripts () {
		wp_deregister_script('wc-add-to-cart-variation'); 
		wp_dequeue_script('wc-add-to-cart-variation'); 
		wp_register_script( 'wc-add-to-cart-variation', plugins_url( 'woocommerce\assets\js\frontend\add-to-cart-variation.min.js', __FILE__ ), array( 'jquery'), false, true ); 
		wp_enqueue_script('wc-add-to-cart-variation'); 
		wp_enqueue_style('woo-variation-description', plugins_url('/style.css', __FILE__) );

	} 

	/**
	* Add varation description field to backend.
	* @since 0.3
	*/

	public function variable_fields( $loop, $variation_data ) {
	?>	
		<tr>
			<td>
				<div>
					<label><?php _e( 'Variation Description', 'woo-vdrb' ); ?></label>
					<input type="text" size="5" name="isa_woo_variation_desc[<?php echo $loop; ?>]" value="<?php echo $variation_data['_isa_woo_variation_desc'][0]; ?>"/>
				</div>
			</td>
		</tr>
	<?php
	}
	
	/**
	* JS for variation description field.
	* @since 0.3
	*/

	public function variable_fields_js() {
	?>
		<tr>
			<td>
				<div>
					<label><?php _e( 'Variation Description', 'woo-vdrb' ); ?></label>\
					<input type="text" size="5" name="isa_woo_variation_desc[' + loop + ']" />\
				</div>
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
			$variable_custom_field = $_POST['isa_woo_variation_desc'];
			for ( $i = 0; $i < sizeof( $variable_sku ); $i++ ) :
				$variation_id = (int) $variable_post_id[$i];
				if ( isset( $variable_custom_field[$i] ) ) {
					update_post_meta( $variation_id, '_isa_woo_variation_desc', stripslashes( $variable_custom_field[$i] ) );
				}
			endfor;
		endif;
	}
} // end class
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$woo_variation_description_radio_buttons = Woo_Variation_Description_Radio_Buttons::get_instance();
}
?>