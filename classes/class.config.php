<?php
/**
 * @package XV Planeta
 */

class XV_Planet_Config {
	
	private $blogs;
	
	private function load_blogs() {
		
		$args = array(
			'post_type' => 'blog',
			'post_status' => 'publish'
		);
		
		$query = new WP_Query( $args );
		
		$this->blogs = $query->get_posts();
	}
	
	public function get_all_blog_configs() {
		
		if ( ! $this->blogs ) {
			$this->load_blogs();
		}
		
		$configs = array();
		foreach ( $this->blogs as $blog ) {
			
			$configs[] = array(
				'title' => get_the_title( $blog->ID ),
				'feed' => get_post_meta( $blog->ID, 'blog_rss_url', true ),
				'home' => get_post_meta( $blog->ID, 'blog_home_url', true ),
				'author' => get_the_author_meta( 'display_name', $blog->post_author ),
				'image'	=> wp_get_attachment_url( get_post_thumbnail_id( $blog->ID ) )		
			);
		}
		
		return $configs;
	}	
}
