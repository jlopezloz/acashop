<?php
namespace Aca\Bundle\ShopBundle\Registration;

use Symfony\Component\HttpFoundation\Session\Session;

class Signup
{
    protected $email;
    protected $fname;
    protected $lname;
    protected $password;

    public function __construct($email, $fname, $lname, $password, $db)
    {
        //$db = $this->get('aca.db');


        $this->email = $email;
        $this->fname = $fname;
        $this->lname = $lname;
        $this->password = $password;
        $fullName = $fname . ' ' . $lname;
        $message = "You are all set! Let's see what your profile looks like";

        $query = "INSERT INTO aca_user (name, username, password) VALUES ('$fullName', '$email', '$password')
        ";

        $db->setQuery($query);
        $db->query();
        
    }


}

