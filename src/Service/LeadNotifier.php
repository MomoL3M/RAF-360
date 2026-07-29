<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\ContactRequest;
use App\Dto\DiagnosticRequest;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Envoi des emails transactionnels de génération de leads (§13.3).
 * Confirmation à l'utilisateur + notification interne. Rendu via gabarits Twig
 * (HTML + texte). Les envois passent par Messenger (async + retry, cf. messenger.yaml) :
 * un échec de dispatch est journalisé et remonté (Sentry) SANS casser le parcours.
 */
final readonly class LeadNotifier
{
    public function __construct(
        private MailerInterface $mailer,
        private LoggerInterface $logger,
        #[Autowire('%env(MAILER_FROM_ADDRESS)%')] private string $fromAddress,
        #[Autowire('%env(MAILER_FROM_NAME)%')] private string $fromName,
        #[Autowire('%env(LEAD_NOTIFICATION_EMAIL)%')] private string $notificationEmail,
    ) {
    }

    public function handleContact(ContactRequest $request): void
    {
        $this->confirm($request->email, $request->nom, 'contact');
        $this->notifyInternal('Nouveau message de contact', [
            'Nom' => $request->nom,
            'Email' => $request->email,
            'Société' => $request->societe,
            'Téléphone' => $request->telephone,
            'Message' => $request->message,
        ]);
    }

    public function handleDiagnostic(DiagnosticRequest $request): void
    {
        $this->confirm($request->email, null, 'diagnostic');
        $this->notifyInternal('Nouvelle demande de diagnostic', [
            'SIREN' => $request->siren,
            'Email' => $request->email,
            'Site web' => $request->siteWeb,
            "Type d'activité" => $request->typeActivite,
        ]);
    }

    private function confirm(string $to, ?string $nom, string $type): void
    {
        if ('' === trim($to)) {
            return;
        }

        $subject = 'contact' === $type
            ? 'Nous avons bien reçu votre message'
            : 'Votre diagnostic RAF360 est en préparation';

        $this->send(
            (new TemplatedEmail())
                ->to($to)
                ->subject($subject)
                ->htmlTemplate('emails/lead_confirmation.html.twig')
                ->textTemplate('emails/lead_confirmation.txt.twig')
                ->context(['nom' => $nom, 'type' => $type]),
        );
    }

    /**
     * @param array<string, string> $fields libellé => valeur
     */
    private function notifyInternal(string $subject, array $fields): void
    {
        $fields = array_filter($fields, static fn (string $v): bool => '' !== trim($v));

        $this->send(
            (new TemplatedEmail())
                ->to($this->notificationEmail)
                ->subject('[RAF360] '.$subject)
                ->htmlTemplate('emails/lead_notification.html.twig')
                ->textTemplate('emails/lead_notification.txt.twig')
                ->context(['subject' => $subject, 'fields' => $fields]),
        );
    }

    private function send(TemplatedEmail $email): void
    {
        try {
            $email->from(new Address($this->fromAddress, $this->fromName));
            $this->mailer->send($email);
        } catch (\Throwable $e) {
            $this->logger->error('Échec d\'envoi d\'un email transactionnel', ['exception' => $e]);
        }
    }
}
