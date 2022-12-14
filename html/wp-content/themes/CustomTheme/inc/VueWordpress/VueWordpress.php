<?php

namespace Awps\VueWordpress;

use \WGU as WGU;
use \RADL as RADL;
use RADL\Store\Callback;
use RADL\Store\Models;

/**
 * Undocumented class
 */
class VueWordpress
{

    public function register()
    {

        if (!class_exists('RADL')) return;

        $schema = $this->get_schema();

        new RADL('__VUE_WORDPRESS__', 'index.bundle.js', $schema);
        if (class_exists('WooCommerce')) {
            new WGU();
        }
    }

    public function get_schema()
    {
        return [
            'routing' => new Callback([$this, 'vue_wordpress_routing']),
            'state' => [
                'auth' => new Models\Auth(),
                'cart' => new Models\Cart(),
                'checkout' => new Models\Checkout(),

                'pages' => new Models\Pages(),
                'banners' => new Models\Banners(),
                'media' => new Models\Media(),

                'orders' => new Models\Orders(),
                'customers' => new Models\Customers(),
                'products' => new Models\Products(),

                'productsCategories' => new Models\ProductsCategories(true),
                'productsAttributes' => new Models\ProductsAttributes(),
                'productsTermsBrands' => new Models\ProductsTermsBrands(),
                'productsTermsMaterials' => new Models\ProductsTermsMaterials(),
                'productsTermsSizes' => new Models\ProductsTermsSizes(),
                'productsTermsColors' => new Models\ProductsTermsColors(),
                // 'productsVariations' => new Models\ProductsVariations(),


                'site' => new Callback([$this, 'vue_wordpress_site']),
                'menus' => new Callback([$this, 'vue_wordpress_menus']),
                'filter' => new Callback([$this, 'vue_wordpress_filter']),
            ]
        ];
    }

    public function vue_wordpress_filter()
    {

        $defaultValues = (object) [
            'sort' => [
                (object) ["id" => 0, "name" => '???? ??????????????????', "orderby" => '', "order" => ''],
                (object) ["id" => 1, "name" => '???? ????????, ???? ??????????????????????', "orderby" => 'price', "order" => 'asc'],
                (object) ["id" => 2, "name" => '???? ????????, ???? ????????????????', "orderby" => 'price', "order" => 'desc'],
                (object) ["id" => 3, "name" => '???? ????????????????????????', "orderby" => 'popularity', "order" => 'asc'],
                (object) ["id" => 4, "name" => '???? ????????????????', "orderby" => 'rating', "order" => 'asc'],
            ]
        ];

        $params = (object) [
            "page" => 1,
            "category" => null,
            'min_price' => 1000,
            'max_price' => 1000000,
            "orderAndOrderBy" => $defaultValues->sort[0],
        ];

        $attrs = RADL\Store::get_all('state.productsAttributes');
        foreach ($attrs as $key => $value) {
            $slug = $value['slug'];
            $params->$slug = (object) [
                'id' => $value['id'],
                'options' => [],
            ];
        }

        return [
            "minCost" => null,
            "maxCost" => null,
            "params" => $params,
            "defaultValues" => $defaultValues
        ];
    }

    function vue_wordpress_routing()
    {
        $routing = [
            'category_base' => get_option('category_base'),
            'page_on_front' => null,
            'page_for_posts' => null,
            'permalink_structure' => get_option('permalink_structure'),
            'show_on_front' => get_option('show_on_front'),
            'tag_base' => get_option('tag_base'),
            'url' => get_bloginfo('url')
        ];

        if ($routing['show_on_front'] === 'page') {
            $front_page_id = get_option('page_on_front');
            $posts_page_id = get_option('page_for_posts');

            if ($front_page_id) {
                $front_page = get_post($front_page_id);
                $routing['page_on_front'] = $front_page->post_name;
            }

            if ($posts_page_id) {
                $posts_page = get_post($posts_page_id);
                $routing['page_for_posts'] = $posts_page->post_name;
            }
        }
        return $routing;
    }


    function vue_wordpress_menus()
    {
        $menus = array();

        $locations = get_nav_menu_locations();
        foreach (array_keys($locations) as $name) {
            $id = $locations[$name];
            $menu = array();
            $menu_items = wp_get_nav_menu_items($id);
            if ($menu_items) :
                foreach ($menu_items as $i) {
                    array_push($menu, array(
                        'id'      => $i->ID,
                        'parent'  => $i->menu_item_parent,
                        'target'  => $i->target,
                        'content' => $i->title,
                        'title'   => $i->attr_title,
                        'url'     => $i->url,
                    ));
                }
            endif;

            $menus[$name] = $menu;
        }
        return $menus;
    }

    function vue_wordpress_site()
    {
        return array(
            'description' => get_bloginfo('description'),
            'docTitle' => '',
            'loading' => false,
            'logo' => get_theme_mod('custom_logo'),
            'name' => get_bloginfo('name'),
            'posts_per_page' => get_option('posts_per_page'),
            'url' => get_bloginfo('url')
        );
    }
}
