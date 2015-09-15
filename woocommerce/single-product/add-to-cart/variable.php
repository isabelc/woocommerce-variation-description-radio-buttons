<?php
/**
 * Variable product add to cart
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$attribute_keys = array_keys( $attributes );
do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->id ); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title( $attribute_name ); ?>"><?php echo wc_attribute_label( $attribute_name ); ?></label></td>
						<td class="value"><fieldset>
						<?php
						$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
						?>
						<strong><?php echo wc_attribute_label( $attribute_name ); ?></strong><br />
						<?php

						$attribute = $attribute_name;
						$name      = 'attribute_' . sanitize_title( $attribute );
						$id        = sanitize_title( $attribute );

						if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
							$attributes = $product->get_variation_attributes();
							$options    = $attributes[ $attribute ];
						}


					if ( ! empty( $options ) ) {
						if ( $product && taxonomy_exists( $attribute ) ) {
							// Get terms if this is a taxonomy - ordered. We need the names too.
							$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );
							foreach ( $terms as $term ) {
								if ( in_array( $term->slug, $options ) ) {
									echo '<div class="wvdrb-one-third"><input type="radio" value="' . esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $selected ), $term->slug, false ) . ' id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">  &nbsp; &nbsp; ' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</div><div class="wvdrb-two-thirds"><pre>' . $term->description . '</pre></div><br />';
								}
							}
						} else {
							foreach ( $options as $key => $option ) {
								// This handles < 2.4.0 bw compatibility where text attributes were not sanitized.

								// Use attribute key to get the variation id from the $available_variations array
								$var_id = $available_variations[$key]['variation_id'];
											
								// Then use the variation_id to get the value from _isa_woo_variation_desc
								$var_description = get_post_meta( $var_id, '_isa_woo_variation_desc', true);

								$selected = sanitize_title( $selected ) === $selected ? checked( $selected, sanitize_title( $option ), false ) : checked( $selected, $option, false );

								echo '<div class="wvdrb-one-third"><input type="radio" value="' . esc_attr( $option ) . '" ' . $selected . ' id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">  &nbsp; &nbsp; ' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</div><div class="wvdrb-two-thirds">' . $var_description . '</div><br />';
							}
						}
					} ?>
					</fieldset></td>
					</tr>
				<?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" style="display:none;">
			<?php do_action( 'woocommerce_before_single_variation' ); ?>

			<?php 
			/**
			 * woocommerce_single_variation hook. Used to output the cart button and placeholder or variation data.
			 * @since 2.4.0
			 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
			 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
			 */
			do_action( 'woocommerce_single_variation' ); ?>

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>
	<?php do_action( 'woocommerce_after_variations_form' ); ?>

</form>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
