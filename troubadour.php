<?php

/**
* Plugin Name: Troubadour
* Plugin URI: 
* Description: A plugin for posting and organizing collections of stories with non-user authors on WordPress.
* Version: 0.1.1
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
		'query_var'							=> 'story',
		'capability_type'       => 'page',
		'menu_icon'   => 'dashicons-book-alt',
		'rewrite' => array( 'slug' => 'stories', 'with_front' => true ),
	);

	register_post_type( 'trbdr_story', $args );
	flush_rewrite_rules();

}

add_action( 'init', 'custom_post_type', 0 );


// Writer Info

// Add meta box
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


// Render meta box html
function show_writer_meta_box() {
  global $post;  
  $meta = get_post_meta($post->ID, 'story_writer_name', true);
	
  // Use nonce for verification  
	echo '<input type="hidden" name="writer_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
   
 	echo '<table class="form-table">';   
    // begin a table row with
    echo '<tr>
    	<th><label for="story_writer_name">Writer Name</label></th>
      <td><input type="text" name="story_writer_name" id="story_writer_name" value="' . $meta . '" /></td>
      </tr>';
          
  echo '</table>';

}

// Save meta box data

add_action( 'save_post', 'save_story_writer_meta_fields', 10, 2 );

function save_story_writer_meta_fields( $post_id, $post ) {
    if ( $post->post_type == 'trbdr_story' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['story_writer_name'] ) && $_POST['story_writer_name'] != '' ) {
            update_post_meta( $post_id, 'story_writer_name', $_POST['story_writer_name'] );
        }
    }
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


// Display audio meta html
// @todo: make keys specific to the audio service instead of integers

function show_audio_meta_box() {
  global $post;
  $meta = ( get_post_meta($post->ID, 'story_audio_info', true) ) ? get_post_meta( $post->ID, 'story_audio_info', true ) : array();  

	echo '<input type="hidden" name="audio_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';  
   
  echo '<table class="form-table">';
  
  echo '<tr>
    <th><label for="story_audio_info_soundcloud">Soundcloud</label></th>
    <td><textarea name="story_audio_info[]" id="story_audio_info_soundcloud" cols="60" rows="4">' . $meta[0] . '</textarea>
    <span class="description">Paste the embed code here.</span></td>
    </tr>';
  
  echo '<tr>
    <th><label for="story_audio_info_youtube">YouTube</label></th>
    <td><textarea name="story_audio_info[]" id="story_audio_info_youtube" cols="60" rows="4">' . $meta[1] . '</textarea>
    <span class="description">Paste the embed code here.</span></td>
    </tr>';
            
  echo '</table>';
}

// Save meta box data

add_action( 'save_post', 'save_story_audio_info_fields', 10, 2 );

function save_story_audio_info_fields( $post_id, $post ) {
    if ( $post->post_type == 'trbdr_story' ) {
        // Store data in post meta table if present in post data
        if ( isset( $_POST['story_audio_info'] ) && $_POST['story_audio_info'] != '' ) {
            update_post_meta( $post_id, 'story_audio_info', $_POST['story_audio_info'] );
        }
    }
}


// Add single story template

function get_story_template($single_template) {
  
  global $post;

	if ($post->post_type == 'trbdr_story') {
		$single_template = dirname( __FILE__ ) . '/templates/single-story.php';
	}
	return $single_template;
}

add_filter( 'single_template', 'get_story_template' );

?>