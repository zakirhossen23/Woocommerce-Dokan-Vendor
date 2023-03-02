<?php
/**
 * Template part for displaying ajax search 
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/header
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! KAPEE_WOOCOMMERCE_ACTIVE || ! kapee_get_option( 'header-search', 1 ) ) return;
		
$classes[] = kapee_get_option( 'header-ajax-search-style', 'ajax-search-style-1' );
$classes[] = kapee_get_option( 'ajax-search-shape', 'ajax-search-simple' );?>	

<div class="kapee-ajax-search <?php kapee_implode_classes($classes);?>">
	<form method="get" class="searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
		<input type="search" class="search-field"  name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( kapee_get_option('search-placeholder-text', 'Search for products, categories, brands, sku...') ); ?>"/>
		<div class="search-categories">
		<?php
			$selected_cat 	= isset( $_GET['product_cat'] ) ? $_GET['product_cat'] : '';     
			$product_cat 	= kapee_uniqid('product-cat-');
			$args = array(
			  'name'         		=> 'product_cat',
			  'value_field'  		=>'slug',
			  'class'        		=> 'categories-filter product_cat',
			  'id'        	 		=> $product_cat,
			  'show_option_none' 	=> esc_html__( 'All Categories','kapee' ),
			  'option_none_value' 	=> '',
			  'hide_empty'   		=> 1,
			  'orderby'      		=> 'name',
			  'order'        		=> 'asc',
			  'echo'         		=> 0,
			  'taxonomy'     		=> 'product_cat',
			);
			
			if($selected_cat !=''):
				$args['selected'] = $selected_cat;
			else:
				$args['selected'] = 0;
			endif;
			
			if( kapee_get_option('search-categories','all') =='parent' ):
				$args['depth'] = 1;
			endif;
			
			if( kapee_get_option( 'categories-hierarchical', 0 ) ):
				$args['hierarchical'] = true;
			endif;
			
			if( kapee_get_option( 'categories-dropdow', 1 ) ):
				echo wp_dropdown_categories( $args );
			endif;
			?>
		</div>
		<button type="submit" class="search-submit"><?php esc_html_e('Search','kapee');?></button>
		<input type="hidden" name="post_type" value="product" />			
	</form>
	<div class="search-results-wrapper woocommerce"></div>
	
	<?php if( kapee_get_option( 'search-recent', 1 ) || kapee_get_option( 'search-trending', 0 ) ) { ?>
		<div class="trending-search-results">
			<?php if( kapee_get_option( 'search-recent', 1 ) ) { 			
				if( isset( $_COOKIE['kapee_recent_search'] ) && ! empty( $_COOKIE['kapee_recent_search'] ) ) : 
					$prev_value = $_COOKIE["kapee_recent_search"];
					$prev_value = stripslashes($prev_value);
					$recent_search = json_decode($prev_value, true);
					if( ! empty( $recent_search ) ) : ?>
						<div class="recent-search-box">
							<ul>
								<li class="recent-search-title">
									<?php esc_html_e('Recent Search', 'kapee');?> <span class="clear-recent-search"><?php esc_html_e('Clear X', 'kapee');?></span>
								</li>
								<?php 
								$max_view_recent_search = 5;
								$i = 0;
								foreach($recent_search  as $key => $search_string) : ?>
									<li class="item recent-search-item">
										<a href="<?php echo esc_url($search_string['url']);?>"><span class="keyword"><?php echo esc_html($search_string['keyword']);?></span> 
										</a><span class="recent-remove" data-key="<?php echo esc_attr($key);?>"><?php esc_html_e('Remove', 'kapee');?></span>
									</li>
									<?php						
									$i++;
									if( $max_view_recent_search == $i){ break;}
								endforeach; ?>
							</ul>
						</div>
					<?php endif;
				endif;
			}
			
			if( kapee_get_option( 'search-trending', 0 ) ) {
				$trending_categories_ids = kapee_get_option( 'search-trending-categories', array() );
				if( ! empty( $trending_categories_ids ) ):
					$trending_categories = get_terms( 'product_cat', array(
						'include' => $trending_categories_ids,
						'orderby' => 'include',
					) );
					if( ! is_wp_error( $trending_categories ) ) : ?>
					<div class="trending-search">				
						<ul>
							<li class="trending-title"><?php esc_html_e('Trending Search','kapee');?> </li>
							<?php foreach( $trending_categories  as $trending_cat ) : ?>
								<li class="item">
									<a href="<?php echo esc_url(get_term_link($trending_cat->term_id))?>"><span class="keyword"><?php echo esc_html($trending_cat->name);?></span></a>
								</li>
							<?php endforeach;?>
						</ul>
					</div>
				<?php endif;
				endif;
			}?>
		</div>
	<?php } ?>
</div>
