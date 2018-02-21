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

<<<<<<< HEAD
/**
 * Tests for the PHP_Token class.
 *
 * @package    PHP_TokenStream
 * @subpackage Tests
 * @author     Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright  Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-token-stream/
 * @since      Class available since Release 1.0.0
 */
class PHP_TokenTest extends PHPUnit_Framework_TestCase
=======
use PHPUnit\Framework\TestCase;

class PHP_TokenTest extends TestCase
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
{
    /**
     * @covers PHP_Token::__construct
     * @covers PHP_Token::__toString
     */
    public function testToString()
    {
        $this->markTestIncomplete();
    }

    /**
     * @covers PHP_Token::__construct
     * @covers PHP_Token::getLine
     */
    public function testGetLine()
    {
        $this->markTestIncomplete();
    }
}
