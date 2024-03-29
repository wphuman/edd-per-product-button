<?php
/**
*
* @package         EDD\PerProductButton\includes
* @author          WP Human
* @copyright       Copyright (c) WP Human
*
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
* The public-facing functionality of the plugin.
*
* @since       1.0.0
*/

class EDD_Per_Product_Button_Admin {

	/**
	* Per Product Button Fields
	*
	* Adds fields do the EDD Downloads meta box for specifying the "Per Product Button"
	*
	* @since 1.0.0
	* @param integer $post_id Download (Post) ID
	*/
	public function meta_box_fields( $post_id ) {

		$saved_text = get_post_meta( $post_id, '_edd_per_product_button_text', true );
		$saved_force_override = get_post_meta( $post_id, '_edd_per_product_button_force_override', true );
		$saved_style = get_post_meta( $post_id, '_edd_per_product_button_style', true );
		$saved_color = get_post_meta( $post_id, '_edd_per_product_button_color', true );

		$colors = edd_get_button_colors();
		?>

		<p><strong><?php _e( 'Per Product Button:', 'edd-external-product' ); ?></strong></p>
		<div id="edd_per_product_button_fields">
			<table class="widefat" width="100%" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<th style="max-width: 25%;">Add to Cart Text</th>
						<th>Force Override ?</th>
						<th>Style</th>
						<th>Color</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="max-width: 25%;">
							<input type="text" name="_edd_per_product_button_text" id="edd_per_product_button_text" class="large-text" value="<?php echo esc_attr( $saved_text ); ?>" placeholder="Add to Cart"/>
						</td>
						<td>
							<input type="checkbox" name="_edd_per_product_button_force_override" id="edd_per_product_button_force_override" value="1" <?php checked( $saved_force_override, '1' ); ?>/>
						</td>
						<td>
							<select id="edd_per_product_button_style" name="_edd_per_product_button_style" class="edd-select">
								<option value=""><?php _e( 'Default', 'edd' ); ?></option>
								<option value="button" <?php selected( $saved_style, "button" ) ?> >button</option>
								<option value="text link"  <?php selected( $saved_style, "text link" ) ?> >text link</option>
							</select>
						</td>
						<td>
							<?php
							if( $colors ) {
								?>
								<select id="edd_per_product_button_color" name="_edd_per_product_button_color" class="edd-select">
									<option value="">
										<?php _e('Default', 'edd'); ?>
									</option>
									<?php
									foreach ( $colors as $key => $color ) {
										$color_val = str_replace( ' ', '_', $key );
										echo '<option value="' . $color_val . '"' . selected( $saved_color, $color_val ) . '>' . $color['label'] . '</option>';
									}
									?>
								</select>
								<?php } ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<?php _e( 'The add to cart text for this pictacular product', 'edd-external-product' ); ?>

			<script type="text/javascript">
			function handleSelectStyleChange() {
				if ( jQuery('#edd_per_product_button_style').val() === 'button') {
					jQuery('#edd_per_product_button_color').removeAttr('disabled');
				} else {
					jQuery('#edd_per_product_button_color').attr('disabled','disabled');
				};
			};
			jQuery(document).ready(function () {
				handleSelectStyleChange();
				jQuery('#edd_per_product_button_style').change( handleSelectStyleChange );
			});
			</script>
			<?php
		}

		/**
		* Add the EDD Per Product Button fields to the list of saved product fields
		*
		* @since  1.0.0
		*
		* @param  array $fields The default product fields list
		* @return array         The updated product fields list
		*/
		public function metabox_fields_save( $fields ) {

			// Add our fields
			$fields[] = '_edd_per_product_button_text';
			$fields[] = '_edd_per_product_button_force_override';
			$fields[] = '_edd_per_product_button_style';
			$fields[] = '_edd_per_product_button_color';

			// Return the fields array
			return $fields;
		}

		/**
		* Sanitize button_text
		*
		* @param  string $input	User input
		* @return string 		Sanitized user input

		* @since 1.0.0
		*/
		public function sanitize_button_text( $input ) {

			return sanitize_text_field( $input );

		}

		/**
		* Sanitize button force override
		*
		* @param  string $input	User input
		* @return string 		Sanitized user input

		* @since 1.0.0
		*/
		public function sanitize_button_force_override( $input ) {

			if ( $input != '1' ) {
				$input = null;
			}

			return $input;

		}

		/**
		* Sanitize button style
		*
		* @param  string $input	User input
		* @return string 		Sanitized user input

		* @since 1.0.0
		*/
		public function sanitize_button_style( $input ) {

			$accepted_styles = array( 'text link', 'button' );

			if ( ! in_array( $input, $accepted_styles ) ) {
				$input = null;
			}

			return $input;

		}

		/**
		* Sanitize button color
		*
		* @param  string $input	User input
		* @return string 		Sanitized user input

		* @since 1.0.0
		*/
		public function sanitize_button_color( $input ) {

			$colors = edd_get_button_colors();

			foreach ( $colors as $key => $color ) {
				$accepted_colors[] = str_replace( ' ', '_', $key );
			}

			if ( ! in_array( $input, $accepted_colors ) ) {
				$input = null;
			}

			return $input;

		}
	}
