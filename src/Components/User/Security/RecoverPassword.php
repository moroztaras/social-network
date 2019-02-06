<?php

namespace App\Components\User\Security;

use App\Components\Utils\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\User;

class RecoverPassword
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    private $em;

    private $router;

    /**
     * RecoverPassword constructor.
     *
     * @param \Swift_Mailer          $mailer
     * @param \Twig_Environment      $twig
     * @param EntityManagerInterface $entity_manager
     * @param RouterInterface        $router
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, EntityManagerInterface $entity_manager, RouterInterface $router)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->em = $entity_manager;
        $this->router = $router;
    }

    public function send(User $user)
    {
        $token = TokenGenerator::generateToken();

        $url = $this->router->generate('recover', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
        $fullname = $user->getFullname();

        try {
            $template = $this->twig->render('User/MailTemplate/recover.html.twig', [
        'url' => $url,
        'fullname' => $fullname,
      ]);
        } catch (\Exception $e) {
            return false;
        }

        $mail = new \Swift_Message();
        $mail->setFrom('info@svisteti.dev');
        $mail->setTo($user->getEmail());
        $mail->setSubject('Recover password');
        $mail->setBody($template);

        $user->getAccount()->setTokenRecover($token);
        $this->em->persist($user);
        $this->em->flush();
        $status = $this->mailer->send($mail);
        if ($status) {
            return true;
        } else {
            return false;
        }
    }
}
