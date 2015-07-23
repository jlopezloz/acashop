<?php


namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class ProductController extends Controller
{
    public function showAction()
    {

        $db = $this->get('aca.db');
        $query = 'select * from aca_product';
        $db->setQuery($query);
        $products = $db->loadObjectList();

        #this is the way of var-dumping in order to see what we have just accomplished.
        return $this->render('AcaShopBundle:Product:list.html.twig',
            array(
                'products' => $products
            )
        );

    }


}