<?php

namespace OAuth2\Controller;

use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;

interface IntrospectControllerInterface
{
    public function validateIntrospectRequest(RequestInterface $request, ResponseInterface $response);
}
