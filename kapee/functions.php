<?php

/**
 * Kapee functions and definitions
 *
 * @author 	PressLayouts
 * @package kapee
 * @since 1.0.0
 */

/*-----------------------------------------------------------------------*/
/* Define Constants.
/*-----------------------------------------------------------------------*/
define('KAPEE_DIR',                  get_template_directory());              // template directory
define('KAPEE_URI',                  get_template_directory_uri());          // template directory uri

class Kapee_Theme_Class
{


	public function __construct()
	{
		$this->constants();
		$this->include_functions();
		add_action('after_setup_theme', array($this, 'theme_setup'), 10);
		add_action('widgets_init', array($this, 'register_sidebars'));

		if (is_admin()) {
			add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
			add_action('admin_enqueue_scripts', array($this, 'admin_style'));
		} else {
			add_action('wp_enqueue_scripts', array($this, 'theme_css'), 10000);
			add_action('wp_enqueue_scripts', array($this, 'theme_js'));
			add_action('wp_head', array($this, 'favicon'), 1);
			add_action('wp_head', array($this, 'pingback_header'), 1);
			add_action('wp_head', array($this, 'kapee_google_theme_color'), 2);
			add_action('wp_head', array($this, 'javascript_detection'), 0);
			add_action('wp_head', array($this, 'kapee_custom_head_js'));
			add_action('wp_footer', array($this, 'kapee_enqueue_inline_style'), 10);
			add_action('wp_footer', array($this, 'kapee_print_css'), 15);
			add_action('wp_footer', array($this, 'kapee_custom_footer_js'));
			add_action('pre_get_posts', array($this, 'search_posts_per_page'));
			add_action('wp', array($this, 'kapee_post_view_count'), 999);
			add_filter('excerpt_more', array($this, 'kapee_excerpt_more'));
			add_filter('the_content_more_link',  array($this, 'kapee_read_more_tag'));
			add_filter('excerpt_length', array($this, 'kapee_excerpt_length'), 999);
			add_action('wp_footer', array($this, 'kapee_owl_param'));
			add_action('upload_mimes', array($this, 'kapee_upload_mimes'));
			if (KAPEE_WOOCOMMERCE_ACTIVE) {
				add_filter('posts_search', array($this, 'product_search_sku'), 9);
			}
		}
	}

	/**
	 * Define Constants
	 *
	 * @since   1.0.0
	 */
	public  function constants()
	{

		$theme = wp_get_theme('Kapee');

		// Theme version
		define('KAPEE_THEME_NAME', 'Kapee');
		define('KAPEE_VERSION', $theme->get('Version'));

		// Include Paths
		define('KAPEE_INC_DIR', KAPEE_DIR . '/inc/');
		define('KAPEE_INC_DIR_URI', KAPEE_URI . '/inc/');
		define('KAPEE_ADMIN_DIR_URI', KAPEE_INC_DIR_URI . 'admin/');

		// Javascript and CSS Paths
		define('KAPEE_JS_DIR_URI', KAPEE_URI . '/assets/js/');
		define('KAPEE_CSS_DIR_URI', KAPEE_URI . '/assets/css/');

		//Images path
		define('KAPEE_IMAGES', KAPEE_URI . '/assets/images/'); // image uri
		define('KAPEE_ADMIN_IMAGES', KAPEE_ADMIN_DIR_URI . 'assets/images/'); // 	admin image uri		

		// Check if plugins are active		
		if (!defined('KAPEE_WOOCOMMERCE_ACTIVE')) {
			define('KAPEE_WOOCOMMERCE_ACTIVE', class_exists('WooCommerce'));
		}
		if (!defined('KAPEE_DOKAN_ACTIVE')) {
			define('KAPEE_DOKAN_ACTIVE', class_exists('WeDevs_Dokan'));
		}
		if (!defined('KAPEE_WC_VENDORS_ACTIVE')) {
			define('KAPEE_WC_VENDORS_ACTIVE', class_exists('WC_Vendors'));
		}
		// Othere	

		if (!defined('KAPEE_API')) {
			define('KAPEE_API', 'https://presslayouts.com/demo/api/');
		}
		if (!defined('KAPEE_PREFIX')) {
			define('KAPEE_PREFIX', '_kp_');
		}
	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public function include_functions()
	{
		require_once KAPEE_INC_DIR . 'kp-layout.php';
		require_once KAPEE_INC_DIR . 'font-config.php';
		require_once KAPEE_INC_DIR . 'kp-core-functions.php';
		require_once KAPEE_INC_DIR . 'kp-template-tags.php';
		require_once KAPEE_INC_DIR . 'kp-template-functions.php';
		require_once KAPEE_INC_DIR . 'kp-template-hooks.php';
		require_once KAPEE_INC_DIR . 'dynamic-css.php';
		if (KAPEE_WOOCOMMERCE_ACTIVE) {
			require_once KAPEE_INC_DIR . 'integrations/woocommerce/wc-core-functions.php';
			require_once KAPEE_INC_DIR . 'integrations/woocommerce/wc-template-hooks.php';
			require_once KAPEE_INC_DIR . 'integrations/woocommerce/wc-template-functions.php';
			require_once KAPEE_INC_DIR . 'classes/class-swatches.php';
			require_once KAPEE_INC_DIR . 'classes/class-bought-together.php';

			if (class_exists('WeDevs_Dokan')) {
				require_once KAPEE_INC_DIR . 'integrations/dokan/dokan-core-functions.php';
			}

			if (class_exists('WCMp')) {
				require_once KAPEE_INC_DIR . 'integrations/wcmp/wcmp-core-functions.php';
			}

			if (class_exists('WC_Vendors')) {
				require_once KAPEE_INC_DIR . 'integrations/wc-vendor/wc-vendors-core-functions.php';
			}

			if (class_exists('WCFMmp')) {
				require_once KAPEE_INC_DIR . 'integrations/wcfm/wcfm-core-functions.php';
			}

			if (function_exists('YITH_YWRAQ_Frontend')) {
				require_once KAPEE_INC_DIR . 'integrations/yith-add-to-quote/yith-add-to-quote-core-functions.php';
			}
		}

		require_once KAPEE_INC_DIR . 'classes/class-metabox.php';
		require_once KAPEE_INC_DIR . 'classes/class-walker-nav-menu.php';
		require_once KAPEE_INC_DIR . 'classes/class-breadcrumb.php';
		require_once KAPEE_INC_DIR . 'classes/class-sidebar-generator.php';
		require_once KAPEE_INC_DIR . 'classes/class-cookie-notice.php';
		require_once KAPEE_INC_DIR . 'thirdparty/tgm-plugin-activation/tgm-plugin-activation.php';
		require_once KAPEE_INC_DIR . 'admin/theme_options.php';
		require_once KAPEE_INC_DIR . 'admin/class-admin.php';
		require_once KAPEE_INC_DIR . 'admin/class-dashboard.php';
		require_once KAPEE_INC_DIR . 'admin/class-update-theme.php';
	}

	/**
	 * Theme Setup
	 *
	 * @since   1.0.0
	 */
	public function theme_setup()
	{

		load_theme_textdomain('kapee', get_template_directory() . '/languages');
		load_theme_textdomain('kapee', get_stylesheet_directory() . '/languages');

		/* Theme support */
		add_theme_support('automatic-feed-links');
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_theme_support('post-formats', array('image', 'gallery', 'video', 'audio', 'quote', 'link'));
		add_theme_support('html5', array('gallery', 'caption'));
		add_theme_support('wp-block-styles');

		// Disable Widget block editor.
		if (apply_filters('kapee_disable_widgets_block_editor', true)) {
			remove_theme_support('block-templates');
			remove_theme_support('widgets-block-editor');
		}

		// Add support for responsive embedded content.
		add_theme_support('responsive-embeds');

		// Set the default content width.
		$GLOBALS['content_width'] = 1200;

		register_nav_menus(array(
			'primary'					=> esc_html__('Primary(Main) Menu', 'kapee'),
			'secondary'					=> esc_html__('Secondary Menu', 'kapee'),
			'categories-menu' 			=> esc_html__('Categories(Vertical) Menu', 'kapee'),
			'topbar-menu' 				=> esc_html__('Topbar Menu', 'kapee'),
			'mobile-menu' 				=> esc_html__('Mobile Primary Menu', 'kapee'),
			'mobile-categories-menu' 	=> esc_html__('Mobile Categories Menu', 'kapee'),
			'myaccount-menu' 			=> esc_html__('MyAccount/Profile Menu', 'kapee'),
		));

		add_editor_style(array('assets/css/editor-style.css', $this->kapee_fonts_url()));
	}

	/*-----------------------------------------------------------------------*/
	/* Register custom fonts.
	/*-----------------------------------------------------------------------*/
	public function kapee_fonts_url()
	{
		$fonts_url = '';
		$fonts = array();
		$fonts[] = 'Lato:100,100i,300,300i,400,400i,700,700i,900,900i';

		if ($fonts) {
			$fonts_url = add_query_arg(
				array(
					'family' => urlencode(implode('|', $fonts)),
					'subset' => urlencode('latin,latin-ext'),
				),
				'https://fonts.googleapis.com/css'
			);
		}

		return esc_url_raw($fonts_url);
	}

	/**
	 * Registers sidebars
	 *
	 * @since   1.0.0
	 */
	public function register_sidebars()
	{

		register_sidebar(array(
			'name'          => esc_html__('Blog Sidebar', 'kapee'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
		register_sidebar(array(
			'name'          => esc_html__('Shop Page Sidebar', 'kapee'),
			'id'            => 'shop-page-sidebar',
			'description'   => esc_html__('Add widgets here to appear in your shop page.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
		register_sidebar(array(
			'name'          => esc_html__('Shop Filter Sidebar', 'kapee'),
			'id'            => 'shop-filters-sidebar',
			'description'   => esc_html__('Add widgets here to appear in your shop page.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
		register_sidebar(array(
			'name'          => esc_html__('Product Page Sidebar', 'kapee'),
			'id'            => 'product-page-sidebar',
			'description'   => esc_html__('Add widgets here to appear in your single product page.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
		register_sidebar(array(
			'name'          => esc_html__('Footer Area 1', 'kapee'),
			'id'            => 'footer-area-1',
			'description'   => esc_html__('Add widgets here to appear in your footer first column.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));

		register_sidebar(array(
			'name'          => esc_html__('Footer Area 2', 'kapee'),
			'id'            => 'footer-area-2',
			'description'   => esc_html__('Add widgets here to appear in your footer second column.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));

		register_sidebar(array(
			'name'          => esc_html__('Footer Area 3', 'kapee'),
			'id'            => 'footer-area-3',
			'description'   => esc_html__('Add widgets here to appear in your footer third column.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));

		register_sidebar(array(
			'name'          => esc_html__('Footer Area 4', 'kapee'),
			'id'            => 'footer-area-4',
			'description'   => esc_html__('Add widgets here to appear in your footer fourth column.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));

		register_sidebar(array(
			'name'          => esc_html__('Footer Area 5', 'kapee'),
			'id'            => 'footer-area-5',
			'description'   => esc_html__('Add widgets here to appear in your footer fifth column.', 'kapee'),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		));
	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public function admin_style($hook)
	{
		global $pagenow;
		$theme_version = KAPEE_VERSION;
		wp_enqueue_style('wp-color-picker');
		if ('nav-menus.php' == $pagenow) {
			wp_enqueue_style('font-awesome', KAPEE_CSS_DIR_URI . 'third/font-awesome.min.css', array(), '4.7.0');
			wp_enqueue_style('simple-line', KAPEE_CSS_DIR_URI . 'third/simple-line-icons.css', array(), '2.4.0');
		}
		wp_enqueue_style('kapee-fonts', KAPEE_CSS_DIR_URI . 'third/kapee-font.css', array(), $theme_version);

		$dashboard_pages = array('toplevel_page_kapee-theme', 'kapee_page_kapee-system-status', 'kapee_page_kapee-theme-option', 'kapee_page_kapee-demo-import');
		if (in_array($hook, $dashboard_pages)) {
			wp_enqueue_style('magnific-popup', KAPEE_CSS_DIR_URI . 'third/magnific-popup.css', array(), $theme_version);
		}
		if ('customize.php' != $pagenow) {
			wp_enqueue_style('kapee-style', KAPEE_INC_DIR_URI . 'admin/assets/css/admin.css', array(), $theme_version);
		}
	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts($hook)
	{
		global $pagenow;
		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');

		if ('toplevel_page_kapee-theme' == $hook) {
			wp_enqueue_script('kapee-activation-theme', KAPEE_INC_DIR_URI . 'admin/assets/js/kapee-activation.js');
		}
		if ('kapee_page_kapee-system-status' == $hook) {
			wp_enqueue_script('kapee-system-status', KAPEE_INC_DIR_URI . 'admin/assets/js/kapee-system-status.js');
		}
		if ('nav-menus.php' == $pagenow) {
			wp_enqueue_script('kapee-mega-menu', KAPEE_INC_DIR_URI . 'admin/assets/js/mega-menu.js');
		}
		$dashboard_pages = array('toplevel_page_kapee-theme', 'kapee_page_kapee-system-status', 'kapee_page_kapee-theme-option', 'kapee_page_kapee-demo-import');

		if (in_array($hook, $dashboard_pages)) {
			wp_enqueue_script('magnific-popup', KAPEE_JS_DIR_URI . 'jquery.magnific-popup.min.js');
		}

		wp_enqueue_script('kapee-admin-js', KAPEE_INC_DIR_URI . 'admin/assets/js/admin.js');
		wp_localize_script('kapee-admin-js', 'kapee_admin_params', apply_filters('kapee_admin_js_params', array(
			'ajaxurl'          		=> admin_url('admin-ajax.php'),
			'nonce'            		=> wp_create_nonce('kapee_nonce'),
			'loading_text'      	=> esc_html__('Loading...', 'kapee'),
			'bindmessage'      		=> esc_html__('Are you sure you want to leave?', 'kapee'),
			'demo_success'      	=> esc_html__('Demo imported successfully.', 'kapee'),
			'menu_icon_change_text'	=> esc_html__('Change Custom Icon', 'kapee'),
			'menu_icon_upload_text'	=> esc_html__('Upload Custom Icon', 'kapee'),
			'menu_delete_icon_msg'	=> esc_html__('Are you sure,You want to remove this icon?', 'kapee'),
		)));
	}

	/**
	 * Load front-end css
	 *
	 * @since   1.0.0
	 */
	public function theme_css()
	{

		// Remove font awesome style from plugins
		wp_deregister_style('fontawesome');
		wp_deregister_style('font-awesome');
		wp_deregister_style('yith-wcwl-font-awesome');
		wp_deregister_style('wplc-font-awesome');

		// Load our main stylesheet.
		wp_enqueue_style('kapee-style', KAPEE_URI . '/style.css', array(), KAPEE_VERSION);

		// Load visual composer css
		wp_enqueue_style('js_composer_front');
		wp_enqueue_style('js_composer_custom_css');

		$style = (is_rtl()) ? KAPEE_CSS_DIR_URI . 'style-rtl.css' : KAPEE_CSS_DIR_URI . 'style.css';
		$woocommerce_style = (is_rtl()) ? KAPEE_CSS_DIR_URI . 'third/woocommerce-rtl' : KAPEE_CSS_DIR_URI . 'third/woocommerce';

		wp_enqueue_style('kapee-default-fonts', $this->kapee_fonts_url(), array(), null);
		wp_enqueue_style('bootstrap', KAPEE_CSS_DIR_URI . 'third/bootstrap.min.css', array(), '4.0.0');
		wp_enqueue_style('kapee-woocommerce', $woocommerce_style . '.css', array(), '3.4.5');
		wp_enqueue_style('font-awesome', KAPEE_CSS_DIR_URI . 'third/font-awesome.min.css', array(), '4.7.0');
		wp_enqueue_style('kapee-fonts', KAPEE_CSS_DIR_URI . 'third/kapee-font.css', array(), '1.0');
		wp_enqueue_style('simple-line', KAPEE_CSS_DIR_URI . 'third/simple-line-icons.css', array());
		wp_enqueue_style('owl-carousel', KAPEE_CSS_DIR_URI . 'third/owl.carousel.min.css', array(), '2.3.3');
		wp_enqueue_style('slick', KAPEE_CSS_DIR_URI . 'third/slick.css', array(), '1.8.0');
		wp_enqueue_style('animate', KAPEE_CSS_DIR_URI . 'third/animate.min.css', array(), '3.7.0');
		wp_enqueue_style('magnific-popup', KAPEE_CSS_DIR_URI . 'third/magnific-popup.css', array(), '1.1.0');

		// Theme basic stylesheet.
		wp_enqueue_style('kapee-basic', $style, array('bootstrap', 'kapee-woocommerce'), KAPEE_VERSION);

		// Dynamic CSS
		wp_add_inline_style('kapee-basic', kapee_theme_style());

		// load typekit fonts
		$enable_typekit_font 	= kapee_get_option('typekit-font', 0);
		$typekit_id 			= kapee_get_option('typekit-kit-id', '');

		if ($enable_typekit_font && !empty($typekit_id)) {
			wp_enqueue_style('kapee-typekit',  kapee_get_protocol() . '//use.typekit.net/' . esc_attr($typekit_id) . '.css', array(), $theme_version);
		}

		// REMOVE WP EMOJI
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('wp_print_styles', 'print_emoji_styles');

		wp_register_style('kapee-custom-css', false);
	}

	/**
	 * Load front-end script
	 *
	 * @since   1.0.0
	 */
	public function theme_js()
	{

		// Load visual composer Js
		wp_enqueue_script('waypoints', KAPEE_JS_DIR_URI . 'waypoints.min.js', array('jquery'), '2.0.2', true);
		wp_enqueue_script('wpb_composer_front_js');
		wp_enqueue_script('popper', KAPEE_JS_DIR_URI . 'popper.min.js', array('jquery'), '4.0.0', true);
		wp_enqueue_script('bootstrap', KAPEE_JS_DIR_URI . 'bootstrap.min.js', array('jquery'), '4.0.0', true);
		wp_enqueue_script('owl-carousel', KAPEE_JS_DIR_URI . 'owl.carousel.min.js', array('jquery'), '2.3.3', true);
		wp_enqueue_script('isinviewport', KAPEE_JS_DIR_URI . 'isInViewport.min.js', array('jquery'), '1.8.0', true);
		wp_enqueue_script('slick', KAPEE_JS_DIR_URI . 'slick.min.js', array('jquery'), '1.8.0', true);
		wp_enqueue_script('isotope', KAPEE_JS_DIR_URI . 'isotope.pkgd.min.js', array('jquery'), '', true);
		wp_enqueue_script('cookie', KAPEE_JS_DIR_URI . 'cookie.min.js', array('jquery'), '', true);
		wp_register_script('parallax', KAPEE_JS_DIR_URI . 'jquery.parallax.js', array('jquery'), '', true);
		wp_enqueue_script('magnific-popup', KAPEE_JS_DIR_URI . 'jquery.magnific-popup.min.js', array('jquery'), '', true);
		if (kapee_get_option('product-ajax-search', 1) == 1) {
			wp_enqueue_script('autocomplete', KAPEE_JS_DIR_URI . 'jquery.autocomplete.min.js', array('jquery'), '', true);
		}
		wp_enqueue_script('lazyload', KAPEE_JS_DIR_URI . 'jquery.lazyload.min.js', array('jquery'), KAPEE_VERSION, true);
		wp_enqueue_script('jqplugin', KAPEE_JS_DIR_URI . 'jquery.plugin.min.js', array('jquery'), '1.2.0', true);
		wp_enqueue_script('countdown', KAPEE_JS_DIR_URI . 'jquery.countdown.min.js', array('jquery'), '1.2.0', true);
		wp_enqueue_script('counterup', KAPEE_JS_DIR_URI . 'jquery.counterup.min.js', array('jquery'), '1.2.0', true);
		wp_enqueue_script('sticky-kit', KAPEE_JS_DIR_URI . 'sticky-kit.min.js', array('jquery'), '1.0.0', true);
		wp_enqueue_script('hideMaxListItem', KAPEE_JS_DIR_URI . 'hideMaxListItem-min.js', array('jquery'), '1.3.6', true);
		wp_enqueue_script('nanoscroller', KAPEE_JS_DIR_URI . 'jquery.nanoscroller.min.js', array('jquery'), '0.8.7', true);
		if (kapee_get_option('product-quickview-button', 1)) {
			wp_enqueue_script('wc-add-to-cart-variation');
		}
		wp_enqueue_script('wc-password-strength-meter');
		$google_api_key = kapee_get_option('google-map-api', '');
		if (!empty($google_api_key)) {
			wp_enqueue_script('kapee-google-map-api', kapee_get_protocol() . '//maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.22&key=' . $google_api_key . '', array(), '', false);
		}

		wp_enqueue_script('kapee-script', KAPEE_JS_DIR_URI . 'functions.js', array('jquery'), '1.1.0', true);

		$is_rtl = is_rtl() ? true : false;
		$kapee_options 	= apply_filters('kapee_localize_script_data', array(
			'rtl' 							=> $is_rtl,
			'ajax_url' 						=> admin_url('admin-ajax.php'), false,
			'nonce'            				=> wp_create_nonce('kapee_nonce'),
			'product_ajax_search'			=> kapee_get_option('product-ajax-search', 1) ? true : false,
			'sticky_header'					=> kapee_get_option('sticky-header', 0) ? true : false,
			'sticky_header_tablet'			=> kapee_get_option('sticky-header-tablet', 0) ? true : false,
			'sticky_header_mobile'			=> kapee_get_option('sticky-header-mobile', 0) ? true : false,
			'login_register_popup'			=> kapee_get_option('login-register-popup', 1) ? true : false,
			'header_minicart_popup'			=> kapee_get_option('header-minicart-popup', 1) ? true : false,
			'lazy_load'						=> kapee_get_option('lazy-load', 0) ? true : false,
			'cookie_path'					=> COOKIEPATH,
			'cookie_expire'					=> 3600 * 24 * 30,
			'permalink'						=> (get_option('permalink_structure') == '') ? 'plain' : '',
			'newsletter_args'				=> apply_filters('kapee_js_newsletter_args', array(
				'popup_enable'			=> kapee_get_option('newsletter-popup', 0) ? true : false,
				'popup_display_on'		=> kapee_get_option('newsletter-when-appear', 'page_load'),
				'popup_delay'			=> kapee_get_option('newsletter-delay', 5),
				'popup_x_scroll'		=> kapee_get_option('newsletter-x-scroll', 30),
				'show_for_mobile'		=> kapee_get_option('newsletter-show-mobile', 1),
			)),
			'js_translate_text'				=> apply_filters('kapee_js_text', array(
				'days_text'				=> esc_html__('Days', 'kapee'),
				'hours_text'			=> esc_html__('Hrs', 'kapee'),
				'mins_text'				=> esc_html__('Mins', 'kapee'),
				'secs_text'				=> esc_html__('Secs', 'kapee'),
				'sdays_text'			=> esc_html__('d', 'kapee'),
				'shours_text'			=> esc_html__('h', 'kapee'),
				'smins_text'			=> esc_html__('m', 'kapee'),
				'ssecs_text'			=> esc_html__('s', 'kapee'),
				'show_more'				=> esc_html__('+ Show more', 'kapee'),
				'show_less'				=> esc_html__('- Show less', 'kapee'),
				'loading_txt'			=> esc_html__('Loading...', 'kapee'),
				'variation_unavailable'	=> esc_html__('Sorry, this product is unavailable. Please choose a different combination.', 'kapee'),
			)),
			'product_tooltip'				=> kapee_get_option('product-hover-tooltip', 1) ? true : false,
			'product_image_zoom'			=> kapee_get_option('product-gallery-zoom', 1) ? true : false,
			'product_add_to_cart_ajax'		=> kapee_get_option('product_add_to_cart_ajax', 1) ? true : false,
			'product_open_cart_mini'		=> kapee_get_option('product_open_cart_mini', 1) ? true : false,
			'product_quickview_button'		=> kapee_get_option('product-quickview-button', 1) ? true : false,
			'sticky_image_wrapper'			=> kapee_get_option('sticky-product-image', 1) ? true : false,
			'sticky_summary_wrapper'		=> kapee_get_option('sticky-product-summary', 1) ? true : false,
			'sticky_sidebar'				=> kapee_get_option('sticky-sidebar', 1) ? true : false,
			'widget_toggle'					=> kapee_get_option('widget-toggle', 0) ? true : false,
			'widget_menu_toggle'			=> kapee_get_option('widget-menu-toggle', 0) ? true : false,
			'widget_hide_max_limit_item' 	=> kapee_get_option('widget-items-hide-max-limit', 0) ? true : false,
			'sidebar_canvas_mobile'			=> kapee_get_option('sidebar-canvas-mobile', 0) ? true : false,
			'number_of_show_widget_items' 	=> kapee_get_option('number-of-show-widget-items', 8),
			'bought_together_success'		=> esc_html__('Added all items to cart', 'kapee'),
			'bought_together_error'			=> esc_html__('Someting wrong', 'kapee'),
			'disable_variation_price_change' => false,
			'maintenance_mode'				=> kapee_get_option('maintenance-mode', 0) ? true : false,
			'dokan_active'					=> (KAPEE_DOKAN_ACTIVE) ? true : false

		));

		if (class_exists('WooCommerce')) {
			$kapee_options['price_format']             = get_woocommerce_price_format();
			$kapee_options['price_decimals']           = wc_get_price_decimals();
			$kapee_options['price_thousand_separator'] = wc_get_price_thousand_separator();
			$kapee_options['price_decimal_separator']  = wc_get_price_decimal_separator();
			$kapee_options['currency_symbol']          = get_woocommerce_currency_symbol();
			$kapee_options['wc_tax_enabled']           = wc_tax_enabled();
			$kapee_options['cart_url']                 = wc_get_cart_url();
			if (wc_tax_enabled()) {
				$kapee_options['ex_tax_or_vat'] = WC()->countries->ex_tax_or_vat();
			} else {
				$kapee_options['ex_tax_or_vat'] = '';
			}
		}

		wp_localize_script('kapee-script', 'kapee_options', $kapee_options);

		wp_enqueue_script('html5', KAPEE_JS_DIR_URI . 'html5.js', array(), '3.7.3');
		wp_script_add_data('html5', 'conditional', 'lt IE 9');

		if (is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}
	}

	/**
	 * Load custom js in footer
	 * @since 1.0.0
	 */
	function kapee_owl_param()
	{
		global $kapee_owlparam;
		wp_localize_script('kapee-script', 'kapeeOwlParam', (array) $kapee_owlparam);
	}

	/**
	 * Support to font mime
	 * @since 1.0.0
	 */
	function kapee_upload_mimes($existing_mimes)
	{
		$existing_mimes['svg'] = 'image/svg+xml';
		$existing_mimes['svgz'] = 'image/svg+xml';
		$existing_mimes['woff'] = 'application/x-font-woff';
		$existing_mimes['woff2'] = 'application/x-font-woff2';
		$existing_mimes['ttf'] = 'application/x-font-ttf';
		$existing_mimes['otf'] = 'application/x-font-otf';
		$existing_mimes['eot'] = 'application/vnd.ms-fontobject';
		return $existing_mimes;
	}
	/**
	 * Search product with sku
	 * @since 1.3.9
	 */
	public function product_search_sku($where)
	{
		global $pagenow, $wpdb, $wp;

		if ((is_admin() && 'edit.php' != $pagenow)
			|| !is_search()
			|| !isset($wp->query_vars['s'])
			|| (isset($wp->query_vars['post_type']) && 'product' != $wp->query_vars['post_type'])
			|| (isset($wp->query_vars['post_type']) && is_array($wp->query_vars['post_type']) && !in_array('product', $wp->query_vars['post_type']))
		) {
			return $where;
		}
		$search_ids = array();
		$terms      = explode(',', $wp->query_vars['s']);

		foreach ($terms as $term) {
			//Include the search by id if admin area.
			if (is_admin() && is_numeric($term)) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku.

			$sku_to_parent_id = $wpdb->get_col($wpdb->prepare("SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean($term)));

			//Search for a simple product that matches the sku.
			$sku_to_id = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean($term)));

			$search_ids = array_merge($search_ids, $sku_to_id, $sku_to_parent_id);
		}

		$search_ids = array_filter(array_map('absint', $search_ids));

		if (sizeof($search_ids) > 0) {
			$where = str_replace(')))', ") OR ({$wpdb->posts}.ID IN (" . implode(',', $search_ids) . "))))", $where);
		}

		return $where;
	}

	/**
	 * Add Favicon.
	 */
	function favicon()
	{
		ob_start();
		if (function_exists('has_site_icon') && has_site_icon()) {
			return '';
		}
		$favicon_url 				= kapee_get_option('theme-favicon', array('url' => KAPEE_IMAGES . 'favicon.png'));
		$favicon_appple_touch_url 	= kapee_get_option('theme-favicon-appple-touch', array('url' => KAPEE_IMAGES . 'favicon-152.png'));
		if (empty($favicon_url['url'])) {
			$favicon_url['url'] =  KAPEE_IMAGES . 'favicon.png';
		}
		if (empty($favicon_appple_touch_url['url'])) {
			$favicon_appple_touch_url['url'] =  KAPEE_IMAGES . 'favicon-152.png';
		}
		if (is_ssl()) {
			$favicon 				= str_replace('http://', 'https://', $favicon_url['url']);
			$favicon_appple_touch 	= str_replace('http://', 'https://', $favicon_appple_touch_url['url']);
		} else {
			$favicon				= $favicon_url['url'];
			$favicon_appple_touch	= $favicon_appple_touch_url['url'];
		}
		echo '<link rel="shortcut icon" sizes="32x32" href=" ' . esc_url($favicon) . '">';
		echo '<link rel="apple-touch-icon" sizes="152x152" href=" ' . esc_url($favicon_appple_touch) . '">';
	}

	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 */
	function pingback_header()
	{
		if (is_singular() && pings_open()) {
			printf('<link rel="pingback" href="%s">' . "\n", esc_url(get_bloginfo('pingback_url')));
		}
	}

	function kapee_google_theme_color()
	{

		$google_theme_color = kapee_get_option('google-theme-color', 'transparent');

		if ('transparent' != $google_theme_color) { ?>
			<meta name="theme-color" content="<?php echo esc_attr($google_theme_color); ?>">
	<?php
		}
	}

	/**
	 * Javascript detection
	 */
	public function javascript_detection()
	{
		echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>";
	}

	/**
	 * Output of custom js options.
	 */
	public function kapee_custom_head_js()
	{

		$custom_js = kapee_get_option('custom-js-head', '');

		if (!empty(trim($custom_js))) {

			echo apply_filters('kapee_head_custom_js', $custom_js); // WPCS: XSS OK.

		}
	}

	/**
	 * Output of custom js options.
	 */
	public function kapee_custom_footer_js()
	{

		$custom_js = kapee_get_option('custom-js-footer', '');

		if (!empty(trim($custom_js))) {

			echo apply_filters('kapee_footer_custom_js', $custom_js); // WPCS: XSS OK.

		}
	}

	/**
	 * Output of dyanamic css.
	 */
	public  function kapee_print_css()
	{
		global $kapee_custom_css;

		if (!empty(trim($kapee_custom_css))) {
			// Sanitize.
			$kapee_custom_css = wp_check_invalid_utf8($kapee_custom_css);
			wp_add_inline_style('kapee-custom-css', $kapee_custom_css);
		}
	}

	/**
	 * Enqueue custom inline style
	 */
	public function kapee_enqueue_inline_style()
	{
		wp_enqueue_style('kapee-custom-css');
	}

	/**
	 * Alter the search posts per page
	 *
	 * @since 1.0.0
	 */
	public  function search_posts_per_page($query)
	{
		if (is_admin() || !$query->is_main_query()) return;
		$portfolio_per_page = kapee_get_option('portfolio-per-page', 9);
		if (in_array($query->get('post_type'), array('portfolio'))) {
			$query->set('posts_per_page', $portfolio_per_page);
			return;
		} elseif ($query->is_main_query() && is_search() && isset($_GET['post_type']) && $_GET['post_type'] == 'product') {
			$posts_per_page = kapee_get_option('products-per-page', 12);
			$query->set('posts_per_page', $posts_per_page);
		}
	}

	/**
	 *Post View Count 
	 */
	public function kapee_post_view_count()
	{
		$prefix = KAPEE_PREFIX;
		if (!is_single() || !is_singular('post')) return;
		$post_id = get_the_ID();
		$views = get_post_meta($post_id, $prefix . 'views_count', true);
		$views = !empty($views) ? $views : 0;

		update_post_meta($post_id, $prefix . 'views_count', ($views + 1));
		$views = get_post_meta($post_id, $prefix . 'views_count', true);
	}


	/**
	 * 'Continue reading' link.
	 */
	public function kapee_excerpt_more($link)
	{
		return '';
	}
	public function kapee_read_more_tag()
	{

		return sprintf(
			'<p class="read-more-btn link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
			esc_url(get_permalink(get_the_ID())),
			kapee_get_option('read-more-text', 'Continue Reading')
		);
	}

	/**
	 * Filter the except length to 30 words.
	 */
	function kapee_excerpt_length($length)
	{
		return kapee_get_option('blog-excerpt-length', 30);
	}
}
// Initialize theme
$kapee_theme_class = new Kapee_Theme_Class;


//changes for vendor
function hook_inquiry_button()
{
	if (is_user_logged_in()) {
		echo '<a href="#send-quote-form" class="button fancybox">Inquiry</a>';
	}
}
//add_action('woocommerce_after_add_to_cart_button','hook_inquiry_button');


function send_inquiry_form_shortcode()
{
	ob_start();
	$vendor_id = get_post_field('post_author', get_the_ID());
	?>
	<form method="post" id="send-quote-form" class="send-quote-form" style="display: none;">
		<div class="form-wrap">
			<label for="product-name">Product Name</label>
			<input type="text" name="name" value="<?php echo get_the_title(get_the_ID()); ?>" id="product-name">
			<input type="hidden" name="user" value="<?php echo $vendor_id; ?>">
			<input type="hidden" name="buyer" value="<?php echo get_current_user_id(); ?>">
			<input type="hidden" name="pid" value="<?php echo get_the_ID(); ?>">
		</div>
		<div class="form-wrap textarea-wrap">
			<label for="product-description">Inquiry Notes</label>
			<textarea name="description" id="product-description"></textarea>
		</div>
		<div class="form-wrap submit-wrap">
			<input type="submit" name="send-inquiry" value="Send Quote">
			<div class="message"></div>
		</div>
	</form>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.send-quote-form').on('submit', function(e) {
				e.preventDefault();
				var val = $('.send-quote-form').serialize();
				jQuery.ajax({
					type: 'post',
					context: this,
					url: wc_add_to_cart_params.ajax_url,
					data: {
						action: 'send_quotes_to_seller',
						val: val
					},
					success: function(response) {
						$('.message').html('<p class="alert-success">Successfully Sent</p>');
						location.reload();
					},
				});
			});
		});
	</script>
<?php
	$data = ob_get_clean();
	echo $data;
}
add_action('wp_footer', 'send_inquiry_form_shortcode');


function send_quotes_to_seller()
{
	$params = array();
	parse_str($_POST['val'], $params);
	$id = get_user_by('id', $params['buyer']);
	$my_post = array(
		'post_title'    => 'Quote - ' . $id->user_email,
		'post_status'   => 'publish',
		'post_author'   => $params['user'],
		'post_type'		=> 'quote'
	);

	// Insert the post into the database
	$pid = wp_insert_post($my_post);
	update_post_meta($pid, 'name', $params['name']);
	update_post_meta($pid, 'product-id', $params['pid']);
	update_post_meta($pid, 'description', $params['description']);
	update_post_meta($pid, 'buyer', $params['buyer']);
	wp_send_json_success();
	die();
}
add_action('wp_ajax_send_quotes_to_seller', 'send_quotes_to_seller');
add_action('wp_ajax_nopriv_send_quotes_to_seller', 'send_quotes_to_seller');


function send_proposal_to_buyer()
{
	// ob_start();
	$params = array();
	parse_str($_POST['val'], $params);
	//print_r($params);
	$pid = $params['s-pid'];
	// Insert the post into the database
	update_post_meta($pid, 's-name', $params['s-name']);
	update_post_meta($pid, 's-product-id', $params['s-pid']);
	update_post_meta($pid, 's-description', $params['s-description']);
	update_post_meta($pid, 's-brand', $params['s-brand']);
	update_post_meta($pid, 's-price', $params['s-price']);
	update_post_meta($pid, 's-tel', $params['s-tel']);
	update_post_meta($pid, 's-delivery-time', $params['s-delivery-time']);
	wp_send_json_success();
	die();
}
add_action('wp_ajax_send_proposal_to_buyer', 'send_proposal_to_buyer');
add_action('wp_ajax_nopriv_send_proposal_to_buyer', 'send_proposal_to_buyer');




//register quote
function custom_post_type_quote()
{

	$labels = array(
		'name'                  => _x('Quotes', 'Quote General Name', 'text_domain'),
		'singular_name'         => _x('Quote', 'Quote Singular Name', 'text_domain'),
		'menu_name'             => __('Quotes', 'text_domain'),
		'name_admin_bar'        => __('Quote', 'text_domain'),
		'archives'              => __('Item Archives', 'text_domain'),
		'attributes'            => __('Item Attributes', 'text_domain'),
		'parent_item_colon'     => __('Parent Item:', 'text_domain'),
		'all_items'             => __('All Items', 'text_domain'),
		'add_new_item'          => __('Add New Item', 'text_domain'),
		'add_new'               => __('Add New', 'text_domain'),
		'new_item'              => __('New Item', 'text_domain'),
		'edit_item'             => __('Edit Item', 'text_domain'),
		'update_item'           => __('Update Item', 'text_domain'),
		'view_item'             => __('View Item', 'text_domain'),
		'view_items'            => __('View Items', 'text_domain'),
		'search_items'          => __('Search Item', 'text_domain'),
		'not_found'             => __('Not found', 'text_domain'),
		'not_found_in_trash'    => __('Not found in Trash', 'text_domain'),
		'featured_image'        => __('Featured Image', 'text_domain'),
		'set_featured_image'    => __('Set featured image', 'text_domain'),
		'remove_featured_image' => __('Remove featured image', 'text_domain'),
		'use_featured_image'    => __('Use as featured image', 'text_domain'),
		'insert_into_item'      => __('Insert into item', 'text_domain'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'text_domain'),
		'items_list'            => __('Items list', 'text_domain'),
		'items_list_navigation' => __('Items list navigation', 'text_domain'),
		'filter_items_list'     => __('Filter items list', 'text_domain'),
	);
	$args = array(
		'label'                 => __('Quote', 'text_domain'),
		'description'           => __('Quote Description', 'text_domain'),
		'labels'                => $labels,
		'supports'              => false,
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'supports' 				=> array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
	);
	register_post_type('quote', $args);
}
add_action('init', 'custom_post_type_quote');

/**
 * Register meta boxes.
 */
function hcf_register_meta_boxes()
{
	add_meta_box('hcf-1', __('Buyer\'s Quote', 'hcf'), 'hcf_display_callback', 'quote');
}
add_action('add_meta_boxes', 'hcf_register_meta_boxes');

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function hcf_display_callback($post)
{	?>
	<div class="hcf_box">
		<style scoped>
			.hcf_box {
				display: grid;
				grid-template-columns: max-content 1fr;
				grid-row-gap: 10px;
				grid-column-gap: 20px;
			}

			.hcf_field {
				display: block;
			}
		</style>
		<div style="margin: 10px; display: block;">
			<h3>Buyers Quote</h3>
			<p class="meta-options hcf_field">
				<label for="product-id">Product Id</label>
				<input id="product-id" type="text" name="product-id" value="<?php echo get_post_meta($post->ID, 'product-id', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="product-name">Product Name</label>
				<input id="product-name" type="text" name="name" value="<?php echo get_post_meta($post->ID, 'name', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="product-description">Product Description</label>
				<textarea name="description" id="product-description"><?php echo get_post_meta($post->ID, 'description', true); ?></textarea>
			</p>
		</div>

		<div style="margin: 10px; display: block;">
			<h3>Sellers Response</h3>
			<p class="meta-options hcf_field">
				<label for="product-id">Product Id</label>
				<input id="product-id" type="text" name="s-product-id" value="<?php echo get_post_meta($post->ID, 's-product-id', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="product-name">Product Name</label>
				<input id="product-name" type="text" name="s-name" value="<?php echo get_post_meta($post->ID, 's-name', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="brand">Brand</label>
				<input id="brand" type="text" name="s-brand" value="<?php echo get_post_meta($post->ID, 's-brand', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="price">Price</label>
				<input id="price" type="text" name="s-price" value="<?php echo get_post_meta($post->ID, 's-price', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="tel">Phone</label>
				<input id="tel" type="text" name="s-tel" value="<?php echo get_post_meta($post->ID, 's-tel', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="delivery-time">Delivery Time</label>
				<input id="delivery-time" type="text" name="s-delivery-time" value="<?php echo get_post_meta($post->ID, 's-delivery-time', true); ?>">
			</p>
			<p class="meta-options hcf_field">
				<label for="product-description">Notes</label>
				<textarea name="s-description" id="product-description"><?php echo get_post_meta($post->ID, 's-description', true); ?></textarea>
			</p>
		</div>
	</div>
<?php
}


function enqueue_fancy_box()
{
	wp_enqueue_script('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js', true);
	wp_enqueue_style('fancybox', '//cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css', true);
}
add_action('wp_enqueue_scripts', 'enqueue_fancy_box');

function enqueue_fabcybox_js()
{	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('.fancybox').fancybox({
				touch: false,
				'transitionIn': 'elastic',
				'transitionOut': 'elastic',
				'speedIn': 600,
				'speedOut': 200,
				'overlayShow': false
			});

		});
	</script>
<?php
}
add_action('wp_footer', 'enqueue_fabcybox_js');



//shows quotes received by seller with buyer's notes
function show_seller_quotes()
{
	ob_start();
?>
	<table>
		<tr class="quote-head">
			<th>Product Name</th>
			<th>Inquiry Description</th>
			<th>Buyer's Email</th>
			<th></th>
		</tr>
		<?php
		$args = array('post_type' => 'quote');
		$the_query = new WP_Query($args);

		// The Loop
		if ($the_query->have_posts()) {
			while ($the_query->have_posts()) {
				$the_query->the_post();
				global $post;
				$pid = get_post_meta($post->ID, 'product-id', true);
				$author_id = get_post_field('post_author', $pid);
				if (get_current_user_id() == $author_id) {
		?>
					<tr>
						<td><?php echo get_post_meta($post->ID, 'name', true); ?></td>
						<td><?php echo get_post_meta($post->ID, 'description', true); ?></td>
						<td class="buyer-mail">
							<?php $author_id = $post->post_author; ?>
							<a href="mailto: <?php echo get_the_author_meta('user_email', $author_id); ?>"><?php echo get_the_author_meta('display_name', $author_id); ?></a>
						</td>

						</td>
						<?php
						$id = '#proposal-form-' . $post->ID;
						?>
						<td><a class="fancybox" href="<?php echo $id; ?>">Send Proposal</a></td>
					</tr>
					<tr>
						<div class="seller-proposal-form proposal-form-<?php echo $post->ID; ?>" id="proposal-form-<?php echo $post->ID; ?>" style="display: none;">
							<form method="post" class="send-proposal-form-<?php echo $post->ID; ?>">
								<div class="form-wrap">
									<label for="product-name">Product Name</label>
									<input type="text" name="s-name" value="<?php echo get_post_meta($post->ID, 'name', true); ?>" id="product-name">
									<input type="hidden" name="s-pid" value="<?php echo $post->ID; ?>">
								</div>
								<div class="form-wrap">
									<label for="brand">Brand</label>
									<input type="text" name="s-brand" value="" id="brand">
								</div>
								<div class="form-wrap">
									<label for="price">Price</label>
									<input type="number" name="s-price" value="" id="price" placeholder="$">
								</div>
								<div class="form-wrap">
									<label for="phone">Phone</label>
									<input type="tel" name="s-tel" value="" id="phone">
								</div>
								<div class="form-wrap">
									<label for="delivery-time">Delivery Time</label>
									<input type="text" name="s-delivery-time" value="" id="delivery-time" placeholder="Days">
								</div>
								<div class="form-wrap textarea-wrap">
									<label for="product-description">Notes</label>
									<textarea name="s-description" id="product-description"></textarea>
								</div>
								<div class="form-wrap submit-wrap">
									<input type="submit" name="send-inquiry" value="Send Quote">
								</div>
								<div class="message"></div>
							</form>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									// $('.fancybox').fancybox();
									$('.send-proposal-form-<?php echo $post->ID; ?>').on('submit', function(e) {
										e.preventDefault();
										var val = $('.send-proposal-form-<?php echo $post->ID; ?>').serialize();
										jQuery.ajax({
											type: 'post',
											context: this,
											url: wc_add_to_cart_params.ajax_url,
											data: {
												action: 'send_proposal_to_buyer',
												val: val
											},
											success: function(response) {
												$('.message').html('<p class="alert-success">Successfully Sent</p>');
												location.reload();
											},
										});
									});
								});
							</script>
						</div>
					</tr>
		<?php
				}
			}
			/* Restore original Post Data */
			wp_reset_postdata();
		}
		?>
	</table>
<?php
	$data = ob_get_clean();
	return $data;
}
add_shortcode('show_seller_quotes', 'show_seller_quotes');


//show seller's response to buyers quotes
function show_buyers_quotes()
{
	ob_start();
?>
	<table>
		<tr>
			<th>Product Name</th>
			<th>Inquiry Description</th>
		</tr>
		<?php
		$args = array('post_type' => 'quote');
		$the_query = new WP_Query($args);

		// The Loop
		if ($the_query->have_posts()) {
			while ($the_query->have_posts()) {
				$the_query->the_post();
				global $post;
				$pid = get_post_meta($post->ID, 'product-id', true);
				$author_id = get_post_meta($post->ID, 'buyer', true);
				if (get_current_user_id() == $author_id) {
		?>
					<tr>
						<td><?php echo get_post_meta($post->ID, 'name', true); ?></td>
						<td><?php echo get_post_meta($post->ID, 'description', true); ?></td>
						<?php
						$id = '#seller-reponse-list-' . $post->ID;
						?>
						<td><a class="fancybox" href="<?php echo $id; ?>">Seller's Response</a></td>
					</tr>

					<div class="seller-reponse-list-<?php echo $post->ID; ?>" id="seller-reponse-list-<?php echo $post->ID; ?>" style="display: none; width: 50%;">
						<div>
							<div class="form-wrap">
								<span style="width: 50%;">Product Name</span>
								<span class="div-response" style="width: 50%;float: right;"><?php echo get_the_title($pid); ?></span>
							</div>
							<div class="form-wrap">
								<span for="product-description" style="width: 50%;">Notes</span>
								<span class="div-response" style="width: 50%;float: right;"><?php echo get_post_meta($post->ID, 's-description', true); ?></span>
							</div>
							<div class="form-wrap">
								<span for="brand" style="width: 50%;">Brand</span>
								<span class="div-response" style="width: 50%;float: right;"><?php echo get_post_meta($post->ID, 's-brand', true); ?></span>
							</div>
							<div class="form-wrap">
								<span for="price" style="width: 50%;">Price</span>
								<span class="div-response" style="width: 50%;float: right;"><?php echo get_post_meta($post->ID, 's-price', true); ?></span>
							</div>
							<div class="form-wrap">
								<span for="phone" style="width: 50%;">Phone</span>
								<span class="div-response" style="width: 50%;float: right;"><?php echo get_post_meta($post->ID, 's-tel', true); ?></span>
							</div>
							<div class="form-wrap">
								<span for="delivery-time" style="width: 50%;">Delivery Time</span>
								<span class="label-response" style="width: 50%;float: right;"><?php echo get_post_meta($post->ID, 's-delivery-time', true); ?> days</span>
							</div>
						</div>
					</div>
		<?php
				}
			}
			/* Restore original Post Data */
			wp_reset_postdata();
		}
		?>
	</table>
<?php
	$data = ob_get_clean();
	return $data;
}
add_shortcode('show_buyers_quotes', 'show_buyers_quotes');


//filter for tab
add_filter('dokan_query_var_filter', 'dokan_load_document_menu');
function dokan_load_document_menu($query_vars)
{
	$query_vars['quotes'] = 'quotes';
	return $query_vars;
}
add_filter('dokan_get_dashboard_nav', 'dokan_add_quotes_menu');
function dokan_add_quotes_menu($urls)
{
	$urls['quotes'] = array(
		'title' => __('Quotes/Inquiries', 'dokan'),
		'icon'  => '<i class="fa fa-user"></i>',
		'url'   => dokan_get_navigation_url('quotes'),
		'pos'   => 51
	);
	return $urls;
}
add_action('dokan_load_custom_template', 'dokan_load_template');
function dokan_load_template($query_vars)
{
	if (isset($query_vars['quotes'])) {
		require_once dirname(__FILE__) . '/quotes.php';
	}
}


add_action('init', 'register_quotes_endpoint');

/**
 * Register New Endpoint.
 *
 * @return void.
 */
function register_quotes_endpoint()
{
	add_rewrite_endpoint('quotes', EP_ROOT | EP_PAGES);
}



add_filter('query_vars', 'quotes_query_vars');

/**
 * Add new query var.
 *
 * @param array $vars vars.
 *
 * @return array An array of items.
 */
function quotes_query_vars($vars)
{

	$vars[] = 'quotes';
	return $vars;
}


add_filter('woocommerce_account_menu_items', 'add_quotes_tab');

/**
 * Add New tab in my account page.
 *
 * @param array $items myaccount Items.
 *
 * @return array Items including New tab.
 */
function add_quotes_tab($items)
{

	$items['quotes'] = 'Quotes';
	return $items;
}


add_action('woocommerce_account_quotes_endpoint', 'add_quotes_content');

/**
 * Add content to the new tab.
 *
 * @return  string.
 */
function add_quotes_content()
{
	echo do_shortcode('[show_buyers_quotes]');
}
