<?php

namespace App\Controller;

use App\Entity\Politicien;
use App\Form\PoliticienNewType;
use App\Form\PoliticienEditType;
use App\Repository\PoliticienRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/politicien")
 * @IsGranted("ROLE_USER")
 */
class PoliticienController extends AbstractController
{
    /**
     * @Route("/", name="politicien_index", methods={"GET"})
     */
    public function index(PoliticienRepository $politicienRepository): Response
    {
        return $this->render('politicien/index.html.twig', [
            'politiciens' => $politicienRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="politicien_new", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function new(Request $request): Response
    {
        $politicien = new Politicien();
        $form = $this->createForm(PoliticienNewType::class, $politicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($politicien);
            $entityManager->flush();

            return $this->render('politicien/index.html.twig', [
                'politiciens' => $this->getDoctrine()->getManager()->getRepository(Politicien::class)->findAll(),
                'success' => 'Le politicien a bien été ajouté.',
            ]);
        }

        return $this->render('politicien/new.html.twig', [
            'politicien' => $politicien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="politicien_show", methods={"GET"})
     */
    public function show(Politicien $politicien): Response
    {
        return $this->render('politicien/show.html.twig', [
            'politicien' => $politicien,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="politicien_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function edit(Request $request, Politicien $politicien): Response
    {
        $form = $this->createForm(PoliticienEditType::class, $politicien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            return $this->render('politicien/index.html.twig', [
                'politiciens' => $this->getDoctrine()->getManager()->getRepository(Politicien::class)->findAll(),
                'success' => 'Le politicien a bien été modifié.',
            ]);
        }

        return $this->render('politicien/edit.html.twig', [
            'politicien' => $politicien,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="politicien_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Politicien $politicien): Response
    {
        if ($this->isCsrfTokenValid('delete'.$politicien->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($politicien);
            $entityManager->flush();
        }

        return $this->render('politicien/index.html.twig', [
            'politiciens' => $this->getDoctrine()->getManager()->getRepository(Politicien::class)->findAll(),
            'success' => 'Le politicien a bien été supprimé.',
        ]);
    }
}
