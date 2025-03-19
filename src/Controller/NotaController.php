<?php

namespace App\Controller;

use App\Entity\Nota;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotaController extends AbstractController
{
    #[Route('/', name: 'nota_list')]
    public function index(EntityManagerInterface $em): Response
    {
        // Obtener las notas
        $notas = $em->getRepository(Nota::class)->findAll();

        // Obtener la hora actual del servidor (hora local de Guatemala)
        $horaActual = new \DateTime('now', new \DateTimeZone('America/Guatemala'));

        // Pasar las notas y la hora actual a la vista
        return $this->render('nota/index.html.twig', [
            'notas' => $notas,
            'horaActual' => $horaActual->format('d/m/Y H:i'),
        ]);
    }
    

    #[Route('/nota/nueva', name: 'nota_nueva')]
    public function nueva(Request $request, EntityManagerInterface $em)
    {
        if ($request->isMethod('POST')) {
            $titulo = $request->request->get('titulo');
            $contenido = $request->request->get('contenido');

            $nota = new Nota();
            $nota->setTitulo($titulo);
            $nota->setContenido($contenido);
            $nota->setCreadoEn(new \DateTime('now', new \DateTimeZone(date_default_timezone_get())));
            $em->persist($nota);
            $em->flush();

            return $this->redirectToRoute('nota_list');
        }
        return $this->render('nota/nueva.html.twig');
    }

    #[Route('/nota/{id}/eliminar', name: 'nota_eliminar')]
    public function eliminar(EntityManagerInterface $em, $id)
    {
        $nota = $em->getRepository(Nota::class)->find($id);
        if ($nota) {
            $em->remove($nota);
            $em->flush();
        }
        return $this->redirectToRoute('nota_list');
    }
}
