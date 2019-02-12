<?php

namespace App\Controller;

use App\Components\User\Models\RecoverUserModel;
use App\Components\User\Models\RegistrationUserModel;
use App\Components\User\Security\RecoverPassword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User;
use App\Entity\UserAccount;
use App\Components\User\Forms\RecoverUserForm;
use App\Components\User\Forms\RegistrationUserForm;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Components\User\Models\ChangePasswordModel;
use App\Components\User\Forms\ChangePasswordForm;

class SecurityController extends Controller
{
    /**
     * @var AuthenticationUtils
     */
    private $helper;
    /**
     * @var RegistrationUserModel
     */
    private $registrationModel;

    /**
     * @var AuthenticationUtils
     */
    private $authorizationChecker;

    public function __construct(AuthenticationUtils $helper, RegistrationUserModel $registrationModel, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->helper = $helper;
        $this->registrationModel = $registrationModel;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_default');
        }
        $error = $this->helper->getLastAuthenticationError();
        $lastUsername = $this->helper->getLastUsername();

        return $this->render('User/Security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/registration", methods={"GET", "POST"}, name="app_registration")
     */
    public function registration(Request $request)
    {
        if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_default');
        }
        $registrationForm = $this->createForm(RegistrationUserForm::class, $this->registrationModel);
        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $user = $this->registrationModel->getUserHandler();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/Security/register.html.twig', [
            'register_form' => $registrationForm->createView(),
          ]);
    }

    /**
     * @param $token
     * @param Request                       $request
     * @param RecoverPassword               $recoverPassword
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/recover/{token}", name="recover", defaults={"token": "null"})
     */
    public function recover($token, Request $request, RecoverPassword $recoverPassword, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_default');
        }
        if ($token) {
            /** @var UserAccount $userRecover */
            $userAccountRecover = $this->getDoctrine()->getRepository('App:UserAccount')->findOneByTokenRecover($token);

            if ($userAccountRecover) {
                $userPasswordToken = new UsernamePasswordToken($userAccountRecover->getUser(), null, 'main', $userAccountRecover->getUser()->getRoles());
                $this->get('security.token_storage')->setToken($userPasswordToken);

                return $this->redirectToRoute('user_password_recover');
            }
        }

        $recoverModel = new RecoverUserModel();
        $recoverForm = $this->createForm(RecoverUserForm::class, $recoverModel);
        $recoverForm->handleRequest($request);
        if ($recoverForm->isSubmitted() && $recoverForm->isValid()) {
            $email = $recoverModel->getEmail();
            $user = $this->getDoctrine()->getRepository('App:User')->findOneByEmail($email);

            if ($user) {
                $status = $recoverPassword->send($user);
            }

            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/Security/recover.html.twig', [
      'recover_form' => $recoverForm->createView(),
    ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/user/password_recover", name="user_password_recover")
     */
    public function recoverPassword(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $userAccount = $user->getAccount();
        if (!$userAccount->getTokenRecover()) {
            return $this->redirectToRoute('recover');
        }
        $changePasswordModel = new ChangePasswordModel();
        $formChangePassword = $this->createForm(ChangePasswordForm::class, $changePasswordModel);
        $formChangePassword->handleRequest($request);
        if ($formChangePassword->isSubmitted() && $formChangePassword->isValid()) {
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($user, $changePasswordModel->password);
            $user->setPassword($password);
            $userAccount->setTokenRecover(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/Security/recover_password.html.twig', [
      'recover_form' => $formChangePassword->createView(),
    ]);
    }
}
