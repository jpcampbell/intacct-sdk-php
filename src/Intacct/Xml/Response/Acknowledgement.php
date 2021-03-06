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

namespace Intacct\Xml\Response;

use Intacct\Exception;
use SimpleXMLIterator;

class Acknowledgement
{

    /** @var string */
    private $status;

    /**
     * Initializes the class
     *
     * @param SimpleXMLIterator $acknowledgement
     * @throws Exception
     */
    public function __construct(SimpleXMLIterator $acknowledgement)
    {
        if (!isset($acknowledgement->status)) {
            throw new Exception('Acknowledgement block is missing status element');
        }

        $this->status = strval($acknowledgement->status);
    }

    /**
     * Get acknowledgement status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}
