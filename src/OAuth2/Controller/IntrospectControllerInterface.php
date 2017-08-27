<?php

namespace OAuth2\Controller;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
interface IntrospectControllerInterface
{
    public function handleIntrospectRequest(RequestInterface $request,  ResponseInterface $response);
    public function validateIntrospectRequest(RequestInterface $request,  ResponseInterface $response);
}
