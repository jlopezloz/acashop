<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class ReceiptController extends Controller
{

    public function showAction()
    {
        $session = $this->get('session');
        /**
         * @var DBCommon db
         */
        $db = $this->get('aca.db');
        $loggedIn = $session->get('logged_in');

        $orderId = $session->get('completed_order_id');

        $shippingQuery ='
        SELECT
        *
        FROM
        aca_order_address
        WHERE
        order_id = "'.$orderId.'"
        ';

        $db->setQuery($shippingQuery);
        $rows = $db->loadObjectList();

        $billingAddress = null;
        $shippingAddress = null;

        foreach ($rows as $row) {
            if($row->type == 'billing') {
                $billingAddress = $row;
            } else {
                $shippingAddress = $row;
            }
        }

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

        $db->setQuery($query);
        $products = $db->loadObjectList();

        return $this->render('AcaShopBundle:Receipt:receipt.html.twig',
            array(
                'orderId' => $orderId,
                'billing' => $billingAddress,
                'shipping' => $shippingAddress,
                'products' => $products,
                'loggedIn' => $loggedIn
        )
        );

    }


}
