<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class SignupController extends Controller
{
    public function signAction()
    {
        $db = $this->get('aca.db');


        return $this->render('AcaShopBundle:Signup:signup.html.twig',
            array('')
        );
    }

    public function proceedAction()
    {
        $db = $this->get('aca.db');
        $session = $this->get('session');

        $email = $_POST['email'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $password = $_POST['password'];

        //Here is where a new object would be created by simply adding the above-mentioned variables

    }
}
