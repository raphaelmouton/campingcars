<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Entity\Message;
use App\Form\MessageType;
use App\Entity\Conversation;
use App\Repository\MessageRepository;
use App\Repository\AnnoncesRepository;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MessageController extends AbstractController
{
    #[Route('/messages/{annonceId}/{conversationId}', name: 'app_message_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnoncesRepository $annoncesRepository, ConversationRepository $conversationRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager, $annonceId, $conversationId, MailerInterface $mailer): Response
    {
        // dd($this->isGranted('IS_AUTHENTICATED_FULLY'))
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'danger',
                'Merci de vous connecter pour envoyer un message !'
            );
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }
        $user = $this->getUser();

        $allmessages = $messageRepository->findBy(['conversation' => $conversationId]);
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        $conversationRepository = $entityManager->getRepository(Conversation::class);
        // Créer un objet DateTime avec l'heure actuelle en France
        $timezone = new DateTimeZone('Europe/Paris');
        $date = new DateTimeImmutable('now', $timezone);
        $annonceId2 = $annoncesRepository->findOneBy(['id' => $annonceId]);
        // EMPECHE A UN AUTRE USER DACCEDER A LA CONVERSATION
        $envoyeur = $this->getUser();
        $conversation = $conversationRepository->find($conversationId);
        $receveur = null;
        $TitreAnnonce = $annonceId2->getTitre();

        // dd($conversation->getReceveur()->getId();
        if ($conversation != null) {
            if ($conversation->getEnvoyeur()->getEmail() == $envoyeur->getEmail() or ($this->getUser()->getRoles()[0] == "ROLE_ADMIN")) {
                $receveur = $conversation->getReceveur();
            } elseif ($conversation->getReceveur()->getEmail() == $envoyeur->getEmail()) {
                $receveur = $conversation->getEnvoyeur();
            } else {
                $this->addFlash(
                    'danger',
                    'Vous ne pouvez-vous pas accèder à la conversation de quelqu\'un d\'autre !'
                );
                return $this->redirectToRoute('app_conversation', [], Response::HTTP_SEE_OTHER);
            }
        }
        ////
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $annoncesRepository->find($annonceId2);
            if (!$annonce) {
                throw $this->createNotFoundException('Cette annonce n\'existe pas !');
            }
            // Récupérez les deux utilisateurs impliqués dans la conversation
            $envoyeur = $this->getUser();
            $receveur = $annonce->getUser();

            // dd($envoyeur->getEmail());
            //changer si envoyeur est receveur
            if ($envoyeur->getEmail() == $receveur->getEmail()) {
                $conversation = $messageRepository->findOneBy(['Receveur' => $receveur->getEmail(), 'conversation' => $conversationId, 'annonce' => $annonceId2]);
                $receveur = $envoyeur;
                // Vérifiez si une conversation existe déjà pour l'annonce et les deux utilisateurs
                $conversation = $conversationRepository->findOneBy(['id' => $conversationId]);
                if ($conversation === null) {
                    $this->addFlash(
                        'danger',
                        'Vous ne pouvez-vous pas vous envoyer un message à vous même !'
                    );
                    $route = $request->headers->get('referer');
                    return $this->redirect($route);
                }
            } else {
                // Vérifiez si une conversation existe déjà pour l'annonce et les deux utilisateurs
                $conversation = $conversationRepository->findOneBy(['id' => $conversationId]);
            }

            if ($conversation === null) {
                // Requête pour compter les conversations créées aujourd'hui par l'utilisateur
                $conversationsToday = $conversationRepository->findNumberConversations($user->getEmail());
                // dd($conversationsToday);

                // Limiter à 5 conversations par jour
                if (count($conversationsToday) >= 5) {
                    $this->addFlash(
                        'danger',
                        'Vous avez atteint la limite de conversations créées aujourd\'hui. Pour plus d\'informations contactez-nous sur : contact@ulmavendre.fr'
                    );
                    return $this->redirectToRoute('app_conversation', [], Response::HTTP_SEE_OTHER);
                }
                
                // Si aucune conversation n'existe, créez-en une nouvelle
                // dd($annonce);
                $conversation = new Conversation();
                $conversation->setCreatedAt(new \DateTimeImmutable()); // Ajout de la date de création
                $conversation->setAnnonce($annonce);
                $conversation->setEnvoyeur($envoyeur);
                $conversation->setReceveur($receveur);
                $conversation->setSujet("Message de : - Pour l'annonce : " . $annonce->getTitre());
                $entityManager->persist($conversation);
                $entityManager->flush();
                $message->setUser($this->getUser());
                $message->setAnnonce($annonce);
                $message->setEnvoyeur($envoyeur);
                $message->setReceveur($receveur);
                $message->setPosteLe($date);
                $message->setContenu($form->getData()->getContenu());
                $message->setConversation($conversation);
                $entityManager->persist($message);
                $entityManager->flush();
                $email = (new TemplatedEmail())
                    ->from(new Address('contact@ulmavendre.fr', 'Raphaël - Ulmavendre.fr'))
                    ->to($receveur->getEmail())
                    ->replyTo('contact@ulmavendre.fr')
                    ->subject($TitreAnnonce . ' : Message en attente sur ulmavendre.fr')
                    ->htmlTemplate('emails/newMessage.html');
                $mailer->send($email);
                return $this->redirectToRoute('app_conversation', [], Response::HTTP_SEE_OTHER);
            } else {
                $message->setAnnonce($annonce);
                $message->setEnvoyeur($user);
                $message->setReceveur($conversation->getReceveur());
                $message->setContenu($form->getData()->getContenu());
                $message->setConversation($conversation);
                $message->setPosteLe($date);
                if ($conversation->getEnvoyeur() != $this->getUser()) {
                    $email = (new TemplatedEmail())
                    ->from(new Address('contact@ulmavendre.fr', 'Raphaël - Ulmavendre.fr'))
                    ->to($conversation->getEnvoyeur()->getEmail())
                    ->replyTo('contact@ulmavendre.fr')
                    ->subject($TitreAnnonce . ' : Message en attente sur ulmavendre.fr')
                    ->htmlTemplate('emails/newMessage.html');
                    $mailer->send($email);
                }
                if ($conversation->getEnvoyeur() == $this->getUser()) {
                    $email = (new TemplatedEmail())
                    ->from(new Address('contact@ulmavendre.fr', 'Raphaël - Ulmavendre.fr'))
                    ->to($conversation->getReceveur()->getEmail())
                    ->replyTo('contact@ulmavendre.fr')
                    ->subject($TitreAnnonce . ' : Message en attente sur ulmavendre.fr')
                    ->htmlTemplate('emails/newMessage.html');
                    $mailer->send($email);
                }
                $entityManager->persist($message);
                $entityManager->flush();
                return $this->redirectToRoute('app_conversations_edit', ['id' => $conversationId]);
            }
        }
        return $this->render('message/new.html.twig', [
            'form' => $form->createView(),
            'messages' => $allmessages,
            'annonce' => $annonceId2,
            'conversation' => $conversationId
        ]);
    }

    #[Route('/messages/{id}', name: 'app_message_delete', methods: ['POST'])]
    public function delete(Request $request, Message $message, MessageRepository $messageRepository): Response
    {
        if ($this->getUser()->getRoles()[0] !== "ROLE_ADMIN") {
            $this->addFlash(
                'danger',
                'Vous n\'avez pas les droits pour archiver ce message !'
            );
            return $this->redirectToRoute('your_annonces', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            $message->setContenu("Message supprimé par un administrateur. Pour plus d'informations merci d'envoyer un mail à : contact@ulmavendre.fr");
            $messageRepository->save($message, true);
            $this->addFlash(
                'success',
                'Le message vient d\'être archivée !'
            );
        }

        return $this->redirectToRoute('superAdmin', [], Response::HTTP_SEE_OTHER);
    }
}