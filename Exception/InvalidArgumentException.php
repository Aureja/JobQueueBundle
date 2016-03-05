<?php

/*
 * This file is part of the Aureja package.
 *
 * (c) Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aureja\Bundle\JobQueueBundle\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;

/**
 * @author Tadas Gliaubicas <tadcka89@gmail.com>
 *
 * @since 3/5/16 9:47 AM
 */
class InvalidArgumentException extends BaseInvalidArgumentException
{
    /**
     * @param string $message
     *
     * @return InvalidArgumentException
     */
    public static function create($message)
    {
        return new static();
    }
}
