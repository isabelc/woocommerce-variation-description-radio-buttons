<?php 
/*
Plugin Name: WooCommerce Variation Description Radio Buttons
Plugin URI: http://isabelcastillo.com/free-plugins/woocommerce-variation-description-radio-buttons
Description: Change WooCommerce variations into radio buttons and adds descriptions to variations.
Version: 1.2.alpha1
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
	
}
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$woo_variation_description_radio_buttons = Woo_Variation_Description_Radio_Buttons::get_instance();
}
?>