<?php
/**
 * Plugin Name: WP OG Share Image
 * Plugin URI: https://github.com/dataforge/wp-og-share-image
 * Description: Outputs og:image and other Open Graph tags for posts and WooCommerce products, using the featured/main product image and excerpt, so Facebook and other platforms use them as the share preview.
 * Version: 1.0.0
 * Author: Dataforge
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Update URI: https://github.com/dataforge/wp-og-share-image
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'OGSI_PLUGIN_FILE' ) ) {
	define( 'OGSI_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'OGSI_PLUGIN_DIR' ) ) {
	define( 'OGSI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'OGSI_PLUGIN_BASENAME' ) ) {
	define( 'OGSI_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'OGSI_VERSION' ) ) {
	if ( ! function_exists( 'get_file_data' ) ) {
		require_once ABSPATH . 'wp-includes/functions.php';
	}
	$ogsi_header = get_file_data( __FILE__, [ 'Version' => 'Version' ] );
	define( 'OGSI_VERSION', $ogsi_header['Version'] ?: '0.0.0' );
}

require_once OGSI_PLUGIN_DIR . 'includes/class-ogsi-updater.php';
OGSI_Updater::init();

add_action( 'wp_head', function () {
	if ( ! is_singular() ) {
		return;
	}

	$post_id = get_queried_object_id();
	if ( ! $post_id ) {
		return;
	}

	$image_id = null;

	if ( is_singular( 'product' ) && function_exists( 'wc_get_product' ) ) {
		$product = wc_get_product( $post_id );
		if ( $product ) {
			$image_id = $product->get_image_id();
		}
	} elseif ( has_post_thumbnail( $post_id ) ) {
		$image_id = get_post_thumbnail_id( $post_id );
	}

	if ( ! $image_id ) {
		return;
	}

	$image = wp_get_attachment_image_src( $image_id, 'full' );
	if ( ! $image ) {
		return;
	}

	list( $url, $width, $height ) = $image;

	$description = get_the_excerpt( $post_id );
	if ( ! $description ) {
		$description = wp_trim_words( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ), 55 );
	}

	echo "\n<!-- WP OG Share Image -->\n";
	printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta property="og:image:width" content="%d" />' . "\n", (int) $width );
	printf( '<meta property="og:image:height" content="%d" />' . "\n", (int) $height );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( get_the_title( $post_id ) ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( get_permalink( $post_id ) ) );
	printf( '<meta property="og:type" content="article" />' . "\n" );
}, 5 );
