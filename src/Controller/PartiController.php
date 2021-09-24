<?php

namespace App\Controller;

use App\Entity\Parti;
use App\Form\PartiType;
use App\Repository\PartiRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parti")
 * @IsGranted("ROLE_USER")
 */
class PartiController extends AbstractController
{
    /**
     * @Route("/", name="parti_index", methods={"GET"})
     */
    public function index(PartiRepository $partiRepository): Response
    {
        return $this->render('parti/index.html.twig', [
            'partis' => $partiRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="parti_new", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function new(Request $request): Response
    {
        $parti = new Parti();
        $form = $this->createForm(PartiType::class, $parti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parti);
            $entityManager->flush();

            return $this->render('parti/index.html.twig', [
                'partis' => $this->getDoctrine()->getManager()->getRepository(Parti::class)->findAll(),
                'success' => 'Le parti a bien été ajouté.',
            ]);
        }

        return $this->render('parti/new.html.twig', [
            'parti' => $parti,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parti_show", methods={"GET"})
     */
    public function show(Parti $parti): Response
    {
        $em = $this->getDoctrine()->getManager(); 
        $repo = $em->getRepository(Parti::class);
        $pariteM = $repo->pariteM($parti)[0]['M'];
        $pariteF = $repo->pariteF($parti)[0]['F'];
        $ageMoyen = $repo->ageMoyen($parti)[0]['ageMoyen'];
        
        return $this->render('parti/show.html.twig', [
            'parti' => $parti,
            'parites' => array('M' => $pariteM, 'F' => $pariteF, 'nb' => $pariteM + $pariteF), 
            'ageMoyen' => $ageMoyen
        ]);
    }

    /**
     * @Route("/{id}/edit", name="parti_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_MODO")
     */
    public function edit(Request $request, Parti $parti): Response
    {
        $form = $this->createForm(PartiType::class, $parti);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->render('parti/index.html.twig', [
                'partis' => $this->getDoctrine()->getManager()->getRepository(Parti::class)->findAll(),
                'success' => 'Le parti a bien été modifié.',
            ]);
        }

        return $this->render('parti/edit.html.twig', [
            'parti' => $parti,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="parti_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Parti $parti): Response
    {
        if(count($parti->getPoliticiens()) == 0) {
            if ($this->isCsrfTokenValid('delete'.$parti->getId(), $request->request->get('_token'))) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($parti);
                $entityManager->flush();
            }
            return $this->render('parti/index.html.twig', [
                'partis' => $this->getDoctrine()->getManager()->getRepository(Parti::class)->findAll(),
                'success' => 'Le parti a bien été supprimé.',
            ]);
        } else {
            $em = $this->getDoctrine()->getManager(); 
            $repo = $em->getRepository(Parti::class);
            $pariteM = $repo->pariteM($parti)[0]['M'];
            $pariteF = $repo->pariteF($parti)[0]['F'];
            $ageMoyen = $repo->ageMoyen($parti)[0]['ageMoyen'];
            
            return $this->render('parti/show.html.twig', [
                'parti' => $parti,
                'parites' => array('M' => $pariteM, 'F' => $pariteF, 'nb' => $pariteM + $pariteF), 
                'ageMoyen' => $ageMoyen, 
                'erreur' => 'Vous ne pouvez pas supprimer ce parti, il reste encore des Politiciens.'
            ]);
        }
    }
}
