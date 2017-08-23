<?php

namespace TSG\WordPress\Plugin;

class Top_Trumps {
	/**
	 * Custom post type name
	 */
	const CPT_NAME = 'fttptr_card';

	/**
	 * Register custom post type
	 */
	public function register_post_type()
	{
		$labels = array(
			'name'               => _x( 'Cards', 'Post Type General Name', 'tsg_top_trumps' ),
			'singular_name'      => _x( 'Card', 'Post Type Singular Name', 'tsg_top_trumps' ),
			'menu_name'          => __( 'Cards', 'tsg_top_trumps' ),
			'name_admin_bar'     => __( 'Card', 'tsg_top_trumps' ),
			'all_items'          => __( 'All Cards', 'tsg_top_trumps' ),
			'add_new_item'       => __( 'Add New Card', 'tsg_top_trumps' ),
			'new_item'           => __( 'New Card', 'tsg_top_trumps' ),
			'edit_item'          => __( 'Edit Card', 'tsg_top_trumps' ),
			'update_item'        => __( 'Update Card', 'tsg_top_trumps' ),
			'view_item'          => __( 'View Card', 'tsg_top_trumps' ),
			'view_items'         => __( 'View Cards', 'tsg_top_trumps' ),
			'search_items'       => __( 'Search Card', 'tsg_top_trumps' ),
			'not_found'          => __( 'No cards found', 'tsg_top_trumps' ),
			'not_found_in_trash' => __( 'No cards found in Trash', 'tsg_top_trumps' ),
		);

		$args = array(
			'label'               => __( 'Card', 'tsg_top_trumps' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-index-card',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => false,
		);

		register_post_type( self::CPT_NAME, $args );
	}
}