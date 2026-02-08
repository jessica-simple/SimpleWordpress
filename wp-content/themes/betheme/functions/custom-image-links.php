<?php
/**
 * Custom Image Links for BeTheme Gallery
 * 
 * This module adds the ability to set custom links on gallery images
 * instead of using the default WordPress attachment page or lightbox
 */

if ( ! function_exists( 'mfn_custom_image_links_init' ) ) {
	function mfn_custom_image_links_init() {
		add_filter( 'attachment_fields_to_edit', 'mfn_add_custom_image_link_field', 10, 2 );
		add_filter( 'attachment_fields_to_save', 'mfn_save_custom_image_link_field', 10, 2 );
		
		// Add custom link column in media library
		add_filter( 'manage_media_columns', 'mfn_add_custom_link_column' );
		add_action( 'manage_media_custom_column', 'mfn_display_custom_link_column', 10, 2 );
	}
}

/**
 * Add custom image link field to attachment edit form
 */
if ( ! function_exists( 'mfn_add_custom_image_link_field' ) ) {
	function mfn_add_custom_image_link_field( $form_fields, $post ) {
		$custom_link = get_post_meta( $post->ID, '_mfn_custom_image_link', true );
		
		$form_fields['mfn_custom_image_link'] = array(
			'label' => __( 'Custom Image Link', 'betheme' ),
			'input' => 'text',
			'value' => esc_attr( $custom_link ),
			'helps' => __( 'Enter a URL to link this image to. Leave empty to use default gallery behavior. Example: https://example.com', 'betheme' ),
		);
		
		return $form_fields;
	}
}

/**
 * Save custom image link field
 */
if ( ! function_exists( 'mfn_save_custom_image_link_field' ) ) {
	function mfn_save_custom_image_link_field( $post, $attachment ) {
		if ( isset( $attachment['mfn_custom_image_link'] ) ) {
			$url = sanitize_url( $attachment['mfn_custom_image_link'] );
			
			if ( ! empty( $url ) ) {
				update_post_meta( $post['ID'], '_mfn_custom_image_link', $url );
			} else {
				delete_post_meta( $post['ID'], '_mfn_custom_image_link' );
			}
		}
		
		return $post;
	}
}

/**
 * Add custom link column to media library
 */
if ( ! function_exists( 'mfn_add_custom_link_column' ) ) {
	function mfn_add_custom_link_column( $columns ) {
		$columns['mfn_custom_link'] = __( 'Custom Link', 'betheme' );
		return $columns;
	}
}

/**
 * Display custom link in media library column
 */
if ( ! function_exists( 'mfn_display_custom_link_column' ) ) {
	function mfn_display_custom_link_column( $column_name, $post_id ) {
		if ( 'mfn_custom_link' === $column_name ) {
			$custom_link = get_post_meta( $post_id, '_mfn_custom_image_link', true );
			
			if ( ! empty( $custom_link ) ) {
				echo '<a href="' . esc_url( $custom_link ) . '" target="_blank" rel="noopener noreferrer">';
				echo wp_kses_post( wp_trim_words( $custom_link, 5 ) );
				echo '</a>';
			} else {
				echo '<span style="color: #999;">—</span>';
			}
		}
	}
}

/**
 * Add custom image link support to media modal
 */
if ( ! function_exists( 'mfn_enqueue_custom_image_link_scripts' ) ) {
	function mfn_enqueue_custom_image_link_scripts() {
		if ( is_admin() ) {
			wp_enqueue_script(
				'mfn-custom-image-links',
				get_theme_file_uri( '/functions/js/custom-image-links.js' ),
				array( 'jquery' ),
				MFN_THEME_VERSION,
				true
			);
			
			wp_localize_script(
				'mfn-custom-image-links',
				'mfn_custom_image_links',
				array(
					'label' => __( 'Custom Link for Gallery', 'betheme' ),
					'help'  => __( 'Enter a URL to redirect to when this image is clicked in the gallery', 'betheme' ),
				)
			);
		}
	}
}

// Initialize hooks
if ( is_admin() ) {
	add_action( 'admin_init', 'mfn_custom_image_links_init' );
	add_action( 'admin_enqueue_scripts', 'mfn_enqueue_custom_image_link_scripts' );
}

/**
 * Helper function to get custom image link
 */
if ( ! function_exists( 'mfn_get_custom_image_link' ) ) {
	function mfn_get_custom_image_link( $attachment_id ) {
		return get_post_meta( $attachment_id, '_mfn_custom_image_link', true );
	}
}

/**
 * Helper function to set custom image link
 */
if ( ! function_exists( 'mfn_set_custom_image_link' ) ) {
	function mfn_set_custom_image_link( $attachment_id, $url ) {
		if ( ! empty( $url ) ) {
			return update_post_meta( $attachment_id, '_mfn_custom_image_link', sanitize_url( $url ) );
		} else {
			return delete_post_meta( $attachment_id, '_mfn_custom_image_link' );
		}
	}
}
