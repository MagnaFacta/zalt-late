<?php

declare(strict_types=1);

/**
 *
 * @package    Zalt
 * @subpackage Late
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Zalt\Late;

/**
 *
 * @package    Zalt
 * @subpackage Late
 * @since      Class available since version 1.0
 */
class LateGetRa extends LateAbstract
{
    public function __construct(protected array $names)
    { }

    /**
     * The functions that fixes and returns a value.
     *
     * Be warned: this function may return a late value.
     *
     * @param StackInterface $stack A StackInterface object providing variable data
     * @return mixed
     */
    public function __toValue(StackInterface $stack)
    {
        $output = [];
        foreach ($this->names as $key => $get) {
            if ($get instanceof LateInterface) {
                $output[$key] = Late::rise($get, $stack);
            } else {
                $output[$key] = $stack->lateGet($get);
            }
        }
        return $output;
    }
}