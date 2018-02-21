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
 * Tests for the PHP_Token_FUNCTION class.
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
class PHP_Token_ClosureTest extends PHPUnit_Framework_TestCase
{
    protected $functions;
=======
use PHPUnit\Framework\TestCase;

class PHP_Token_ClosureTest extends TestCase
{
    /**
     * @var PHP_Token_FUNCTION[]
     */
    private $functions;
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57

    protected function setUp()
    {
        $ts = new PHP_Token_Stream(TEST_FILES_PATH . 'closure.php');

        foreach ($ts as $token) {
            if ($token instanceof PHP_Token_FUNCTION) {
                $this->functions[] = $token;
            }
        }
    }

    /**
     * @covers PHP_Token_FUNCTION::getArguments
     */
    public function testGetArguments()
    {
<<<<<<< HEAD
        $this->assertEquals(array('$foo' => null, '$bar' => null), $this->functions[0]->getArguments());
        $this->assertEquals(array('$foo' => 'Foo', '$bar' => null), $this->functions[1]->getArguments());
        $this->assertEquals(array('$foo' => null, '$bar' => null, '$baz' => null), $this->functions[2]->getArguments());
        $this->assertEquals(array('$foo' => 'Foo', '$bar' => null, '$baz' => null), $this->functions[3]->getArguments());
        $this->assertEquals(array(), $this->functions[4]->getArguments());
        $this->assertEquals(array(), $this->functions[5]->getArguments());
=======
        $this->assertEquals(['$foo' => null, '$bar' => null], $this->functions[0]->getArguments());
        $this->assertEquals(['$foo' => 'Foo', '$bar' => null], $this->functions[1]->getArguments());
        $this->assertEquals(['$foo' => null, '$bar' => null, '$baz' => null], $this->functions[2]->getArguments());
        $this->assertEquals(['$foo' => 'Foo', '$bar' => null, '$baz' => null], $this->functions[3]->getArguments());
        $this->assertEquals([], $this->functions[4]->getArguments());
        $this->assertEquals([], $this->functions[5]->getArguments());
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    }

    /**
     * @covers PHP_Token_FUNCTION::getName
     */
    public function testGetName()
    {
<<<<<<< HEAD
        $this->assertEquals('anonymous function', $this->functions[0]->getName());
        $this->assertEquals('anonymous function', $this->functions[1]->getName());
        $this->assertEquals('anonymous function', $this->functions[2]->getName());
        $this->assertEquals('anonymous function', $this->functions[3]->getName());
        $this->assertEquals('anonymous function', $this->functions[4]->getName());
        $this->assertEquals('anonymous function', $this->functions[5]->getName());
=======
        $this->assertEquals('anonymousFunction:2#5', $this->functions[0]->getName());
        $this->assertEquals('anonymousFunction:3#27', $this->functions[1]->getName());
        $this->assertEquals('anonymousFunction:4#51', $this->functions[2]->getName());
        $this->assertEquals('anonymousFunction:5#71', $this->functions[3]->getName());
        $this->assertEquals('anonymousFunction:6#93', $this->functions[4]->getName());
        $this->assertEquals('anonymousFunction:7#106', $this->functions[5]->getName());
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    }

    /**
     * @covers PHP_Token::getLine
     */
    public function testGetLine()
    {
        $this->assertEquals(2, $this->functions[0]->getLine());
        $this->assertEquals(3, $this->functions[1]->getLine());
        $this->assertEquals(4, $this->functions[2]->getLine());
        $this->assertEquals(5, $this->functions[3]->getLine());
    }

    /**
     * @covers PHP_TokenWithScope::getEndLine
     */
    public function testGetEndLine()
    {
        $this->assertEquals(2, $this->functions[0]->getLine());
        $this->assertEquals(3, $this->functions[1]->getLine());
        $this->assertEquals(4, $this->functions[2]->getLine());
        $this->assertEquals(5, $this->functions[3]->getLine());
    }
}
