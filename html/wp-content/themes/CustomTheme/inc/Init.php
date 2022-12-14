<?php

/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package awps
 */

namespace Awps;

use Exception;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function get_services()
    {
        $services = [
            [Core\Tags::class, []],
            [Core\Sidebar::class, []],
            [Setup\Setup::class, []],
            [Setup\Menus::class, []],
            [VueWordpress\VueWordpress::class, []],
            [Setup\Enqueue::class, []],
            [Custom\PostTypes::class, []],
            [Custom\Admin::class, []],
            [Custom\Extras::class, []],
            [Api\Customizer::class, []],
            [Api\Gutenberg::class, []],
            [Api\Widgets\TextWidget::class, []],
            [Plugins\ThemeJetpack::class, []],
            [Plugins\Acf::class, []],
            // [\MyTest\MyReflection\Reflection::class, []]
        ];
        // if (class_exists('RADL')) {
        //     $services[] = [VueWordpress\VueWordpress::class, []];
        // }
        return $services;
    }

    /**
     * Loop through the classes, initialize them, and call the register() method if it exists
     * @return
     */
    public static function register_services()
    {
        foreach (self::get_services() as $array) {
            $service = self::instantiate($array[0], $array[1]);
            if (method_exists($service, 'register')) {
                $service->register();
            } else {
                new Exception("Method register() dont exist in class $array[0]");
            }
        }
    }

    /**
     * Initialize the class
     * @param  class $class 		class from the services array
     * @return class instance 		new instance of the class
     */
    private static function instantiate($class, $args)
    {
        return new $class($args);
    }
}
