<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Yaml\Tag;

/**
 * @author Nicolas Grekas <p@tchwork.com>
 * @author Guilhem N. <egetick@gmail.com>
 */
final class TaggedValue
{
    private $tag;
    private $value;

<<<<<<< HEAD
    /**
     * @param string $tag
     * @param mixed  $value
     */
    public function __construct($tag, $value)
=======
    public function __construct(string $tag, $value)
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        $this->tag = $tag;
        $this->value = $value;
    }

<<<<<<< HEAD
    /**
     * @return string
     */
    public function getTag()
=======
    public function getTag(): string
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    {
        return $this->tag;
    }

<<<<<<< HEAD
    /**
     * @return mixed
     */
=======
>>>>>>> eb25bd2e3f08ed0703676cf8b19fe06d45060d57
    public function getValue()
    {
        return $this->value;
    }
}
