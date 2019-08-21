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
        $this->products = [['Nom'=>'iPhone X','Slug'=>'iphone-x','Description'=>'Un iPhone de 2017','Prix'=>'999'],
                           ['Nom'=>'iPhone XR','Slug'=>'iphone-xr','Description'=>'Un iPhone de 2018','Prix'=>'1099'],
                           ['Nom'=>'iPhone XS','Slug'=>'iphone-xs','Description'=>'Un iPhone de 2019','Prix'=>'1999']
        ];
    }    


}
