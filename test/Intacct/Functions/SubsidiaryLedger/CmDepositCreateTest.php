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

use Intacct\Fields\Date;
use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class CmDepositCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\CmDepositCreate::writeXml
     */
    public function testDefaultParams()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <record_deposit>
        <bankaccountid>BA1145</bankaccountid>
        <depositdate>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </depositdate>
        <depositid>Deposit Slip 2015-06-30</depositid>
        <receiptkeys>
            <receiptkey>1234</receiptkey>
        </receiptkeys>
    </record_deposit>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $deposit = new CmDepositCreate('unittest');
        $deposit->setBankAccountId('BA1145');
        $deposit->setDepositDate(new Date('2015-06-30'));
        $deposit->setDepositSlipId('Deposit Slip 2015-06-30');
        $deposit->setTransactionKeysToDeposit([
            1234,
        ]);

        $deposit->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\CmDepositCreate::writeXml
     */
    public function testParamOverrides()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <record_deposit>
        <bankaccountid>BA1145</bankaccountid>
        <depositdate>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </depositdate>
        <depositid>Deposit Slip 2015-06-30</depositid>
        <receiptkeys>
            <receiptkey>1234</receiptkey>
        </receiptkeys>
        <description>Desc</description>
        <supdocid>AT111</supdocid>
        <customfields>
            <customfield>
                <customfieldname>customfield1</customfieldname>
                <customfieldvalue>customvalue1</customfieldvalue>
            </customfield>
        </customfields>
    </record_deposit>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $deposit = new CmDepositCreate('unittest');
        $deposit->setBankAccountId('BA1145');
        $deposit->setDepositDate(new Date('2015-06-30'));
        $deposit->setDepositSlipId('Deposit Slip 2015-06-30');
        $deposit->setDescription('Desc');
        $deposit->setAttachmentsId('AT111');
        $deposit->setTransactionKeysToDeposit([
            1234,
        ]);
        $deposit->setCustomFields([
            'customfield1' => 'customvalue1',
        ]);

        $deposit->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\CmDepositCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage CM Deposit must have at least 1 transaction key to deposit
     */
    public function testMissingCmDepositEntries()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $deposit = new CmDepositCreate('unittest');
        $deposit->setBankAccountId('BA1145');
        $deposit->setDepositDate(new Date('2015-06-30'));
        $deposit->setDepositSlipId('Deposit Slip 2015-06-30');

        $deposit->writeXml($xml);
    }
}
