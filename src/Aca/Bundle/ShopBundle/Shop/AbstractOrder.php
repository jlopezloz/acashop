<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class AbstractOrder Skeleton for all the functionality for ordering in the site, anywhere from the cart
 * to the completion of the order
 * @package Aca\Bundle\ShopBundle\Shop
 */
abstract class AbstractOrder
{
    /**
     * @var DBCommon
     */
    protected $db;

    /**
     * @var Session
     */
    protected $session;


    public function __construct($db, $session)
    {
        $this->db = $db;
        $this->session = $session;
    }

}