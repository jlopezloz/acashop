<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;

class User
{
    /**
     * @var DBCommon $db;
     */
    protected $db;

    /**
     * @var Session $session;
     */
    protected $session;

    public function __construct($db, $session)
    {

        $this->session = $session;
        $this->db = $db;

    }

    public function isLoggedIn()
    {
       $loggedIn = $this->session->get('logged_in');

        return $loggedIn;
    }

    public function setLoggedIn()
    {
        $loggedIn = $this->session->set('logged_in', 1);

        return $loggedIn;

    }

    public function setLoggedOut()
    {
        $loggedIn = $this->session->set('logged_in',0);

        return $loggedIn;
    }

    public function getName()
    {
        $name = $this->session->get('name');

        return $name;
    }

    public function setName($name)
    {
        $name  = $this->session->set('name', $name);

        return $name;
    }

    public function getErrorMessage()
    {
        $errorMessage = $this->session->get('error_message');

        return $errorMessage;
    }

    public function setErrorMessage($message)
    {
        $errorMessage = $this->session->set('error_message',$message);

        return $errorMessage;
    }

    public function setUserId($user)
    {
        $userId = $this->session->set('user_id', $user);

        return $userId;

    }

    public function logIn()
    {

        //acquire user input
        if(isset($_POST))
        {
            $username=$_POST['username'];
//          echo '$username=' . $username . '<br/>';
            $password =$_POST['password'];
//          echo '$password=' . $password . '<br/>';
        } else {

        }

        //check username and password
        $query= 'SELECT * FROM aca_user WHERE username = "'.$username.'" AND password="'.$password.'"';
//        $query = "SELECT * FROM aca_user WHERE username = $username AND password = $password";

        $this->db->setQuery($query);
        $user = $this->db->loadObject();


//        session_start(); symfony does it for you

        if(empty($user)) {
            $this->setLoggedOut();
            $this->setErrorMessage('Login Failed. Please Try Again');

        } else {
            $this->setLoggedIn();
            $this->setName($user->name);
            $this->setUserId($user->user_id);
        }


    }

}
