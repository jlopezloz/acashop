<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Aca\Bundle\ShopBundle\Shop\OrderComplete;

class ReceiptController extends Controller
{

    public function showAction()
    {
        $session = $this->get('session');

        $session->remove('cart');

        $loggedIn = $session->get('logged_in');



        /**
         * @var OrderComplete $order
         */
        $order = $this->get('aca.order');

        $orderId = $session->get('completed_order_id');

        $products = $order->getProducts();

        $billingAddress = $order->getBillingAddress();

        $shippingAddress = $order->getShippingAddress();


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
