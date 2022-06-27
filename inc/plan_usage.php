<?php
/**
 * Plan Usage Inc
 *
 * This file contains the hooks for the
 * plans page in the CRM Settings app
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

    // Load language
    $this->lang->load( 'crm_chatbot_plan_usage', $this->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );

    // Set plan's usage item
    set_crm_settings_plan_usage_item (
        array(
            'item_slug' => 'plan_usage_chatbot',
            'item_icon' => md_the_user_icon(array('icon' => 'forum')),
            'item_name' => $this->lang->line('crm_chatbot'),
            'item_statistics' => array(
                array(
                    'stats' => 'the_crm_chatbot_plan_usage_item_websites'
                ),
                array(
                    'stats' => 'the_crm_chatbot_plan_usage_item_automatic_replies'
                )            
            ),
            'item_position' => 10
        )
    );

    if ( !function_exists('the_crm_chatbot_plan_usage_item_websites') ) {
        
        /**
         * The function the_crm_chatbot_plan_usage_item_websites provides the item's data
         * 
         * @return string with stat
         */
        function the_crm_chatbot_plan_usage_item_websites() {

            // Get codeigniter object instance
            $CI = get_instance();

            // Set number of allowed websites
            $allowed_websites = md_the_plan_feature('app_crm_chatbot_allowed_websites')?md_the_plan_feature('app_crm_chatbot_allowed_websites'):0;

            // Get total number of websites
            $the_websites = $CI->base_model->the_data_where(
                'crm_chatbot_websites',
                'COUNT(*) AS total',
                array(
                    'user_id' => md_the_user_id()
                )
            );

            // Prepare the number of websites
            $total_number_websites = $the_websites?$the_websites[0]['total']:0;

            // Get usage's left
            $usage_left = number_format((100 - (($allowed_websites - $total_number_websites) / $allowed_websites) * 100));

            return array(
                'label' => $CI->lang->line('crm_chatbot_websites'),
                'value' => $total_number_websites . '/' . $allowed_websites,
                'percentage' => $usage_left
            );

        }

    }

    if ( !function_exists('the_crm_chatbot_plan_usage_item_automatic_replies') ) {
        
        /**
         * The function the_crm_chatbot_plan_usage_item_automatic_replies provides the item's data
         * 
         * @return string with stat
         */
        function the_crm_chatbot_plan_usage_item_automatic_replies() {

            // Get codeigniter object instance
            $CI = get_instance();

            // Set number of allowed automatic replies
            $allowed_replies = md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies')?md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies'):0; 
            
            // Set done automatic replies
            $automatic_replies = md_the_user_option(md_the_user_id(), 'crm_chatbot_automatic_replies');

            // The automatic replies container
            $the_automatic_replies = 0;

            // Verify if automatic replies exists
            if ( $automatic_replies ) {

                // Unserialize array
                $replies_array = unserialize($automatic_replies);

                // Set replies
                $the_automatic_replies = $replies_array['replies'];

            }

            // Get usage's left
            $usage_left = $the_automatic_replies?number_format((100 - (($the_automatic_replies - $allowed_replies) / $the_automatic_replies) * 100)):0;

            return array(
                'label' => $CI->lang->line('crm_chatbot_automatic_replies'),
                'value' => $the_automatic_replies . '/' . $allowed_replies,
                'percentage' => $usage_left
            );

        }

    }

}

/* End of file plan_usage.php */