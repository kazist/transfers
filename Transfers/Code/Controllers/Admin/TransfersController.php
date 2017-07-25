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

namespace Transfers\Transfers\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Transfers\Transfers\Code\Models\TransfersModel;

class TransfersController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $this->data_arr['gatewaysinput'] = $this->model->getGetawaysInput();

        return parent::indexAction($offset, $limit);
    }

    public function editAction() {

        $this->model = new TransfersModel();

        $item = $this->model->getRecord();
        $gateways = $this->model->getTransferGateways();

        $data_arr['item'] = $item;
        $data_arr['gateways'] = $gateways;

        $this->html = $this->render('Transfers:Transfers:Code:views:admin:edit.index.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

    public function reversetransferAction() {

        $this->model = new TransfersModel();
        $this->model->reverseTransfer();

        return $this->redirectToRoute('admin.transfers.transfers');
    }

}
