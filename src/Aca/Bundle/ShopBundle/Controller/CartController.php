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

        return new RedirectResponse('/cart');
    }

    public function displayAction()
    {
        $db = $this->get('aca.db');
        $session = $this->get('session');

        $cart = $session->get('cart'); //flaw if there are no items in the cart
        $product_id =[];
        foreach($cart as $cartitem)
        {
            $product_id[] = $cartitem['product_id'];
        }

        $list = implode(',',$product_id);
        $query = "SELECT * FROM aca_product WHERE product_id IN ($list)";
        $db->setQuery($query);
        $shoppingcart = $db->loadObjectList();

        $prodQty = [];
        $GT = 0.00;

        foreach($shoppingcart as $item)
        {
            foreach($cart as $cartitem)
            {
                if($cartitem['product_id'] == $item->product_id)
                {
                    $item->quantity = $cartitem['quantity'];

                    $item->total = $cartitem['quantity'] * $item->price;
                    $GT += $item->total;

                    $prodQty[] = $item;

                }

            }

        }
        return $this->render('AcaShopBundle:Cart:cart.html.twig',
            array(
                'products' => $prodQty,
                'grandTotal' => $GT
            )
        );
    }

    /**
     * Deletes the whole product from the shopping cart
     * ENHANCEMENT: Make it delete quantity
     * @return RedirectResponse
     */
    public function removeAction()
    {
        $product_id = $_POST['product_id'];

        $session = $this->get('session');
        $cart = $session->get('cart');

        foreach($cart as $index => $cartitem)
        {

            if($cartitem['product_id'] == $product_id)
            {
                if($cartitem['quantity'] == 1) {
                    unset($cart[$index]);
                } else {
                    $int=(int)$cart[$index]['quantity'];
                    $int -= 1;
                    $cart[$index]['quantity'] = $int;
                }

            }
        }

        $session->set('cart', $cart);

        return new RedirectResponse('/cart');
    }

    public function updateAction()
    {
        $product_id = $_POST['product_id'];
        $updated_quantity = $_POST['quantity'];

        $session = $this->get('session');
        $cart = $session->get('cart');

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_id) {
                $cart[$index]['quantity'] = $updated_quantity;
            }
        }

        $session->set('cart', $cart);

        return new RedirectResponse('/cart');

    }

    public function deleteAction()
    {
        $product_id = $_POST['product_id'];
        $session = $this->get('session');
        $cart = $session->get('cart');

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_id) {
                unset($cart[$index]);
            }
        }
        $session->set('cart', $cart);

        return new RedirectResponse('/cart');
    }

}

