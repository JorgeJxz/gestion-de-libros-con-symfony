<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Libro;
use App\Entity\Editorial;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;


class LibroController extends AbstractController
{
    /**
    * @Route("/libros", name="ver_libros")
    */
    public function libros()
    {
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libros = $repositorio->findAll();
        return $this->render('lista_libros.html.twig', array('libros' => $libros));
    }

    /**
    * @Route("/libros/{isbn}", name="ficha_libro")
    */

    public function ficha($isbn)
    {
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libro = $repositorio->find($isbn);
        return $this->render('ficha_libro.html.twig', array('libro' => $libro));
    }

    /**
    * @Route("/eliminar/{isbn}", name="eliminar_libro")
    */
    public function eliminarLibro($isbn)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido a administradores');
        $entityManager = $this->getDoctrine()->getManager();
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);

        $libro = $repositorio->find($isbn);
        if ($libro)
        {
            $entityManager->remove($libro);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ver_libros');
    }
    
    /**
    * @Route("/libros/paginas/{pag}", name="filtrar_paginas")
    */
    public function filtrarPaginas($pag)
    {
        /**
         * @var LibroReposity
         */
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libro = $repositorio->nPaginas($pag);
        return $this->render('lista_libros_paginas.html.twig', array('libros' => $libro));
    }

    /**
    * @Route("/nuevo", name="nuevo_libro")
    */
    public function nuevo_libro(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido a administradores');
        $Libro = new Libro();
        $formulario = $this->createFormBuilder($Libro)
            ->add('isbn', TextType::class)
            ->add('titulo', TextType::class)
            ->add('autor', TextType::class)
            ->add('paginas', IntegerType::class)
            ->add('editorial', EntityType::class, ['class' => Editorial::class, 'choice_label' => 'nombre'])
            ->add('save', SubmitType::class, array('label' => 'Enviar'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid())
        {
            $Libro = $formulario->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Libro);
            $entityManager->flush();
            return $this->redirectToRoute('ver_libros');
        }

        return $this->render('nuevo_libro.html.twig',array('formulario' => $formulario->createView()));
    }


    /**
    * @Route("/libro/editar/{isbn}", name="editar_libro")
    */
    public function editar_libro(Request $request, $isbn)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Acceso restringido a administradores');
        $repositorio = $this->getDoctrine()->getRepository(Libro::class);
        $libro = $repositorio->find($isbn);
        $formulario = $this->createFormBuilder($libro)
            ->add('isbn', TextType::class)
            ->add('titulo', TextType::class)
            ->add('autor', TextType::class)
            ->add('paginas', IntegerType::class)
            ->add('editorial', EntityType::class, ['class' => Editorial::class, 'choice_label' => 'nombre'])
            ->add('save', SubmitType::class, array('label' => 'Enviar'))
            ->getForm();
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid())
        {
            $Libro = $formulario->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($Libro);
            $entityManager->flush();
            return $this->redirectToRoute('ver_libros');
        }

        return $this->render('nuevo_libro.html.twig',array('formulario' => $formulario->createView()));
    }

    /**
    * @Route("/buscar", name="buscar_libro")
    */
    public function buscar_libro(Request $request)
    {
        $lib_resultados = null;
        $buscador = $this->createFormBuilder()
            ->add('buscador', TextType::class)
            ->add('buscar', SubmitType::class, array('label' => 'Buscar'))
            ->getForm();

        $buscador->handleRequest($request);

        if ($buscador->isSubmitted() && $buscador->isValid())
        {
            $resultados = $buscador->getData()['buscador'];
            /**
            *@var LibroReposity
            */
            $repositorio = $this->getDoctrine()->getRepository(Libro::class);
            $lib_resultados = $repositorio->buscarLibros($resultados);
        }

        return $this->render('buscar_libros.html.twig',array('buscador' => $buscador->createView(),'libros' => $lib_resultados));
        
    }
}
?>

