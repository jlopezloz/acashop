<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class HomeController extends Controller
{
    public function indexAction()
    {

        $db = $this->get('aca.db');

        $session = $this->get('session');

        $loggedIn = $session->get('logged_in');
        $name = $session->get('name');
        $errorMessage = $session->get('error_message');

        return $this->render(
            'AcaShopBundle:Home:index.html.twig',
            array(
                'loggedIn' => $loggedIn,
                'name' => $name,
                'errorMessage' => $errorMessage
            ));
    }

    /**
     * This logs the user in
     */
    public function loginAction()
    {
        $session = $this->get('session');
        //acquire user input
        $username=$_POST['username'];
//        echo '$username=' . $username . '<br/>';
        $password =$_POST['password'];
//        echo '$password=' . $password . '<br/>';


        //check username and password
        $query= 'SELECT * FROM aca_user WHERE username = "'.$username.'" AND password="'.$password.'"';
//        $query = "SELECT * FROM aca_user WHERE username = $username AND password = $password";

        $db = $this->get('aca.db');
        $db->setQuery($query);
        $user = $db->loadObject();

//        session_start(); symfony does it for you

        if(empty($user)) {
            $session->set('logged_in',0);
            $session->set('error_message', 'Login failed, please try again');
        } else {
            $session->set('logged_in', 1);
            $session->set('name', $user->name);
        }

        //redirect user back to the homepage

        return new RedirectResponse('/');

    }

    public function logoutAction()
    {
        $session = $this->get('session');
        $session->clear(); //or set('logged_in',0)


        return new RedirectResponse('/');
    }



}
