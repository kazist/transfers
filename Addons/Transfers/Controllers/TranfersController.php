<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Transfers\Addons\Transfers\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Transfers\Addons\Transfers\Models\TransfersModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class TransfersController extends AddonController {

    function indexAction($show_inviter_link = false, $my_account_link = 'users.users.edit') {

        $data_arr['show_inviter_link'] = $show_inviter_link;
        $data_arr['my_account_link'] = $my_account_link;


        $this->html = $this->render('Transfers:Addons:Transfers:views:transfers.twig', $data_arr);

        $response = $this->response($this->html);



        return $response;
    }

}
