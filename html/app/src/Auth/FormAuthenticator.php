<?php

namespace App\Auth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

class FormAuthenticator extends AbstractFormLoginAuthenticator
{
    /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface */
    /** @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder */
    private $pwdEncoder;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface */
    private $csrfTokenManager;

    public function __construct(UserPasswordEncoderInterface $pwdEncoder, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->pwdEncoder = $pwdEncoder;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function supports(Request $request)
    {
        return 'signin' === $request->attributes->get('_route')
        && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return $request->get('signin');
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['_csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        /** @var \Symfony\Bridge\Doctrine\Security\User\EntityUserProvider $userProvider */
        return $userProvider->loadUserByUsername($credentials['identifier']);
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('signin');
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->pwdEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        return new RedirectResponse( $this->router->generate('index') );
    }
}