<?php
/**
 * @package XV Planeta
 */

class XV_Planet_Data {

	public $main_feed_url;
	public $main_feed_title;
	public $main_feed_website;
	
	public $entries;
	
	public function __construct( $main_feed_url, $main_feed_title, $main_feed_website ) {
		$this->main_feed_url = $main_feed_url;
		$this->main_feed_title = $main_feed_title;
		$this->main_feed_website = $main_feed_website;
		$this->entries = array();
	}
	
	public function add( $array ) {
		$this->entries[] = $array;
	}
}

