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

use Intacct\Xml\XMLWriter;

class ArPaymentItem
{

    /** @var string|int */
    private $applyToRecordId;

    /** @var float|string */
    private $amountToApply;

    /**
     * @return int|string
     */
    public function getApplyToRecordId()
    {
        return $this->applyToRecordId;
    }

    /**
     * @param int|string $applyToRecordId
     */
    public function setApplyToRecordId($applyToRecordId)
    {
        $this->applyToRecordId = $applyToRecordId;
    }

    /**
     * @return float|string
     */
    public function getAmountToApply()
    {
        return $this->amountToApply;
    }

    /**
     * @param float|string $amountToApply
     */
    public function setAmountToApply($amountToApply)
    {
        $this->amountToApply = $amountToApply;
    }

    /**
     * Write the arpaymentitem block XML
     *
     * @param XMLWriter $xml
     */
    public function writeXml(XMLWriter &$xml)
    {
        $xml->startElement('arpaymentitem');

        $xml->writeElement('invoicekey', $this->applyToRecordId, true);
        $xml->writeElement('amount', $this->amountToApply, true);

        $xml->endElement(); //arpaymentitem
    }
}
