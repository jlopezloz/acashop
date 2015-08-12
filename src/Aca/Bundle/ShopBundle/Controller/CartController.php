<?php
namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Aca\Bundle\ShopBundle\Shop\Cart;
use Aca\Bundle\ShopBundle\Shop\Product;
use Aca\Bundle\ShopBundle\Shop\User;

class CartController extends Controller
{
    /**
     * Adds an item to the cart and registers it within the session
     * @return RedirectResponse /cart
     */
    public function addAction()
    {
        /**
         * @var Cart $cart;
         */
        $cart = $this->get('aca.cart');

        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $cart->addItem($product_id, $quantity);

        return new RedirectResponse('/cart');
    }

    /**
     * Shows the items that are in the sessions/user's cart.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayAction()
    {
        /**
         * @var Cart $cart
         */
        $cart = $this->get('aca.cart');

        /**
         * @var User $user;
         */
        $user = $this->get('aca.user');
        $loggedIn = $user->isLoggedIn();


        $UserSelectedProducts = null;
        $grandTotal = null;
        $errorMessage = null;

        try{
            $UserSelectedProducts = $cart->getCartProducts();

            $grandTotal = $cart->getGrandTotal();

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        //fix the error message here
        return $this->render('AcaShopBundle:Cart:cart.html.twig',
            array(
                'products' => $UserSelectedProducts,
                'grandTotal' => $grandTotal,
                'loggedIn' =>$loggedIn,
                'errorMessage' =>$errorMessage
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
        /**
         * @var Cart $cart;
         */
        $cart = $this->get('aca.cart');

        $cart->remove($product_id);

        return new RedirectResponse('/cart');


    }

    /**
     * Updates the item quantity in the cart
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

    /**
     * Completely deletes the item from the cart, regardless of the quantity.
     * @return RedirectResponse
     * @throws \Exception
     */
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

        if(empty($userId)){
            $session->set('error_message', 'Please create an account before proceeding to check-out');
            return new RedirectResponse('/');  //probably better to direct the user to the sign-up page.
        }
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

