<?php
namespace Aca\Bundle\ShopBundle\Controller;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class CartController extends Controller
{
    public function addAction()
    {
        $session = $this->get('session');
        $cart = $session->get('cart');

        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        /**
         *Add to the cart if its empty.
         */
           if(empty($cart)) {
            $cart[] = array(
                'product_id' => $product_id,
                'quantity' => $quantity
            );
           } else {
               $existingItem = false;   //boolean that was artificially created

               foreach($cart as &$cartItem) {

                   if($cartItem['product_id'] == $product_id) {

                       $existingItem = true;

                       $cartItem['quantity'] += $quantity;
                   }
               };

               if($existingItem==false){
                   $cart[] = array(
                       'product_id' => $product_id,
                       'quantity' => $quantity
                   );
               }
           }

        $session->set('cart',$cart);

        echo '<pre>';
        print_r($cart);

        die('whatuup');
        #Redirect to the cart page
    }

}
