<?php

namespace App\Security;

use App\Request\LoginRequest;
use App\Response\MessageType;
use App\Response\StatusType;
use App\Service\AuthenticationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginFormAuthenticationService extends AbstractAuthenticator
{
    public function __construct(
        private ValidatorInterface    $validator,
        private TranslatorInterface   $translator,
        private AuthenticationService $authService
    )
    {
    }

    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/auth/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        if (is_array($data)) {
            $request->request->replace($data);
        }

        $loginRequest = new LoginRequest($this->validator, $request);
        $loginRequest->validate();

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        return new Passport(new UserBadge($email), new PasswordCredentials($password));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if (!($request->getPathInfo() === '/auth/login') && $request->isMethod("POST")) {
            throw $exception;
        }

        return new JsonResponse([
            'statusCode' => 400,
            'message' => $this->translator->trans(MessageType::ERROR_LOGIN_BAD_CREDENTIAL->value),
            'status' => StatusType::FAIL
        ], 400);
    }

    public function createToken(Passport $passport, string $firewallName): TokenInterface
    {
        $token = parent::createToken($passport, $firewallName);
        $token->setAttribute('permissions', $this->authService->createPermission($passport->getUser()));
        return $token;
    }

}
