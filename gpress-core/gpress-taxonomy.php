<?php

function gpress_taxonomies() {
	
	$place_tax_plural_name = __( 'Types of Places', 'gpress' );
	$place_tax_singular_name = __( 'Type of Place', 'gpress' );
	$place_tax_plural_taxonomy = __( 'places', 'gpress' );
	$places_taxonomy = __( 'place', 'gpress' );

	// Hierarchical Taxonomy for Places
	$labels = array(
		'name' => _x( $place_tax_plural_name, 'taxonomy general name' ),
		'singular_name' => _x( $place_tax_singular_name, 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Types of Places', 'gpress' ),
		'all_items' => __( 'All Types of Places', 'gpress' ),
		'parent_item' => __( 'Parent Types of Places', 'gpress' ),
		'parent_item_colon' => __( 'Parent Type of Place:', 'gpress' ),
		'edit_item' => __( 'Edit Type of Place', 'gpress' ), 
		'update_item' => __( 'Update Type of Place', 'gpress' ),
		'add_new_item' => __( 'Add New Type of Place', 'gpress' ),
		'new_item_name' => __( 'New Type of Place', 'gpress' )
  	); 	
  	register_taxonomy($place_tax_plural_taxonomy,array($places_taxonomy), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $place_tax_plural_taxonomy )
  	));
	
	$place_tag_plural_name = __( 'Place Tags', 'gpress' );
	$place_tag_singular_name = __( 'Place Tag', 'gpress' );
	$place_tag_plural_taxonomy = __( 'placed', 'gpress' );

	// Non-Hierarchical Taxonomy for Places
	$labels = array(
		'name' => _x( $place_tag_plural_name, 'taxonomy general name' ),
		'singular_name' => _x( $place_tag_singular_name, 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Place Tags', 'gpress' ),
		'popular_items' => __( 'Popular Place Tags', 'gpress' ),
		'all_items' => __( 'All Place Tags', 'gpress' ),
		'parent_item' => null,
		'parent_item_colon' => null,
		'edit_item' => __( 'Edit Place Tag', 'gpress' ), 
		'update_item' => __( 'Update Place Tag', 'gpress' ),
		'add_new_item' => __( 'Add New Place Tag', 'gpress' ),
		'new_item_name' => __( 'New Place Tag Name', 'gpress' ),
		'separate_items_with_commas' => __( 'Separate place tags with commas', 'gpress' ),
		'add_or_remove_items' => __( 'Add or remove place tags', 'gpress' ),
		'choose_from_most_used' => __( 'Choose from the most used place tags', 'gpress' )
	); 
	register_taxonomy($place_tag_plural_taxonomy,$places_taxonomy,array(
		'hierarchical' => false,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => $place_tag_plural_taxonomy ),
	));
	
}

?>