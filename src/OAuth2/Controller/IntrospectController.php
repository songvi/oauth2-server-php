<?php

namespace OAuth2\Controller;

use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\Storage\AccessTokenInterface;
use Oauth2\Storage\ClientInterface;

class IntrospectController implements IntrospectControllerInterface
{

    private $scope;
    private $state;
    private $client_id;
    private $redirect_uri;
    private $response_type;

    protected $accessTokenStorage;
    protected $clientStorage;
    protected $responseTypes;
    protected $config;
    protected $scopeUtil;

    public function __construct(ClientInterface $clientStorage, AccessTokenInterface $accessTokenStorage)
    {
        $this->clientStorage = $clientStorage;
        $this->accessTokenStorage = $accessTokenStorage;
    }

    public function validateIntrospectRequest(RequestInterface $request, ResponseInterface $response)
    {
        // Make sure a valid client id was supplied (we can not redirect because we were unable to verify the URI)
        if (!$client_id = $request->query('client_id', $request->request('client_id'))) {
            // We don't have a good URI to use
            $response->setError(401, 'Unauthorized', "Unauthorized");
            return false;
        }

        // Get client details
        if (!$clientData = $this->clientStorage->getClientDetails($client_id)) {
            $response->setError(401, 'Unauthorized', 'Unauthorized');
            return false;
        }

        /*
         * $clientData['token']
         * $clientData['token_type_hint']='access_token'
         */
        if (!$authorizedToken = $request->request('access_token')) {
            $response->setError(401, 'Unauthorized', "Unauthorized");
            return false;
        }
        return true;
    }

    public function handleIntrospectRequest(RequestInterface $request, ResponseInterface $response)
    {
        $authorizedToken = $request->request('access_token');
        $tokenInfo = $this->accessTokenStorage->getAccessToken($authorizedToken);

        // Token is not valid
        if (
            !isset($tokenInfo) ||
            isset($tokenInfo['expires']) ||
            (time() > $tokenInfo['expires'])

        ) {
            $response->setStatusCode(200);
            $response->addHttpHeaders(array("Content-Type" => "application/json"));
            $response->addParameters(array('active' => false));
        }

        // Token is valid return 200 with data
        $response->addParameters(array('active' => true));
        $response->addParameters($tokenInfo);
        return $response;

    }
}