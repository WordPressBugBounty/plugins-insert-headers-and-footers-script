<?php

/*=======================================================
*    Register Post type
* =======================================================*/
function ihafs_custom_posts() {
	// Headers and Footer Scripts
	$labels = array(
		'name'                  => _x( 'All Scripts', 'All Scripts', 'ihafs' ),
		'singular_name'         => _x( 'Script', 'Script', 'ihafs' ),
		'menu_name'             => __( 'HT Script', 'ihafs' ),
		'name_admin_bar'        => __( 'ihafs', 'ihafs' ),
		'archives'              => __( 'Script Archives', 'ihafs' ),
		'parent_item_colon'     => __( 'Parent Script:', 'ihafs' ),
		'all_items'             => __( 'All Scripts', 'ihafs' ),
		'add_new_item'          => __( 'Add New Script', 'ihafs' ),
		'add_new'               => __( 'Add New Script', 'ihafs' ),
		'new_item'              => __( 'New Script', 'ihafs' ),
		'edit_item'             => __( 'Edit Script', 'ihafs' ),
		'update_item'           => __( 'Update Script', 'ihafs' ),
		'view_item'             => __( 'View Script', 'ihafs' ),
		'search_items'          => __( 'Search Script', 'ihafs' ),
		'not_found'             => __( 'Not found', 'ihafs' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ihafs' ),
		'featured_image'        => __( 'Featured Image', 'ihafs' ),
		'set_featured_image'    => __( 'Set featured image', 'ihafs' ),
		'remove_featured_image' => __( 'Remove featured image', 'ihafs' ),
		'use_featured_image'    => __( 'Use as featured image', 'ihafs' ),
		'insert_into_item'      => __( 'Insert into item', 'ihafs' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'ihafs' ),
		'items_list'            => __( 'Scripts list', 'ihafs' ),
		'items_list_navigation' => __( 'Scripts list navigation', 'ihafs' ),
		'filter_items_list'     => __( 'Filter items list', 'ihafs' ),
	);
	$args = array(
		'label'                 => __( 'Scripts', 'ihafs' ),
		'labels'                => $labels,
		'supports'              => array('title' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-edit',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => array('ihafs_script', 'ihafs_scripts'),
		'capabilities'          => array(
			'edit_post'              => 'edit_ihafs_script',
			'read_post'              => 'read_ihafs_script',
			'delete_post'            => 'delete_ihafs_script',
			'edit_posts'             => 'edit_ihafs_scripts',
			'edit_others_posts'      => 'edit_others_ihafs_scripts',
			'publish_posts'          => 'publish_ihafs_scripts',
			'read_private_posts'     => 'read_private_ihafs_scripts',
			'delete_posts'           => 'delete_ihafs_scripts',
			'delete_private_posts'   => 'delete_private_ihafs_scripts',
			'delete_published_posts' => 'delete_published_ihafs_scripts',
			'delete_others_posts'    => 'delete_others_ihafs_scripts',
			'edit_private_posts'     => 'edit_private_ihafs_scripts',
			'edit_published_posts'   => 'edit_published_ihafs_scripts',
			'create_posts'           => 'edit_ihafs_scripts',
		),
		'map_meta_cap'          => true,
	);
	register_post_type( 'ihafs_script', $args );

}
add_action( 'init', 'ihafs_custom_posts');

/**
 * Grant custom ihafs_script capabilities to users with unfiltered_html
 * Security fix for CVE-2025-12112
 *
 * This filter ensures that only users with unfiltered_html capability
 * can manage scripts, preventing Authors from accessing the feature.
 *
 * The user_has_cap filter dynamically grants ihafs_script capabilities to users who have unfiltered_html:
 * - Only Editors and Administrators can manage scripts (they have unfiltered_html by default)
 * - Authors, Contributors, and Subscribers cannot access script management
 * - WordPress handles all meta-to-primitive capability mapping, then we grant access based on unfiltered_html
 */
add_filter('user_has_cap', function($allcaps, $caps, $args, $user) {
	// Check if any of the required capabilities are ihafs_script capabilities
	$checking_ihafs = false;
	foreach ($caps as $cap) {
		if (strpos($cap, 'ihafs_script') !== false) {
			$checking_ihafs = true;
			break;
		}
	}

	// If we're checking ihafs_script capabilities and user has unfiltered_html
	if ($checking_ihafs && isset($allcaps['unfiltered_html']) && $allcaps['unfiltered_html']) {
		// Grant all required ihafs_script capabilities
		foreach ($caps as $cap) {
			if (strpos($cap, 'ihafs_script') !== false) {
				$allcaps[$cap] = true;
			}
		}
	}

	return $allcaps;
}, 10, 4);