<?php

/**
 *
 * @package    Zalt
 * @subpackage Late
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 * @copyright  Copyright (c) 2011 Erasmus MC
 * @license    New BSD License
 */

namespace Zalt\Late;

use Traversable;

/**
 *
 * @package    Zalt
 * @subpackage Late
 * @copyright  Copyright (c) 2011 Erasmus MC
 * @license    New BSD License
 * @since      Class available since version 1.0
 */
#[\AllowDynamicProperties]
class RepeatableByKeyValue extends Repeatable
{
    public GetRepeatableLate $key;
    public GetRepeatableLate $value;

    /**
     *
     * @param array|Traversable $data
     * @throws LateException
     */
    public function __construct($data)
    {
        if (! (is_array($data) || ($data instanceof Traversable))) {
            throw new LateException('The $data parameter is not an array or a Traversable interface instance ');
        }

        $result = array();

        foreach($data as $key => $value) {
            $result[] = ['key' => $key, 'value' => $value];
        }

        parent::__construct($result);

        $this->key = new GetRepeatableLate($this, 'key');
        $this->value = new GetRepeatableLate($this, 'value');
    }
}