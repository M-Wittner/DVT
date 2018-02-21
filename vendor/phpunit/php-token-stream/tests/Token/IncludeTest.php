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
 * Tests for the PHP_Token_REQUIRE_ONCE, PHP_Token_REQUIRE
 * PHP_Token_INCLUDE_ONCE and PHP_Token_INCLUDE_ONCE classes.
 *
 * @package    PHP_TokenStream
 * @subpackage Tests
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://github.com/sebastianbergmann/php-token-stream/
 * @since      Class available since Release 1.0.2
 */
class PHP_Token_IncludeTest extends PHPUnit_Framework_TestCase
{
    protected $ts;
=======
use PHPUnit\Framework\TestCase;

class PHP_Token_IncludeTest extends TestCase
{
    /**
     * @var PHP_Token_Stream
     */
    private $ts;
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57

    protected function setUp()
    {
        $this->ts = new PHP_Token_Stream(TEST_FILES_PATH . 'source3.php');
    }

    /**
     * @covers PHP_Token_Includes::getName
     * @covers PHP_Token_Includes::getType
     */
    public function testGetIncludes()
    {
        $this->assertSame(
<<<<<<< HEAD
          array('test4.php', 'test3.php', 'test2.php', 'test1.php'),
=======
          ['test4.php', 'test3.php', 'test2.php', 'test1.php'],
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
          $this->ts->getIncludes()
        );
    }

    /**
     * @covers PHP_Token_Includes::getName
     * @covers PHP_Token_Includes::getType
     */
    public function testGetIncludesCategorized()
    {
        $this->assertSame(
<<<<<<< HEAD
          array(
            'require_once' => array('test4.php'),
            'require'      => array('test3.php'),
            'include_once' => array('test2.php'),
            'include'      => array('test1.php')
          ),
          $this->ts->getIncludes(TRUE)
=======
          [
            'require_once' => ['test4.php'],
            'require'      => ['test3.php'],
            'include_once' => ['test2.php'],
            'include'      => ['test1.php']
          ],
          $this->ts->getIncludes(true)
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
        );
    }

    /**
     * @covers PHP_Token_Includes::getName
     * @covers PHP_Token_Includes::getType
     */
    public function testGetIncludesCategory()
    {
        $this->assertSame(
<<<<<<< HEAD
          array('test4.php'),
          $this->ts->getIncludes(TRUE, 'require_once')
=======
          ['test4.php'],
          $this->ts->getIncludes(true, 'require_once')
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
        );
    }
}
