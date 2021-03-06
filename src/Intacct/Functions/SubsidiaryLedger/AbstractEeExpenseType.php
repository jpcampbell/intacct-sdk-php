<?php

/**
 * Copyright 2016 Intacct Corporation.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"). You may not
 * use this file except in compliance with the License. You may obtain a copy
 * of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "LICENSE" file accompanying this file. This file is distributed on
 * an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 */

namespace Intacct\Functions\SubsidiaryLedger;

use Intacct\Functions\Traits\CustomFieldsTrait;

abstract class AbstractEeExpenseType extends AbstractAccountLabel
{

    use CustomFieldsTrait;

    /** @var string */
    protected $expenseType;

    /** @var string */
    protected $itemId;

    /**
     * @return string
     */
    public function getExpenseType()
    {
        return $this->expenseType;
    }

    /**
     * @param string $expenseType
     */
    public function setExpenseType($expenseType)
    {
        $this->expenseType = $expenseType;
    }

    /**
     * @return string
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param string $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }
}
