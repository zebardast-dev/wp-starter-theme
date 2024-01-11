<?php // phpcs:ignore
/**
 * Theme functions and definitions.
 *
 * Sets up the theme and provides some helper functions
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development
 * and http://codex.wordpress.org/Child_Themes), you can override certain
 * functions (those wrapped in a function_exists() call) by defining them first
 * in your child theme's functions.php file. The child theme's functions.php
 * file is included before the parent theme's file, so the child theme
 * functions would be used.
 *
 *
 * For more information on hooks, actions, and filters,
 * see http://codex.wordpress.org/Plugin_API
 *
 * @package Zdev
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define Core Theme Constants
 *
 * @since 1.0.0
 */
define( 'ZDEV_THEME_URI', get_template_directory_uri() );
define( 'ZDEV_THEME_DIR', get_template_directory() );
define( 'ZDEV_THEME_VERSION', wp_get_theme()->get( 'Version' ) );

/**
 * Main theme Class
 *
 * @since 1.0.0
 */
final class Zdev {

	/**
	 * Class Constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		/** Load all core theme function files */
		add_action( 'after_setup_theme', array( __CLASS__, 'include_functions' ), 1 );

		/** Setup theme => add_theme_support, register_nav_menus, load_theme_textdomain, etc */
		add_action( 'after_setup_theme', array( __CLASS__, 'theme_setup' ), 10 );

		/** Register sidebar widget areas */
		add_action( 'widgets_init', array( __CLASS__, 'register_sidebars' ) );

		/** Add async/defer attributes to enqueued / registered scripts. */
		add_filter( 'script_loader_tag', array( __CLASS__, 'filter_script_loader_tag' ), 10, 2 );

		/** Load theme CSS */
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'theme_css' ) );

		/** Load theme JS */
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'theme_javascript' ) );

		/** Load scripts in the WP admin */
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );

		/** Loads html5 shiv script */
		add_action( 'wp_head', array( __CLASS__, 'html5_shiv' ) );

		/** Add support for Elementor Pro locations */
		add_action( 'elementor/theme/register_locations', array( __CLASS__, 'register_elementor_locations' ) );

		/** Declare support for selective refreshing of widgets. */
		add_theme_support( 'customize-selective-refresh-widgets' );

		/** Remove WordPress emoji */
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		/** Remove query strings from static resources */
		add_filter( 'script_loader_src', array( __CLASS__, 'remove_script_version' ), 15, 1 );
		add_filter( 'style_loader_src', array( __CLASS__, 'remove_script_version' ), 15, 1 );

		/** Remove admin bar for non-admin users */
		if ( ! current_user_can( 'manage_options' ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
		}

	}

	/**
	 * Load all core theme function files
	 *
	 * @since 1.0.0
	 */
	public static function include_functions() {
		// phpcs:ignore require_once THUNDERWP_THEME_DIR . '/inc/jdf.php';
	}


	/**
	 * Theme Setup
	 *
	 * @since 1.0.0
	 */
	public static function theme_setup() {
		/** Load text domain */
		load_theme_textdomain( 'zdev', ZDEV_THEME_URI . '/languages' );

		/** Get globals */
		global $content_width;

		/** Set content width based on theme's default design */
		if ( ! isset( $content_width ) ) {
			$content_width = 1356;
		}

		/** Register navigation menus */
		register_nav_menus(
			array(
				'main-menu' => esc_html__( 'منوی سربرگ', 'zdev' ),
			)
		);

		/** Enable support for <title> tag */
		add_theme_support( 'title-tag' );

		/** Add default posts and comments RSS feed links to head */
		add_theme_support( 'automatic-feed-links' );

		/** Enable support for Post Thumbnails on posts and pages */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			)
		);

	}

	/**
	 * Registers sidebars
	 *
	 * @since 1.0.0
	 */
	public static function register_sidebars() {
		$heading = 'h2';
		$heading = apply_filters( 'zdev_sidebar_heading', $heading );

		/** Blog Sidebar */
		register_sidebar(
			array(
				'name'         => esc_html__( 'سایدبار وبلاگ', 'zdev' ),
				'id'           => 'sidebar_blog',
				'description'  => esc_html__( 'سایدبار وبلاگ', 'zdev' ),
				'before_title' => '<' . $heading . ' class="widget__title">',
				'after_title'  => '</' . $heading . '>',
			)
		);

	}

	/**
	 * Remove ?ver from scripts and styles url
	 *
	 * @param string $src script path.
	 * @since 1.0.0
	 */
	public static function remove_script_version( $src ) {
		$parts = explode( '?ver', $src );
		return $parts[0];
	}

	/**
	 * Adds async/defer attributes to enqueued / registered scripts.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @since 1.0.0
	 */
	public static function filter_script_loader_tag( $tag, $handle ) {
		foreach ( array( 'async', 'defer' ) as $attr ) {
			if ( ! wp_scripts()->get_data( $handle, $attr ) ) {
				continue;
			}
			// Prevent adding attribute when already added in #12009.
			if ( ! preg_match( ":\s$attr(=|>|\s):", $tag ) ) {
				$tag = preg_replace( ':(?=></script>):', " $attr", $tag, 1 );
			}
			// Only allow async or defer, not both.
			break;
		}
		return $tag;
	}

	/**
	 * Load scripts in the WP admin
	 *
	 * @since 1.0.0
	 */
	public static function admin_scripts() {
		wp_register_style( 'zdev-admin', ZDEV_THEME_URI . '/assets/dist/css/admin.min.css', false, ZDEV_THEME_VERSION, 'all' );
		wp_enqueue_style( 'zdev-admin' );
	}

	/**
	 * Register Frontend Stylesheets
	 *
	 * @since 1.0.0
	 */
	public static function theme_css() {
		/** Register Theme Main Stylesheet */
		wp_register_style( 'zdev-style', ZDEV_THEME_URI . '/assets/dist/css/main.min.css', false, ZDEV_THEME_VERSION, 'all' );
		wp_enqueue_style( 'zdev-style' );
	}

	/**
	 * Register All Frontend JS
	 *
	 * @since 1.0.0
	 */
	public static function theme_javascript() {
		/** Comments Reply */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/** Main Theme Javascript */
		wp_register_script( 'zdev-javascript', ZDEV_THEME_URI . '/assets/dist/js/main.min.js', false, ZDEV_THEME_VERSION, true );
		wp_enqueue_script( 'zdev-javascript' );

		/** Add defer data to theme registred scripts */
		self::add_defer_data_scripts();
	}

	/**
	 *
	 * Add defer data to theme registered scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_defer_data_scripts() {
		$handles = array(
			'zdev-javascript',
		);
		$handles = apply_filters( 'zdev_defer_scripts', $handles );

		foreach ( $handles as $handle ) {
			wp_script_add_data( $handle, 'defer', true );
		}
	}

	/**
	 * Load HTML5 dependencies for IE8
	 *
	 * @since 1.0.0
	 */
	public static function html5_shiv() {
		wp_register_script( 'html5shiv', ZDEV_THEME_URI . '/assets/dist/js/html5shiv.min.js', false, '3.7.3', true );
		wp_enqueue_script( 'html5shiv' );
		wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );
	}

	/**
	 * Add support for Elementor Pro locations
	 *
	 * @param object $elementor_theme_manager Elementor Theme Manager Object.
	 * @since 1.0.0
	 */
	public static function register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_all_core_location();
	}
}
new Zdev();
