<?php

namespace App\Security;

use App\Entity\Backend\Administrators;
use App\Service\Core\General\MailerService;
use App\Service\Core\Settings\SettingsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\InteractiveAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class LoginFormAuthenticator
 *
 * @package App\Security
 *
 */
class LoginFormAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface, InteractiveAuthenticatorInterface
{
    use TargetPathTrait;

    private $entityManager;

    private $router;

    private $csrfTokenManager;

    private $passwordEncoder;

    private $authorizationChecker;

    private $mailer;


    private $allowTwoStepAuth;



    /**
     * LoginFormAuthenticator constructor.
     *
     * @param EntityManagerInterface        $entityManager
     * @param RouterInterface               $router
     * @param CsrfTokenManagerInterface     $csrfTokenManager
     * @param UserPasswordEncoderInterface  $passwordEncoder
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder,
        AuthorizationCheckerInterface $authorizationChecker,
        MailerService $mailer,
        SettingsService $settings
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->authorizationChecker = $authorizationChecker;
        $this->mailer = $mailer;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->getLoginUrl());
    }

    public function isInteractive(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): ?bool
    {
        $res =  'administrators_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');

       
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'email'      => strtolower($request->request->get('_username')),
            'password'   => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );


        return $credentials;
    }

    public function authenticate(Request $request): Passport
    {
        $credentials = $this->getCredentials($request);

        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $email = $credentials["email"];
        $password = $credentials["password"];

        $badge = new UserBadge($email, function (string $userIdentifier) {
            return $this->getUser($userIdentifier);
        });


        $cred = new PasswordCredentials($password);
        return new Passport(
            $badge,
            $cred
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($username)
    {

        $user = $this->entityManager->getRepository(Administrators::class)->findOneBy(['username' => $username, 'isActive' => [1, 2]]);


        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('error.invalid_user_account');
        }
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

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $user = $token->getUser();



        if ($targetPath = $this->getTargetPath($request->getSession(), $token)) {

            if (!strpos($targetPath, "/logout")) {
                return new RedirectResponse($targetPath);
            }
        }



        $urlName = $this->authorizationChecker->isGranted("ROLE_DEVELOPER") ? 'administrators_index' : 'backend_default';

        return new RedirectResponse($this->router->generate($urlName));
    }


    /**
     * Override to change what happens after a bad username/password is submitted.
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {


        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
            throw new CustomUserMessageAuthenticationException('error custom ');
        }


        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('administrators_login');
    }
}
