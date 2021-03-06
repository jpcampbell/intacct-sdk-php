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

namespace Intacct\Functions\GeneralLedger;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class StatisticalAccountCreateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\GeneralLedger\StatisticalAccountCreate::writeXml
     */
    public function testConstruct()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <create>
        <STATACCOUNT>
            <ACCOUNTNO>9000</ACCOUNTNO>
            <TITLE>hello world</TITLE>
            <ACCOUNTTYPE>forperiod</ACCOUNTTYPE>
        </STATACCOUNT>
    </create>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $account = new StatisticalAccountCreate('unittest');
        $account->setAccountNo('9000');
        $account->setTitle('hello world');
        $account->setReportType('forperiod');

        $account->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\GeneralLedger\StatisticalAccountCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Account No is required for create
     */
    public function testRequiredAccountNo()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $account = new StatisticalAccountCreate('unittest');
        //$account->setAccountNo('9000');
        $account->setTitle('hello world');
        $account->setReportType('forperiod');

        $account->writeXml($xml);
    }

    /**
     * @covers Intacct\Functions\GeneralLedger\StatisticalAccountCreate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Title is required for create
     */
    public function testRequiredTitle()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $account = new StatisticalAccountCreate('unittest');
        $account->setAccountNo('9000');
        //$account->setTitle('hello world');
        $account->setReportType('forperiod');

        $account->writeXml($xml);
    }
}
