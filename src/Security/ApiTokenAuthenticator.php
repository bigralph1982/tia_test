<?php

namespace App\Security;

use App\Repository\Backend\AdministratorsDevicesRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{

    private $administratorsDevicesRepository;
    
    

    public function __construct(AdministratorsDevicesRepository $administratorsDevicesRepository)
    {
        $this->administratorsDevicesRepository = $administratorsDevicesRepository;
       
    }

    public function supportsRememberMe()
    {
        return false;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('apiKey');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $apiToken = $request->headers->get('apiKey');


        // $userType = $request->headers->get('X-USER-TYPE');

        if (null === $apiToken) {
            // The token header was empty, authentication fails with HTTP Status
            // Code 401 "Unauthorized"
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        $badge = new UserBadge($apiToken, function($token){

            $user = $this->getUser($token);
            
            return $user;

        });

        // if (null === $userType) {
        //     // The token header was empty, authentication fails with HTTP Status
        //     // Code 401 "Unauthorized"
        //     throw new CustomUserMessageAuthenticationException('No User type provided');
        // }
        $entity = new SelfValidatingPassport($badge);

        

        return $entity;
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
       
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function getUser($token){

        $user = $this->administratorsDevicesRepository->findOneBy(["apiKey" => $token]);


        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('error.invalid_user_account');
        }

        $user = $user->getAdministrator();
        
        // user disabled
        if ($user->isDisabled()) {
            throw new CustomUserMessageAuthenticationException('error.account_disabled');
        }

       

        // user disabled
        if ($user->isDeleted()) {
            throw new CustomUserMessageAuthenticationException('error.account_deleted');
        }

        return $user;
    }

    
}
