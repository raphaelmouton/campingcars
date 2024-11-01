<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Form\SearchType;
use App\Data\SearchData;
use App\Repository\AnnoncesRepository;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;


#[Route('/toutes-les-annonces')]
class AnnoncesController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnoncesRepository $annonceRepository, Request $request): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
    
        // Assurez-vous que le paramètre de page est bien transmis
        $data->page = $request->query->getInt('page', 1);
    
        // Récupération des annonces paginées
        $annonces = $annonceRepository->findSearch($data);
    
        // Construire la balise canonical en fonction de la page actuelle
        $canonicalUrl = $this->generateUrl('app_annonce_index', ['page' => $data->page > 1 ? $data->page : null], 0);
    
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonces,
            'form' => $form->createView(),
            'canonicalUrl' => $canonicalUrl
        ]);
    }
    
    

    #[Route('/ajouter-une-annonce', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonces();
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $form->getData();
            $annonce->setUser($this->getUser());  
            $chaine = trim($annonce->getTitre());
            $chaine = stripslashes($chaine);
            $chaine = iconv('UTF-8', 'ASCII//TRANSLIT', $chaine);
            $chaine = str_replace(' ', '-', $chaine); // Remplacer les espaces par des tirets
            $chaine = preg_replace('/[^A-Za-z0-9\-]/', '', $chaine); // Remplacer les caractères spéciaux par une chaîne vide
            $chaine = strtolower($chaine); // Convertir en minuscules
            $annonce->setSlugTitre($chaine); 
            $annonce->setCreation(new \DateTime());  
            $annonce->setMAJ(new \DateTime());  
            $annonce->setActive(1);  
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{SlugTitre}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonces $annonce, ConversationRepository $conversationRepository, AnnoncesRepository $annoncesRepository):Response
    {
        $envoyeur = $this->getUser();
        $receveur = $annonce->getUser();
        if ($envoyeur != null) {
            $conversation = $conversationRepository->createQueryBuilder('c')
            ->where('c.annonce = :annonce')
            ->andWhere('(c.Envoyeur = :envoyeur AND c.Receveur = :receveur) OR (c.Envoyeur = :receveur AND c.Receveur = :envoyeur)')
            ->setParameter('annonce', $annonce)
            ->setParameter('envoyeur', $envoyeur)
            ->setParameter('receveur', $receveur)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        } else {
            $conversation = 0;
        }
        if ($conversation != null) {
            $conversation = $conversation->getId();
        } else {
            $conversation = 0;
        }
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'conversationId' => $conversation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($annonce->getImageFile1() || $annonce->getImageFile2() || $annonce->getImageFile3() || 
            $annonce->getImageFile4() || $annonce->getImageFile5() || $annonce->getImageFile6()) {
                $annonce->setUpdatedAt(new \DateTimeImmutable());
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
