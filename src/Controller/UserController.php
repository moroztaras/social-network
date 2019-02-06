<?php

namespace App\Controller;

use App\Components\User\Forms\AccountSecurityForm;
use App\Components\User\Forms\ProfileAccountForm;
use App\Components\User\Models\ProfileAccountModel;
use App\Components\User\Models\ProfileSecurityModel;
use App\Components\User\UserSecurityManager;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user_default")
     * @Security("is_granted('ROLE_USER')")
     */
    public function user()
    {
        $user = $this->getUser();

        return $this->dashboard($user->getId());
    }

    /**
     * @Route("/user/{id}", name="user_canonical", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function dashboard($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('User/dashboard.html.twig', [
      'user' => $user,
    ]);
    }

    /**
     * @Route("/user/edit", name="user_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(ProfileAccountModel $accountModel, Request $request)
    {
        $user = $this->getUser();

        return $this->editCanonical($user->getId(), $accountModel, $request);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit_canonical", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function editCanonical($id, ProfileAccountModel $accountModel, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $this->denyAccessUnlessGranted('edit', $user);
        $accountModel->setUser($user->getAccount());
        $form = $this->createForm(ProfileAccountForm::class, $accountModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $accountModel->save();

            return $this->redirectToRoute('user_canonical', ['id' => $user->getId()]);
        }

        return $this->render('User/edit.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
    }

    /**
     * @Route("/user/{id}/security", name="user_security_canonical", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function securityCanonical($id, ProfileSecurityModel $profileSecurityModel, UserSecurityManager $userSecurityManager, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $profileSecurityModel->setUser($user);
        $form = $this->createForm(AccountSecurityForm::class, $profileSecurityModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            if ($encoder->isPasswordValid($user, $profileSecurityModel->getPassword())) {
                $userSecurityManager->getChange($user);

                return $this->redirectToRoute('user_canonical', ['id' => $user->getId()]);
            } else {
                return $this->redirectToRoute('user_security_canonical', ['id' => $user->getId()]);
            }
        }

        return $this->render('User/security.html.twig', [
      'form' => $form->createView(),
      'user' => $user,
    ]);
    }
}
