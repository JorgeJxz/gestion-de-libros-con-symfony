<?php

namespace App\Controller;

use App\Entity\Usuario;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/usuario/nuevo", name="nuevo_usuario")
     */
    public function nuevo_usuario(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $usuario = new Usuario();
        $formulario = $this->createFormBuilder($usuario)
            ->add('login', TextType::class)
            ->add('password', PasswordType::class)
            ->add('email', EmailType::class)
            ->add('rol', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Insertar'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid())
        {
            $usuario = $formulario->getData();
            $passwordCodificado = $encoder->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($passwordCodificado);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();
            return $this->redirectToRoute('ver_libros');
        }

        return $this->render('nuevo_usuario.html.twig',array('formulario' => $formulario->createView()));
    }
}
