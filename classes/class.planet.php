<?php
/**
 * @package XV_Planeta
 */


include( XV_PLANETA_PATH . 'classes/class.infrastructure.php' );
include( XV_PLANETA_PATH . 'classes/class.config.php' );


class XV_Planet {
	
	private $config_list = false;
	private $config_hash = false;
	
	private $max_entries_per_blog = 2;	
	
	public function __construct() {
		new XV_Planet_Infrastructure();
	}
	
	public function get_user_list() {
		return $this->get_config_list();
	}
	
	public function get_feed( $limit_blog = true) {
	
		$raw_feed = $this->get_raw_feed();
		
		$limited_feed = $this->get_limited_feed( $raw_feed, $limit_blog ? $this->max_entries_per_blog : false );
		
		return $this->prepare_items( $limited_feed );
	}
	
	private function prepare_items( $limited_feed ) {
		
		$new_feed = array();
		
		foreach( $limited_feed as $item ) {
			
			$feed_config = $this->get_feed_config( $item->feed->feed_url );
			
			$new_feed[] = array(
				'entry_title' => $item->get_title(),
				'blog_title'  => $item->feed->get_title(),
				'date'		  => $item->get_date(),
				'image'		  => $feed_config['image'],
				'html'		  => $item->get_content()
			);			
		}
		
		return $new_feed;
	}
	
	private function get_feed_config( $feed_url ) {
		
		return $this->get_config_hash()[$feed_url];
	}
	
	private function get_limited_feed( $raw_feed, $max_entries_per_blog ) {
		
		if ( $max_entries_per_blog !== false ) {
			$raw_feed->set_item_limit( $max_entries_per_blog );
		}
		
		$maxitems = $raw_feed->get_item_quantity( 20 ); 

		return $raw_feed->get_items( 0, $maxitems );
	}
	
	private function get_raw_feed() {
		
		$urls = array_map( function ( $value ) {
			return $value['feed'];
		}, $this->get_config_list());
		
		add_filter( 'wp_feed_cache_transient_lifetime' , array($this, 'get_cache_ttl') );
		$feed = fetch_feed( $urls );
		remove_filter( 'wp_feed_cache_transient_lifetime' , array($this, 'get_cache_ttl') );
		
		return $feed;
	}
	
	private function get_config_list() {
		if ( $this->config_list === false ) {
			$this->setup_config_vars();
		}
		
		return $this->config_list;
	}
	
	private function get_config_hash() {
		if ( $this->config_hash === false ) {
			$this->setup_config_vars();
		}
		
		return $this->config_hash;
	}
	
	private function setup_config_vars() {
		
		if ( $this->config_list === false ) {
			$config_provider = new XV_Planet_Config();
			
			$this->config_list = $config_provider->get_all_blog_configs();
		}
		
		if ( $this->config_hash === false ) {
			
			$this->config_hash = array();
			
			foreach( $this->config_list as $config ) {
				
				$this->config_hash[$config['feed']] = $config;
				
			}
		}
		
		return $this->config_list;
	}	
}
