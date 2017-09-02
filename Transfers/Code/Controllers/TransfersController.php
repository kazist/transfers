<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TransfersController
 *
 * @author sbc
 */

namespace Transfers\Transfers\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Controller\BaseController;
use Transfers\Transfers\Code\Models\TransfersModel;

class TransfersController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();

        return parent::indexAction($offset, $limit);
    }

    public function saveAction($form_data = '') {

        $this->return_url = 'transfers.transfers';

        return parent::saveAction($form_data);
    }

    public function invoiceAction() {

        $this->model = new TransfersModel();

        $rates = $this->model->getInvoice();
        $form = $this->model->getForm();
        $payment_user = $from_user = $this->model->getSubscriber();
        $to_user = $this->model->getUser($form['user_id']);

        $data_arr['description'] = 'Transfer $' . number_format($form['amount'], 2) . ' to ' . $to_user->name;
        $data_arr['gateway'] = $this->model->getGateway($form['gateway_id']);
        $data_arr['rates'] = $rates;
        $data_arr['rates_json'] = json_encode($rates);
        $data_arr['form'] = $form;
        $data_arr['payment_user'] = $payment_user;
        $data_arr['from_user'] = $from_user;
        $data_arr['to_user'] = $to_user;
        $rates_arr = array_reverse($rates);
        $data_arr['total_rate'] = $rates_arr[0];

        $this->html = $this->render('Transfers:Transfers:Code:views:invoice.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

    public function addAction($id = '') {

        $factory = new KazistFactory();

        $this->data_arr['gateway_id'] = $factory->getSetting('payments_gateway_default_gateway');

        return parent::addAction($id);
    }

    public function editAction($id) {

        $factory = new KazistFactory();
        $this->model = new TransfersModel();

        $item = $this->model->getRecord();
        $gateways = $this->model->getTransferGateways();

        $this->data_arr['item'] = $item;
        $this->data_arr['gateways'] = $gateways;
        $this->data_arr['gateway_id'] = $factory->getSetting('payments_gateway_default_gateway');

        return parent::editAction($id);
    }

}
