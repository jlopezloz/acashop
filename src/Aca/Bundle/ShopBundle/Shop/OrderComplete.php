<?php

namespace Aca\Bundle\ShopBundle\Shop;

class OrderComplete extends AbstractOrder
{

    public function getProducts()
    {
        $orderId = $this->session->get('completed_order_id');

        $query = '
        select
            op.price,
            op.quantity,
            p.name,
            p.description,
            p.image
        from
            aca_order_product op
            join aca_product p on (p.product_id = op.product_id)
        where
            order_id = "'.$orderId.'"
        ';

        $this->db->setQuery($query);
        $products = $this->db->loadObjectList();


        return $products;
    }


    protected function getAddress($type)
    {
        $orderId = $this->session->get('completed_order_id');

        $query ='
        SELECT
        *
        FROM
        aca_order_address
        WHERE
        order_id = "'.$orderId.'" and
        type = "'.$type.'"
        ';

        $this->db->setQuery($query);

        return $this->db->loadObject();
    }

    public function getBillingAddress()
    {
        return $this->getAddress('billing');
    }

    public function getShippingAddress()
    {
        return $this->getAddress('shipping');
    }

}