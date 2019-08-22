<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/product/create", name="create")
     */
    public function create(Request $request)
    {

        // On crée un produit "vierge"
        $product = new Product();

        // Créer un formulaire dans le contrôleur
        $form = $this->createFormBuilder($product)
        ->add('name', TextType::class)
        ->add('slug', TextType::class)
        ->add('description', TextareaType::class)
        ->add('price', TextType::class)
        ->getForm();


        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // getData() renvoie les données soumises
            $product = $form->getData();
            
            array_push($this->products,$product);
            return $this->redirectToRoute('product_list');
        }

        return $this->render('product/create.html.twig',
        ['form' => $form->createView(),
        ]);
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
     * @Route("/product/order/{slug}",name="order")
     */
    public function order($slug)
    {
        // On va parcourir tous les produits
        foreach ($this->products as $product)
        {
            // Si le slug du produit correspond à celui de l'URL
            if ($product['slug'] === $slug)
            {
                // Pour créer le message flash en session
                $this->addFlash(
                    'success',
                    'Nous avons bien pris en compte votre commande '.$product['name'].' de '.$product['price'].' €.'
                );
            }
        }

        /* Autre méthode
        // Chercher le produit concerné dans notre tableau
        // le terme "use" du callback permet d'utiliser une variable définie en dehors de celui-ci
        $product = array_filter($this->products,function($product) use ($slug){
            // Cette fonction est appelée sur chaque élément du tableau
            // On renvoie true si on veut garder l'élément dans le filtre qu'on applique
            return $product['slug'] === $slug;
        });
        
        // Réintialise les index du tableau fitlré
        $product = array_values($product);
        // On ne prend qu'un seul produit
        $product = $product[0];
        */
        return $this->redirectToRoute('product_list');
    }


}
