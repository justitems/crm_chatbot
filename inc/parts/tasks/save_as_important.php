<?php
/**
 * Save As Important Inc Parts
 *
 * This file contains the functions for 
 * automations app. It creates tasks to save threads as Important
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('the_crm_chatbot_save_as_important_create_action_task_from_parts') ) {
    
    /**
     * The function the_crm_chatbot_save_as_important_create_action_task_from_parts creates new acion's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_create_action_task_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Load tasks language
        $CI->lang->load( 'crm_chatbot_automations_tasks', $CI->config->item('language'), FALSE, TRUE, CMS_BASE_PATH . 'user/apps/collection/crm_chatbot/' );

        // Counter
        $count = 0;

        // Verify if the action_input_fields key exists
        if ( !empty($params['action_input_fields']) ) {

            // Verify if field's data exists
            if ( !empty($params['action_input_fields'][0]['field_data']) ) {

                // Verify if field's ID exists
                if ( !empty($params['action_input_fields'][0]['field_data'][0]['field_id']) ) {

                    // Verify if field's ID is thread's ID
                    if ( $params['action_input_fields'][0]['field_data'][0]['field_id'] === 'thread_id' ) {

                        // Increase the counter
                        $count++;

                    }

                }

            }

        }
        
        // Verify if count is positive
        if ( $count ) {

            // Return success response
            return array(
                'success' => TRUE
            );  

        } else {

            // Save the chronology
            save_crm_automations_chronology(array(
                'automation_id' => $params['task']['automation_id'],
                'user_id' => $params['task']['user_id'],
                'subject' => $CI->lang->line('crm_automations_automation_was_disabled'),
                'body' => $CI->lang->line('crm_chatbot_thread_id_not_found'),
                'failed' => 1,
                'actions' => array(
                    'automation' => array(
                        'disable' => 1
                    )
                )
            ));

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_thread_id_not_found')
            );   
            
        }

    }

}

if (!function_exists('the_crm_chatbot_save_as_important_run_action_task_from_parts')) {
    
    /**
     * The function the_crm_chatbot_save_as_important_run_action_task_from_parts runs an acion's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_as_important_run_action_task_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Load tasks language
        $CI->lang->load( 'crm_chatbot_automations_tasks', $CI->config->item('language'), FALSE, TRUE, CMS_BASE_PATH . 'user/apps/collection/crm_chatbot/' );

        // Verify if output fields exists
        if ( !empty($params['automation_output_fields']) ) {

            // Get the input fields
            if ( !empty($params['action_input_fields']) ) {

                // Verify if field's data exists
                if ( !empty($params['action_input_fields'][0]['field_data']) ) {

                    // Verify if field's ID exists
                    if ( !empty($params['action_input_fields'][0]['field_data'][0]['field_id']) ) {

                        // Verify if field's ID is thread's ID
                        if ( $params['action_input_fields'][0]['field_data'][0]['field_id'] === 'thread_id' ) {

                            // Get the action's input fields
                            $action_input_fields = the_crm_automations_action_input_fields($params['action_input_fields'][0]['field_data'], $params['automation_output_fields']);

                            // Verify if the fields exists
                            if ( !empty($action_input_fields['success']) ) {

                                // Action's fields
                                $action_fields = array_column($action_input_fields['fields'], 'field_value', 'field_id');

                                // Verify if the thread_id key exists
                                if ( !empty($action_fields['thread_id']) ) {

                                    // Verify if the thread exists
                                    if ( $CI->base_model->the_data_where('crm_chatbot_websites_threads', 'thread_id', array('thread_id' => trim($action_fields['thread_id'])) ) ) {

                                        // Delete the client's data
                                        if (  $CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => trim($action_fields['thread_id'])), array('important' => 1)) ) {   
                                            
                                            // Delete the user's cache
                                            delete_crm_cache_cronology_for_user($params['task']['user_id'], 'crm_chatbot_websites_threads_list');

                                            // Save the chronology
                                            save_crm_automations_chronology(array(
                                                'automation_id' => $params['task']['automation_id'],
                                                'user_id' => $params['task']['user_id'],
                                                'subject' => $CI->lang->line('crm_chatbot_thread_was_saved_as_important'),
                                                'failed' => 0
                                            ));
                                            
                                            // Prepare success response
                                            return array(
                                                'success' => TRUE,
                                                'message' => $CI->lang->line('crm_chatbot_thread_was_saved_as_important')
                                            );

                                        } else {

                                            // Save the chronology
                                            save_crm_automations_chronology(array(
                                                'automation_id' => $params['task']['automation_id'],
                                                'user_id' => $params['task']['user_id'],
                                                'subject' => $CI->lang->line('crm_automations_automation_error_notification'),
                                                'body' => $CI->lang->line('crm_chatbot_thread_was_not_saved_as_important'),
                                                'failed' => 1
                                            ));

                                            // Return error response
                                            return array(
                                                'success' => FALSE,
                                                'message' => $CI->lang->line('crm_chatbot_thread_was_not_saved_as_important')
                                            ); 

                                        }

                                    } else {

                                        // Save the chronology
                                        save_crm_automations_chronology(array(
                                            'automation_id' => $params['task']['automation_id'],
                                            'user_id' => $params['task']['user_id'],
                                            'subject' => $CI->lang->line('crm_automations_automation_error_notification'),
                                            'body' => $CI->lang->line('crm_chatbot_thread_id_not_found'),
                                            'failed' => 1
                                        ));

                                        // Return error response
                                        return array(
                                            'success' => FALSE,
                                            'message' => $CI->lang->line('crm_chatbot_thread_id_not_found')
                                        ); 

                                    }

                                }

                            }

                        }

                    }

                }
                
            }

        }

        // Save the chronology
        save_crm_automations_chronology(array(
            'automation_id' => $params['task']['automation_id'],
            'user_id' => $params['task']['user_id'],
            'subject' => $CI->lang->line('crm_automations_automation_error_notification'),
            'body' => $CI->lang->line('crm_chatbot_thread_id_not_found'),
            'failed' => 1,
            'actions' => array(
                'automation' => array(
                    'disable' => 1
                )
            )
        ));

        // Return error response
        return array(
            'success' => FALSE,
            'message' => $CI->lang->line('crm_chatbot_thread_id_not_found')
        ); 

    }

}
/* End of file delete_client.php */