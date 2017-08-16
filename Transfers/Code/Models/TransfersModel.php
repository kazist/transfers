<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Transfers\Transfers\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Transfers\Payments\Code\Models\PaymentModel;
use Subscriptions\Subscriptions\Code\Classes\Subscriber;
use Kazist\Service\Email\Email;
use Transfers\Transactions\Code\Models\TransactionsModel;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class TransfersModel extends BaseModel {

    //put your code here

    public function getQueryBuilder() {

        $factory = new KazistFactory();
        $user = $factory->getUser();


        if (WEB_IS_ADMIN) {
            $user_id = $this->request->request->get('user_id');
        } else {
            $user_id = $user->id;
        }

        $query = parent::getQueryBuilder('#__transfers_transfers', 'ft');


        if ($user_id) {
            $query->where('ft.origin_user_id=' . $user_id);
        }
        return $query;
    }

    public function save($form) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        if (!WEB_IS_ADMIN) {

            $url = $this->generateUrl('transfers.transfers');

            $form['origin_user_id'] = $user->id;
            $form['target_user_id'] = $form['user_id'];

            $form['return_url'] = base64_encode($url);

            unset($form['search_user_id']);
            unset($form['id']);
            unset($form['user_id']);
        }

        $subscriber = $this->getSubscriber($form['origin_user_id']);
        $total_amount = $subscriber->money_in - $subscriber->money_out;

        $params = $this->getInvoice();
        $params = array_reverse($params);
        $total_param = $params[0];
        unset($params[0]);

        if ($total_param->amount > $total_amount) {
            $factory->enqueueMessage('Amount to be transfered (' . $total_param->amount . ') is more than Available Amount (' . $total_amount . ')');
            $factory->redirect('transfers.transfer.transfer');
        }

        $transfer_id = parent::save($form);

        if ($transfer_id) {
            $this->sendEmail($form['origin_user_id'], $form['target_user_id'], $transfer_id);

            $this->getGiveCommission($form['origin_user_id'], $form['target_user_id'], $form['amount'], $transfer_id, $params);
        }
    }

    public function getGiveCommission($origin_user_id, $target_user_id, $amount, $transfer_id, $params) {

        $factory = new KazistFactory();

        $parent_id = '';
        $origin_user = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id' => $origin_user_id));
        $target_user = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id' => $target_user_id));

        $params = array_reverse($params);

        if (!empty($params)) {
            foreach ($params as $key => $param) {

                $data_obj = new \stdClass();
                $data_obj->user_id = $origin_user_id;
                $data_obj->behalf_user_id = $origin_user_id;
                $data_obj->description = ($key) ? $param->title . ' For ' . $parent_id : 'Fund Transfer To ' . $target_user->username;
                $data_obj->item_id = $transfer_id;
                $data_obj->rate_id = $param->id;
                $data_obj->source = 'transfer';
                $data_obj->debit = $param->amount;
                $data_obj->type = 'fund-transfer';

                $id = $factory->saveRecord('#__transfers_transactions', $data_obj);

                if (!$key) {
                    $parent_id = $id;
                }
            }
        }

        $data_obj = new \stdClass();
        $data_obj->user_id = $target_user_id;
        $data_obj->behalf_user_id = $origin_user_id;
        $data_obj->description = 'Fund Transfer From ' . $target_user->username;
        $data_obj->item_id = $transfer_id;
        $data_obj->source = 'transfer';
        $data_obj->credit = $amount;
        $data_obj->type = 'fund-transfer';

        $factory->saveRecord('#__transfers_transactions', $data_obj);
    }

    public function sendEmail($origin_user_id, $target_user_id, $transfer_id) {

        $tmp_array = array();
        $email = new Email();
        $factory = new KazistFactory();

        $tmp_array['user'] = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id' => $origin_user_id));
        $tmp_array['target_user'] = $factory->getQueryBuilder('#__users_users', 'uu', array('id=:id'), array('id' => $target_user_id));
        $tmp_array['transfer'] = $factory->getRecord('#__transfers_transfers', 'ft', array('id=:id'), array('id' => $transfer_id));

        $email->sendDefinedLayoutEmail('transfers.transfers.fundtranfered', $tmp_array['user']->email, $tmp_array);
        $email->sendDefinedLayoutEmail('transfers.transfers.fundreceived', $tmp_array['target_user']->email, $tmp_array);
    }

    public function getAllowedIn() {
        $tmp_array = array();
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('fgn.*');
        $query->from('#__transfers_transfers_allowedin', 'fgn');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = $record->country_id;
            }
        }

        return $tmp_array;
    }

    public function getTransferGateways() {

        $tmp_array = array();
        $factory = new KazistFactory();


        $query = new Query();
        $query->select('fg.*');
        $query->from('#__payments_gateways', 'fg');
        $query->where('fg.can_transfer=1');

        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->long_name . ' (' . $record->short_name . ')');
            }
        } else {
            $factory->enqueueMessage('No Tranfer Gateway Set. Kindly Contact Admin.', 'error');
            $factory->redirect('transfers.transfer.transfer');
        }

        return $tmp_array;
    }

    public function getInvoice() {

        $tmp_array = array();

        $factory = new KazistFactory();



        $form = $this->request->request->get('form', null, null);

        $paymentModel = new PaymentModel();
        $rates = $paymentModel->getPaymentGatewayInvoiceRates($form['amount'], $form['gateway_id'], 'transfer');

        return $rates;
    }

    public function getForm() {

        $factory = new KazistFactory();


        $form = $this->request->request->get('form', null, null);

        return $form;
    }

    public function getSubscriber($user_id = '') {

        $tmp_array = array();

        $factory = new KazistFactory();


        $user = $factory->getUser();

        $form = $this->request->request->get('form', null, null);

        if (!$user_id) {
            $user_id = (!WEB_IS_ADMIN) ? $user->id : $this->request->request->get('user_id');
        }
        $subscriber = new Subscriber();
        $subscriber_obj = $subscriber->getSubscriber($user_id, true, false);

        return $subscriber_obj;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  reverseTransfer
    public function reverseTransfer() {

        $factory = new KazistFactory();

        $transactionModel = new TransactionsModel();

        $id = $this->request->request->get('id');

        $transactionModel->reverseTransaction($id, 'transfer');

        $data_obj = new \stdClass();
        $data_obj->id = $id;
        $data_obj->is_canceled = 1;
        $factory->saveRecord('#__transfers_transfers', $data_obj);
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  saveSettingTransfer
    public function saveSettingTransfer() {

        $ids_arr = array();

        $factory = new KazistFactory();


        $transactionModel = new TransactionsModel();

        $form = $this->request->request->get('form', null, null);
        $country_ids = $form['country_id'];

        if (!empty($country_ids)) {
            foreach ($country_ids as $country_id) {
                $data_obj = new \stdClass();
                $data_obj->country_id = $country_id;

                $where_arr = array();
                $where_arr[] = 'country_id=:country_id';

                $ids_arr[] = $factory->saveRecord('#__transfers_transfers_allowedin', $data_obj, $where_arr, $data_obj);
            }
        }

        if (!empty($ids_arr)) {
            $query = new Query();
            $query->delete('#__transfers_transfers_allowedin');
            $query->where('id NOT IN (:ids)');
            $query->setParameter('ids', implode(',', $ids_arr));
            $query->execute();
        }
    }

    public function getGetawaysInput() {

        $tmp_array = array();

        $query = new Query();
        $query->select('fg.*');
        $query->from('#__payments_gateways', 'fg');
        $query->where('fg.published=1');
        $records = $query->loadObjectList();

        if (!empty($records)) {
            foreach ($records as $record) {
                $tmp_array[] = array('value' => $record->id, 'text' => $record->long_name . ' (' . $record->short_name . ')');
            }
        }

        return $tmp_array;
    }

}
