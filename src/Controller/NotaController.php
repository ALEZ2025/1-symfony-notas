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

            /*Se miran zumbando en ALR patrullando
            Puro kamikaze y nomás el terror sembrando
            De arriba p'abajo, ahí nos miran patrullando
            Cero tolerancia pa'l que la ande cagando

            Ya se las sabrita que no nos gustan las fallas
            La gorra de lado y la glockson viene eclipsada
            Con un toque bueno y a punta de carcajadas
            Afirma pendientes, nunca se nos pasa nada
            La santa protege si salimos a topones

            Y al cien con los jefes, estamos a la puta orden
            Pa morir nacimos, siempre estamos a la orden
            Y uno de lavada si salimos a misiones
            Y ahí nos ven pasar

            Zumbando los Ranas por el bulevar
            Y el aparato en mano y el tostón atrás
            Y el mayor de los Ranas siempre al frente va
            En uno y al tentón

            Quedó comprobado la vez del jefe ratón
            Que vamos de fresa, no tenemos compasión
            A punta de tostonazos traíamos el boludón*/





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
