<?php
/**
 * Plugin Name: Insert Headers and Footers Code - HT Script
 * Description: This plugin allow allow you to insert script in headers and footers
 * Version: 1.1.2
 * Author: HasThemes
 * Author URI: https://hasthemes.com/
 * Text Domain: ihafs
 * Domain Path: /languages
*/

// define path
define( 'IHAFS_VERSION', '1.1.2' );
define( 'IHAFS_URI', plugins_url('', __FILE__) );
define( 'IHAFS_DIR', dirname( __FILE__ ) );

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$ihafs_pro_active = false;
if(is_plugin_active( 'insert-headers-and-footers-script-pro/init.php' )){
	$ihafs_pro_active = true;
}

// include all files
include_once( IHAFS_DIR. '/inc/custom-posts.php');
include_once( IHAFS_DIR. '/admin/cmb2/init.php');
include_once( IHAFS_DIR. '/admin/functions.php');

if(is_admin()){
	include_once( IHAFS_DIR. '/admin/recommended-plugins/recommendations.php');
	include_once( IHAFS_DIR. '/admin/class-diagnostic-data.php');
}
add_action('init', function() {
	if(is_admin()){
		include_once( IHAFS_DIR. '/admin/class-rating-notice.php');
	}
});
add_action('admin_head', function() {
	if(get_option('ihafs_rating_already_rated', false)) {
		return;
	}
	$message = '<div class="hastech-review-notice-wrap">
				<div class="hastech-rating-notice-logo">
					<img src="' . esc_url(IHAFS_URI . "/assets/images/logo.png") . '" alt="'.esc_attr__('HT Script','ihafs').'" style="max-width:110px"/>
				</div>
				<div class="hastech-review-notice-content">
					<h3>'.esc_html__('Thank you for choosing HT Script to insert script into header and footer of your site!','ihafs').'</h3>
					<p>'.esc_html__('Would you mind doing us a huge favor by providing your feedback on WordPress? Your support helps us spread the word and greatly boosts our motivation.','ihafs').'</p>
					<div class="hastech-review-notice-action">
						<a href="https://wordpress.org/support/plugin/insert-headers-and-footers-script/reviews/?filter=5#new-post" class="hastech-review-notice button-primary" target="_blank">'.esc_html__('Ok, you deserve it!','ihafs').'</a>
						<span class="dashicons dashicons-calendar"></span>
						<a href="#" class="hastech-notice-close swatchly-review-notice">'.esc_html__('Maybe Later','ihafs').'</a>
						<span class="dashicons dashicons-smiley"></span>
						<a href="#" data-already-did="yes" class="hastech-notice-close swatchly-review-notice">'.esc_html__('I already did','ihafs').'</a>
					</div>
				</div>
			</div>';

	\HTScript_Notices::set_notice(
		[
			'id'          => 'ihafs-rating-notice',
			'type'        => 'info',
			'dismissible' => true,
			'message_type' => 'html',
			'message'     => $message,
			'display_after'  => ( 14 * DAY_IN_SECONDS ),
			'expire_time' => ( 24 * DAY_IN_SECONDS ),
			'close_by'    => 'transient'
		]
	);
});

if(!$ihafs_pro_active){
	add_action( 'cmb2_admin_init', 'ihafs_add_metabox' );
	function ihafs_add_metabox(){
		include_once( IHAFS_DIR. '/inc/metabox-multiple-select.php');
	    include_once( IHAFS_DIR. '/inc/metabox.php');
	}
}

register_activation_hook(__FILE__, function() {
	$installed = get_option( 'ihafs_installed' );
	if ( ! $installed ) {
		update_option( 'ihafs_installed', time() );
	}
});

// define text domain path
add_action( 'init', 'ihafs_load_textdomain' );
function ihafs_load_textdomain() {
    load_plugin_textdomain( 'ihafs', false, basename(IHAFS_URI) . '/languages/' );
}
add_action( 'admin_menu', function() {
	add_submenu_page(
		'edit.php?post_type=ihafs_script', 
		'Upgrade to Pro',
		'Upgrade to Pro',
		'manage_options', 
		'https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/?utm_source=admin&utm_medium=mainmenu&utm_campaign=free#pricing',
	);
}, 1000 );
add_action('admin_footer', function() {
	printf( '<style>%s</style>', '#adminmenu #menu-posts-ihafs_script a.ihafs-upgrade-pro { font-weight: 600; background-color: #ff6e30; color: #ffffff; text-align: center; margin-top: 8px;}' );
	printf( '<script>%s</script>', '(function ($) {
		$("#menu-posts-ihafs_script .wp-submenu a").each(function() {
			if($(this)[0].href === "https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/?utm_source=admin&utm_medium=mainmenu&utm_campaign=free#pricing") {
				$(this).addClass("ihafs-upgrade-pro").attr("target", "_blank");
			}
		})
	})(jQuery);' );
});

//add settings in plugin action
add_filter('plugin_action_links_'.plugin_basename(__FILE__),function($links){

    $link = sprintf("<a href='%s'>%s</a>",esc_url(admin_url('edit.php?post_type=ihafs_script')),__('Settings','ihafs'));

    array_unshift($links,$link);

    return $links;

});

// admin enqueue scripts
add_action( 'admin_enqueue_scripts','ihafs_enqueue_scripts');
function  ihafs_enqueue_scripts( $hook ){
    global $ihafs_pro_active;

    if(!$ihafs_pro_active){
    	//enqueue styles
    	wp_enqueue_style( 'ihafs-admin', IHAFS_URI.'/admin/css/admin.css', [], IHAFS_VERSION);
    	wp_enqueue_style( 'wp-jquery-ui-dialog');

    	//enqueue js
    	wp_enqueue_script( 'jquery-ui-dialog');
    	wp_enqueue_script( 'ihafs-admin', IHAFS_URI.'/admin/js/admin.js', array('jquery'), IHAFS_VERSION, true);
    } 
}

// upgrade notice
add_action('admin_footer', 'ihafs_upgrade_popup');
function ihafs_upgrade_popup(){
	?>
	<div id="ihafs_dialog" title="<?php echo esc_attr__( 'Go Premium!', 'ihafs' ); ?>" class="ihafs_dialog" style="display: none;">
		<div class="dashicons-before dashicons-warning"></div>
		<h3><?php echo esc_html__( 'Upgrade to premium version to unlock this feature!', 'ihafs' ) ?></h3>
		<a class="buy_now" target="_blank" href="https://hasthemes.com/plugins/insert-headers-and-footers-code-ht-script/"><?php echo esc_html__('Buy Now', 'ihafs'); ?></a>
	</div>
	<?php
}

// load header scripts
add_action( 'wp_head', 'ihafs_load_script_to_header' );
function ihafs_load_script_to_header(){
	$args = array(
		'post_type' => 'ihafs_script',
		'meta_query' => array(
			array(
				'key'     => '_ihafs_condition',
				'value'   => 'in_header',
				'compare' => 'IN',
			),
		),
	);

	$current_page_id = get_the_ID();

	$query = new WP_Query($args);

	while($query->have_posts()){
	    $query->the_post();

	    $post_id = get_the_id();
	    echo ihafs_output_script($post_id, $current_page_id); // WPCS: XSS ok.
	}
	wp_reset_postdata();
}

// load footer scripts
add_action( 'wp_footer', 'ihafs_load_script_to_footer' );
function ihafs_load_script_to_footer(){
	$args = array(
		'post_type' => 'ihafs_script',
		'meta_query' => array(
			array(
				'key'     => '_ihafs_condition',
				'value'   => 'in_footer',
				'compare' => 'IN',
			),
		),
	);

	$current_page_id = get_the_ID();

	$query = new WP_Query($args);

	while($query->have_posts()){
	    $query->the_post();

	    $post_id = get_the_id();
	    echo ihafs_output_script($post_id, $current_page_id); // WPCS: XSS ok.
	}
	wp_reset_postdata();
}

// load footer scripts
add_action( 'wp_body_open', 'ihafs_load_script_to_after_body', -1 );
function ihafs_load_script_to_after_body(){
	$args = array(
		'post_type' => 'ihafs_script',
		'meta_query' => array(
			array(
				'key'     => '_ihafs_condition',
				'value'   => 'after_body',
				'compare' => 'IN',
			),
		),
	);

	$current_page_id = get_the_ID();

	$query = new WP_Query($args);

	while($query->have_posts()){
	    $query->the_post();

	    $post_id = get_the_id();
	    echo ihafs_output_script($post_id, $current_page_id); // WPCS: XSS ok.
	}
	wp_reset_postdata();
}

// after check the conditions return script output 
function ihafs_output_script($post_id, $current_page_id){
	$status = get_post_meta($post_id, '_ihafs_status', true);
	$snippet = get_post_meta($post_id, '_ihafs_code', true);

	if($status != 'active' || is_admin()){
		return;
	}

	if(defined('IHAFS_PRO_URI')){
		global $wp_query;

		$show_on = get_post_meta($post_id, '_ihafs_show_on', true);
		$pages_list = get_post_meta($post_id, '_ihafs_pages_list', true);
		$posts_list = get_post_meta($post_id, '_ihafs_posts_list', true);
		$categories_list = get_post_meta($post_id, '_ihafs_categories_list', true);
		$tags_list = get_post_meta($post_id, '_ihafs_tags_list', true);

		is_archive() ? $current_category_id = $wp_query->get_queried_object_id() : $current_category_id = '';
		is_archive() ? $current_tag_id = $wp_query->get_queried_object_id() : $current_tag_id = '';

		if($show_on == 'only_pages' && (empty($pages_list) || !in_array($current_page_id, $pages_list)) ){
			return;
		}

		if($show_on == 'only_posts' && (empty($posts_list) || !in_array($current_page_id, $posts_list)) ){
			return;
		}

		if($show_on == 'only_categories' && (empty($categories_list) || !in_array($current_category_id, $categories_list)) ){
			return;
		}

		if($show_on == 'only_tags' && (empty($tags_list) || !in_array($current_tag_id, $tags_list)) ){
			return;
		}
	}

	return wp_unslash($snippet);
}