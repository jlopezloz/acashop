<?php


namespace Aca\Bundle\ShopBundle\Controller;


use Aca\Bundle\ShopBundle\Shop\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Aca\Bundle\ShopBundle\Shop\User;
class ProductController extends Controller
{
    public function showAction()
    {
        /**
         * @var User $user
         */
        $user = $this->get('aca.user');
        $loggedIn = $user->isLoggedIn();

        /** @var Product $product */
        $product = $this->get('aca.product');
        $products = $product->getAllProducts();

        #this is the way of var-dumping in order to see what we have just accomplished.
        return $this->render('AcaShopBundle:Product:list.html.twig',
            array(
                'products' => $products,
                'loggedIn' => $loggedIn
            )
        );

    }


}