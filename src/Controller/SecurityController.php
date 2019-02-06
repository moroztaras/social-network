<?php

namespace App\Controller;

use App\Components\User\Models\RecoverUserModel;
use App\Components\User\Models\RegisterUserModel;
use App\Components\User\Security\RecoverPassword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Entity\User;
use App\Entity\UserAccount;
use App\Components\User\Forms\RecoverUserForm;
use App\Components\User\Forms\RegisterUserForm;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Components\User\Models\ChangePasswordModel;
use App\Components\User\Forms\ChangePasswordForm;

class SecurityController extends Controller
{
    /**
     * @param AuthenticationUtils           $helper
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $helper, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_default');
        }
        $error = $helper->getLastAuthenticationError();
        $lastUsername = $helper->getLastUsername();

        return $this->render('User/Security/login.html.twig', [
      'last_username' => $lastUsername,
      'error' => $error,
    ]);
    }

    /**
     * @param Request                       $request
     * @param RegisterUserModel             $registerModel
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/register", name="register")
     */
    public function register(Request $request, RegisterUserModel $registerModel, AuthorizationCheckerInterface $authorizationChecker)
    {
        if ($authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('user_default');
        }
        $registerForm = $this->createForm(RegisterUserForm::class, $registerModel);
        $registerForm->handleRequest($request);
        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user = $registerModel->getUserHandler();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('User/Security/register.html.twig', [
      'register_form' => $registerForm->createView(),
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

            return $this->redirectToRoute('login');
        }

        return $this->render('User/Security/recover.html.twig', [
      'recover_form' => $recoverForm->createView(),
    ]);
    }

    /**
     * @Route("/logout", name="logout")
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

            return $this->redirectToRoute('login');
        }

        return $this->render('User/Security/recover_password.html.twig', [
      'recover_form' => $formChangePassword->createView(),
    ]);
    }
}
