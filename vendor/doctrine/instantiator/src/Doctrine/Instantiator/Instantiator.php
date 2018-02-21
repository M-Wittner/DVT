<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Instantiator;

<<<<<<< HEAD
use Closure;
=======
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Doctrine\Instantiator\Exception\UnexpectedValueException;
use Exception;
use ReflectionClass;

/**
 * {@inheritDoc}
 *
 * @author Marco Pivetta <ocramius@gmail.com>
 */
final class Instantiator implements InstantiatorInterface
{
    /**
     * Markers used internally by PHP to define whether {@see \unserialize} should invoke
     * the method {@see \Serializable::unserialize()} when dealing with classes implementing
     * the {@see \Serializable} interface.
     */
    const SERIALIZATION_FORMAT_USE_UNSERIALIZER   = 'C';
    const SERIALIZATION_FORMAT_AVOID_UNSERIALIZER = 'O';

    /**
<<<<<<< HEAD
     * @var \Closure[] of {@see \Closure} instances used to instantiate specific classes
     */
    private static $cachedInstantiators = array();

    /**
     * @var object[] of objects that can directly be cloned
     */
    private static $cachedCloneables = array();
=======
     * @var \callable[] used to instantiate specific classes, indexed by class name
     */
    private static $cachedInstantiators = [];

    /**
     * @var object[] of objects that can directly be cloned, indexed by class name
     */
    private static $cachedCloneables = [];
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57

    /**
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        if (isset(self::$cachedCloneables[$className])) {
            return clone self::$cachedCloneables[$className];
        }

        if (isset(self::$cachedInstantiators[$className])) {
            $factory = self::$cachedInstantiators[$className];

            return $factory();
        }

        return $this->buildAndCacheFromFactory($className);
    }

    /**
     * Builds the requested object and caches it in static properties for performance
     *
<<<<<<< HEAD
     * @param string $className
     *
     * @return object
     */
    private function buildAndCacheFromFactory($className)
=======
     * @return object
     */
    private function buildAndCacheFromFactory(string $className)
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        $factory  = self::$cachedInstantiators[$className] = $this->buildFactory($className);
        $instance = $factory();

        if ($this->isSafeToClone(new ReflectionClass($instance))) {
            self::$cachedCloneables[$className] = clone $instance;
        }

        return $instance;
    }

    /**
<<<<<<< HEAD
     * Builds a {@see \Closure} capable of instantiating the given $className without
     * invoking its constructor.
     *
     * @param string $className
     *
     * @return Closure
     */
    private function buildFactory($className)
=======
     * Builds a callable capable of instantiating the given $className without
     * invoking its constructor.
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws \ReflectionException
     */
    private function buildFactory(string $className) : callable
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        $reflectionClass = $this->getReflectionClass($className);

        if ($this->isInstantiableViaReflection($reflectionClass)) {
<<<<<<< HEAD
            return function () use ($reflectionClass) {
                return $reflectionClass->newInstanceWithoutConstructor();
            };
=======
            return [$reflectionClass, 'newInstanceWithoutConstructor'];
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
        }

        $serializedString = sprintf(
            '%s:%d:"%s":0:{}',
<<<<<<< HEAD
            $this->getSerializationFormat($reflectionClass),
=======
            self::SERIALIZATION_FORMAT_AVOID_UNSERIALIZER,
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
            strlen($className),
            $className
        );

        $this->checkIfUnSerializationIsSupported($reflectionClass, $serializedString);

        return function () use ($serializedString) {
            return unserialize($serializedString);
        };
    }

    /**
     * @param string $className
     *
     * @return ReflectionClass
     *
     * @throws InvalidArgumentException
<<<<<<< HEAD
     */
    private function getReflectionClass($className)
=======
     * @throws \ReflectionException
     */
    private function getReflectionClass($className) : ReflectionClass
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        if (! class_exists($className)) {
            throw InvalidArgumentException::fromNonExistingClass($className);
        }

        $reflection = new ReflectionClass($className);

        if ($reflection->isAbstract()) {
            throw InvalidArgumentException::fromAbstractClass($reflection);
        }

        return $reflection;
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string          $serializedString
     *
     * @throws UnexpectedValueException
     *
     * @return void
     */
<<<<<<< HEAD
    private function checkIfUnSerializationIsSupported(ReflectionClass $reflectionClass, $serializedString)
    {
        set_error_handler(function ($code, $message, $file, $line) use ($reflectionClass, & $error) {
=======
    private function checkIfUnSerializationIsSupported(ReflectionClass $reflectionClass, $serializedString) : void
    {
        set_error_handler(function ($code, $message, $file, $line) use ($reflectionClass, & $error) : void {
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
            $error = UnexpectedValueException::fromUncleanUnSerialization(
                $reflectionClass,
                $message,
                $code,
                $file,
                $line
            );
        });

        $this->attemptInstantiationViaUnSerialization($reflectionClass, $serializedString);

        restore_error_handler();

        if ($error) {
            throw $error;
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string          $serializedString
     *
     * @throws UnexpectedValueException
     *
     * @return void
     */
<<<<<<< HEAD
    private function attemptInstantiationViaUnSerialization(ReflectionClass $reflectionClass, $serializedString)
=======
    private function attemptInstantiationViaUnSerialization(ReflectionClass $reflectionClass, $serializedString) : void
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        try {
            unserialize($serializedString);
        } catch (Exception $exception) {
            restore_error_handler();

            throw UnexpectedValueException::fromSerializationTriggeredException($reflectionClass, $exception);
        }
    }

<<<<<<< HEAD
    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return bool
     */
    private function isInstantiableViaReflection(ReflectionClass $reflectionClass)
    {
        if (\PHP_VERSION_ID >= 50600) {
            return ! ($this->hasInternalAncestors($reflectionClass) && $reflectionClass->isFinal());
        }

        return \PHP_VERSION_ID >= 50400 && ! $this->hasInternalAncestors($reflectionClass);
=======
    private function isInstantiableViaReflection(ReflectionClass $reflectionClass) : bool
    {
        return ! ($this->hasInternalAncestors($reflectionClass) && $reflectionClass->isFinal());
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    }

    /**
     * Verifies whether the given class is to be considered internal
<<<<<<< HEAD
     *
     * @param ReflectionClass $reflectionClass
     *
     * @return bool
     */
    private function hasInternalAncestors(ReflectionClass $reflectionClass)
=======
     */
    private function hasInternalAncestors(ReflectionClass $reflectionClass) : bool
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        do {
            if ($reflectionClass->isInternal()) {
                return true;
            }
        } while ($reflectionClass = $reflectionClass->getParentClass());

        return false;
    }

    /**
<<<<<<< HEAD
     * Verifies if the given PHP version implements the `Serializable` interface serialization
     * with an incompatible serialization format. If that's the case, use serialization marker
     * "C" instead of "O".
     *
     * @link http://news.php.net/php.internals/74654
     *
     * @param ReflectionClass $reflectionClass
     *
     * @return string the serialization format marker, either self::SERIALIZATION_FORMAT_USE_UNSERIALIZER
     *                or self::SERIALIZATION_FORMAT_AVOID_UNSERIALIZER
     */
    private function getSerializationFormat(ReflectionClass $reflectionClass)
    {
        if ($this->isPhpVersionWithBrokenSerializationFormat()
            && $reflectionClass->implementsInterface('Serializable')
        ) {
            return self::SERIALIZATION_FORMAT_USE_UNSERIALIZER;
        }

        return self::SERIALIZATION_FORMAT_AVOID_UNSERIALIZER;
    }

    /**
     * Checks whether the current PHP runtime uses an incompatible serialization format
     *
     * @return bool
     */
    private function isPhpVersionWithBrokenSerializationFormat()
    {
        return PHP_VERSION_ID === 50429 || PHP_VERSION_ID === 50513;
    }

    /**
     * Checks if a class is cloneable
     *
     * @param ReflectionClass $reflection
     *
     * @return bool
     */
    private function isSafeToClone(ReflectionClass $reflection)
    {
        if (method_exists($reflection, 'isCloneable') && ! $reflection->isCloneable()) {
            return false;
        }

        // not cloneable if it implements `__clone`, as we want to avoid calling it
        return ! $reflection->hasMethod('__clone');
=======
     * Checks if a class is cloneable
     *
     * Classes implementing `__clone` cannot be safely cloned, as that may cause side-effects.
     */
    private function isSafeToClone(ReflectionClass $reflection) : bool
    {
        return $reflection->isCloneable() && ! $reflection->hasMethod('__clone');
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    }
}
