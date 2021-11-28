<?php
/**
 * New Assigned Category Inc Parts
 *
 * This file contains the functions for 
 * automations app. It processes the fields for the New Assigned Category trigger
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('the_crm_chatbot_validate_new_assigned_category_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_validate_new_assigned_category_fields_from_parts validates the new assigned category's fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_new_assigned_category_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Verify if fields exists
        if ( empty($params['fields']) ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_no_fields_were_found')
            );

        }

        // Get fields
        $fields = array_column($params['fields'], 'field_value', 'field_slug');

        // Verify if a category is selected
        if ( !is_numeric($fields['crm_chatbot_categories']) ) {
            
            // Return the error response
            return array(
                'success' => FALSE,
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_categories',
                        'message' => $CI->lang->line('crm_chatbot_please_select_a_category')
                    )
                    
                )

            );            

        }

        // Gets the categories
        $the_category = $CI->base_model->the_data_where(
            'crm_chatbot_categories',
            '*',
            array(
                'category_id' => $fields['crm_chatbot_categories'],
                'user_id' => $CI->user_id
            )
        );

        // Verify if the category was found
        if ( !$the_category ) {

            // Set the response
            $response = array(
                'success' => FALSE,
            );

            // Set the actions
            $response['actions'] = array(
                array(
                    'action' => 'error',
                    'field' => 'crm_chatbot_categories',
                    'message' => $CI->lang->line('crm_chatbot_no_category_found')
                )
                
            );

            // Return the error response
            return $response;

        }

        // Return the success response
        return array(
            'success' => TRUE
        );

    }

}

if (!function_exists('the_crm_chatbot_save_new_assigned_category_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_save_new_assigned_category_fields_from_parts saves the new assigned category's fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_new_assigned_category_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get fields
        $fields = array_column($params['fields'], 'field_value', 'field_slug');

        // Report array
        $report = array();
        
        // Try to save the category
        if ( update_crm_automations_triggers_meta($params['automation_id'], 'category', $fields['crm_chatbot_categories']) ) {

            // Set report
            $report[] = array(
                'success' => TRUE,
                'message' => $CI->lang->line('crm_chatbot_selected_category_was_saved'),
                'icon' => md_the_user_icon(array('icon' => 'slideshow', 'class' => 'theme-color-green'))
            );

        } else {

            // Verify if the category is the same
            if ( the_crm_automations_triggers_meta($params['automation_id'], 'category') === $fields['crm_chatbot_categories'] ) {

                // Set report
                $report[] = array(
                    'success' => TRUE,
                    'message' => $CI->lang->line('crm_chatbot_selected_category_was_saved'),
                    'icon' => md_the_user_icon(array('icon' => 'slideshow', 'class' => 'theme-color-green'))
                );

            } else {

                // Set false
                $report[] = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('crm_chatbot_selected_category_was_not_saved'),
                    'error_message' => $CI->lang->line('crm_chatbot_an_error_occurred'),
                    'icon' => md_the_user_icon(array('icon' => 'slideshow', 'class' => 'theme-color-red'))
                );   
                
                // Return the error response
                return array(
                    'success' => FALSE,
                    'report' => $report
                );

            }

        }

        // Return the success response
        return array(
            'success' => TRUE,
            'report' => $report
        );

    }

}

if (!function_exists('the_crm_chatbot_the_new_assigned_category_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_the_new_assigned_category_fields_from_parts gets the new assigned category's fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_the_new_assigned_category_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Event input fields
        $event_input_fields = array();

        // Event output fields
        $event_output_fields = array();

        // Set new guest category
        $new_assigned_category = array(
            'field_slug' => 'crm_chatbot_categories',
            'field_type' => 'dropdown',
            'field_label' => $CI->lang->line('crm_chatbot_categories'),
            'field_description' => $CI->lang->line('crm_chatbot_categories_description'),
            'dropdown' => array(
                'type' => 'local',
                'source' => 'the_crm_chatbot_template_dropdown_categories',
                'select' => 'the_crm_chatbot_template_dropdown_category_select',
                'required' => 1,
                'validate' => '',
                'words' => array(
                    'button_title' => $CI->lang->line('crm_chatbot_select_a_category'),
                    'input_placeholder' => $CI->lang->line('crm_chatbot_search_for_categories')
                )

            )

        );

        // Get the category
        $category = the_crm_automations_triggers_meta($params['automation_id'], 'category');

        // Verify if the category exists
        if ( $category ) {

            // Gets the categories
            $the_category = $CI->base_model->the_data_where(
                'crm_chatbot_categories',
                '*',
                array(
                    'category_id' => $category
                )
            );

            // Verify if the category was found
            if ( $the_category ) {

                // Set field's data
                $new_assigned_category['field_data'] = array(
                    'item_id' => $category,
                    'item_name' => $the_category[0]['category_name'] 
                );

            }

            // Set field
            $event_output_fields[] = array(
                'field_id' => 'thread_id',
                'field_name' => $CI->lang->line('crm_chatbot_thread_id'),
                'field_default_example' => '1'                
            );  

        }

        // Set new guest to input triggers
        $event_input_fields[] = $new_assigned_category;

        // Return the trigger's data
        return array(
            'trigger_slug' => 'crm_chatbot_new_assigned_category',
            'trigger_name' => $CI->lang->line('crm_chatbot_new_assigned_category'),
            'trigger_description' => $CI->lang->line('crm_chatbot_new_assigned_category_description'),
            'trigger_autorun' => 0,
            'event_input_fields' => $event_input_fields,
            'event_output_fields' => $event_output_fields
        );

    }

}

/* End of file new_client.php */