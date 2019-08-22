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
                           ['name'=>'iPhone XS','slug'=>'iphone-xs','description'=>'Un iPhone de 2019','price'=>'1999'],
                           ['name'=>'iPhone','slug'=>'iphone','description'=>'Un iPhone de 2019','price'=>'1999'],
                           ['name'=>'iPhone 2','slug'=>'iphone-2','description'=>'Un iPhone de 2019','price'=>'1999'],
                           ['name'=>'iPhone 3g','slug'=>'iphone-3g','description'=>'Un iPhone de 2019','price'=>'1999'],
                           ['name'=>'iPhone 4','slug'=>'iphone-4','description'=>'Un iPhone de 2019','price'=>'1999'],
                           ['name'=>'iPhone 4s','slug'=>'iphone-4s','description'=>'Un iPhone de 2019','price'=>'1999']
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
     * @Route("/product.json")
     */
    public function api()
    {
        // On renvoie le tableau des produits sous forme de json
        return $this->json($this->products);
    }

    /**
     * On peut passer à la ligne pour gagner en lisibilité.
     *
     * @Route(
     *     "/product/{page}",
     *     name="product_list",
     *     requirements={"page"="\d+"}
     * )
     */
    public function list($page = 1)
    {
        $products = $this->products;

        $products = array_slice($products,($page-1)*2,2);
        // Calculer le nombre de page maximal
        $maxPages = ceil(count($this->products) /2);

        // Si la page courante est inférieure au nombre maximun de page
        // On renvoie une 404
        if ($page > $maxPages)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('product/show.html.twig',[
            'products' => $products,
            'current_page'=>$page,
            'max_pages'=>$maxPages
        ]);

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
     * @Route("/create/{slug}",name="create")
     */
    public function create($slug)
    {
        
        // On va parcourir tous les produits
        foreach ($this->products as $product)
        {
            // Si le slug du produit correspond à celui de l'URL
            if ($product['slug'] === $slug)
            {
                // Si un produit existe avec le slug, on retourne le template et on arrête le code
                $this->addFlash(
                    'notice',
                    'Nous avons bien pris en compte votre commande '.$product['name'].' de '.$product['price'].' €.'
                );
            }
        }
    
        return $this->redirectToRoute('product_list');
    }
}
