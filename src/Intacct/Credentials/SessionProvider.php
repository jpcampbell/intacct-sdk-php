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

namespace Intacct\Credentials;

use Intacct\Content;
use Intacct\Functions\ApiSessionCreate;
use Intacct\Xml\RequestHandler;
use Intacct\Endpoint;

class SessionProvider
{
    
    /** @var array */
    private $lastExecution = [];

    /**
     * Initializes the class.
     */
    public function __construct()
    {
        //nothing to see here
    }
    
    /**
     * Get array of the last execution's requests and responses
     *
     * @return array
     */
    public function getLastExecution()
    {
        return $this->lastExecution;
    }

    /**
     * Get config array
     *
     * @param SenderCredentials $senderCreds
     * @param Endpoint $endpoint
     * @return array
     */
    private function getConfig(SenderCredentials $senderCreds, Endpoint $endpoint)
    {
        $config = [
            'sender_id' => $senderCreds->getSenderId(),
            'sender_password' => $senderCreds->getPassword(),
            'control_id' => 'sessionProvider',
            'unique_id' => false,
            'dtd_version' => '3.0',
            'transaction' => false,
            'endpoint_url' => $endpoint->getEndpoint(),
            'verify_ssl' => $endpoint->getVerifySSL(),
            'no_retry_server_error_codes' => [], //retry all 500 level errors
        ];
        
        return $config;
    }

    /**
     * Get API session array
     *
     * @param array $config
     * @return array
     */
    private function getAPISession(array $config)
    {
        $contentBlock = new Content();
        $getApiSession = new ApiSessionCreate();
        $contentBlock->append($getApiSession);

        $requestHandler = new RequestHandler($config);
        
        try {
            $response = $requestHandler->executeSynchronous($config, $contentBlock);
        } finally {
            $this->lastExecution = $requestHandler->getHistory();
        }
        
        $operation = $response->getOperation();
        $authentication = $operation->getAuthentication();
        $result = $operation->getResult(0);
        $data = $result->getData();
        $api = $data->api;

        $session = [
            'session_id' => strval($api->sessionid),
            'endpoint_url' => strval($api->endpoint),
            'verify_ssl' => $requestHandler->getVerifySSL(),
            'current_company_id' => $authentication->getCompanyId(),
            'current_user_id' => $authentication->getUserId(),
            'current_user_is_external' => $authentication->getSlideInUser(),
        ];
        
        return $session;
    }

    /**
     * Generate an Intacct session based on login credentials
     *
     * @param LoginCredentials $loginCreds
     * @return SessionCredentials
     */
    public function fromLoginCredentials(LoginCredentials $loginCreds)
    {
        $senderCreds = $loginCreds->getSenderCredentials();
        $endpoint = $loginCreds->getEndpoint();
        $config = $this->getConfig($senderCreds, $endpoint);
        $config['company_id'] = $loginCreds->getCompanyId();
        $config['user_id'] = $loginCreds->getUserId();
        $config['user_password'] = $loginCreds->getPassword();
        $config['mock_handler'] = $loginCreds->getMockHandler();
        
        $session = $this->getAPISession($config);

        return new SessionCredentials($session, $senderCreds);
    }

    /**
     * Generate an Intacct session based on existing session credentials
     *
     * @param SessionCredentials $sessionCreds
     * @return SessionCredentials
     */
    public function fromSessionCredentials(SessionCredentials $sessionCreds)
    {
        $senderCreds = $sessionCreds->getSenderCredentials();
        $endpoint = $sessionCreds->getEndpoint();
        $config = $this->getConfig($senderCreds, $endpoint);
        $config['session_id'] = $sessionCreds->getSessionId();
        $config['mock_handler'] = $sessionCreds->getMockHandler();
        
        $session = $this->getAPISession($config);

        return new SessionCredentials($session, $senderCreds);
    }
}
