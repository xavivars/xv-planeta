<?php
/**
 * @package XV Planeta
 */
class XV_Planet_Feed {
	
	private $xv_planeta;
	
    public function __construct( $xv_planeta ) {
		
		$this->xv_planeta = $xv_planeta;
		
        # Add our query_var, 'xv_planeta_feed'
        add_filter( 'query_vars',    array( &$this, 'add_query_var' ) );

        # setup rewrite rules for our path
        add_action( 'init',          array( &$this, 'add_rewrite_rules' ), 5 );

        # We're doing this on parse_query to ensure that query vars are set
        add_action( 'pre_get_posts',   array( &$this, 'dispatch_path' ), 1 );
    }

    /**
     * Add rewrite rules for our path
     *
     * @return void
     */
    public function add_rewrite_rules() {
        add_rewrite_rule( "^planeta/rss.xml$", 'index.php?xv_planeta_feed=1', 'top' );
    }

    /**
     * Add the class query var, xv_planeta_feed
     *
     * @param array $qv The current query vars
     * @return array The modified query vars
     */
    public function add_query_var( $qv ) {
        $qv[] = 'xv_planeta_feed';
        return $qv;
    }

    /**
     * When on the specified URL and the combined RSS is set up by the theme,
	 * the combined RSS is rendered
     */
    public function dispatch_path( $query ) {
		
		if ( ! $query->is_main_query() ) {
			return;
		}
		
        if ( get_query_var( 'xv_planeta_feed' ) ) {

			remove_action( 'parse_query',   array( &$this, 'dispatch_path' ) );
			
            $create_combined_rss = apply_filters( 'xv_planeta_feed', false );
			
			if( $create_combined_rss !== false) {
				
				$this->render_rss();
				
				exit;
			} else {
				$query->set_404();
				status_header( 404 );
				return;
			}
        }
    }
	
	public function render_rss() {
		$feed = $this->xv_planeta->get_feed();
		
		Timber::render( XV_PLANETA_PATH . '/templates/rss.twig', array( 'feed' => $feed ) );
	}
}

