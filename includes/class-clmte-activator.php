<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/FluffyKod
 * @since      1.0.0
 *
 * @package    Clmte
 * @subpackage Clmte/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Clmte
 * @subpackage Clmte/includes
 * @author     CLMTE <info@clmte.com>
 */
class Clmte_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Check if compensation is already added
		if ( get_option('clmte_compensation_added') == false ) {
			// Add compensation product on plugin activation
			$post_id = wp_insert_post( array(
				'post_title' => 'Klimatkompensation',
				'post_content' => 'Climate compensation by clmte.com',
				'post_status' => 'publish',
				'post_type' => "product",
				) );
			
			// Publish product and set default price to 0
			wp_set_object_terms( $post_id, 'simple', 'product_type' );
			update_post_meta( $post_id, '_price', '0' );

			// Upload img to media library if the img is not there already
			$desc = 'Carbon Offset powered by CLMTE';
			$file = 'https://i.postimg.cc/L8vDWrWN/website-logo.png';

			$file_array  = [ 'name' => wp_basename( $file ), 'tmp_name' => download_url( $file ) ];

			// If error storing temporarily, return the error.
			if ( is_wp_error( $file_array['tmp_name'] ) ) {
				return $file_array['tmp_name'];
			}

			// Do the validation and storage stuff.
			$img_id = media_handle_sideload( $file_array, 0, $desc );

			// If error storing permanently, unlink.
			if ( is_wp_error( $img_id ) ) {
				@unlink( $file_array['tmp_name'] );
				return $img_id;
			}		

			// Set product image
			update_post_meta( $post_id, '_thumbnail_id', $img_id );

			// Do not show as product in shop or search
			$terms = array( 'exclude-from-catalog', 'exclude-from-search' );
			wp_set_object_terms( $post_id, $terms, 'product_visibility' );

			// Set product id and option added
			update_option('clmte_compensation_product_id', $post_id);
			update_option('clmte_compensation_added', true);
			update_option('clmte_compensation_price', '');
			update_option('clmte_img_id', $img_id);

		}

	}

}
