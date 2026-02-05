<?php
/**
 *
 * The framework's functions and definitions
 */
update_option( 'woodmart_is_activated', '1' );
define( 'WOODMART_THEME_DIR', get_template_directory_uri() );
define( 'WOODMART_THEMEROOT', get_template_directory() );
define( 'WOODMART_IMAGES', WOODMART_THEME_DIR . '/images' );
define( 'WOODMART_SCRIPTS', WOODMART_THEME_DIR . '/js' );
define( 'WOODMART_STYLES', WOODMART_THEME_DIR . '/css' );
define( 'WOODMART_FRAMEWORK', '/inc' );
define( 'WOODMART_DUMMY', WOODMART_THEME_DIR . '/inc/dummy-content' );
define( 'WOODMART_CLASSES', WOODMART_THEMEROOT . '/inc/classes' );
define( 'WOODMART_CONFIGS', WOODMART_THEMEROOT . '/inc/configs' );
define( 'WOODMART_HEADER_BUILDER', WOODMART_THEME_DIR . '/inc/modules/header-builder' );
define( 'WOODMART_ASSETS', WOODMART_THEME_DIR . '/inc/admin/assets' );
define( 'WOODMART_ASSETS_IMAGES', WOODMART_ASSETS . '/images' );
define( 'WOODMART_API_URL', 'https://xtemos.com/wp-json/xts/v1/' );
define( 'WOODMART_DEMO_URL', 'https://woodmart.xtemos.com/' );
define( 'WOODMART_PLUGINS_URL', WOODMART_DEMO_URL . 'plugins/' );
define( 'WOODMART_DUMMY_URL', WOODMART_DEMO_URL . 'dummy-content-new/' );
define( 'WOODMART_TOOLTIP_URL', WOODMART_DEMO_URL . 'theme-settings-tooltips/' );
define( 'WOODMART_SLUG', 'woodmart' );
define( 'WOODMART_CORE_VERSION', '1.1.2' );
define( 'WOODMART_WPB_CSS_VERSION', '1.0.2' );

if ( ! function_exists( 'woodmart_load_classes' ) ) {
	function woodmart_load_classes() {
		$classes = array(
			'class-singleton.php',
			'class-api.php',
			'class-config.php',
			'class-layout.php',
			'class-autoupdates.php',
			'class-activation.php',
			'class-notices.php',
			'class-theme.php',
			'class-registry.php',
		);

		foreach ( $classes as $class ) {
			require WOODMART_CLASSES . DIRECTORY_SEPARATOR . $class;
		}
	}
}

woodmart_load_classes();

new XTS\Theme();

define( 'WOODMART_VERSION', woodmart_get_theme_info( 'Version' ) );

function custom_email_login($user, $username, $password) {
    if (is_email($username)) {
        $user_obj = get_user_by('email', $username);
        if ($user_obj) $username = $user_obj->user_login;
    }
    return wp_authenticate_username_password(null, $username, $password);
}
remove_filter('authenticate', 'wp_authenticate_username_password', 20);
add_filter('authenticate', 'custom_email_login', 20, 3);


// ================================
// 


add_filter( 'get_terms', 'custom_sort_woocommerce_attributes', 10, 3 );
function custom_sort_woocommerce_attributes( $terms, $taxonomies, $args ) {

    // Sirf admin aur attributes ke liye
    if ( ! is_admin() ) {
        return $terms;
    }

    if ( ! in_array( 'pa_size', $taxonomies ) &&
         ! in_array( 'pa_color', $taxonomies ) &&
         ! in_array( 'pa_all-size', $taxonomies ) ) {
        return $terms;
    }

    // Desired order (slug ke hisaab se)
    $priority = array(
        'pa_size',
        'pa_color',
        'pa_all-size',
    );

    usort( $terms, function ( $a, $b ) use ( $priority ) {

        $a_pos = array_search( $a->taxonomy, $priority );
        $b_pos = array_search( $b->taxonomy, $priority );

        if ( $a_pos === false ) $a_pos = 999;
        if ( $b_pos === false ) $b_pos = 999;

        return $a_pos - $b_pos;
    });

    return $terms;
}
