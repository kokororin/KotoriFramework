<?php
/**
 * Kotori.php
 *
 * A Tiny Model-View-Controller PHP Framework
 *
 * This content is released under the Apache 2 License
 *
 * Copyright (c) 2015-2017 Kotori Technology. All rights reserved.
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
 * Memcached Session Class
 *
 * @package     Kotori
 * @subpackage  Session
 * @author      Kokororin
 * @link        https://kotori.love
 */
namespace Kotori\Http\Session;

use Kotori\Core\Cache\Redis as RedisDriver;
use SessionHandlerInterface;

class Redis implements SessionHandlerInterface
{
    /**
     * Default configuration
     *
     * @var array
     */
    protected $config = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => null,
        'database' => 0,
        'expire' => 3600,
        'timeout' => 0,
        'prefix' => '',
    ];

    /**
     * Class constructor
     *
     * Setup Redis
     *
     * @param  array $config
     */
    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * Open session
     *
     * @param  string    $savePath
     * @param  mixed     $sessName
     * @return boolean
     */
    // @codingStandardsIgnoreStart
    public function open($savePath, $sessName)
    {
        $this->redisDriver = new RedisDriver($this->config);
        return true;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Close session
     *
     * @return boolean
     */
    public function close()
    {
        $this->gc(ini_get('session.gc_maxlifetime'));
        $this->redisDriver = null;
        return true;
    }

    /**
     * Read session
     *
     * @param  string $sessID
     * @return string
     */
    public function read($sessID)
    {
        return (string) $this->redisDriver->get($this->config['prefix'] . $sessID);
    }

    /**
     * Write session
     *
     * @param string $sessID
     * @param String $sessData
     * @return boolean
     */
    public function write($sessID, $sessData)
    {
        return $this->redisDriver->set($this->config['prefix'] . $sessID, $sessData, $this->config['expire']);
    }

    /**
     * Delete session
     *
     * @param  string $sessID
     * @return boolean
     */
    public function destroy($sessID)
    {
        return $this->redisDriver->delete($this->config['prefix'] . $sessID);
    }

    /**
     * do garbage collection
     *
     * @param  string $sessMaxLifeTime
     * @return boolean
     */
    public function gc($sessMaxLifeTime)
    {
        return true;
    }
}
