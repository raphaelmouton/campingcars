<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Repository\MessageRepository;
use App\Repository\ConversationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class ConversationController extends AbstractController
{
    #[Route('/conversations', name: 'app_conversation')]
    public function index(Security $security, MessageRepository $messageRepository, ConversationRepository $conversationRepository): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'danger',
                'Merci de vous connecter pour envoyer un message !'
            );
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }
        $envoyeur = $this->getUser();
        $conversations = $conversationRepository->findConversations($envoyeur);
        // $countUnreadConversations = $conversationRepository->countUnreadConversations($this->getUser());

        // dd($conversations->getId());
        return $this->render('message/conversations.html.twig', [
            'conversations' => $conversations,
        ]);
    }

    #[Route('/conversations/{id}/edit', name: 'app_conversations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Security $security, MessageRepository $messageRepository, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository, $id): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash(
                'danger',
                'Merci de vous connecter pour envoyer un message !'
            );
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);

        }
        $user = $this->getUser()->getEmail();
        $conversationEnvoyeur = $conversationRepository->findOneBy(['id' => $id, 'Envoyeur' => $user]);
        $conversationReceveur = $conversationRepository->findOneBy(['id' => $id, 'Receveur' => $user]);
        // dd($conversationEnvoyeur->getReceveur());
        if(($conversationEnvoyeur != null) AND ($conversationEnvoyeur->getEnvoyeur() == $this->getUser()->getEmail())){
                $conversationEnvoyeur->setVuParReceveur(0);
                $conversationEnvoyeur->setVuParEnvoyeur(1);
                $conversationRepository->save($conversationEnvoyeur, true);   
                return $this->redirectToRoute('app_message_new', ['annonceId' => $conversationEnvoyeur->getAnnonce()->getId(),'conversationId' => $id]);
        } 

        if(($conversationReceveur != null) AND ($conversationReceveur->getReceveur() == $this->getUser()->getEmail())){
                $conversationReceveur->setVuParReceveur(1);
                $conversationReceveur->setVuParEnvoyeur(0);
                $conversationRepository->save($conversationReceveur, true);   
                return $this->redirectToRoute('app_message_new', ['annonceId' => $conversationReceveur->getAnnonce()->getId(),'conversationId' => $id]);
        } 
        // dd($conversationEnvoyeur->getAnnonce()->getId());
        return $this->redirectToRoute('app_conversation', [], Response::HTTP_SEE_OTHER);

    }

}
