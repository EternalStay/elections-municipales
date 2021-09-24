<?php

namespace App\Controller;

use App\Entity\Affaire;
use App\Form\AffaireType;
use App\Repository\AffaireRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/affaire")
 * @IsGranted("ROLE_USER")
 */
class AffaireController extends AbstractController
{
    /**
     * @Route("/", name="affaire_index", methods={"GET","POST"})
     */
    public function index(Request $request, AffaireRepository $affaireRepository): Response
    {
        $form = $this->createFormBuilder()
        ->add('rechercher', SearchType::class, ['required' => false, 'label' => false])
        ->add('submit', SubmitType::class, array('label' => 'OK'))
        ->getForm();
         
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $em = $this->getDoctrine()->getManager(); 
            $repo = $em->getRepository(Affaire::class);
            $affaires = $repo->findByDesignation($data['rechercher']);
        }
        else {
            $affaires = $affaireRepository->findAll();
        }
        
        return $this->render('affaire/index.html.twig', [
            'affaires' => $affaires,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/new", name="affaire_new", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function new(Request $request): Response
    {
        $affaire = new Affaire();
        $form = $this->createForm(AffaireType::class, $affaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($affaire);
            $entityManager->flush();

            return $this->render('affaire/index.html.twig', [
                'affaires' => $this->getDoctrine()->getManager()->getRepository(Affaire::class)->findAll(),
                'success' => 'L\'affaire a bien été ajoutée.',
            ]);
        }

        return $this->render('affaire/new.html.twig', [
            'affaire' => $affaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="affaire_show", methods={"GET"})
     */
    public function show(Affaire $affaire): Response
    {
        return $this->render('affaire/show.html.twig', [
            'affaire' => $affaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="affaire_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function edit(Request $request, Affaire $affaire): Response
    {
        $form = $this->createForm(AffaireType::class, $affaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('affaire/index.html.twig', [
                'affaires' => $this->getDoctrine()->getManager()->getRepository(Affaire::class)->findAll(),
                'success' => 'L\'affaire a bien été modifiée.',
            ]);
        }

        return $this->render('affaire/edit.html.twig', [
            'affaire' => $affaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="affaire_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Affaire $affaire): Response
    {
        if(count($affaire->getPoliticiensImpliques()) == 0) {
            if ($this->isCsrfTokenValid('delete'.$affaire->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($affaire);
                $entityManager->flush();
            }
            return $this->render('affaire/index.html.twig', [
                'affaires' => $this->getDoctrine()->getManager()->getRepository(Affaire::class)->findAll(),
                'success' => 'L\'affaire a bien été supprimée.',
            ]);
        } else {
            return $this->render('affaire/show.html.twig', [
                'affaire' => $affaire,
                'erreur' => 'Vous ne pouvez pas supprimer cette affaire, il reste encore des Politiciens.', 
            ]);
        }
    }

}
