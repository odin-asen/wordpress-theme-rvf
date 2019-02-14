<?php
/**
 * rvf functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package rvf
 */    
if (! function_exists( 'rvf_setup' )) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function rvf_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on rvf, use a find and replace
		 * to change 'rvf' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'rvf', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Enable support for the Aside Post Format
		 */
		add_theme_support( 'post-formats', array( 'aside' ) );
	
		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary', 'rvf' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'rvf_custom_background_args', array(
			'default-color' => '050f56', /* RVF default color */
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'rvf_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function rvf_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'rvf_content_width', 654 );
}
add_action( 'after_setup_theme', 'rvf_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
 /*
function rvf_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'rvf' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'rvf' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'rvf_widgets_init' );
*/

/**
 * Enqueue scripts and styles.
 */
function rvf_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_script( 'navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rvf_scripts' );

if ( ! function_exists( 'shape_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function rvf_posted_on() {
    printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'rvf' ),
        esc_url( get_permalink() ),
        esc_attr( get_the_time() ),
        esc_attr( get_the_date( 'c' ) ),
        esc_html( get_the_date() ),
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_attr( sprintf( __( 'View all posts by %s', 'rvf' ), get_the_author() ) ),
        esc_html( get_the_author() )
    );
}
endif;
 
/**
 * Returns true if a blog has more than 1 category
 */
function rvf_categorized_blog() {
    if ( false === ( $all_categories = get_transient( 'all_categories' ) ) ) {
        // Create an array of all the categories that are attached to posts
        $all_categories = get_categories( array(
            'hide_empty' => 1,
        ) );
 
        // Count the number of categories that are attached to the posts
        $all_categories = count( $all_categories );
 
        set_transient( 'all_categories', $all_categories );
    }
 
    if ( '1' != $all_categories ) {
        // This blog has more than 1 category so rvf_categorized_blog should return true
        return true;
    } else {
        // This blog has only 1 category so rvf_categorized_blog should return false
        return false;
    }
}
 
/**
 * Flush out the transients used in rvf_categorized_blog
 */
function rvf_category_transient_flusher() {
    // Like, beat it. Dig?
    delete_transient( 'all_categories' );
}
add_action( 'edit_category', 'rvf_category_transient_flusher' );
add_action( 'save_post', 'rvf_category_transient_flusher' );

/**
 * Custom functions that act independently of the theme templates
 */
require( get_template_directory() . '/inc/tweaks.php' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
//require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
//require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
 /*
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}
*/
/**
 * Load WooCommerce compatibility file.
 */
 /*
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
*/
