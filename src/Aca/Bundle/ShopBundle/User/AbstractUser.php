<?php

namespace Aca\Bundle\ShopBundle\User;


use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;
abstract class AbstractUser
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