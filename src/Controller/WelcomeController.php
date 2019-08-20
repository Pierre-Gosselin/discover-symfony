<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController
{
    /**
     * @route("/hello", name="hello")
     *
     */
    public function hello()
    {
        $name = "Pierre";
        return new Response('Salut les gens et '.$name);
    }

    /**
     * @route("/goodbye",name="goodbye")
     *
     */
    public function goodbye()
    {
        return new Response('Aurevoir les gens');
    }
}