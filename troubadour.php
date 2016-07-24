<?php

/**
* Plugin Name: Troubadour
* Plugin URI: 
* Description: A plugin for posting and organizing collections of stories with non-user authors on WordPress.
* Version: 0.1.0
* Author: Beck Kramer
* Author URI: http://www.beckkramer.com
* License: GPL2
*/


// Register Custom Post Type
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Stories', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Story', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Stories', 'text_domain' ),
		'name_admin_bar'        => __( 'Story', 'text_domain' ),
		'archives'              => __( 'Story Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Story:', 'text_domain' ),
		'all_items'             => __( 'All Stories', 'text_domain' ),
		'add_new_item'          => __( 'Add New Story', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Story', 'text_domain' ),
		'edit_item'             => __( 'Edit Story', 'text_domain' ),
		'update_item'           => __( 'Update Story', 'text_domain' ),
		'view_item'             => __( 'View Story', 'text_domain' ),
		'search_items'          => __( 'Search Story', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into story', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Stories list', 'text_domain' ),
		'items_list_navigation' => __( 'Stories list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter stories list', 'text_domain' ),
	);

	$args = array(
		'label'                 => __( 'Story', 'text_domain' ),
		'description'           => __( 'Story Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'   => 'dashicons-book-alt',
		'rewrite' => array( 'slug' => 'stories', 'with_front' => true ),
	);

	register_post_type( 'trbdr_story', $args );

	flush_rewrite_rules();

}

add_action( 'init', 'custom_post_type', 0 );


// Add Writer Info Meta Box

function add_writer_meta_box() {
    add_meta_box(
        'trbdr_writer_meta_box', // $id
        'Story Writer Information', // $title
        'show_writer_meta_box', // $callback
        'trbdr_story', // $page
        'normal', // $context
        'high'); // $priority
}

add_action('add_meta_boxes', 'add_writer_meta_box');

function show_writer_meta_box() {
    global $post;  
    $meta = get_post_meta($post->ID, 'writer_name', true);  
	
    // Use nonce for verification  
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
   
   echo '<table class="form-table">';   
        // begin a table row with
        echo '<tr>
            <th><label for="writer_name">Writer Name</label></th>
            <td><input type="text" name="writer_name" id="writer_name" value="' . $meta . '" /"></td>
            </tr>';
            
    echo '</table>';
}

// Add Audio Meta Box

function add_audio_meta_box() {
    add_meta_box(
        'trbdr_audio_meta_box', // $id
        'Audio Information', // $title
        'show_audio_meta_box', // $callback
        'trbdr_story', // $page
        'normal', // $context
        'low'); // $priority
}

add_action('add_meta_boxes', 'add_audio_meta_box');

function show_audio_meta_box() {
  global $post;  
  $meta = get_post_meta($post->ID, 'audio_info', true);  

	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
   
  echo '<table class="form-table">';
  
  echo '<tr>
    <th><label for="audio_info_soundcloud">Soundcloud</label></th>
    <td><textarea name="audio_info[\'soundcloud\']" id="audio_info_soundcloud" cols="60" rows="4">' . $meta . '</textarea>
    <span class="description">Paste the embed code here.</span></td>
    </tr>';
  
  echo '<tr>
    <th><label for="audio_info_youtube">YouTube</label></th>
    <td><textarea name="audio_info[\'youtube\']" id="audio_info_youtube" cols="60" rows="4">' . $meta . '</textarea>
    <span class="description">Paste the embed code here.</span></td>
    </tr>';
            
  echo '</table>';
}

?>