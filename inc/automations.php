<?php
/**
 * Chatbot Inc
 *
 * This file contains the hooks 
 * loaded in the CRM Automations app
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Register a new app
set_crm_automations_app(
    'crm_chatbot',
    array(
        'app_icon' => md_the_user_icon(array('icon' => 'wechat', 'class' => 'theme-color-new')),
        'app_name' => $this->lang->line('crm_chatbot'),
        'app_category' => 'extra_apps',
        'app_trigger' => array(
            'events' => array(
                array(
                    'event_slug' => 'crm_chatbot_new_guest',
                    'event_name' => $this->lang->line('crm_chatbot_new_guest'),
                    'event_description' => $this->lang->line('crm_chatbot_new_guest_description'),
                    'event_autorun' => 0,
                    'event_input_fields' => array(

                        array(
                            'field_slug' => 'crm_chatbot_websites',
                            'field_type' => 'dropdown',
                            'field_label' => $this->lang->line('crm_chatbot_websites'),
                            'field_description' => $this->lang->line('crm_chatbot_websites_description'),
                            'dropdown' => array(
                                'type' => 'local',
                                'source' => 'the_crm_chatbot_template_dropdown_websites',
                                'select' => 'the_crm_chatbot_template_dropdown_website_select',
                                'required' => 1,
                                'validate' => '',
                                'words' => array(
                                    'button_title' => $this->lang->line('crm_chatbot_select_a_website'),
                                    'input_placeholder' => $this->lang->line('crm_chatbot_search_for_websites')
                                )
    
                            )
    
                        )
    
                    ),
                    'event_output_fields' => array(),
                    'event_validate_fields' => 'the_crm_chatbot_validate_new_guest_fields',
                    'event_save_fields' => 'the_crm_chatbot_save_new_guest_fields',
                    'event_data' => 'the_crm_chatbot_the_new_guest_fields',
                    'event_new_task' => 'the_crm_chatbot_new_guest_create_task',
                    'event_run_task' => 'the_crm_chatbot_new_guest_run_task'
                ),
                array(
                    'event_slug' => 'crm_chatbot_new_assigned_category',
                    'event_name' => $this->lang->line('crm_chatbot_new_assigned_category'),
                    'event_description' => $this->lang->line('crm_chatbot_new_assigned_category_description'),
                    'event_autorun' => 0,
                    'event_input_fields' => array(
    
                        array(
                            'field_slug' => 'crm_chatbot_categories',
                            'field_type' => 'dropdown',
                            'field_label' => $this->lang->line('crm_chatbot_categories'),
                            'field_description' => $this->lang->line('crm_chatbot_categories_description'),
                            'dropdown' => array(
                                'type' => 'local',
                                'source' => 'the_crm_chatbot_template_dropdown_categories',
                                'select' => 'the_crm_chatbot_template_dropdown_category_select',
                                'required' => 1,
                                'validate' => '',
                                'words' => array(
                                    'button_title' => $this->lang->line('crm_chatbot_select_a_category'),
                                    'input_placeholder' => $this->lang->line('crm_chatbot_search_for_categories')
                                )
    
                            )
    
                        )
    
                    ),
                    'event_output_fields' => array(),
                    'event_validate_fields' => 'the_crm_chatbot_validate_new_assigned_category_fields',
                    'event_save_fields' => 'the_crm_chatbot_save_new_assigned_category_fields',
                    'event_data' => 'the_crm_chatbot_the_new_assigned_category_fields',
                    'event_new_task' => 'the_crm_chatbot_new_assigned_category_create_task',
                    'event_run_task' => 'the_crm_chatbot_new_assigned_category_run_task'
                ) 

            )          

        ),
        'app_action' => array(
            'actions' => array(
                array(
                    'action_slug' => 'crm_chatbot_save_important',
                    'action_name' => $this->lang->line('crm_chatbot_save_important'),
                    'action_description' => $this->lang->line('crm_chatbot_save_important_description'),
                    'action_autorun' => 0,
                    'action_input_fields' => array(
                        array(
                            'field_slug' => 'crm_chatbot_thread_id_form',
                            'field_type' => 'dynamic_form',
                            'field_label' => $this->lang->line('crm_chatbot_condition'),
                            'field_description' => $this->lang->line('crm_chatbot_thread_id_description'),
                            'field_data' => array(
                                array(
                                    'field_id' => 'thread_id',
                                    'field_name' => $this->lang->line('crm_chatbot_thread_id'),
                                    'field_value' => '',
                                    'field_placeholders' => array(),
                                    'field_required' => 1
                                )
                            ),
                            'dynamic_form' => array(
                                'type' => 'onload',
                                'source' => '',
                                'words' => array()
                            )
                        )
    
                    ),
                    'action_output_fields' => array(),
                    'action_validate_fields' => 'the_crm_chatbot_validate_save_as_important_fields',
                    'action_save_fields' => 'the_crm_chatbot_save_save_as_important_fields',
                    'action_data' => 'the_crm_chatbot_save_as_important_condition_fields',
                    'action_new_task' => 'the_crm_chatbot_save_as_important_create_action_task',
                    'action_run_task' => 'the_crm_chatbot_save_as_important_run_action_task'
                )
            )
        ),
        'crm_app' => 'crm_chatbot'
    )
);

if ( !function_exists('the_crm_chatbot_template_dropdown_websites') ) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_websites gets the websites
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_websites($params) {

        // Require the Websites Dropown Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/templates/websites_dropown.php';   

        // Return
        return the_crm_chatbot_template_dropdown_websites_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_template_dropdown_website_select') ) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_website_select receives information when a website is selected
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_website_select($params) {

        // Require the Websites Dropown Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/templates/websites_dropown.php';   

        // Return
        return the_crm_chatbot_template_dropdown_website_select_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_validate_new_guest_fields') ) {
    
    /**
     * The function the_crm_chatbot_validate_new_guest_fields validates the new guest fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_new_guest_fields($params) {

        // Require the New Guest Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_guest.php';   

        // Return
        return the_crm_chatbot_validate_new_guest_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_new_guest_fields') ) {
    
    /**
     * The function the_crm_chatbot_save_new_guest_fields saves the new guest fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_new_guest_fields($params) {

        // Require the New Guest Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_guest.php';   

        // Return
        return the_crm_chatbot_save_new_guest_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_the_new_guest_fields') ) {
    
    /**
     * The function the_crm_chatbot_the_new_guest_fields gets the new guest fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_the_new_guest_fields($params) {

        // Require the New Guest Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_guest.php';   

        // Return
        return the_crm_chatbot_the_new_guest_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_new_guest_create_task') ) {
    
    /**
     * The function the_crm_chatbot_new_guest_create_task creates new event's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_guest_create_task($params) {

        // Require the New Guest Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/new_guest.php';   

        // Return
        return the_crm_chatbot_new_guest_create_task_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_new_guest_run_task') ) {
    
    /**
     * The function the_crm_chatbot_new_guest_run_task runs new event's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_guest_run_task($params) {

        // Require the New Guest Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/new_guest.php';   

        // Return
        return the_crm_chatbot_new_guest_run_task_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_template_dropdown_categories') ) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_categories gets the categories
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_categories($params) {

        // Require the Categories Dropown Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/templates/categories_dropown.php';   

        // Return
        return the_crm_chatbot_template_dropdown_categories_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_template_dropdown_category_select') ) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_category_select receives information when a category is selected
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_category_select($params) {

        // Require the Categories Dropown Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/templates/categories_dropown.php';   

        // Return
        return the_crm_chatbot_template_dropdown_category_select_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_validate_new_assigned_category_fields') ) {
    
    /**
     * The function the_crm_chatbot_validate_new_assigned_category_fields validates the new assigned category fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_new_assigned_category_fields($params) {

        // Require the New Assigned Category Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_assigned_category.php';   

        // Return
        return the_crm_chatbot_validate_new_assigned_category_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_new_assigned_category_fields') ) {
    
    /**
     * The function the_crm_chatbot_save_new_assigned_category_fields saves the new assigned category fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_new_assigned_category_fields($params) {

        // Require the New Assigned Category Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_assigned_category.php';    

        // Return
        return the_crm_chatbot_save_new_assigned_category_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_the_new_assigned_category_fields') ) {
    
    /**
     * The function the_crm_chatbot_the_new_assigned_categoryt_fields gets the new assigned category fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_the_new_assigned_category_fields($params) {

        // Require the New Assigned Category Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/triggers/new_assigned_category.php';  

        // Return
        return the_crm_chatbot_the_new_assigned_category_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_new_assigned_category_create_task') ) {
    
    /**
     * The function the_crm_chatbot_new_assigned_category_create_task creates new event's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_assigned_category_create_task($params) {

        // Require the New Assigned Category Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/new_assigned_category.php';   

        // Return
        return the_crm_chatbot_new_assigned_category_create_task_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_new_assigned_category_run_task') ) {
    
    /**
     * The function the_crm_chatbot_new_assigned_category_run_task runs new event's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_assigned_category_run_task($params) {

        // Require the New Assigned Category Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/new_assigned_category.php';   

        // Return
        return the_crm_chatbot_new_assigned_category_run_task_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_validate_save_as_important_fields') ) {
    
    /**
     * The function the_crm_chatbot_validate_save_as_important_fields validates the thread's ID field
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_save_as_important_fields($params) {

        // Require the Save As Important Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/actions/save_as_important.php';   

        // Return
        return the_crm_chatbot_validate_save_as_important_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_save_as_important_fields') ) {
    
    /**
     * The function the_crm_chatbot_save_save_as_important_fields saves the thread's ID field
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_save_as_important_fields($params) {

        // Require the Save As Important Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/actions/save_as_important.php';   

        // Return
        return the_crm_chatbot_save_save_as_important_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_as_important_condition_fields') ) {
    
    /**
     * The function the_crm_chatbot_save_as_important_condition_fields gets the condition's data
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_condition_fields($params) {

        // Require the Save As Important Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/actions/save_as_important.php';   

        // Return
        return the_crm_chatbot_save_as_important_condition_fields_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_as_important_create_action_task') ) {
    
    /**
     * The function the_crm_chatbot_save_as_important_create_action_task creates new acion's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_create_action_task($params) {

        // Require the Save As Important Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/save_as_important.php';   

        // Return
        return the_crm_chatbot_save_as_important_create_action_task_from_parts($params);

    }

}

if ( !function_exists('the_crm_chatbot_save_as_important_run_action_task') ) {
    
    /**
     * The function the_crm_chatbot_save_as_important_run_action_task runs new acion's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_run_action_task($params) {

        // Require the Save As Important Inc Part
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/tasks/save_as_important.php';   

        // Return
        return the_crm_chatbot_save_as_important_run_action_task_from_parts($params);

    }

}

/* End of file automations.php */