<?php
/**
 * Kotori.php
 *
 * A Tiny Model-View-Controller PHP Framework
 *
 * This content is released under the Apache 2 License
 *
 * Copyright (c) 2015-2016 Kotori Technology. All rights reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Kotori Initialization Class
 *
 * Loads the base classes and executes the request.
 *
 * @package     Kotori
 * @subpackage  Kotori
 * @author      Kokororin
 * @link        https://kotori.love
 */
namespace Kotori;

use Kotori\Core\Common;
use Kotori\Core\Config;
use Kotori\Http\Route;

class App
{
    /**
     * Class constructor
     *
     * Initialize Framework.
     *
     * @return void
     */
    public function __construct()
    {
        version_compare(PHP_VERSION, '5.2.0', '<') && exit('Kotori.php requires PHP >= 5.2.0 !');
        ini_set('display_errors', 'on');
        define('START_TIME', microtime(true));
        define('START_MEMORY', memory_get_usage());
    }

    /**
     * Start the App.
     *
     * @return void
     */
    public function run()
    {
        global $config;
        Config::getSoul()->initialize($config);
        //Define a custom error handler so we can log PHP errors
        set_error_handler(array('\\Kotori\Core\Handle', 'error'));
        set_exception_handler(array('\\Kotori\Core\Handle', 'exception'));
        register_shutdown_function(array('\\Kotori\Core\Handle', 'end'));

        ini_set('date.timezone', Config::getSoul()->TIME_ZONE);

        !session_id() && session_start();

        //Load application's common functions
        Common::import(Config::getSoul()->APP_FULL_PATH . '/common.php');

        if (function_exists('spl_autoload_register')) {
            spl_autoload_register(array('\\Kotori\Core\Common', 'autoload'));
        } else {
            function __autoload($className)
            {
                Common::autoload($className);
            }
        }

        //Load route class
        Route::getSoul()->dispatch();

        //Global security filter
        array_walk_recursive($_GET, array('Request', 'filter'));
        array_walk_recursive($_POST, array('Request', 'filter'));
        array_walk_recursive($_REQUEST, array('Request', 'filter'));
    }

}
