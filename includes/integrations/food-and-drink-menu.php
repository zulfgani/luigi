<?php
/**
 * Functions used to integrate with the Food and Drink Menu plugin
 *
 * @package luigi
 */
if ( !function_exists( 'luigi_fdm_move_item_image' ) ) {
	/**
	 * Move the image and specials locations in the item template
	 *
	 * @since 0.1
	 */
	function luigi_fdm_move_item_image( $elements, $view ) {

		if ( isset( $elements['image'] ) ) {
			$elements['image'] = 'header';
		}

		if ( isset( $elements['special'] ) ) {
			$elements['special'] = 'body';
		}

		return $elements;
	}
	add_filter( 'fdm_menu_item_elements', 'luigi_fdm_move_item_image', 10, 2 );
}

if ( !function_exists( 'luigi_fdm_remove_style_settings' ) ) {
	/**
	 * Remove style-related settings for Food and Drink Menu
	 *
	 * @since 0.1
	 */
	function luigi_fdm_remove_style_settings( $sap ) {
		unset( $sap->pages['food-and-drink-menu-settings']->sections['fdm-style-settings'] );
		unset( $sap->pages['food-and-drink-menu-settings']->sections['fdm-advanced-settings'] );

		return $sap;
	}
	add_filter( 'fdm_settings_page', 'luigi_fdm_remove_style_settings', 200 );
}