<?php

namespace App\Components\Mailer;

class MailerManager
{
    const NO_REPLAY = 'no-replay@mediastark.com';

    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param $twig
     * @param $options
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function template($twig, $options)
    {
        return $this->twig->render($twig, $options);
    }

    public function messages($subject, $body, $to, $from = null, array $header = [])
    {
        if (!$from) {
            $from = static::NO_REPLAY;
        }
        $mail = new \Swift_Message();
        $mail->setFrom($from)->setTo($to);
        $mail->setSubject($subject);
        $mail->setBody($body, 'text/html');

        return $this->send($mail);
    }

    public function send(\Swift_Message $messages)
    {
        try {
            $this->mailer->send($messages);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
