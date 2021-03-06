<?php
/**
 * Functions used to integrate with the Business Profile plugin
 *
 * @package    luigi
 */
if ( !function_exists( 'luigi_customizer_load_bpfwp_map_handlers' ) ) {
	/**
	 * Print a hidden map using Business Profile's native functions to ensure
	 * that the map handler is loaded and initialized properly. This is used by
	 * the customer to ensure that if a map is loaded in during customization,
	 * it will update properly.
	 *
	 * @since 0.0.1
	 */
	function luigi_customizer_load_bpfwp_map_handlers() {

		if ( !function_exists( 'bpwfwp_print_map' ) ) {
			return;
		}

		?>

		<div style="display:none;">
			<?php bpwfwp_print_map(); ?>
		</div>

		<?php
	}
}

if ( !function_exists( 'luigi_bpfwp_print_contact_card' ) ) {
	/**
	 * A wrapper function for printing a contact card that can be safely called
	 * whether or not Business Profile is active
	 *
	 * @since 1.1.3
	 */
	function luigi_bpfwp_print_contact_card( $args = array() ) {
		return function_exists( 'bpwfwp_print_contact_card' ) ? bpwfwp_print_contact_card( $args ) : '';
	}
}

if ( !function_exists( 'luigi_bpfwp_get_contact_card_modal' ) ) {
	/**
	 * Print a hidden map using Business Profile's native functions to ensure
	 * that the map handler is loaded and initialized properly. This is used by
	 * the customer to ensure that if a map is loaded in during customization,
	 * it will update properly.
	 *
	 * @since 0.0.1
	 */
	function luigi_bpfwp_get_contact_card_modal() {

		if ( !function_exists( 'bpwfwp_print_contact_card' ) ) {
			wp_send_json_error(
				array(
					'error' => 'bpfwp_inactive',
					'msg'   => current_user_can( 'activate_plugins' ) ? __( 'The Business Profile plugin is not active.', 'luigi' ) : __( 'Contact card is not currently available', 'luigi' ),
				)
			);
		}

		// Register plugin assets
		global $bpfwp_controller;
		$bpfwp_controller->register_assets();

		$return = array( 'output' => do_shortcode( '[contact-card]' ) );

		// Retrieve the map script and script data
		global $wp_scripts;
		if ( !empty( $wp_scripts ) && !empty( $wp_scripts->registered ) && !empty( $wp_scripts->registered['bpfwp-map']) ) {
			$return['script'] = $wp_scripts->registered['bpfwp-map']->src;

			// Duplicate data collection from plugin
			$return['script_data'] = array(
				// Override loading and intialization of Google Maps api
				'autoload_google_maps' => apply_filters( 'bpfwp_autoload_google_maps', true ),
				'map_options' => apply_filters( 'bpfwp_google_map_options', array() ),
				'strings' => array(
					'get_directions' => __( 'Get directions', 'luigi' ),
				),
			);
		}

		$return = apply_filters( 'luigi_bpfwp_contact_card_modal', $return );

		wp_send_json_success( $return );
	}
	add_action( 'wp_ajax_nopriv_luigi-bpfwp-get-contact-card-modal', 'luigi_bpfwp_get_contact_card_modal' );
	add_action( 'wp_ajax_luigi-bpfwp-get-contact-card-modal', 'luigi_bpfwp_get_contact_card_modal' );
}

if ( !function_exists( 'luigi_bp_maybe_print_map' ) ) {
	/**
	 * Print a location map if an address exists
	 *
	 *  @since 1.1
	 */
	function luigi_bp_maybe_print_map( $location = false ) {

		if ( function_exists( 'bpwfwp_print_map' ) ) {
			bpwfwp_print_map( $location );
		}
	}
}

if ( !function_exists( 'luigi_bp_location_archive_title' ) ) {
	/**
	 * Adjust the location archive title
	 *
	 * @since 1.1
	 */
	function luigi_bp_location_archive_title( $title ) {
		if ( !is_post_type_archive( 'location' ) ) {
			return $title;
		}

		return post_type_archive_title( '', false );

	}
	add_filter( 'get_the_archive_title', 'luigi_bp_location_archive_title' );
}

if ( !function_exists( 'luigi_bp_adjust_footer_contact_card' ) ) {
	/**
	 * Hide elements of the footer contact card if multiple locations are
	 * enabled.
	 *
	 * @since 1.1
	 */
	function luigi_bp_adjust_footer_contact_card( $args ) {

		if ( post_type_exists( 'location' ) ) {
			$args['show_get_directions'] = false;
			$args['show_booking_link'] = false;
		}

		return $args;
	}
	add_filter( 'luigi-footer-contact-card', 'luigi_bp_adjust_footer_contact_card' );
}
