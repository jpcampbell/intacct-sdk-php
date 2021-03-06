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

class ApAdjustmentCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\ApAdjustmentCreate::writeXml
     */
    public function testDefaultParams()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create_apadjustment>
        <vendorid>VENDOR1</vendorid>
        <datecreated>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </datecreated>
        <apadjustmentitems>
            <lineitem>
                <glaccountno/>
                <amount>76343.43</amount>
            </lineitem>
        </apadjustmentitems>
    </create_apadjustment>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $apAdjustment = new ApAdjustmentCreate('unittest');
        $apAdjustment->setVendorId('VENDOR1');
        $apAdjustment->setTransactionDate(new Date('2015-06-30'));

        $line1 = new ApBillLineCreate();
        $line1->setTransactionAmount(76343.43);

        $apAdjustment->setLines([
            $line1,
        ]);

        $apAdjustment->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\ApAdjustmentCreate::writeXml
     */
    public function testParamOverrides()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create_apadjustment>
        <vendorid>VENDOR1</vendorid>
        <datecreated>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </datecreated>
        <dateposted>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </dateposted>
        <batchkey>20323</batchkey>
        <adjustmentno>234</adjustmentno>
        <action>Submit</action>
        <billno>234235</billno>
        <description>Some description</description>
        <externalid>20394</externalid>
        <basecurr>USD</basecurr>
        <currency>USD</currency>
        <exchratedate>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </exchratedate>
        <exchratetype>Intacct Daily Rate</exchratetype>
        <nogl>false</nogl>
        <customfields>
            <customfield>
                <customfieldname>customfield1</customfieldname>
                <customfieldvalue>customvalue1</customfieldvalue>
            </customfield>
        </customfields>
        <apadjustmentitems>
            <lineitem>
                <glaccountno/>
                <amount>76343.43</amount>
            </lineitem>
        </apadjustmentitems>
    </create_apadjustment>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $apAdjustment = new ApAdjustmentCreate('unittest');
        $apAdjustment->setVendorId('VENDOR1');
        $apAdjustment->setTransactionDate(new Date('2015-06-30'));
        $apAdjustment->setGlPostingDate(new Date('2015-06-30'));
        $apAdjustment->setBatchKey(20323);
        $apAdjustment->setAdjustmentNumber('234');
        $apAdjustment->setAction('Submit');
        $apAdjustment->setBillNumber('234235');
        $apAdjustment->setDescription('Some description');
        $apAdjustment->setExternalId('20394');
        $apAdjustment->setBaseCurrency('USD');
        $apAdjustment->setTransactionCurrency('USD');
        $apAdjustment->setExchangeRateDate(new Date('2015-06-30'));
        $apAdjustment->setExchangeRateType('Intacct Daily Rate');
        $apAdjustment->setDoNotPostToGL(false);
        $apAdjustment->setAttachmentsId('6942');
        $apAdjustment->setCustomFields([
            'customfield1' => 'customvalue1',
        ]);

        $line1 = new ApBillLineCreate();
        $line1->setTransactionAmount(76343.43);

        $apAdjustment->setLines([
            $line1,
        ]);

        $apAdjustment->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\ApAdjustmentCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage AP Adjustment must have at least 1 line
     */
    public function testMissingApAdjustmentEntries()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $apAdjustment = new ApAdjustmentCreate('unittest');
        $apAdjustment->setVendorId('VENDOR1');
        $apAdjustment->setTransactionDate(new Date('2015-06-30'));

        $apAdjustment->writeXml($xml);
    }
}
