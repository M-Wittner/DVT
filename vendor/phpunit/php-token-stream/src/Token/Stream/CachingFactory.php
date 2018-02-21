<?php
/*
<<<<<<< HEAD
 * This file is part of the PHP_TokenStream package.
=======
 * This file is part of php-token-stream.
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A caching factory for token stream objects.
<<<<<<< HEAD
 *
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/php-token-stream/tree
 * @since     Class available since Release 1.0.0
=======
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
 */
class PHP_Token_Stream_CachingFactory
{
    /**
     * @var array
     */
<<<<<<< HEAD
    protected static $cache = array();

    /**
     * @param  string $filename
=======
    protected static $cache = [];

    /**
     * @param string $filename
     *
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
     * @return PHP_Token_Stream
     */
    public static function get($filename)
    {
        if (!isset(self::$cache[$filename])) {
            self::$cache[$filename] = new PHP_Token_Stream($filename);
        }

        return self::$cache[$filename];
    }

    /**
     * @param string $filename
     */
    public static function clear($filename = null)
    {
        if (is_string($filename)) {
            unset(self::$cache[$filename]);
        } else {
<<<<<<< HEAD
            self::$cache = array();
=======
            self::$cache = [];
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
        }
    }
}
