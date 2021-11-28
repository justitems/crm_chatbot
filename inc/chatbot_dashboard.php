<?php
/**
 * Chatbot Dashboard Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks for
 * the CRM Dashboard App
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( md_the_option('app_crm_chatbot_enabled') && md_the_plan_feature('app_crm_chatbot_enabled') && md_the_team_role_permission('crm_chatbot') && the_crm_apps_installed_app('crm_chatbot') ) {

    /**
     * The public set_crm_dashboard_search_filter registers the search's filters
     * 
     * @since 0.0.8.5
     */
    set_crm_dashboard_search_filter(
        'crm_chatbot',
        array(
            'filter_name' => $this->lang->line('crm_chatbot_threads'),
            'filter_icon' => md_the_user_icon(array('icon' => 'forum')),
            'filter_search_data' => 'the_crm_chatbot_search_data_dashboard',
            'filter_position' => 2
        )
    );

    if ( !function_exists('the_crm_chatbot_search_data_dashboard') ) {
        
        /**
         * The function the_crm_chatbot_search_data_dashboard provides a search filter for dashboard
         * 
         * @param array $params contains the parameters
         * 
         * @return array with response
         */
        function the_crm_chatbot_search_data_dashboard($params) {

            // Require the Search Inc Part
            require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/dashboard/chatbot_dashboard_search.php';   

            // Return
            return the_crm_chatbot_search_data_dashboard_from_parts($params);

        }

    }

    /**
     * The public set_crm_dashboard_widget registers the widgets
     * 
     * @since 0.0.8.5
     */
    set_crm_dashboard_widget(
        'crm_chatbot_threads',
        array(
            'widget_name' => $this->lang->line('crm_chatbot_threads'),
            'widget_description' => $this->lang->line('crm_chatbot_threads_description'),
            'widget_icon' => md_the_user_icon(array('icon' => 'forum')),
            'widget_data' => 'the_crm_chatbot_widgets_dashboard',
            'widget_default_enabled' => the_crm_widget_position('crm_chatbot_threads')?the_crm_widget_position('crm_chatbot_threads'):1,
            'css_urls' => array(
                array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/widget-threads.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all')
            ),
            'js_urls' => array(),            
            'widget_position' => 10
        )
    );

    if ( !function_exists('the_crm_chatbot_widgets_dashboard') ) {
        
        /**
         * The function the_crm_chatbot_widgets_dashboard gets chatbot widget content
         * 
         * @return string with response
         */
        function the_crm_chatbot_widgets_dashboard() {

            // Require the Chatbot Widgets Inc Part
            require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/dashboard/chatbot_widgets.php';   

            // Return
            return the_crm_chatbot_widgets_dashboard_from_parts();

        }

    }

}

/* End of file chatbot_dashboard.php */