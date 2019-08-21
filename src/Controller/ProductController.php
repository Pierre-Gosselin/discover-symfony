<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    private $products = [];

    public function __construct()
    {
        // On intialise un tableau avec des produits
        // L'attribut $products est accessible sur toutes les routes...
        $this->products = [['name'=>'iPhone X','slug'=>'iphone-x','description'=>'Un iPhone de 2017','price'=>'999'],
                           ['name'=>'iPhone XR','slug'=>'iphone-xr','description'=>'Un iPhone de 2018','price'=>'1099'],
                           ['name'=>'iPhone XS','slug'=>'iphone-xs','description'=>'Un iPhone de 2019','price'=>'1999']
        ];
    }

    /**
     * @Route("/product/random",name="random")
     */
    public function random()
    {
        // On récupère une clé aléatoire du tableau
        $key = array_rand($this->products);
        // On récupère le product "random"
        $product = $this->products[$key];

        return $this->render('product/random.html.twig',['product' => $product]);
    }

    /**
     * @Route("/product",name="product")
     */
    public function product()
    {
        return $this->render('product/show.html.twig',['products' => $this->products]);
    }

    /**
     * @Route("/product/{slug}",name="oneProduct")
     */
    public function oneProduct($slug)
    {
        // On va parcourir tous les produits
        foreach ($this->products as $product)
        {
            // Si le slug du produit correspond à celui de l'URL
            if ($product['slug'] === $slug)
            {
                // Si un produit existe avec le slug, on retourne le template et on arrête le code
                return $this->render('product/random.html.twig',['product' => $product]);
            }
        }
        // On s'assure de parcourir tout le tableau et seulement on affiche la 404
        throw $this->createNotFoundException();
    }

    /**
     * @Route("/product/create",name="create")
     */
    public function create()
    {

    }
}
