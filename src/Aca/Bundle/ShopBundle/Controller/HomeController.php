<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;  //you may be able to delete this

use Aca\Bundle\ShopBundle\Shop\User;

class HomeController extends Controller
{
    /**
     * Evaluates whether the user is signed in or not.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /**
         * @var User $user;
         */
        $user = $this->get('aca.user');
        $loggedIn = $user->isLoggedIn();
        $name = $user->getName();
        $errorMessage = $user->getErrorMessage();

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
        /**
         * @var User $user;
         */
        $user = $this->get('aca.user');
        $user->logIn();

        //redirect user back to the homepage
        return new RedirectResponse('/');

    }

    /**
     * This logs the user out and redirects to the homepage
     * @return RedirectResponse
     */
    public function logoutAction()
    {

        $session = $this->get('session');
        $session->clear(); //or set('logged_in',0)

        return new RedirectResponse('/');
    }



}
