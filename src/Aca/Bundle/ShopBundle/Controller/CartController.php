<?php
namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Aca\Bundle\ShopBundle\Shop\Cart;
use Aca\Bundle\ShopBundle\Shop\Product;

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

        /** @var Product $product; */
        $product = $this->get('aca.product');
        /** @var Cart $cart; */
        $cart = $this->get('aca.cart');

        $session = $this->get('session');
        $loggedIn = $session->get('logged_in');

        $cartItems = $session->get('cart'); //flaw if there are no items in the cart


        $product_id = $cart->getProductIds();
        $shoppingCart = $product->getAllProducts($product_id);



        $prodQty = [];
        $GT = 0.00;

        foreach($shoppingCart as $item)
        {
            foreach($cartItems as $cartitem)
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
                'grandTotal' => $GT,
                'loggedIn' =>$loggedIn
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

    /**
     * @return RedirectResponse
     */
    public function updateAction()
    {
        $product_id = $_POST['product_id'];
        $updated_quantity = $_POST['quantity'];

        /**
         * @var Cart $cart
         */
        $cart = $this->get('aca.cart');
        $cart->update($product_id, $updated_quantity);

        return new RedirectResponse('/cart');

    }

    public function deleteAction()
    {
        $product_Id = $_POST['product_id'];

        /** @var Cart $cart */
        $cart = $this->get('aca.cart');
        $cart->delete($product_Id);
        return new RedirectResponse('/cart');


    }

    public function shippingAddressAction()
    {
        /** @var Session $session */
        $session = $this->get('session');
        /** @var DBCommon $db */
        $db = $this->get('aca.db');
        /** @var int $userId Logged in user identifier */
        $userId = $session->get('user_id');


        // Get the shipping_address_id and billing_address_id from the user table

        $query = '
        select
            shipping_address_id,
            billing_address_id
        from
            aca_user
        where
            user_id = "'.$userId.'"
            ';

        $db->setQuery($query);
        $shippingIds = $db->loadObject();
        $shippingAddressId = $shippingIds->shipping_address_id;
        $billingAddressId = $shippingIds->billing_address_id;


        // Get shipping and billing address
        $shippingQuery = '
        select
            *
        from
            aca_order_address
        where
            id = "'.$shippingAddressId.'"'
        ;
        $db->setQuery($shippingQuery);
        $shippingAddress = $db->loadObject();
        $billingQuery = '
        select
            *
        from
            aca_order_address
        where
            id ="'.$billingAddressId.'"
            ';
        $db->setQuery($billingQuery);
        $billingAddress = $db->loadObject();




        return $this->render('AcaShopBundle:Shipping:address.html.twig',
            array(
                'shipping' => $shippingAddress,
                'billing' => $billingAddress
            )
        );


    }


}

