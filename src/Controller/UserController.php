<?php

namespace App\Controller;

use App\Form\User\Model\ProfileModel;
use App\Form\User\ProfileForm;
use App\Components\User\Forms\AccountSecurityForm;
use App\Components\User\Models\ProfileSecurityModel;
use App\Components\User\UserSecurityManager;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @var
     */
    private $passwordEncoder;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * UserController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param FlashBagInterface            $flashBag
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, FlashBagInterface $flashBag)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->flashBag = $flashBag;
    }

    /**
     * @Route("/user", methods={"GET"}, name="user_default")
     * @Security("is_granted('ROLE_USER')")
     */
    public function user()
    {
        $user = $this->getUser();

        return $this->dashboard($user->getId());
    }

    /**
     * @Route("/user/{id}", methods={"GET"}, name="user_canonical", requirements={"id"="\d+"}, defaults={"id" = null})
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
     * @Route("/user/edit", methods={"GET", "POST"}, name="user_edit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(ProfileModel $profileModel, Request $request)
    {
        $user = $this->getUser();

        return $this->editCanonical($user->getId(), $profileModel, $request);
    }

    /**
     * @Route("/user/{id}/edit", methods={"GET", "POST"}, name="user_edit_canonical", requirements={"id"="\d+"}, defaults={"id" = null})
     * @Security("is_granted('ROLE_USER')")
     */
    public function editCanonical($id, ProfileModel $profileModel, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $this->denyAccessUnlessGranted('edit', $user);
        $profileModel->setUser($user);
        $form = $this->createForm(ProfileForm::class, $profileModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $profileModel->save($user);
            $this->flashBag->add('success', 'user_edit_save');

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
    public function securityCanonical($id, ProfileSecurityModel $profileSecurityModel, UserSecurityManager $userSecurityManager, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $this->denyAccessUnlessGranted('edit', $user);
        $profileSecurityModel->setUser($user);
        $form = $this->createForm(AccountSecurityForm::class, $profileSecurityModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordEncoder->isPasswordValid($user, $profileSecurityModel->getPassword())) {
                $userSecurityManager->getChange($user);
                $this->flashBag->add('success', 'user_change_security_successfully');

                return $this->redirectToRoute('user_canonical', ['id' => $user->getId()]);
            } else {
                $this->flashBag->add('danger', 'data_is_not_correct');

                return $this->redirectToRoute('user_security_canonical', ['id' => $user->getId()]);
            }
        }

        return $this->render('User/security.html.twig', [
          'form' => $form->createView(),
          'user' => $user,
        ]);
    }
}
