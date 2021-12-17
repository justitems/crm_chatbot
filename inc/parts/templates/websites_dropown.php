<?php
/**
 * Websites Dropown Functions Inc Parts
 *
 * This file contains the functions for 
 * automations app. It processes the dropown trigger template.
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('the_crm_chatbot_template_dropdown_websites_from_parts')) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_websites_from_parts gets the websites
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_websites_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Set like
        $like = isset($params['key'])?array('domain' => trim(str_replace('!_', '_', $CI->db->escape_like_str($params['key'])))):array();

        // Gets the websites
        $the_websites = $CI->base_model->the_data_where(
            'crm_chatbot_websites',
            '*',
            array(
                'user_id' => $CI->user_id
            ),
            array(),
            $like,
            array(),
            array(
                'order_by' => array('website_id', 'DESC'),
                'start' => 0,
                'limit' => 10
            )
        );

        // Verify if the websites exists
        if ( $the_websites ) {

            // Items array
            $items = array();

            // List all websites
            foreach ( $the_websites as $the_website ) {

                // Set website
                $items[] = array(
                    'item_id' => $the_website['website_id'],
                    'item_name' => $the_website['domain']
                );

            }

            // Set the response
            $response = array(
                'success' => TRUE,
                'items' => $items,
                'app' => $params['app'],
                'field' => $params['field']
            );

            // Verify if the action exists
            if ( isset($params['action']) ) {

                // Set the action
                $response['action'] = $params['action'];

            } else {

                // Set the event
                $response['event'] = $params['event'];

            }

            // Return websites
            return $response;

        } else {

            // Set the response
            $response = array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_no_websites_were_found'),
                'app' => $params['app'],
                'field' => $params['field']
            );

            // Verify if the action exists
            if ( isset($params['action']) ) {

                // Set the action
                $response['action'] = $params['action'];

            } else {

                // Set the event
                $response['event'] = $params['event'];

            }

            // Return error message
            return $response;

        }

    }

}



if (!function_exists('the_crm_chatbot_template_dropdown_website_select_from_parts')) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_website_select_from_parts receives information when a website is selected
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_website_select_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // All fields array
        $all_fields = array();

        // Verify if the websites are selected
        if ( empty($params['item']) ) {

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
        if ( !is_numeric($params['item']) ) {
            
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
                'website_id' => $params['item'],
                'user_id' => $CI->user_id
            )
        );

        // Verify if the website was found
        if ( !$the_website ) {

            // Set the response
            $response = array(
                'success' => FALSE,
                'app' => $params['app'],
                'field' => $params['field']
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

        // Get the website's metas
        $the_websites_meta = $CI->base_model->the_data_where(
            'crm_chatbot_websites_meta',
            '*',
            array(
                'website_id' => $params['item']
            )
        );  
        
        // Verify if website's meta was found
        if ( !$the_websites_meta ) {

            // Set the response
            $response = array(
                'success' => FALSE,
                'app' => $params['app'],
                'field' => $params['field']
            );

            // Set the actions
            $response['actions'] = array(
                array(
                    'action' => 'error',
                    'field' => 'crm_chatbot_websites',
                    'message' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields')
                )
                
            );

            // Return the error response
            return $response;

        }

        // Group the metas
        $metas = array_column($the_websites_meta, 'meta_value', 'meta_name');

        // Verify if the guests fields are enabled
        if ( empty($metas['guest_name']) && empty($metas['guest_email']) && empty($metas['guest_phone']) ) {

            // Set the response
            $response = array(
                'success' => FALSE,
                'app' => $params['app'],
                'field' => $params['field']
            );

            // Set the actions
            $response['actions'] = array(
                array(
                    'action' => 'error',
                    'field' => 'crm_chatbot_websites',
                    'message' => $CI->lang->line('crm_chatbot_no_enabled_guests_fields')
                )
                
            );

            // Return the error response
            return $response;

        }

        // Set the response
        $response = array(
            'success' => TRUE,
            'app' => $params['app'],
            'field' => $params['field']
        );

        // Set the event's output fields
        $response['event_output_fields'] = array();

        // Set field
        $response['event_output_fields'][] = array(
            'field_id' => 'default_id',
            'field_name' => $CI->lang->line('crm_chatbot_default_id'),
            'field_default_example' => '1'
        );        

        // Verify if the guest's name exists
        if ( !empty($metas['guest_name']) ) {

            // Set field
            $response['event_output_fields'][] = array(
                'field_id' => 'guest_name',
                'field_name' => $CI->lang->line('crm_chatbot_guest_name'),
                'field_default_example' => '1'
            );

        }

        // Verify if the guest's email exists
        if ( !empty($metas['guest_email']) ) {

            // Set field
            $response['event_output_fields'][] = array(
                'field_id' => 'guest_email',
                'field_name' => $CI->lang->line('crm_chatbot_guest_email'),
                'field_default_example' => '1'
            );

        }  
        
        // Verify if the guest's phone exists
        if ( !empty($metas['guest_phone']) ) {

            // Set field
            $response['event_output_fields'][] = array(
                'field_id' => 'guest_phone',
                'field_name' => $CI->lang->line('crm_chatbot_guest_phone'),
                'field_default_example' => '1'
            );

        }          

        // Return the error response
        return $response;

    }

}

/* End of file websites_dropown.php */