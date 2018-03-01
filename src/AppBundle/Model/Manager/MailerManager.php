<?php

namespace AppBundle\Model\Manager;


use AppBundle\Entity\User;
use Monolog\Handler\HandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\Translator;

class MailerManager
{
    const TRANS_DOMAIN = 'mail';

    private $mailer;
    private $router;
    private $twig;
    private $translator;
    private $logger;
    private $loggerHandler;
    private $siteEmail;
    private $templates;
    private $fromEmail;

    /**
     * MailerManager constructor.
     * @param \Swift_Mailer $mailer
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment $twig
     * @param Translator $translator
     * @param LoggerInterface $logger
     * @param HandlerInterface $loggerHandler
     * @param array $templates
     */
    public function __construct(
        \Swift_Mailer $mailer,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig,
        Translator $translator,
        LoggerInterface $logger,
        HandlerInterface $loggerHandler,
        $siteEmail,
        $templates
    )
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->twig = $twig;
        $this->translator = $translator;
        $this->logger = $logger;
        $this->loggerHandler = $loggerHandler;
        $this->siteEmail = $siteEmail;
        $this->fromEmail = $siteEmail;
        $this->templates = $templates;
    }


    /**
     * @param User $user
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendWelcomeToUser(User $user)
    {
        $template = $this->templates['welcome_template'];
        $requestUrl = $this->router->generate(
            'login'
        );

        $context = [
            'user' => $user->getName(),
            'request_url' => $requestUrl
        ];

        $this->sendEmail($template, $context, $this->fromEmail, $user->getEmail());
    }

    /**
     * @param $templateName
     * @param $context
     * @param $fromEmail
     * @param $toEmail
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    protected function sendEmail($templateName, $context, $fromEmail, $toEmail)
    {
        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $context = $this->twig->mergeGlobals($context);

        $subject = $template->renderBlock('subject', $context);
        $context['message'] = $template->renderBlock('message', $context);

        $textBody = $template->renderBlock('body_text', $context);
        $htmlBody = $template->renderBlock('body_html', $context);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setTo($toEmail);

        if (!empty($htmlBody)) {
            $message
                ->setBody($htmlBody, 'text/html')
                ->addPart($textBody, 'text/plain');
        } else {
            $message->setBody($textBody);
        }

        $this->mailer->send($message);

        /** @var \Twig_Template $template */
        $template = $this->twig->loadTemplate($templateName);
        $context = $this->twig->mergeGlobals($context);

        $this->logger->setHandlers([$this->loggerHandler]);
        $this->logger->info(
            'from:' . $fromEmail .
            ', to:' . $toEmail .
            ', subject:' . trim($template->renderBlock('subject', $context))
        );
    }
}