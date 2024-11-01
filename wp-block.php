<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WPSLAB_Blocktopost  {
	public function __construct()
	 {
       add_action('enqueue_block_editor_assets',array(&$this, 'wpslab_loadMyBlock'));
       add_filter( 'block_categories', array(&$this, 'wpslab_block_categories'), 10, 2 );
    }

    function wpslab_block_categories( $categories, $post ) {
    return array_merge(
        $categories,
        array(
            array(
                'slug' => 'mycategory',
                'title' => __( 'shortcute link', 'wpcat-embed-shortcode' ),
                'icon'  => 'wordpress',
            ),
        )
    );
}

    function wpslab_loadMyBlock() 
    {
    	wp_enqueue_script(
		    'wpslab-block',
		    plugin_dir_url(__FILE__) . 'js/block.js',
           array( 'wp-blocks', 'wp-element', 'wp-components' ),
		    true
		  );
    	 wp_localize_script( 'wpslab-block', 'the_in_url', array( 'in_url' => admin_url( 'admin-ajax.php' ) ) ); 
    }

}new WPSLAB_Blocktopost() ; 