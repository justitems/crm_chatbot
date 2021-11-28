<?php
/**
 * App Inc
 *
 * This file contains the hooks required
 * to register the Chatbot app
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Register the chatbot app
set_crm_apps_app(
    'crm_chatbot',
    array(
        'app_name' => $this->lang->line('crm_chatbot'),
        'app_category' => 'websites',
        'app_description' => $this->lang->line('crm_chatbot_description'),
        'app_cover' => base_url('assets/base/user/apps/collection/crm-chatbot/img/cover.png'),
        'app_screenshots' => array(
            array(
                'thumbnail' => base_url('assets/base/user/apps/collection/crm-chatbot/img/thumbnail.png'),
                'original' => base_url('assets/base/user/apps/collection/crm-chatbot/img/large.png')
            )
        )
    )
);

/* End of file user.php */