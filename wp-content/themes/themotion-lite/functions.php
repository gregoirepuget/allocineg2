<?php
/**
 * TheMotion Lite functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package themotion
 */

define( 'THEMOTION_PHP_INCLUDE',  get_template_directory() . '/inc' );
define( 'THEMOTION_VERSION', '1.2.6' );

if ( ! function_exists( 'themotion_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function themotion_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on themotion, use a find and replace
		 * to change 'themotion-lite' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'themotion-lite', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails', array( 'post', 'page', 'product', 'download' ) );
				set_post_thumbnail_size( 1200, 9999 );

		add_image_size( 'themotion-post-thumbnail', 370, 215, true );

		add_image_size( 'themotion-thumbnail-no-crop', 345, 200 );

		add_image_size( 'themotion-playlist-thumbnail', 175, 100, true );

		add_image_size( 'themotion-thumbnail-blog', 770, 425, true );

		add_image_size( 'themotion-thumbnail-blog-no-crop', 770, 425 );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary', 'themotion-lite' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 * See https://developer.wordpress.org/themes/functionality/post-formats/
		 */
		add_theme_support(
			'post-formats', array(
				'video',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background', apply_filters(
				'themotion_custom_background_args', array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for custom logo
		add_theme_support(
			'custom-logo', array(
				'height'      => 55,
				'width'       => 280,
				'flex-width' => true,
			)
		);

		add_theme_support( 'woocommerce' );

		/**
		 * WooCommerce
		 *
		 * Unhook sidebar
		 */
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
endif;
add_action( 'after_setup_theme', 'themotion_setup' );

/**
 * Check for static page
 *
 * Checks if the page is static and returns a boolean.
 */
function themotion_is_not_static_page() {
	return ('posts' != get_option( 'show_on_front' ));
}
/**
 * Content Width
 *
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function themotion_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'themotion_content_width', 640 );
}
add_action( 'after_setup_theme', 'themotion_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function themotion_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'themotion-lite' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'themotion-lite' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebars(
		3, array(
			/* translators: Footer area id */
			'name'          => esc_html__( 'Footer Widget Area %d', 'themotion-lite' ),
			'id'            => 'footer-area',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'themotion_widgets_init' );


/**
 * Return the Google font stylesheet URL, if available.
 *
 * The use of Source Sans Pro and Bitter by default is localized. For languages
 * that use characters not supported by the font, the font can be disabled.
 *
 * @since themotion 1.0
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function themotion_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Source Sans Pro, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Merriweather font: on or off', 'themotion-lite' );

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Bitter, translate this to 'off'. Do not translate into your
	 * own language.
	 */
	$bitter = _x( 'on', 'Cabin font: on or off', 'themotion-lite' );

	if ( 'off' != $source_sans_pro || 'off' != $bitter ) {
		$font_families = array();

		if ( 'off' != $source_sans_pro ) {
			$font_families[] = 'Merriweather:400';
		}

		if ( 'off' != $bitter ) {
			$font_families[] = 'Cabin:400,500,600,700';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}

/**
 * Registers an editor stylesheet for the theme.
 */
function themotion_add_editor_styles() {
	add_editor_style( array( 'css/custom-editor-style.css', themotion_fonts_url() ) );
}
add_action( 'admin_init', 'themotion_add_editor_styles' );

/**
 * Enqueue scripts and styles.
 */
function themotion_scripts() {
	wp_enqueue_style( 'themotion-style', get_stylesheet_uri(), array( 'bootstrap' ), THEMOTION_VERSION );

	wp_enqueue_style( 'wp-mediaelement' );

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/vendor/bootstrap.min.css', array(), THEMOTION_VERSION, 'all' );

	wp_enqueue_style( 'themotion-fonts', themotion_fonts_url(), array(), THEMOTION_VERSION );

	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/vendor/font-awesome.min.css', array(), THEMOTION_VERSION );

	wp_enqueue_script( 'themotion-functions-js', get_template_directory_uri() . '/js/functions.js', array(), THEMOTION_VERSION, true );

	wp_localize_script(
		'themotion-functions-js', 'screenReaderText', array(
			'expand'   => '<span class="screen-reader-text">' . esc_html__( 'expand child menu', 'themotion-lite' ) . '</span>',
			'collapse' => '<span class="screen-reader-text">' . esc_html__( 'collapse child menu', 'themotion-lite' ) . '</span>',
		)
	);

	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/vendor/bootstrap.min.js', array( 'jquery' ), THEMOTION_VERSION, true );

	wp_enqueue_script( 'themotion-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), THEMOTION_VERSION, true );

	if ( get_option( 'thread_comments' ) && is_singular() && comments_open() ) {
		wp_enqueue_script( 'comment-reply' );
	}

}
add_action( 'wp_enqueue_scripts', 'themotion_scripts' );


/**
 * Load customize controls js
 */
function themotion_customizer_script() {
	wp_enqueue_script( 'themotion-customizer-script', get_template_directory_uri() . '/js/themotion_customizer.js', array( 'jquery', 'jquery-ui-draggable' ), THEMOTION_VERSION, true );
	wp_enqueue_style( 'themotion-admin-stylesheet', get_template_directory_uri() . '/css/admin-style.css', array(), THEMOTION_VERSION );
}
add_action( 'customize_controls_enqueue_scripts', 'themotion_customizer_script' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Load homepage sections.
 */
require get_template_directory() . '/inc/frontpage-sections.php';

/**
 * Init preview demo images
 */
require_once( get_template_directory() . '/demo-preview-images/init-prevdem.php' );
;

/**
 * Init pro.
 */
$pro_path = get_template_directory() . '/inc/init-pro.php';
if ( file_exists( $pro_path ) ) {
	require $pro_path;
}

/**
 * Init pro.
 */
$header_main_funct_path = get_template_directory() . '/header-main-function.php';
if ( file_exists( $header_main_funct_path ) ) {
	require $header_main_funct_path;
}

if ( ! function_exists( 'themotion_excerpt_more' ) && ! is_admin() ) :
	/**
	 * Replaces "[...]" (appended to automatically generated excerpts) with ...
	 * and a Continue reading link.
	 *
	 * @since themotion 1.4
	 *
	 * @param string $more Default Read More excerpt link.
	 * @return string Filtered Read More excerpt link.
	 */
	function themotion_excerpt_more( $more ) {
		$link = sprintf(
			'<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Name of current post */
			sprintf( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'themotion-lite' ), '<span class="screen-reader-text">' . get_the_title( get_the_ID() ) . '</span>' )
		);
		return ' &hellip; ' . $link;
	}
	add_filter( 'excerpt_more', 'themotion_excerpt_more' );
endif;


/**
 * Return the site brand
 *
 * @since TheMotion Lite 1.0
 */
function themotion_brand() {
	if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
		the_custom_logo(); ?>
		<div class="header-logo-wrap themotion-only-customizer">
			<?php
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$description = get_bloginfo( 'description', 'display' );
if ( $description || is_customize_preview() ) :
	?>
	<p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
	<?php
			endif;
			?>
		</div>
		<?php
	} else {
		if ( is_customize_preview() ) {
			?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link themotion-only-customizer" title="<?php echo esc_attr( get_bloginfo( 'title' ) ); ?>">
				<img src="">
			</a>
			<?php
		}
		?>

		<div class="header-logo-wrap">
			<?php
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$description = get_bloginfo( 'description', 'display' );
if ( $description || is_customize_preview() ) :
	?>
	<p class="site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
	<?php
			endif;
			?>
		</div>
		<?php
	}// End if().
}

/**
 * Customizer selective refresh for Site title
 *
 * @since 1.1.31
 * @param class $wp_customize - the wp_customize class.
 */
function themotion_site_title_selective_refresh( $wp_customize ) {
	// Abort if selective refresh is not available.
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}

	$wp_customize->selective_refresh->add_partial(
		'blogname', array(
			'selector' => '.header-logo-wrap .site-title',
			'render_callback' => 'themotion_customize_partial_blogname',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'blogdescription', array(
			'selector' => '.header-logo-wrap .site-description',
			'render_callback' => 'themotion_customize_partial_blogdescription',
		)
	);

	$wp_customize->selective_refresh->add_partial(
		'themotion_social_icons', array(
			'selector' => '.social-media-icons',
			'render_callback' => 'themotion_social_media_render_callback',
		)
	);

}
add_action( 'customize_register', 'themotion_site_title_selective_refresh' );

/**
 * Get header social icons and search icon
 *
 * @since 1.1.31
 */
function themotion_social_media_render_callback() {
	themotion_social_icons();
	themotion_search_icon();
}

/**
 * Get site title
 *
 * @since 1.1.31
 */
function themotion_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Get site description
 *
 * @since 1.1.31
 */
function themotion_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Display the search icon
 *
 * @since TheMotion Lite 1.0
 */
function themotion_search_icon() {
	$themotion_show_search = get_theme_mod( 'themotion_show_search' );
	if ( (bool) $themotion_show_search === true ) {
		echo '<li class="themotion-search-icon themotion-only-customizer"></li>';
		return;
	}
	?>
	<li class="themotion-search-icon">
		<button type="button" class="search-opt search-toggle">
		</button>
		<div class="header-search">
			<div class="container container-header-search">
				<?php get_search_form(); ?>
			</div>
		</div>
	</li>
<?php
}


/**
 * Custom Excerpt Length
 *
 * Set the length of the post excerpt.
 *
 * @since TheMotion Lite 1.0
 */
function themotion_custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'themotion_custom_excerpt_length', 999 );


/**
 * Escape Lightbox iFrame
 *
 * Escape the iFrame for embedds.
 */
function themotion_escape_lightbox( $input ) {

	$allowed_tags = array(
		'video' => array(
			'autoplay'  => true,
			'controls'  => true,
			'height'    => true,
			'loop'      => true,
			'muted'     => true,
			'poster'    => true,
			'preload'   => true,
			'src'       => true,
			'width'     => true,
			'class'     => true,
			'id'        => true,
			'style'     => true,
			'title'     => true,
			'role'      => true,
		),
		'iframe' => array(
			'height'    => true,
			'width'     => true,
			'name'      => true,
			'sandbox'   => true,
			'src'       => true,
			'srcdoc'    => true,
			'class'     => true,
			'id'        => true,
			'style'     => true,
			'title'     => true,
			'role'      => true,
			'frameborder' => true,
			'webkitallowfullscreen' => true,
			'allowfullscreen' => true,
		),
		'source' => array(
			'type'     => true,
			'src'      => true,
			'class'    => true,
			'id'       => true,
			'style'    => true,
			'role'     => true,
			'title'    => true,
		),
	);

	return wp_kses( $input, $allowed_tags );
}

/**
 * About page class
 */
require_once get_template_directory() . '/ti-about-page/class-themeisle-about-page.php';

/*
* About page instance
*/
$config = array(
	// Menu name under Appearance.
	'menu_name'           => __( 'About TheMotion Lite', 'themotion-lite' ),
	// Page title.
	'page_name'           => __( 'About TheMotion Lite', 'themotion-lite' ),
	// Main welcome title
	/* translators: s - theme name */
	'welcome_title'       => sprintf( __( 'Welcome to %s! - Version ', 'themotion-lite' ), 'TheMotion Lite' ),
	// Main welcome content
	'welcome_content'     => esc_html__( 'TheMotion Lite is a clean and elegant WordPress theme primarily made for video sites, and it can also be a perfect fit for blogs or portfolio websites. It features a beautiful slider, classy light colors, and a modern appearance. Its stylish and minimalist design makes it multipurpose, ready to fit any kind of website.', 'themotion-lite' ),
	/**
	 * Tabs array.
	 *
	 * The key needs to be ONLY consisted from letters and underscores. If we want to define outside the class a function to render the tab,
	 * the will be the name of the function which will be used to render the tab content.
	 */
	'tabs'                => array(
		'getting_started'     => __( 'Getting Started', 'themotion-lite' ),
		'recommended_plugins' => __( 'Useful Plugins', 'themotion-lite' ),
		'support'             => __( 'Support', 'themotion-lite' ),
		'changelog'           => __( 'Changelog', 'themotion-lite' ),
		'free_pro'            => __( 'Free vs PRO', 'themotion-lite' ),
	),
	// Support content tab.
	'support_content'     => array(
		'first'  => array(
			'title'        => esc_html__( 'Contact Support', 'themotion-lite' ),
			'icon'         => 'dashicons dashicons-sos',
			'text'         => esc_html__( 'We want to make sure you have the best experience using TheMotion Lite, and that is why we have gathered all the necessary information here for you. We hope you will enjoy using TheMotion Lite as much as we enjoy creating great products.', 'themotion-lite' ),
			'button_label' => esc_html__( 'Contact Support', 'themotion-lite' ),
			'button_link'  => esc_url( 'https://themeisle.com/contact/' ),
			'is_button'    => true,
			'is_new_tab'   => true,
		),
		'second' => array(
			'title'        => esc_html__( 'Documentation', 'themotion-lite' ),
			'icon'         => 'dashicons dashicons-book-alt',
			'text'         => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use TheMotion Lite.', 'themotion-lite' ),
			'button_label' => esc_html__( 'Read full documentation', 'themotion-lite' ),
			'button_link'  => 'http://docs.themeisle.com/article/469-themotion-lite-documentation',
			'is_button'    => false,
			'is_new_tab'   => true,
		),
		'third'  => array(
			'title'        => esc_html__( 'Changelog', 'themotion-lite' ),
			'icon'         => 'dashicons dashicons-portfolio',
			'text'         => esc_html__( 'Want to get the gist on the latest theme changes? Just consult our changelog below to get a taste of the recent fixes and features implemented.', 'themotion-lite' ),
			'button_label' => esc_html__( 'Changelog', 'themotion-lite' ),
			'button_link'  => esc_url( admin_url( 'themes.php?page=themotion-lite-welcome&tab=changelog&show=yes' ) ),
			'is_button'    => false,
			'is_new_tab'   => false,
		),
		'fourth' => array(
			'title'        => esc_html__( 'Create a child theme', 'themotion-lite' ),
			'icon'         => 'dashicons dashicons-admin-customizer',
			'text'         => esc_html__( "If you want to make changes to the theme's files, those changes are likely to be overwritten when you next update the theme. In order to prevent that from happening, you need to create a child theme. For this, please follow the documentation below.", 'themotion-lite' ),
			'button_label' => esc_html__( 'View how to do this', 'themotion-lite' ),
			'button_link'  => 'http://docs.themeisle.com/article/14-how-to-create-a-child-theme',
			'is_button'    => false,
			'is_new_tab'   => true,
		),
		'fifth'  => array(
			'title'        => esc_html__( 'Speed up your site', 'themotion-lite' ),
			'icon'         => 'dashicons dashicons-controls-skipforward',
			'text'         => esc_html__( 'If you find yourself in a situation where everything on your site is running very slowly, you might consider having a look at the documentation below where you will find the most common issues causing this and possible solutions for each of the issues.', 'themotion-lite' ),
			'button_label' => esc_html__( 'View how to do this', 'themotion-lite' ),
			'button_link'  => 'http://docs.themeisle.com/article/63-speed-up-your-wordpress-site',
			'is_button'    => false,
			'is_new_tab'   => true,
		),
	),
	// Getting started tab
	'getting_started'     => array(
		'first'  => array(
			'title'               => esc_html__( 'Read full documentation', 'themotion-lite' ),
			'text'                => esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use TheMotion Lite.', 'themotion-lite' ),
			'button_label'        => esc_html__( 'Documentation', 'themotion-lite' ),
			'button_link'         => 'http://docs.themeisle.com/article/469-themotion-lite-documentation',
			'is_button'           => false,
			'recommended_actions' => false,
			'is_new_tab'          => true,
		),
		'second' => array(
			'title'               => esc_html__( 'Go to the Customizer', 'themotion-lite' ),
			'text'                => esc_html__( 'Using the WordPress Customizer you can easily customize every aspect of the theme.', 'themotion-lite' ),
			'button_label'        => esc_html__( 'Go to the Customizer', 'themotion-lite' ),
			'button_link'         => esc_url( admin_url( 'customize.php' ) ),
			'is_button'           => true,
			'recommended_actions' => false,
			'is_new_tab'          => true,
		),
	),
	// Free vs PRO array.
	'free_pro'            => array(
		'free_theme_name'     => 'Themotion Lite',
		'pro_theme_name'      => 'Themotion',
		'pro_theme_link'      => 'https://themeisle.com/themes/themotion/',
		/* translators: s - theme name */
		'get_pro_theme_label' => sprintf( __( 'Get %s now!', 'themotion-lite' ), 'Themotion Pro' ),
		'features'            => array(
			array(
				'title'       => __( 'Mobile friendly', 'themotion-lite' ),
				'description' => __( 'Responsive layout. Works on every device.', 'themotion-lite' ),
				'is_in_lite'  => 'true',
				'is_in_pro'   => 'true',
			),
			array(
				'title'       => __( 'Quality Support', 'themotion-lite' ),
				'description' => __( '24/7 HelpDesk Professional Support', 'themotion-lite' ),
				'is_in_lite'  => 'true',
				'is_in_pro'   => 'true',
			),
			array(
				'title'       => __( 'Fully Customizable Colors', 'themotion-lite' ),
				'description' => __( 'Change colors for the header overlay, header text and navbar.', 'themotion-lite' ),
				'is_in_lite'  => 'false',
				'is_in_pro'   => 'true',
			),
			array(
				'title'       => __( 'WooCommerce Compatible', 'themotion-lite' ),
				'description' => __( 'Ready for e-commerce. You can build an online store here.', 'themotion-lite' ),
				'is_in_lite'  => 'false',
				'is_in_pro'   => 'true',
			),
			array(
				'title'       => __( 'Two frontpage templates.', 'themotion-lite' ),
				'description' => __( 'Alternate page template to display your posts.', 'themotion-lite' ),
				'is_in_lite'  => 'false',
				'is_in_pro'   => 'true',
			),
		),
	),
	// Plugins array.
	'recommended_plugins' => array(
		'already_activated_message' => esc_html__( 'Already activated', 'themotion-lite' ),
		'version_label'             => esc_html__( 'Version: ', 'themotion-lite' ),
		'install_label'             => esc_html__( 'Install and Activate', 'themotion-lite' ),
		'activate_label'            => esc_html__( 'Activate', 'themotion-lite' ),
		'deactivate_label'          => esc_html__( 'Deactivate', 'themotion-lite' ),
		'content'                   => array(
			array(
				'slug' => 'visualizer',
			),
			array(
				'slug' => 'tweet-old-post',
			),
			array(
				'slug' => 'adblock-notify-by-bweb',
			),
			array(
				'slug' => 'feedzy-rss-feeds',
			),
		),
	),
);
Themeisle_About_Page::init( apply_filters( 'hestia_about_page_array', $config ) );

/* Adding pro features preview in customizer */
$themotion_customizer_theme_info_path = trailingslashit( THEMOTION_PHP_INCLUDE ) . 'customizer-pro/customizer-theme-info.php';
if ( file_exists( $themotion_customizer_theme_info_path ) ) {
	require_once( $themotion_customizer_theme_info_path );
}

/* Remove pro features preview from pro version */
$themotion_pro_manager_path = trailingslashit( THEMOTION_PHP_INCLUDE ) . 'features/feature-pro-manager.php';
if ( file_exists( $themotion_pro_manager_path ) ) {
	require_once( $themotion_pro_manager_path );
}

/**
 * Function to determine if a video is youtube or vimeo.
 *
 * @param string $url Video url.
 *
 * @return string
 * @since 1.2.4
 */
function themotion_video_type( $url ) {
	if ( strpos( $url, 'youtu' ) > 0 ) {
		return 'youtube';
	} elseif ( strpos( $url, 'vimeo' ) > 0 ) {
		return 'vimeo';
	} else {
		return 'unknown';
	}
}

/**
 * Return src attribute from html
 *
 * @return string;
 * @since 1.2.4
 */
function themotion_get_embeded_src( $html ) {
	$doc = new DOMDocument();
	$doc->loadHTML( $html );
	$xpath = new DOMXPath( $doc );
	$src = $xpath->evaluate( 'string(//iframe/@src)' );
	return $src;
}

/**
 * Get video id from url.
 *
 * @param string $url Youtube url.
 *
 * @return string|bool
 */
function themotion_get_youtube_video_id( $url ) {
	preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match );
	if ( ! empty( $match[1] ) ) {
		return $match[1];
	}
	return false;
}
