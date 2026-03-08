<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CategorieController extends AbstractController
{
    #[Route('/categories', name: 'app_categories')]
    public function index(CategorieRepository $repo): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $repo->findAll()
        ]);
    }

    #[Route('/categories/nouvelle', name: 'app_categorie_nouvelle')]
    public function nouvelle(Request $request, EntityManagerInterface $em): Response
    {
        $categorie = new Categorie();

        $form = $this->createFormBuilder($categorie)
            ->add('nom')
            ->add('description', null, ['required' => false])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie créée');

            return $this->redirectToRoute('app_categories');
        }

        return $this->render('categorie/nouvelle.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/categories/{id}/supprimer', name: 'app_categorie_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, Categorie $categorie, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {

            $em->remove($categorie);
            $em->flush();

            $this->addFlash('success', 'Catégorie supprimée');
        }

        return $this->redirectToRoute('app_categories');
    }
}