<?php
/**
 * @package XV Planeta
 */

class XV_Planet_Infrastructure {
	public function __construct() {
		add_action( 'init', array( $this, 'register_blog_cpt' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_acf_fields' ) );
		add_action( 'add_meta_boxes', array( $this, 'remove_yoast_metabox' ), 11 );		
	}
	
	public function register_blog_cpt() {
		$labels = array(
			'name'               => 'Blogs',
			'singular_name'      => 'Blog',
			'menu_name'          => 'Blogs',
			'name_admin_bar'     => 'Blog',
			'add_new'            => 'Afegeix-ne un',
			'add_new_item'       => 'Afegeix un blog',
			'new_item'           => 'Blog nou',
			'edit_item'          => 'Edita el blog',
			'view_item'          => 'Visualitza el blog',
			'all_items'          => 'Tots els blogs',
			'search_items'       => 'Cerca els blogs',
			'not_found'          => 'No s\'han trobat blogs.',
			'not_found_in_trash' => 'No s\'han trobat blogs a la paperera.'
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Gestiona els blogs del Planeta',
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'blog' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'author', 'thumbnail', ),
			'menu_icon'          => 'dashicons-rss'
		);

		register_post_type( 'blog', $args );
	}
			
	function remove_yoast_metabox() {
		remove_meta_box( 'wpseo_meta', 'blog', 'normal' );
	}
	
	public function load_acf_fields( $paths ) {
		$paths;

		$paths[] = XV_PLANETA_PATH . 'conf/';
		
		return $paths;		
	}
}
