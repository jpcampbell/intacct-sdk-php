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

class ApPaymentRequestItem
{

    /** @var string|int */
    private $applyToRecordId;

    /** @var float|string */
    private $amountToApply;

    /** @var float|string */
    private $creditToApply;

    /** @var float|string */
    private $discountToApply;

    /**
     * @todo add externalkey support
     */

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
     * @return float|string
     */
    public function getCreditToApply()
    {
        return $this->creditToApply;
    }

    /**
     * @param float|string $creditToApply
     */
    public function setCreditToApply($creditToApply)
    {
        $this->creditToApply = $creditToApply;
    }

    /**
     * @return float|string
     */
    public function getDiscountToApply()
    {
        return $this->discountToApply;
    }

    /**
     * @param float|string $discountToApply
     */
    public function setDiscountToApply($discountToApply)
    {
        $this->discountToApply = $discountToApply;
    }

    /**
     * Write the paymentrequestitem block XML
     *
     * @param XMLWriter $xml
     */
    public function writeXml(XMLWriter &$xml)
    {
        $xml->startElement('paymentrequestitem');

        $xml->writeElement('key', $this->getApplyToRecordId(), true);
        $xml->writeElement('paymentamount', $this->getAmountToApply(), true);

        $xml->writeElement('credittoapply', $this->getCreditToApply());
        $xml->writeElement('discounttoapply', $this->getDiscountToApply());

        $xml->endElement(); //paymentrequestitem
    }

}