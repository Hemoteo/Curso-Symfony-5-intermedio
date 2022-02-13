<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/tarea")
 */
class TareaController extends AbstractController
{
    /**
     * @Route("/", name="tarea_index", methods={"GET"})
     */
    public function index(TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tarea_new", methods={"GET","POST"})
     */
    public function new(Security $security, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tarea->setUsuario($security->getUser());
            $em->persist($tarea);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_show", methods={"GET"})
     */
    public function show(Tarea $tarea): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tarea_edit", methods={"GET","POST"})
     */
    public function edit(Tarea $tarea, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_delete", methods={"DELETE"})
     */
    public function delete(Tarea $tarea, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($this->isCsrfTokenValid('delete'.$tarea->getId(), $request->request->get('_token'))) {
            $em->remove($tarea);
            $em->flush();
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/{id}", name="finalizar_tarea", methods={"POST"})
     */
    public function finalizar(Tarea $tarea, Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if (!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $tarea->setFinalizada(!$tarea->getFinalizada());
        $em->flush();
        return $this->json(['finalizada' => $tarea->getFinalizada()]);
    }
}
