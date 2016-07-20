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
 * Database Class
 *
 * @package     Kotori
 * @subpackage  Core
 * @author      Kokororin
 * @link        https://kotori.love
 */

namespace Kotori\Core;

use Kotori\Debug\Hook;
use Kotori\Debug\Log;

class Database extends \medoo
{
    /**
     * SQL queries
     *
     * @var array
     */
    public $queries = array();

    /**
     * Instance Handle
     *
     * @var array
     */
    protected static $_soul;

    /**
     * Disable Clone
     *
     * @return boolean
     */
    public function __clone()
    {
        return false;
    }

    /**
     * get singleton
     *
     * @return object
     */
    public static function getSoul()
    {
        if (Config::getSoul()->DB_TYPE == null) {
            $config = array();
            return null;
        } else {
            $config = array(
                'database_type' => Config::getSoul()->DB_TYPE,
                'database_name' => Config::getSoul()->DB_NAME,
                'server' => Config::getSoul()->DB_HOST,
                'username' => Config::getSoul()->DB_USER,
                'password' => Config::getSoul()->DB_PWD,
                'charset' => Config::getSoul()->DB_CHARSET,
                'port' => Config::getSoul()->DB_PORT,
            );
        }
        $key = $config['server'] . ':' . $config['port'];
        if (!isset(self::$_soul[$key])) {
            self::$_soul[$key] = new self($config);
        }
        return self::$_soul[$key];
    }

    /**
     * Class constructor
     *
     * Initialize Database.
     *
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        Hook::listen('Database');
    }

    public function query($query)
    {
        array_push($this->logs, $query);
        Log::sql($this->last_query());
        array_push($this->queries, $this->last_query());
        return parent::query($query);
    }

    public function exec($query)
    {
        array_push($this->logs, $query);
        Log::sql($this->last_query());
        array_push($this->queries, $this->last_query());
        return parent::exec($query);
    }

}