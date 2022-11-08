<?php

namespace app\controllers\admin;

use RedBeanPHP\R;
use sw\Pagination;

class OrderController extends AppController
{
    public function indexAction()
    {
        $status = get('status', 's');
        $status = $status == 'new' ? 'status = 0' : '';
        
        $page = get('page');
        $perpage = 30;
        $total = R::count('orders', $status);
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();

        $orders = $this->model->getOrders($start, $perpage, $status);
        
        $title = 'Order list';
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'orders', 'pagination', 'total'));
    }
}
