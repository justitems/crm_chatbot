<?php
/**
 * New Guest Functions Inc Parts
 *
 * This file contains the functions for 
 * automations app. It creates tasks for the New Guest event
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('the_crm_chatbot_new_guest_create_task_from_parts')) {
    
    /**
     * The function the_crm_chatbot_new_guest_create_task_from_parts creates an automation's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_guest_create_task_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Delete the user's cache
        delete_crm_cache_cronology_for_user($params['user_id'], 'crm_automations_list');

        // Verify if the automation has actions
        if ( $CI->base_model->the_data_where(
            'crm_automations_actions',
            '*',
            array(
                'automation_id' => $params['automation_id']
            )
        ) ) {

            // Save the chronology
            save_crm_automations_chronology(array(
                'automation_id' => $params['automation_id'],
                'user_id' => $params['user_id'],
                'subject' => $CI->lang->line('crm_automations_task_was_created'),
                'failed' => 0
            ));

            // Return success response
            return array(
                'success' => TRUE,
                'message' => $CI->lang->line('crm_automations_task_was_saved')
            );  

        } else {

            // Save the chronology
            save_crm_automations_chronology(array(
                'automation_id' => $params['automation_id'],
                'user_id' => $params['user_id'],
                'subject' => $CI->lang->line('crm_automations_automation_was_disabled'),
                'body' => $CI->lang->line('crm_automations_automation_no_actions'),
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
                'message' => $CI->lang->line('crm_automations_automation_no_actions')
            );   

        }

    }

}

if (!function_exists('the_crm_chatbot_new_guest_run_task_from_parts')) {
    
    /**
     * The function the_crm_chatbot_new_guest_run_task_from_parts runs an automation's task
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_new_guest_run_task_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Load tasks language
        $CI->lang->load( 'crm_chatbot_automations_tasks', $CI->config->item('language'), FALSE, TRUE, CMS_BASE_PATH . 'user/apps/collection/crm_chatbot/' );

        // Configuration container
        $configuration = array();

        // Verify if input fields exists
        if ( !empty($params['event']['event_input_fields']) ) {

            // List the input fields
            foreach ( $params['event']['event_input_fields'] as $field ) {

                // Verify if field data exists
                if ( !empty($field['field_data']) ) {

                    // Verify if field's slug exists
                    if ( $field['field_slug'] === 'crm_chatbot_websites' ) {
                        
                        // Verify if item's id exists
                        if ( !empty($field['field_data']['item_id']) ) {

                            // Verify if item's id is numeric
                            if ( is_numeric($field['field_data']['item_id']) ) {

                                // Set website
                                $configuration['website'] = $field['field_data']['item_id'];

                            }

                        }

                    }

                }

            }

        }
        
        // Verify if the website and  exists
        if ( !empty($configuration['website']) ) {

            // Get the guest's ID
            $the_guest_id = the_crm_automations_tasks_meta($params['task']['task_id'], 'guest_id');

            // Verify if the guest's ID exists
            if ( !$the_guest_id ) {

                // Save the chronology
                save_crm_automations_chronology(array(
                    'automation_id' => $params['task']['automation_id'],
                    'user_id' => $params['task']['user_id'],
                    'subject' => $CI->lang->line('crm_automations_an_error_occurred'),
                    'body' => $CI->lang->line('crm_chatbot_guest_id_not_found'),
                    'failed' => 1
                ));

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('crm_chatbot_guest_id_not_found')
                );  
                
            } else {

                // Get the website's metas
                $the_websites_meta = $CI->base_model->the_data_where(
                    'crm_chatbot_websites_meta',
                    '*',
                    array(
                        'website_id' => $configuration['website']
                    )
                );  
                
                // Verify if website's meta was found
                if ( !$the_websites_meta ) {

                    // Save the chronology
                    save_crm_automations_chronology(array(
                        'automation_id' => $params['task']['automation_id'],
                        'user_id' => $params['task']['user_id'],
                        'subject' => $CI->lang->line('crm_automations_an_error_occurred'),
                        'body' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields'),
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
                        'message' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields')
                    );

                }

                // Group the metas
                $metas = array_column($the_websites_meta, 'meta_value', 'meta_name');

                // Verify if the guests fields are enabled
                if ( empty($metas['guest_name']) && empty($metas['guest_email']) && empty($metas['guest_phone']) ) {

                    // Save the chronology
                    save_crm_automations_chronology(array(
                        'automation_id' => $params['task']['automation_id'],
                        'user_id' => $params['task']['user_id'],
                        'subject' => $CI->lang->line('crm_automations_an_error_occurred'),
                        'body' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields'),
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
                        'message' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields')
                    );

                }

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Output fields
                $output_fields = array();

                // Set output's field
                $output_fields[] = array(
                    'field_id' => 'default_id',
                    'field_value' => $the_guest_id
                );                

                // Verify if the guest's name exists
                if ( !empty($metas['guest_name']) ) {

                    // Set output's field
                    $output_fields[] = array(
                        'field_id' => 'guest_name',
                        'field_value' => the_crm_chatbot_websites_guests_meta($the_guest_id, 'guest_name')
                    );

                }

                // Verify if the guest's email exists
                if ( !empty($metas['guest_email']) ) {

                    // Set output's field
                    $output_fields[] = array(
                        'field_id' => 'guest_email',
                        'field_value' => the_crm_chatbot_websites_guests_meta($the_guest_id, 'guest_email')
                    );

                }  
                
                // Verify if the guest's phone exists
                if ( !empty($metas['guest_phone']) ) {

                    // Set output's field
                    $output_fields[] = array(
                        'field_id' => 'guest_phone',
                        'field_value' => the_crm_chatbot_websites_guests_meta($the_guest_id, 'guest_phone')
                    );

                }

                // Verify if output fields exists
                if ( $output_fields ) {

                    // Save the chronology
                    save_crm_automations_chronology(array(
                        'automation_id' => $params['task']['automation_id'],
                        'user_id' => $params['task']['user_id'],
                        'subject' => $CI->lang->line('crm_chatbot_the_task_has_been_processed'),
                        'failed' => 0
                    ));                        

                    // Return success response
                    return array(
                        'success' => TRUE,
                        'output_fields' => $output_fields
                    );  

                } else {

                    // Save the chronology
                    save_crm_automations_chronology(array(
                        'automation_id' => $params['task']['automation_id'],
                        'user_id' => $params['task']['user_id'],
                        'subject' => $CI->lang->line('crm_chatbot_the_task_has_been_processed'),
                        'body' => $CI->lang->line('crm_chatbot_no_output_filters_for_fields'),
                        'failed' => 0
                    ));

                    // Return error response
                    return array(
                        'success' => FALSE,
                        'message' => $CI->lang->line('crm_chatbot_no_output_filters_for_fields')
                    );                          

                }

            }

        } else {

            // Save the chronology
            save_crm_automations_chronology(array(
                'automation_id' => $task['automation_id'],
                'user_id' => $task['user_id'],
                'subject' => $CI->lang->line('crm_automations_automation_was_disabled'),
                'body' => $CI->lang->line('crm_automations_automation_trigger_not_configured'),
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
                'message' => $CI->lang->line('crm_automations_automation_trigger_not_configured')
            );  

        }

    }

}

/* End of file new_guest.php */