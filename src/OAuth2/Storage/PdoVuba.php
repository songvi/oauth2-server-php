<?php

namespace OAuth2\Storage;

class PdoVuba extends Pdo{
    protected  $userCredentialService;

    public function setUserCredentialService(UserCredentialsInterface $service){
        $this->userCredentialService = $service;
    }

    public function checkUserCredentials($username, $password)
    {
        return $this->userCredentialService->checkUserCredentials($username, $password);
    }

    /**
     * @param string $username
     * @return array|bool
     */
    public function getUserDetails($username)
    {
        return $this->userCredentialService->getUserDetails($username);
    }
}
