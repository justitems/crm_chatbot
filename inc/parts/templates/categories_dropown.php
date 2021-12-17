<?php
/**
 * Categories Dropown Functions Inc Parts
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

if (!function_exists('the_crm_chatbot_template_dropdown_categories_from_parts')) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_categories_from_parts gets the categories
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_categories_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Set like
        $like = isset($params['key'])?array('category_name' => trim(str_replace('!_', '_', $CI->db->escape_like_str($params['key'])))):array();

        // Gets the categories
        $the_categories = $CI->base_model->the_data_where(
            'crm_chatbot_categories',
            '*',
            array(
                'user_id' => $CI->user_id
            ),
            array(),
            $like,
            array(),
            array(
                'order_by' => array('category_id', 'DESC'),
                'start' => 0,
                'limit' => 10
            )
        );

        // Verify if the categories exists
        if ( $the_categories ) {

            // Items array
            $items = array();

            // List all categories
            foreach ( $the_categories as $the_category ) {

                // Set category
                $items[] = array(
                    'item_id' => $the_category['category_id'],
                    'item_name' => $the_category['category_name']
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

            // Return categories
            return $response;

        } else {

            // Set the response
            $response = array(
                'success' => FALSE,
                'message' => $CI->lang->line('crm_chatbot_no_categories_were_found'),
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

if (!function_exists('the_crm_chatbot_template_dropdown_category_select_from_parts')) {
    
    /**
     * The function the_crm_chatbot_template_dropdown_category_select_from_parts receives information when a category is selected
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_template_dropdown_category_select_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // All fields array
        $all_fields = array();

        // Verify if the categories are selected
        if ( empty($params['item']) ) {

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

        // Verify if a category is selected
        if ( !is_numeric($params['item']) ) {
            
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
                'category_id' => $params['item'],
                'user_id' => $CI->user_id
            )
        );

        // Verify if the category was found
        if ( !$the_category ) {

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
                    'field' => 'crm_chatbot_categories',
                    'message' => $CI->lang->line('crm_chatbot_no_category_found')
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
            'field_id' => 'thread_id',
            'field_name' => $CI->lang->line('crm_chatbot_thread_id'),
            'field_default_example' => '1'
        );          

        // Return the error response
        return $response;

    }

}

/* End of file categories_dropown.php */