<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

$fecha = date('d-m-Y');

class InicioController extends AbstractController
{

    /**
    * @Route("/", name="inicio")
    */
    public function inicio()
    {
        $fecha = date('d-m-Y');
        return $this->render('inicio.html.twig',['fecha' => $fecha]);
    }

    
        
}

?>