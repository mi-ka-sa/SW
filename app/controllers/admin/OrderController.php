<?php

namespace app\controllers\admin;

use RedBeanPHP\R;
use sw\Pagination;

/** @property Order $model */
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

    public function editAction()
    {
        $id_order = get('id');
        if (isset($_GET['status'])) {
            $status = get('status');
            if ($this->model->changeStatus($id_order, $status)) {
                $_SESSION['success'] = 'Order status has been successfully changed';
            } else {
                $_SESSION['errors'] = 'Error when updating order status';
            }
        }

        $order = $this->model->getOneOrder($id_order);
        if (!$order) {
            throw new \Exception("Not found order with this id: {$id_order}", 404);
        }

        $title = 'Order №' . $id_order;
        $this->setMeta('Admin::' . $title);
        $this->setData(compact('title', 'order'));
    }
}
