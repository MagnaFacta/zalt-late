<?php

declare(strict_types=1);

/**
 *
 * @package    Zalt
 * @subpackage Late\Stack
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Zalt\Late\Stack;

use Zalt\Late\A;
use Zalt\Late\StackInterface;

/**
 *
 * @package    Zalt
 * @subpackage Late\Stack
 * @since      Class available since version 1.0
 */
class StackCombiner implements \Zalt\Late\StackInterface
{
    /**
     * @var array All the StackInterface items gathered
     */
    protected $stacks = [];
    
    public function addStack(string $id, StackInterface $stack)
    {
        // Last added stack will be tried first
        $this->stacks = array_merge([$id => $stack], $this->stacks);
    }
    
    /**
     * @inheritDoc
     */
    public function lateGet($name)
    {
        foreach ($this->stacks as $id => $stack) {
            if ($stack instanceof StackInterface) {
                $output = $stack->lateGet($name);
                // file_put_contents('data/logs/echo.txt', __CLASS__ . '->' . __FUNCTION__ . '(' . __LINE__ . '): ' . "$id -> $name => $output\n", FILE_APPEND);
                if ($output) {
                    return $output;
                }
            }
        }
        return null;
    }
}