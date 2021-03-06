<?php

/**
 * Copyright 2016 Intacct Corporation.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License"). You may not
 *  use this file except in compliance with the License. You may obtain a copy
 *  of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * or in the "LICENSE" file accompanying this file. This file is distributed on
 * an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
 * express or implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 */

namespace Intacct;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;

class QueryClientTest// extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers Intacct\QueryClient::readAllObjectsByQuery
     * @covers Intacct\QueryClient::performReadByQuery
     * @covers Intacct\QueryClient::performReadMore
     * @covers Intacct\QueryClient::addRecords
     */
    public function testPerformReadByQuery()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>success</status>
            <function>readByQuery</function>
            <controlid>testFunctionId</controlid>
            <data listtype="customer" count="1" totalcount="2" numremaining="1" resultId="6465763031V2wi28CoHYQAAF0HcP8AAAAc5">
                <customer>
                    <RECORDNO>1</RECORDNO>
                    <!-- Removed remaining elements -->
                </customer>
            </data>
        </result>
    </operation>
</response>
EOF;

        $xml2 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>success</status>
            <function>readByQuery</function>
            <controlid>testFunctionId</controlid>
            <data listtype="customer" count="1" totalcount="2" numremaining="0" resultId="6465763031V2wi28CoHYQAAF0HcP8AAAAc5">
                <customer>
                    <RECORDNO>2</RECORDNO>
                    <!-- Removed remaining elements -->
                </customer>
            </data>
        </result>
    </operation>
</response>
EOF;
        $headers = [
            'Content-Type' => 'text/xml; encoding="UTF-8"',
        ];
        $mockResponse = new Response(200, $headers, $xml);
        $mockResponse2 = new Response(200, $headers, $xml2);
        $mock = new MockHandler([
            $mockResponse, $mockResponse2
        ]);

        $params = [
            'sender_id' => 'testsenderid',
            'sender_password' => 'pass123!',
            'session_id' => 'testsession..',
            'mock_handler' => $mock,
            'object' => 'CUSTOMER',
            'control_id' => 'unittest',
            'query' => 'RECORDNO < 3',
            'page_size' => 1,
        ];

        $queryClient = new QueryClient();
        $records = $queryClient->readAllObjectsByQuery($params);

        $this->assertEquals(count($records), 2);
    }

    /**
     * @covers Intacct\QueryClient::readAllObjectsByQuery
     * @covers Intacct\QueryClient::performReadByQuery
     * @covers Intacct\QueryClient::performReadMore
     * @expectedException \Intacct\Xml\Response\Operation\ResultException
     * @expectedExceptionMessage An error occurred trying to get query records
     */
    public function testUnsuccessfulReadByQuery()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>failure</status>
            <function>readByQuery</function>
            <controlid>testFunctionId</controlid>
            <errormessage>
                <error>
                    <errorno>Query Failed</errorno>
                    <description></description>
                    <description2>Object definition CUSTOMER1 not found</description2>
                    <correction></correction>
                </error>
            </errormessage>
        </result>
    </operation>
</response>
EOF;
        $headers = [
            'Content-Type' => 'text/xml; encoding="UTF-8"',
        ];
        $mockResponse = new Response(200, $headers, $xml);
        $mock = new MockHandler([
            $mockResponse,
        ]);

        $params = [
            'sender_id' => 'testsenderid',
            'sender_password' => 'pass123!',
            'session_id' => 'testsession..',
            'mock_handler' => $mock,
            'object' => 'CUSTOMER1',
            'control_id' => 'unittest',
            'query' => 'RECORDNO < 3',
            'page_size' => 1,
        ];

        $queryClient = new QueryClient();
        $queryClient->readAllObjectsByQuery($params);
    }


    /**
     * @covers Intacct\QueryClient::readAllObjectsByQuery
     * @covers Intacct\QueryClient::performReadByQuery
     * @covers Intacct\QueryClient::performReadMore
     * @expectedException \Intacct\Xml\Response\Operation\ResultException
     * @expectedExceptionMessage Query result totalcount of 100001 exceeds max_total_count parameter of 100000
     */
    public function testMaxReadByQueryResults()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>success</status>
            <function>readByQuery</function>
            <controlid>testFunctionId</controlid>
            <data listtype="customer" count="100000" totalcount="100001" numremaining="1" resultId="6465763031V2wi28CoHYQAAF0HcP8AAAAc5">
                <customer>
                    <RECORDNO>1</RECORDNO>
                </customer>
                <!-- Removed remaining elements -->
            </data>
        </result>
    </operation>
</response>
EOF;
        $headers = [
            'Content-Type' => 'text/xml; encoding="UTF-8"',
        ];
        $mockResponse = new Response(200, $headers, $xml);
        $mock = new MockHandler([
            $mockResponse,
        ]);

        $params = [
            'sender_id' => 'testsenderid',
            'sender_password' => 'pass123!',
            'session_id' => 'testsession..',
            'mock_handler' => $mock,
            'object' => 'CUSTOMER',
            'control_id' => 'unittest',
            'query' => 'RECORDNO < 3',
        ];

        $queryClient = new QueryClient();
        $queryClient->readAllObjectsByQuery($params);
    }

    /**
     * @covers Intacct\QueryClient::readAllObjectsByQuery
     * @covers Intacct\QueryClient::performReadByQuery
     * @covers Intacct\QueryClient::performReadMore
     * @expectedException \Intacct\Xml\Response\Operation\ResultException
     * @expectedExceptionMessage An error occurred trying to get query records
     */
    public function testUnsuccessfulReadMore()
    {
        $xml = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>success</status>
            <function>readByQuery</function>
            <controlid>testFunctionId</controlid>
            <data listtype="customer" count="1" totalcount="2" numremaining="1" resultId="6465763031V2wi28CoHYQAAF0HcP8AAAAc5">
                <customer>
                    <RECORDNO>1</RECORDNO>
                    <!-- Removed remaining elements -->
                </customer>
            </data>
        </result>
    </operation>
</response>
EOF;

        $xml2 = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>
<response>
    <control>
        <status>success</status>
        <senderid>testsenderid</senderid>
        <controlid>sessionProvider</controlid>
        <uniqueid>false</uniqueid>
        <dtdversion>3.0</dtdversion>
    </control>
    <operation>
        <authentication>
            <status>success</status>
            <userid>testuser</userid>
            <companyid>testcompany</companyid>
            <sessiontimestamp>2015-12-06T15:57:08-08:00</sessiontimestamp>
        </authentication>
        <result>
            <status>failure</status>
            <function>readMore</function>
            <controlid>testFunctionId</controlid>
            <errormessage>
                <error>
                    <errorno>readMore failed</errorno>
                    <description></description>
                    <description2>Attempt to readMore with an invalid or expired resultId: 6465763031V2wi28CoHYQAAF0HcP8AAAAc5</description2>
                    <correction></correction>
                </error>
            </errormessage>
        </result>
    </operation>
</response>
EOF;
        $headers = [
            'Content-Type' => 'text/xml; encoding="UTF-8"',
        ];
        $mockResponse = new Response(200, $headers, $xml);
        $mockResponse2 = new Response(200, $headers, $xml2);
        $mock = new MockHandler([
            $mockResponse, $mockResponse2
        ]);

        $params = [
            'sender_id' => 'testsenderid',
            'sender_password' => 'pass123!',
            'session_id' => 'testsession..',
            'mock_handler' => $mock,
            'object' => 'CUSTOMER',
            'control_id' => 'unittest',
            'query' => 'RECORDNO < 3',
            'page_size' => 1,
        ];

        $queryClient = new QueryClient();
        $queryClient->readAllObjectsByQuery($params);
    }
}
