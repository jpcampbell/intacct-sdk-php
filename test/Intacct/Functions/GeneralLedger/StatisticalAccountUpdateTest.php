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

class StatisticalAccountUpdateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\GeneralLedger\StatisticalAccountUpdate::writeXml
     */
    public function testConstruct()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <update>
        <STATACCOUNT>
            <ACCOUNTNO>9000</ACCOUNTNO>
            <TITLE>hello world</TITLE>
        </STATACCOUNT>
    </update>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $account = new StatisticalAccountUpdate('unittest');
        $account->setAccountNo('9000');
        $account->setTitle('hello world');

        $account->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\GeneralLedger\StatisticalAccountUpdate::writeXml
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Account No is required for update
     */
    public function testRequiredAccountNo()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $account = new StatisticalAccountUpdate('unittest');
        //$account->setAccountNo('9000');
        $account->setTitle('hello world');

        $account->writeXml($xml);
    }
}
