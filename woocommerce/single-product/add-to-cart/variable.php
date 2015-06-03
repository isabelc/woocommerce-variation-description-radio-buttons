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
global $product, $post;
?>
<script type="text/javascript">
    var product_variations_<?php echo $post->ID; ?> = <?php echo json_encode( $available_variations ) ?>;
</script>

<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( ! empty( $available_variations ) ) : ?>
		<table class="variations" cellspacing="0">
			<tbody>
				<?php $loop = 0; foreach ( $attributes as $name => $options ) : $loop++; ?>
					<tr>
						<td class="label"><label for="<?php echo sanitize_title($name); ?>"><?php echo wc_attribute_label( $name ); ?></label></td>
						<td class="value"><fieldset>
                        <strong><?php echo wc_attribute_label( $name ); ?></strong><br />
                        <?php
						if ( is_array( $options ) ) {


							if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
								$selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
							} elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
								$selected_value = $selected_attributes[ sanitize_title( $name ) ];
							} else {
								$selected_value = '';
							}


 
						// if ( empty( $_POST ) )
						// 	$selected_value = ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) ? $selected_attributes[ sanitize_title( $name ) ] : '';
						// else
						// 	$selected_value = isset( $_POST[ 'attribute_' . sanitize_title( $name ) ] ) ? $_POST[ 'attribute_' . sanitize_title( $name ) ] : '';


						// Get terms if this is a taxonomy - ordered
						if ( taxonomy_exists( $name ) ) {

							$terms = wc_get_product_terms( $post->ID, $name, array( 'fields' => 'all' ) );
							foreach ( $terms as $term ) {
								if ( ! in_array( $term->slug, $options ) ) {
									continue;
								}

								echo '<div class="wvdrb-one-third"><input type="radio" value="' .esc_attr( $term->slug ) . '" ' . checked( sanitize_title( $selected_value ), sanitize_title( $term->slug ), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name) .'"> &nbsp; &nbsp; ' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</div><div class="wvdrb-two-thirds">' . $term->description . '</div><br />';

							}

						} else {
							// not taxonomy
							foreach ( $options as $key => $option ) {

								// use attribute $key to get the variation id from the $available_variations array
								$var_id = $available_variations[$key]['variation_id'];
								
								// We then use the variation_id to get the value from _isa_woo_variation_desc
								$var_description = get_post_meta( $var_id, '_isa_woo_variation_desc', true);
								
								echo '<div class="wvdrb-one-third"><input type="radio" value="' .esc_attr( sanitize_title( $option ) ) . '" ' . checked( sanitize_title( $selected_value ), sanitize_title( $option ), false ) . ' id="'. esc_attr( sanitize_title($name) ) .'" name="attribute_'. sanitize_title($name) .'"> &nbsp; &nbsp; ' . apply_filters( 'woocommerce_variation_option_name', $option ) . '</div><div class="wvdrb-two-thirds">' . $var_description . '</div><br />';

							}
						}
					}
                        ?>
                    </fieldset>
                    </td>
					</tr>
		        <?php endforeach;?>
			</tbody>
		</table>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" style="display:none;">
			<?php do_action( 'woocommerce_before_single_variation' ); ?>

			<div class="single_variation"></div>

			<div class="variations_button">
				<?php woocommerce_quantity_input(); ?>
				<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
			</div>

			<input type="hidden" name="add-to-cart" value="<?php echo $product->id; ?>" />
			<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
			<input type="hidden" name="variation_id" value="" />

			<?php do_action( 'woocommerce_after_single_variation' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php else : ?>

		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>

	<?php endif; ?>

</form>
<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>