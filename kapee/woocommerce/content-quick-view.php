<?php 
/**
 * Quickview template
 *
 * @author Presslayouts
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $post, $product;
// Ensure visibility
if ( ! $product || ! $product->is_visible() ) return;

$classes 			= array();
$classes[] 			= 'product product-quick-view';
$attachment_ids 	= $product->get_gallery_image_ids();
$attachment_count 	= count( $attachment_ids );
wp_enqueue_script( 'wc-add-to-cart-variation' );
?>
<div class="woocommerce">
	<div id="product-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
		<div class="single-product-wrapper row">
			<div class="product-images col-12 col-md-6 col-lg-6 woocommerce-product-gallery">
				<div class="images">
					<figure class="woocommerce-product-gallery__wrapper">
						<div class="product-gallery-image kapee-slick-slider" data-slick='{"slidesToShow": 1, "slidesToScroll": 1, "fade":true <?php if ( is_rtl() ) { echo ',"rtl": true'; } ?>}'>
						
						<?php
						$attributes = array( 'title' => esc_attr( get_the_title( get_post_thumbnail_id() ) ) );
						if ( has_post_thumbnail() ) {
							echo '<div class="woocommerce-product-gallery__image">' . get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), $attributes ) . '</div>';

							if ( $attachment_count > 0 ) {
								foreach ( $attachment_ids as $attachment_id ) {
									echo '<div class="woocommerce-product-gallery__image">' . wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ) . '</div>';
								}
							}
						} else {
							echo '<div class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', esc_url(wc_placeholder_img_src()), esc_attr__( 'Placeholder', 'kapee' ) ), $post->ID ) . '</div>';
						}?>
					</figure>
				</div>
			</div>
			<div class="summary entry-summary col-12 col-md-6 col-lg-6">
				<div class="summary-inner kapee-scroll"> 
					<div class="kapee-scroll-content">
						<?php
							/**
							 * woocommerce_single_product_summary hook
							 *
							 * @hooked woocommerce_template_single_title - 5
							 * @hooked woocommerce_template_single_rating - 10
							 * @hooked woocommerce_template_single_price - 10
							 * @hooked woocommerce_template_single_excerpt - 20
							 * @hooked woocommerce_template_loop_add_to_cart - 30
							 * @hooked woocommerce_template_single_meta - 40
							 * @hooked woocommerce_template_single_sharing - 50
							 */
							do_action( 'woocommerce_single_product_summary' );
						?>
					</div>
				</div>
			</div><!-- .summary -->
		</div>
	</div>
</div>