<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Shop\Product;
use Symfony\Component\HttpFoundation\Session\Session;
use Aca\Bundle\ShopBundle\Db\DBCommon;

class Cart extends AbstractOrder
{
    /**
     * Product class
     * @var Product
     */
    protected $product;

    protected $grandTotal;

    /**
     * These are the products the user has selected in the shopping cart.
     * @var array
     */
    protected $userSelectedProducts;

    /**
     * @param DBCommon $db
     * @param Session $session
     * @param Product $product
     */
    public function __construct($db, $session, $product)
    {
        parent::__construct($db, $session);
        $this->product = $product;

    }

    /**
     * Delete one product from the shopping cart
     * @throws \Exception
     * @param $product_Id Primary key from product table
     * @return bool
     */
    public function delete($product_Id)
    {

        $cart = $this->session->get('cart');

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['product_id'] == $product_Id) {
                unset($cart[$index]);
            }
        }

        $this->session->set('cart', $cart);

        $didRemove = true;

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['product_id'] == $product_Id) {
                $didRemove = false;
            }
        }
        if (!$didRemove) {
        } else {
            throw new \Exception('Cannot delete item from cart!');
        }

        return $didRemove;


    }

    /**
     * Remove a single item from the shopping cart
     * @param $product_id
     */
    public function remove($product_id)
    {

        $cart = $this->session->get('cart');

        foreach ($cart as $index => $cartitem) {

            if ($cartitem['product_id'] == $product_id) {
                if ($cartitem['quantity'] == 1) {
                    unset($cart[$index]);
                } else {
                    $int = (int)$cart[$index]['quantity'];
                    $int -= 1;
                    $cart[$index]['quantity'] = $int;
                }

            }
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Update the quantity of the shopping cart
     * @param $product_id Primary key from product table
     * @param $updated_quantity input from user
     * @return bool
     * @throws \Exception
     */
    public function update($product_id, $updated_quantity)
    {

        $cart = $this->session->get('cart');

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['product_id'] == $product_id) {
                $cart[$index]['quantity'] = $updated_quantity;
            }
        }

        $this->session->set('cart', $cart);

        $didUpdate = true;

        foreach ($cart as $index => $cartItem) {
            if ($cartItem['product_id'] == $product_Id) {
                $didUpdate = false;
            }
        }
        if (!$didUpdate) {
        } else {
            throw new \Exception('Cannot update item in cart!');
        }

        return $didUpdate;

    }

    /**
     * Get the product ids off the cart that the user has
     * @throws |Exception
     * @return array
     */
    public function getProductIdsInCart()
    {
        $product_id = [];
        $cart = $this->session->get('cart');


        foreach ($cart as $cartitem) {
            $product_id[] = $cartitem['product_id'];
        }
        if (empty($cart)) {
            throw new \Exception('the cart is empty');
        }

        return $product_id;
    }

    /**
     * Gets the products within the cart and the total price
     * @return array
     * @throws \Exception
     */
    public function getCartProducts()
    {
        if (isset($this->userSelectedProducts)) {
            return $this->userSelectedProducts;
        }

        $cartItems = $this->session->get('cart');
        $product_id_in_cart = $this->getProductIdsInCart();
        $products = $this->product->getCartProducts($product_id_in_cart);

        $userSelectedProducts = [];
        $grandTotal = 0.00;
        foreach ($products as $item) {
            foreach ($cartItems as $cartItem) {
                if ($cartItem['product_id'] == $item->product_id) {
                    $item->quantity = $cartItem['quantity'];
                    $userSelectedProducts[] = $item;

                    $item->total = $cartItem['quantity'] * $item->price;
                    $grandTotal += $item->total;


                }

            }

        }

        $this->grandTotal = $grandTotal;
        $this->userSelectedProducts = $userSelectedProducts;


        return $userSelectedProducts;
    }

    /**
     * Gets the grand total of the products and their quantities within the cart
     * @return mixed
     */
    public function getGrandTotal()
    {
        if (!isset($this->grandTotal)) {
            $this->getCartProducts();
        }

        return $this->grandTotal;

    }

    /**
     * Adds an item to the cart.
     * @param $product_id
     * @param $quantity
     */
    public function addItem($product_id, $quantity)
    {

        $cart = $this->session->get('aca.cart');

        /**
         *Add to the cart if its empty.
         */
        if (empty($cart)) {
            $cart[] = array(
                'product_id' => $product_id,
                'quantity' => $quantity
            );
        } else {
            $existingItem = false;   //boolean that was artificially created

            foreach ($cart as &$cartItem) {

                if ($cartItem['product_id'] == $product_id) {

                    $existingItem = true;

                    $cartItem['quantity'] += $quantity;
                }
            };

            if ($existingItem == false) {
                $cart[] = array(
                    'product_id' => $product_id,
                    'quantity' => $quantity
                );
            }
        }

        $this->session->set('cart', $cart);
    }

    public function getAddress()
    {

    }


}
