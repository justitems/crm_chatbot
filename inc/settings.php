<?php
/**
 * Settings Inc
 *
 * This file contains the functions required
 * to display the settings in the CRM Settings app
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// Verify if the app is enabled
if ( md_the_option('app_crm_chatbot_enabled') && md_the_plan_feature('app_crm_chatbot_enabled') && md_the_team_role_permission('crm_chatbot') && the_crm_apps_installed_app('crm_chatbot') ) {

    // Set general's tab
    set_crm_settings_general_tab (
        'crm_chatbot_general_chatbot_tab',
        array(
            'name' => $this->lang->line('crm_chatbot'),
            'icon' => '<img src="' . base_url('assets/base/user/apps/collection/crm-chatbot/img/cover.png') . '" alt="' . $this->lang->line('crm_chatbot') . '">',
            'position' => 10
        )
    );

    // Set general's tab option
    set_crm_settings_general_tab_option (
        'crm_chatbot_general_chatbot_tab',
        array(
            'template_slug' => 'category',
            'slug' => 'crm_chatbot_general_chatbot_tab_notifications_category',
            'label' => $this->lang->line('crm_chatbot_notifications'),
            'position' => 1
        )
    );

    // Set general's tab option
    set_crm_settings_general_tab_option (
        'crm_chatbot_general_chatbot_tab',
        array(
            'template_slug' => 'checkbox_input',
            'slug' => 'crm_chatbot_new_threads_alerts',
            'label' => $this->lang->line('crm_chatbot_new_threads_alerts'),
            'label_description' => $this->lang->line('crm_chatbot_new_threads_alerts_description'),
            'position' => 2
        )
    );

    // Set general's tab option
    set_crm_settings_general_tab_option (
        'crm_chatbot_general_chatbot_tab',
        array(
            'template_slug' => 'checkbox_input',
            'slug' => 'crm_chatbot_new_messages_alerts',
            'label' => $this->lang->line('crm_chatbot_new_messages_alerts'),
            'label_description' => $this->lang->line('crm_chatbot_new_messages_alerts_description'),
            'position' => 3
        )
    );    

    // Set general's tab option
    set_crm_settings_general_tab_option (
        'crm_chatbot_general_chatbot_tab',
        array(
            'template_slug' => 'checkbox_input',
            'slug' => 'crm_chatbot_new_threads_notifications',
            'label' => $this->lang->line('crm_chatbot_new_threads_notifications'),
            'label_description' => $this->lang->line('crm_chatbot_new_threads_notifications_description'),
            'position' => 4
        )
    );

    // Set general's tab option
    set_crm_settings_general_tab_option (
        'crm_chatbot_general_chatbot_tab',
        array(
            'template_slug' => 'checkbox_input',
            'slug' => 'crm_chatbot_new_messages_notifications',
            'label' => $this->lang->line('crm_chatbot_new_messages_notifications'),
            'label_description' => $this->lang->line('crm_chatbot_new_messages_notifications_description'),
            'position' => 5
        )
    );

}

/* End of file settings.php */