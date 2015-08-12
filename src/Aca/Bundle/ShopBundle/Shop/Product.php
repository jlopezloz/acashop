<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;

class Product
{
    /**
     * @var DBCommon
     */
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    /**
     * This functionality queries the database [YOU COULD LIMIT IT BY LIMITING THE QUERY!]
     * @
     * @return \Aca\Bundle\ShopBundle\Db\stdClass[]
     */
    public function getAllProducts()
    {
        $query = 'select * from aca_product';
        $this->db->setQuery($query);
        $products = $this->db->loadObjectList();

        return $products;
    }

    /**
     * Get a number of products from the DB from the specified productIds
     * @param array $product_ids
     * @return \Aca\Bundle\ShopBundle\Db\stdClass[] ]stdClass[]
     */
    public function getCartProducts($product_ids)
    {

        $list = implode(',',$product_ids);
        $query = "SELECT * FROM aca_product WHERE product_id IN ($list)";
        $this->db->setQuery($query);
        $shoppingcart = $this->db->loadObjectList();


        return $shoppingcart;

    }
}