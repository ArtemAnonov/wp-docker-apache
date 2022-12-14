<?php

namespace Awps\Setup;

/**
 * Menus
 */
class Menus
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action( 'after_setup_theme', array( $this, 'menus' ) );
    }

    public function menus()
    {
        /*
            Register all your menus here
        */
        register_nav_menus(array(
            'top_header' => esc_html__( 'Top Header', 'awps' ),
            'main_category' => esc_html__( 'Main-Category', 'awps' ),
            'banners' => esc_html__( 'Banners', 'awps' ),
        ));

    }
}
