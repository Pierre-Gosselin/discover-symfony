<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/bonjour", name="bonjour")
     *
     */
    public function bonjour(Request $request)
    {
        $name = "Pierre";
        // $request->get('a) équivaut à $_GET['a'];
        dump($request->get('a'));

        // On fait un rendu de la vue Twig qui est sitée dans le dossier Template/
        // Le controleur passe la variable name à la vue grace au second parametre de render qui est un tableau
        return $this->render('welcome/hello.html.twig',['name'=> $name]);
    }

    /**
     * @Route("/goodbye",name="goodbye")
     *
     */
    public function goodbye()
    {
        return new Response('<body>Aurevoir les gens</body>');
    }


    /**
     * @Route("/hello", name="helloAll")
     */
    public function helloAll()
    {
        return new Response('<body>Hello tout le monde</body>');
    }

    /**
     * @Route("/hello/{nom}", name="hello",requirements={"nom"="[a-z]{3,9}"})
     */
    public function hello($nom)
    {
        $nom = ucfirst($nom);
        return $this->render('welcome/hello.html.twig',['nom'=>$nom]);
    }
}