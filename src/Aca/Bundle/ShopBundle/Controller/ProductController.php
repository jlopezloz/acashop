<?php


namespace Aca\Bundle\ShopBundle\Controller;


use Aca\Bundle\ShopBundle\Shop\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class ProductController extends Controller
{
    public function showAction()
    {
        /** @var Product $product */
        $product = $this->get('aca.product');
        $products = $product->getAllProducts();

        #this is the way of var-dumping in order to see what we have just accomplished.
        return $this->render('AcaShopBundle:Product:list.html.twig',
            array(
                'products' => $products
//                'loggedIn' => $loggedIn
            )
        );

    }


}