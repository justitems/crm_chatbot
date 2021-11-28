<?php
/**
 * Save As Important Inc Parts
 *
 * This file contains the functions for 
 * automations app. It processes the fields for the Delete Client action
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('the_crm_chatbot_validate_save_as_important_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_validate_save_as_important_fields_from_parts validates the thread's ID field
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_save_as_important_fields_from_parts($params) {

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

        // Verify if crm_chatbot_thread_id_form exists
        if ( empty($fields['crm_chatbot_thread_id_form']) ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_action_configuration_wrong_data'),
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_thread_id_form',
                        'message' => $CI->lang->line('crm_chatbot_please_enter_thread_id')
                    )
                    
                )

            );
            
        }

        // Verify if field exists
        if ( empty($fields['crm_chatbot_thread_id_form'][0]['field']) ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_action_configuration_wrong_data'),
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_thread_id_form',
                        'message' => $CI->lang->line('crm_chatbot_please_enter_thread_id')
                    )
                    
                )

            );
            
        }  
        
        // Verify if thread_id exists
        if ( $fields['crm_chatbot_thread_id_form'][0]['field'] !== 'thread_id' ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_action_configuration_wrong_data'),
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_thread_id_form',
                        'message' => $CI->lang->line('crm_chatbot_please_enter_thread_id')
                    )
                    
                )

            );
            
        }

        // Verify if placeholders exists
        if ( empty($fields['crm_chatbot_thread_id_form'][0]['placeholders']) ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_action_configuration_wrong_data'),
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_thread_id_form',
                        'message' => $CI->lang->line('crm_chatbot_please_enter_thread_id')
                    )
                    
                )

            );
            
        }    
        
        // Verify if thread_id exists
        if ( $fields['crm_chatbot_thread_id_form'][0]['placeholders'][0]['placeholder_id'] !== 'thread_id' ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_action_configuration_wrong_data'),
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_thread_id_form',
                        'message' => $CI->lang->line('crm_chatbot_please_enter_thread_id')
                    )
                    
                )

            );
            
        }

        // Return the success response
        return array(
            'success' => TRUE
        );

    }

}

if (!function_exists('the_crm_chatbot_save_save_as_important_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_save_save_as_important_fields_from_parts saves the delete client fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_save_as_important_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get fields
        $fields = array_column($params['fields'], 'field_value', 'field_slug');

        // Report array
        $report = array();
        
        // Try to save the condition
        if ( update_crm_automations_actions_meta($params['action_id'], 'condition', serialize($fields['crm_chatbot_thread_id_form'])) ) {

            // Set report
            $report[] = array(
                'success' => TRUE,
                'message' => $CI->lang->line('crm_chatbot_condition_was_saved'),
                'icon' => md_the_user_icon(array('icon' => 'bi_hdd_stack', 'class' => 'theme-color-green'))
            );

        } else {

            // Verify if the action's meta is the same
            if ( the_crm_automations_actions_meta($params['action_id'], 'condition') === serialize($fields['crm_chatbot_thread_id_form']) ) {

                // Set report
                $report[] = array(
                    'success' => TRUE,
                    'message' => $CI->lang->line('crm_chatbot_condition_was_saved'),
                    'icon' => md_the_user_icon(array('icon' => 'bi_hdd_stack', 'class' => 'theme-color-green'))
                );                

            } else {

                // Set false
                $report[] = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('crm_chatbot_condition_was_not_saved'),
                    'error_message' => $CI->lang->line('crm_chatbot_an_error_occurred'),
                    'icon' => md_the_user_icon(array('icon' => 'bi_hdd_stack', 'class' => 'theme-color-red'))

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

if (!function_exists('the_crm_chatbot_save_as_important_condition_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_save_as_important_condition_fields_from_parts gets the condition's data
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_condition_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Action input fields
        $action_input_fields = array();

        // Action output fields
        $action_output_fields = array();

        // Fields data
        $fields_data = array();

        // Get the condition
        $condition = the_crm_automations_actions_meta($params['action_id'], 'condition');

        // Verify if the condition exists
        if ( $condition ) {              

            // Unserialize the fields
            $unserialized_fields = unserialize($condition);

            // Set fields data
            $fields_data[] = array(
                'field_id' => $unserialized_fields[0]['field'],
                'field_name' => $CI->lang->line('crm_chatbot_thread_id'),
                'field_value' => $unserialized_fields[0]['field_value'],
                'field_placeholders' => !empty($unserialized_fields[0]['placeholders'])?$unserialized_fields[0]['placeholders']:array(),
                'field_required' => 1
            );

        }

        // Set delete client fields
        $delete_client_fields = array(
            'field_slug' => 'crm_chatbot_thread_id_form',
            'field_type' => 'dynamic_form',
            'field_label' => $CI->lang->line('crm_chatbot_condition'),
            'field_description' => $CI->lang->line('crm_chatbot_thread_id_description'),
            'field_data' => array(
                array(
                    'field_id' => 'thread_id',
                    'field_name' => $CI->lang->line('crm_chatbot_thread_id'),
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

        );

        // Verify if $fields_data is not empty
        if ( $fields_data ) {

            // Set field data
            $delete_client_fields['field_data'] = $fields_data;

        }

        // Add fields to input actions
        $action_input_fields[] = $delete_client_fields;

        // Return the action's data
        return array(
            'action_slug' => 'crm_chatbot_save_important',
            'action_name' => $CI->lang->line('crm_chatbot_save_important'),
            'action_description' => $CI->lang->line('crm_chatbot_save_important_description'),
            'action_autorun' => 0,
            'action_input_fields' => $action_input_fields,
            'action_output_fields' => $action_output_fields
        );

    }

}

/* End of file delete_client.php */