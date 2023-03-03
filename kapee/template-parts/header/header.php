<?php
/**
 * Template part for displaying header
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/header
 * @since 1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$args['header_top'] 		= $header_top;
?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<header id="header" class="site-header <?php echo esc_attr($class);?>">
	
	<?php do_action( 'kapee_header_top' );?>
	
	<?php kapee_get_template( 'template-parts/header/'.$header_style ,$args);?>
	
	<?php do_action( 'kapee_header_bottom' );?>
	
</header><!-- .site-header -->