<?php
/**
 * Sanitize script field - require unfiltered_html capability
 * Security fix for CVE-2025-12112
 *
 * @param mixed $value The unsanitized value
 * @param array $field_args Array of field parameters
 * @param object $field The field object
 * @return mixed Sanitized value or empty string if capability check fails
 */
function ihafs_sanitize_script_field( $value, $field_args, $field ) {
	// Check if user has unfiltered_html capability
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		// Return empty string - script will not be saved
		return '';
	}

	// User has proper capability, allow the value
	return $value;
}

function ihafs_post_list_arr($post_type = 'post', $per_page = 10){
	$arr = array();

	$args = array(
		'post_type' => $post_type,
		'posts_per_page'	=> $per_page,
		'no_found_rows' => true,
		'fields' => 'ids',
	);

	$query = new WP_Query($args);
	if ($query->have_posts()) {
		$post_ids = $query->posts;
		foreach ($post_ids as $post_id) {
			$arr[$post_id] = get_the_title($post_id);
		}
	} 
	wp_reset_postdata();

	return $arr;
}

add_action('cmb2_meta_boxes','ihafs_meta_boxes');

if(!function_exists('ihafs_meta_boxes')){
	function ihafs_meta_boxes(){

		$prefix = '_ihafs_';

		if( function_exists('wp_body_open') ){
			$where_to_show_options = array(
				'in_header'	=> 	__('Header - The Script will be placed within the &lt;head&gt; section. ', 'ihafs'),
				'after_body' => 	__('After Body - The script will be placed just below the &lt;body&gt; start tag. <br><span>This option may not be compatible with your current theme.</span> ', 'ihafs'),
				'in_footer'	=>  __('Footer - The Script will be placed near the bottom of the &lt;body&gt; section.', 'ihafs')
			);
		} else{
			$where_to_show_options = array(
				'in_header'	=> 	__('Header - The Script will be placed within the &lt;head&gt; section. ', 'ihafs'),
				'in_footer'	=>  __('Footer - The Script will be placed near the bottom of the &lt;body&gt; section.', 'ihafs')
			);
		}

		$meta_box = new_cmb2_box( array(
			'id'           		 => $prefix . 'options',
			'title'        		 => __( 'Headers and Footers Script Options', 'ihafs' ),
			'object_types' 		 => array('ihafs_script'),
			'context'      		 => 'normal',
			'priority'     		 => 'high',
			'show_names'         => true,
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'code',
			'name'        		 => __( 'Script', 'ihafs' ),
			'type'        		 => 'textarea_code',
			'description'		=> __('Put the Script / Style you want to load in header/footer', 'ihafs'),
			'sanitization_cb'    => 'ihafs_sanitize_script_field',
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'status',
			'name'        		 => __( 'Status', 'ihafs' ),
			'type'        		 => 'select',
			'options'		     => array(
				'active'	=> 		__('Active', 'ihafs'),
				'inactive'	=> 		__('Inactive', 'ihafs'),
			),
			'description'		=> __('Active/Inactive the script', 'ihafs')
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'condition',
			'name'        		 => __( 'Condition', 'ihafs' ),
			'type'        		 => 'radio',
			'options'     		 => $where_to_show_options,
			'default'     		 => 'in_header',
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'show_on',
			'name'        		 => __( 'Show On', 'ihafs' ),
			'type'        		 => 'radio_inline',
			'options'     		 => array(
				'full_website'	=> 	__('Full Website', 'ihafs'),
				'only_home'		=>  __('Only Homepage <span class="pro">(Pro)</span>', 'ihafs'),
				'only_pages'		=>  __('Only Pages <span class="pro">(Pro)</span>', 'ihafs'),
				'only_posts'		=>  __('Only Posts <span class="pro">(Pro)</span>', 'ihafs'),
				'only_categories'   =>  __('Only Categories <span class="pro">(Pro)</span>', 'ihafs'),
				'only_tags'			=>  __('Only Tags  <span class="pro">(Pro)</span>', 'ihafs'),
			),
			'default'     		 => 'full_website',
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'exclude_pages',
			'name'        		 => __( 'Exclude Pages <span class="pro">(Pro)</span>', 'ihafs' ),
			'type'        		 => 'select_multiple',
			'options'     		 =>  ihafs_post_list_arr('page'),
			'description'		 => __('Select the pages you want to exclude', 'ihafs'),
		) );

		$meta_box->add_field( array(
			'id'                 => $prefix.'exclude_posts',
			'name'        		 => __( 'Exclude Posts <span class="pro">(Pro)</span>', 'ihafs' ),
			'type'        		 => 'select_multiple',
			'options'     		 =>  ihafs_post_list_arr('post'),
			'description'		 => __('Select the posts you want to exclude', 'ihafs'),
		) );
	}
}