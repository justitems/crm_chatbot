<?php
/**
 * New Guest Inc Parts
 *
 * This file contains the functions for 
 * automations app. It processes the fields for the New Guest trigger
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('the_crm_chatbot_validate_new_guest_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_validate_new_guest_fields_from_parts validates the new guest's fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_validate_new_guest_fields_from_parts($params) {

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

        // Verify if the website are selected
        if ( empty($fields['crm_chatbot_websites']) ) {

            // Return the error response
            return array(
                'success' => FALSE,
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_websites',
                        'message' => $CI->lang->line('crm_chatbot_please_select_a_website')
                    )
                    
                )

            );

        }

        // Verify if a website is selected
        if ( !is_numeric($fields['crm_chatbot_websites']) ) {
            
            // Return the error response
            return array(
                'success' => FALSE,
                'actions' => array(
                    array(
                        'action' => 'error',
                        'field' => 'crm_chatbot_websites',
                        'message' => $CI->lang->line('crm_chatbot_please_select_a_website')
                    )
                    
                )

            );            

        }

        // Gets the websites
        $the_website = $CI->base_model->the_data_where(
            'crm_chatbot_websites',
            '*',
            array(
                'website_id' => $fields['crm_chatbot_websites'],
                'user_id' => $CI->user_id
            )
        );

        // Verify if the website was found
        if ( !$the_website ) {

            // Set the response
            $response = array(
                'success' => FALSE,
            );

            // Set the actions
            $response['actions'] = array(
                array(
                    'action' => 'error',
                    'field' => 'crm_chatbot_websites',
                    'message' => $CI->lang->line('crm_chatbot_no_website_found')
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

if (!function_exists('the_crm_chatbot_save_new_guest_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_save_new_guest_fields_from_parts saves the new guest fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_save_new_guest_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get fields
        $fields = array_column($params['fields'], 'field_value', 'field_slug');

        // Report array
        $report = array();
        
        // Try to save the website
        if ( update_crm_automations_triggers_meta($params['automation_id'], 'website', $fields['crm_chatbot_websites']) ) {

            // Set report
            $report[] = array(
                'success' => TRUE,
                'message' => $CI->lang->line('crm_chatbot_selected_website_was_saved'),
                'icon' => md_the_user_icon(array('icon' => 'insert_link'))
            );

        } else {

            // Verify if the website is the same
            if ( the_crm_automations_triggers_meta($params['automation_id'], 'website') === $fields['crm_chatbot_websites'] ) {

                // Set report
                $report[] = array(
                    'success' => TRUE,
                    'message' => $CI->lang->line('crm_chatbot_selected_website_was_saved'),
                    'icon' => md_the_user_icon(array('icon' => 'insert_link'))
                );

            } else {

                // Set false
                $report[] = array(
                    'success' => FALSE,
                    'message' => $CI->lang->line('crm_chatbot_selected_website_was_not_saved'),
                    'error_message' => $CI->lang->line('crm_chatbot_an_error_occurred'),
                    'icon' => md_the_user_icon(array('icon' => 'insert_link'))
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

if (!function_exists('the_crm_chatbot_the_new_guest_fields_from_parts')) {
    
    /**
     * The function the_crm_chatbot_the_new_guest_fields_from_parts gets the new guest's fields
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_the_new_guest_fields_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Event input fields
        $event_input_fields = array();

        // Event output fields
        $event_output_fields = array();

        // Set new guest website
        $new_guest_website = array(
            'field_slug' => 'crm_chatbot_websites',
            'field_type' => 'dropdown',
            'field_label' => $CI->lang->line('crm_chatbot_websites'),
            'field_description' => $CI->lang->line('crm_chatbot_websites_description'),
            'dropdown' => array(
                'type' => 'local',
                'source' => 'the_crm_chatbot_template_dropdown_websites',
                'select' => 'the_crm_chatbot_template_dropdown_website_select',
                'required' => 1,
                'validate' => '',
                'words' => array(
                    'button_title' => $CI->lang->line('crm_chatbot_select_a_website'),
                    'input_placeholder' => $CI->lang->line('crm_chatbot_search_for_websites')
                )

            )

        );

        // Get the website
        $website = the_crm_automations_triggers_meta($params['automation_id'], 'website');

        // Verify if the website exists
        if ( $website ) {

            // Gets the websites
            $the_website = $CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $website
                )
            );

            // Verify if the website was found
            if ( $the_website ) {

                // Set field's data
                $new_guest_website['field_data'] = array(
                    'item_id' => $website,
                    'item_name' => $the_website[0]['domain'] 
                );

            }

        }

        // Set new guest to input triggers
        $event_input_fields[] = $new_guest_website;

        // Get the website's metas
        $the_websites_meta = $CI->base_model->the_data_where(
            'crm_chatbot_websites_meta',
            '*',
            array(
                'website_id' => $website
            )
        );

        $the_websites_meta = $the_websites_meta?$the_websites_meta:array();

        // Group the metas
        $metas = array_column($the_websites_meta, 'meta_value', 'meta_name');

        // Verify if the guests fields are enabled
        if ( !empty($metas['guest_name']) || !empty($metas['guest_email']) || !empty($metas['guest_phone']) ) {

            // Set field
            $event_output_fields[] = array(
                'field_id' => 'default_id',
                'field_name' => $CI->lang->line('crm_chatbot_default_id'),
                'field_default_example' => '1'                
            );  

            // Verify if the guest's name exists
            if ( !empty($metas['guest_name']) ) {

                // Set field
                $event_output_fields[] = array(
                    'field_id' => 'guest_name',
                    'field_name' => $CI->lang->line('crm_chatbot_guest_name'),
                    'field_default_example' => '1'
                );

            }

            // Verify if the guest's email exists
            if ( !empty($metas['guest_email']) ) {

                // Set field
                $event_output_fields[] = array(
                    'field_id' => 'guest_email',
                    'field_name' => $CI->lang->line('crm_chatbot_guest_email'),
                    'field_default_example' => '1'
                );

            }  
            
            // Verify if the guest's phone exists
            if ( !empty($metas['guest_phone']) ) {

                // Set field
                $event_output_fields[] = array(
                    'field_id' => 'guest_phone',
                    'field_name' => $CI->lang->line('crm_chatbot_guest_phone'),
                    'field_default_example' => '1'
                );

            }

        }

        // Return the trigger's data
        return array(
            'trigger_slug' => 'crm_chatbot_new_guest',
            'trigger_name' => $CI->lang->line('crm_chatbot_new_guest'),
            'trigger_description' => $CI->lang->line('crm_chatbot_new_guest_description'),
            'trigger_autorun' => 0,
            'event_input_fields' => $event_input_fields,
            'event_output_fields' => $event_output_fields
        );

    }

}

/* End of file new_client.php */