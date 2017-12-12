<?php
namespace Ichte\User\Auth;

use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthService extends AuthenticationService
{
    public function __invoke(ServiceLocatorInterface $container)
    {
        $credentialValidationCallback = function($dbCredential, $requestCredential) {
            $bcrypt =new \Zend\Crypt\Password\Bcrypt();
            $bcrypt->setCost(14);
            return $bcrypt->verify($requestCredential,$dbCredential);
        };

        $this->authAdapter = new CallbackCheckAdapter(
            \Ahdbase\Common::getdatabase()->Adapter,
            'user',
            null,
            'password',
            $credentialValidationCallback
        );

        $this->setStorage(new Session('AHDCOMPANY'));
        $this->setAdapter($this->authAdapter);
        return $this;
    }
}