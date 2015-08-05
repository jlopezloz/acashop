<?php

namespace Aca\Bundle\ShopBundle\Shop;

class Cart extends AbstractOrder
{
    /**
     * Delete one product from the shopping cart
     * @throws \Exception
     * @param $product_Id Primary key from product table
     * @return bool
     */
    public function delete($product_Id)
    {

        $cart = $this->session->get('cart');

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_Id) {
                unset($cart[$index]);
            }
        }

        $this->session->set('cart', $cart);

        $didRemove = true;

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_Id) {
                $didRemove = false;
            }
        }
        if (!$didDelete) {
        } else {
            throw new \Exception('Cannot delete item from cart!');
        }

        return $didRemove;


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

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_id) {
                $cart[$index]['quantity'] = $updated_quantity;
            }
        }

        $this->session->set('cart', $cart);

        $didUpdate = true;

        foreach($cart as $index => $cartItem) {
            if($cartItem['product_id'] == $product_Id) {
                $didUpdate= false;
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
     * @return array
     */
    public function getProductIds()
    {
        $product_id =[];
        $cart = $this->session->get('cart');
        

        foreach($cart as $cartitem)
        {
            $product_id[] = $cartitem['product_id'];
        }

        return $product_id;
    }







}
