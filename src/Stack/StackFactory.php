<?php

declare(strict_types=1);

/**
 *
 * @package    Zalt
 * @subpackage Late\Stack
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Zalt\Late\Stack;

use Zalt\Late\LateException;
use Zalt\Late\Repeatable;
use Zalt\Late\StackInterface;
use Zalt\Ra\Ra;

/**
 *
 * @package    Zalt
 * @subpackage Late\Stack
 * @since      Class available since version 1.0
 */
class StackFactory
{
    /**
     * Create a StackInterface object
     *
     * @param mixed $stack Value to be turned into stack interface for evaluation
     * @return StackInterface
     */
    public static function createLateStack(mixed $stack)
    {
        if ($stack instanceof StackInterface) {
            return $stack;
        }
        
        if ($stack instanceof Repeatable) {
            return new RepeatableStack($stack);
        }
        if (Ra::is($stack)) {
            $stack = Ra::to($stack);

            return new ArrayStack($stack);
        }
        
        if (is_object($stack)) {
            return new ObjectStack($stack);
        }
        
        throw new LateException("Late stack set to invalid scalar type.");
    }
}