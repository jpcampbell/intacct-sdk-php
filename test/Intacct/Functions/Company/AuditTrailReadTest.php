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

namespace Intacct\Functions\Company;

use Intacct\Xml\XMLWriter;

class AuditTrailReadTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\Functions\Company\AuditTrailRead::writeXml
     */
    public function testConstruct()
    {
        $expected = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<function controlid="unittest">
    <getObjectTrail>
        <object>GLENTRY</object>
        <objectKey>GLENTRY123</objectKey>
    </getObjectTrail>
</function>
EOF;

        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString('    ');
        $xml->startDocument();

        $getAuditTrail = new AuditTrailRead('unittest');
        $getAuditTrail->setObjectName('GLENTRY');
        $getAuditTrail->setObjectKey('GLENTRY123');

        $getAuditTrail->writeXml($xml);

        $this->assertXmlStringEqualsXmlString($expected, $xml->flush());
    }
}
