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

namespace Intacct\Functions\Common;

use Intacct\Xml\XMLWriter;
use InvalidArgumentException;

class ReadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Intacct\Functions\Common\Read::writeXml
     */
    public function testDefaultParams()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <read>
        <object>CLASS</object>
        <keys></keys>
        <fields>*</fields>
    </read>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $read = new Read('unittest');
        $read->setObjectName('CLASS');

        $read->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\Common\Read::writeXml
     */
    public function testParamOverrides()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <read>
        <object>CLASS</object>
        <keys>Key1,Key2</keys>
        <fields>Field1,Field2</fields>
        <returnFormat>xml</returnFormat>
        <docparid>Sales Invoice</docparid>
    </read>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $read = new Read('unittest');
        $read->setObjectName('CLASS');
        $read->setReturnFormat('xml');
        $read->setFields([
            'Field1',
            'Field2',
        ]);
        $read->setKeys([
            'Key1',
            'Key2',
        ]);
        $read->setDocParId('Sales Invoice');

        $read->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }

    /**
     * @covers Intacct\Functions\Common\Read::setReturnFormat
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Return Format is not a valid format
     */
    public function testReturnFormatJson()
    {
        $read = new Read('unittest');
        $read->setObjectName('CLASS');
        $read->setReturnFormat('json');
    }

    /**
     * @covers Intacct\Functions\Common\Read::setReturnFormat
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Return Format is not a valid format
     */
    public function testReturnFormatCsv()
    {
        $read = new Read('unittest');
        $read->setObjectName('CLASS');
        $read->setReturnFormat('csv');
    }

    /**
     * @covers Intacct\Functions\Common\Read::setKeys
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Keys count cannot exceed 100
     */
    public function testTooManyKeys()
    {
        $keys = [];
        for ($i = 1; $i <= 101; $i++) {
            $keys[] = $i;
        }

        $read = new Read('unittest');
        $read->setObjectName('CLASS');
        $read->setKeys($keys);
    }

    /**
     * @covers Intacct\Functions\Common\Read::setKeys
     * @covers Intacct\Functions\Common\Read::writeXmlKeys
     */
    public function testWriteXmlKeys()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <read>
        <object>CLASS</object>
        <keys>5,6</keys>
        <fields>*</fields>
    </read>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $read = new Read('unittest');
        $read->setObjectName('CLASS');
        $read->setKeys([
            '5',
            '6',
        ]);

        $read->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }
}
