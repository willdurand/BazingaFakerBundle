<?php

/**
 * This file is part of the FakerBundle package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT License
 */

namespace Bazinga\Bundle\FakerBundle\Factory;

/**
 * @author William Durand <william.durand1@gmail.com>
 */
class FormatterFactory
{
    public static function createClosure($generator, $method, array $parameters = array())
    {
        if (0 === count($parameters)) {
            return function() use ($generator, $method) { return $generator->$method(); };
        }

        return function() use ($generator, $method, $parameters) { return call_user_func_array(array($generator, $method), (array) $parameters); };
    }
}
