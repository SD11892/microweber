<?php


namespace shop\orders\controllers;

use Microweber\View;


class Admin
{
    public $app = null;
    public $views_dir = 'views';


    function __construct($app = null)
    {

        only_admin_access();


        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->views_dir = dirname(__DIR__) . DS . 'views' . DS;


    }

    private function _get_orders_from_params($params)
    {
        $ord = 'order_by=id desc';
        $orders = false;

        if (isset($params['order'])) {
            $data['order_by'] = $params['order'];
            $ord = 'order_by=' . $params['order'];
        }

        $orders_type = 'completed';
        $kw = '';

        if (isset($params['keyword'])) {
            $kw = '&search_in_fields=email,first_name,last_name,country,created_at,transaction_id,city,state,zip,address,phone,user_ip,payment_gw&keyword=' . $params['keyword'];
        }

        if (isset($params['order-type']) and $params['order-type'] == 'carts') {
            $orders_type = 'carts';
            $ord = 'order_by=updated_at desc';
            $orders = get_cart('limit=1000&group_by=session_id&no_session_id=true&order_completed=0&' . $ord);
            //$orders = get_cart('debug=1&limit=1000&group_by=id&no_session_id=true&order_completed=0&'.$ord);

        } else {
            if (isset($params['get_new_orders'])) {
                $orders = get_orders('no_limit=true&order_completed=1&order_status=new&' . $ord . $kw);

            } else {
                $orders = get_orders('no_limit=true&order_completed=1&' . $ord . $kw);

            }





        }

        return $orders;

    }

    function index($params)
    {


        $orders_type = 'completed';
        if (isset($params['order-type']) and $params['order-type'] == 'carts') {
            $orders_type = 'carts';
        }

        $has_new = false;

        $orders = $this->_get_orders_from_params($params);
        $params2 = $params;
        $params2['get_new_orders'] = true;
        $new_orders = $this->_get_orders_from_params($params2);
        if($new_orders){
            $has_new = true;
        }




        $abandoned_carts = get_cart('count=1&no_session_id=true&order_completed=0&group_by=session_id');
        $completed_carts = get_orders('count=1&order_completed=1');


        $view_file = $this->views_dir . 'admin.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('has_new', $has_new);
        $view->assign('orders', $orders);
        $view->assign('new_orders', $new_orders);
        $view->assign('orders_type', $orders_type);
        $view->assign('abandoned_carts', $abandoned_carts);
        $view->assign('completed_carts', $completed_carts);

        return $view->display();


    }

    function abandoned_carts($params)
    {
        $abandoned_carts = get_cart('count=1&no_session_id=true&order_completed=0&group_by=session_id');
        $completed_carts = get_orders('count=1&order_completed=1');
        $orders = $this->_get_orders_from_params($params);


        $view_file = $this->views_dir . 'abandoned_carts.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('orders', $orders);

        $view->assign('abandoned_carts', $abandoned_carts);
        $view->assign('completed_carts', $completed_carts);

        return $view->display();


    }

    function edit_order($params)
    {


        $ord = mw()->shop_manager->get_order_by_id($params['order-id']);

        $cart_items = array();
        if (is_array($ord)) {
            $cart_items = false;
            if (empty($cart_items)) {
                $cart_items = mw()->shop_manager->order_items($ord['id']);
            }
        } else {
            mw_error("Invalid order id");
        }

        $show_ord_id = $ord['id'];
        if (isset($ord['order_id']) and $ord['order_id'] != false) {
            $show_ord_id = $ord['order_id'];
        }


        $view_file = $this->views_dir . 'edit_order.php';
        $view = new View($view_file);
        $view->assign('params', $params);
        $view->assign('show_ord_id', $show_ord_id);
        $view->assign('cart_items', $cart_items);
        $view->assign('ord', $ord);

        return $view->display();
    }
}