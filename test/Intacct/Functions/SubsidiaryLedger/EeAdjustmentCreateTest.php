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

class EeAdjustmentCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\EeAdjustmentCreate::writeXml
     */
    public function testDefaultParams()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create_expenseadjustmentreport>
        <employeeid>E0001</employeeid>
        <datecreated>
            <year>2015</year>
            <month>06</month>
            <day>30</day>
        </datecreated>
        <expenseadjustments>
            <expenseadjustment>
                <glaccountno/>
                <amount/>
            </expenseadjustment>
        </expenseadjustments>
    </create_expenseadjustmentreport>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $expense = new EeAdjustmentCreate('unittest');
        $expense->setEmployeeId('E0001');
        $expense->setTransactionDate(new Date('2015-06-30'));

        $line1 = new EeAdjustmentLineCreate();

        $expense->setLines([
            $line1,
        ]);

        $expense->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\EeAdjustmentCreate::writeXml
     */
    public function testParamOverrides()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create_expenseadjustmentreport>
        <employeeid>E0001</employeeid>
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
        <batchkey>123</batchkey>
        <adjustmentno>ADJ001</adjustmentno>
        <docnumber>EXP001</docnumber>
        <description>For hotel</description>
        <basecurr>USD</basecurr>
        <currency>USD</currency>
        <expenseadjustments>
            <expenseadjustment>
                <glaccountno/>
                <amount/>
            </expenseadjustment>
        </expenseadjustments>
        <supdocid>AT122</supdocid>
    </create_expenseadjustmentreport>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $expense = new EeAdjustmentCreate('unittest');
        $expense->setEmployeeId('E0001');
        $expense->setTransactionDate(new Date('2015-06-30'));
        $expense->setGlPostingDate(new Date('2015-06-30'));
        $expense->setSummaryRecordNo('123');
        $expense->setExpenseAdjustmentNumber('ADJ001');
        $expense->setExpenseReportNumber('EXP001');
        $expense->setDescription('For hotel');
        $expense->setBaseCurrency('USD');
        $expense->setReimbursementCurrency('USD');
        $expense->setAttachmentsId('AT122');

        $line1 = new EeAdjustmentLineCreate();
        $expense->setLines([
            $line1,
        ]);

        $expense->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\SubsidiaryLedger\EeAdjustmentCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage EE Adjustment must have at least 1 line
     */
    public function testMissingLines()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $expense = new EeAdjustmentCreate('unittest');
        $expense->setEmployeeId('E0001');
        $expense->setTransactionDate(new Date('2015-06-30'));

        $expense->writeXml($xml);
    }
}
