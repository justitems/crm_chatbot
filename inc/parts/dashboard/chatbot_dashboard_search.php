<?php
/**
 * Dashboard Search Inc Part
 *
 * PHP Version 7.3
 *
 * This files contains a function to provide a search filter in
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

if ( !function_exists('the_crm_chatbot_search_data_dashboard_from_parts') ) {
    
    /**
     * The function the_crm_chatbot_search_data_dashboard_from_parts provides a search filter for dashboard
     * 
     * @param array $params contains the parameters
     * 
     * @return array with response
     */
    function the_crm_chatbot_search_data_dashboard_from_parts($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Load the CRM Chatbot Websites Model
        $CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_websites_model', 'crm_chatbot_websites_model' );

        // Verify if key exists
        if ( !empty($params['key']) ) {

            // Get team's member
            $member = $CI->session->userdata('member')?the_crm_current_team_member():array();

            // Set where
            $where = array(
                'crm_chatbot_websites_threads.user_id' => $CI->user_id
            );

            // Verify if the guest parameter exists
            if ( !empty($params['guest']) ) {

                // Set guest
                $where['crm_chatbot_websites_threads.guest_id'] = $params['guest'];

            }

            // Verify if the website parameter exists
            if ( !empty($params['website']) ) {

                // Set website
                $where['crm_chatbot_websites_threads.website_id'] = $params['website'];

            }

            // Verify if the status parameter exists
            if ( !empty($params['status']) ) {

                // Set status
                $where['crm_chatbot_websites_threads.status'] = $params['status'];

                // Verify if status is active
                if ( $params['status'] === '1' ) {

                    // Set time
                    $where['crm_chatbot_websites_threads.updated >'] = (time() - 3600);

                }

            }        

            // Set where in
            $where_in = array();

            // Verify if member exists
            if ( $CI->session->userdata( 'member' ) ) {

                // Verify if member's role exists
                if ( isset($member['role_id']) ) {

                    // Get the websites
                    $the_websites_list = $CI->base_model->the_data_where(
                        'crm_chatbot_websites',
                        '*',
                        array(
                            'user_id' => $CI->user_id
                        )

                    );

                    // Verify if websites exists
                    if ( $the_websites_list ) {

                        // Set websites container
                        $websites = array();     
                        
                        // List the websites
                        foreach ( $the_websites_list as $the_website ) {

                            // Verify if the website is allowed
                            if ( !the_crm_team_roles_multioptions_list_item($CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
                                continue;
                            }

                            // Add website to the list
                            $websites[] = $the_website['website_id'];

                        } 
                        
                        // Verify if $websites is not empty
                        if ( $websites ) {

                            // Set team's member to params
                            $params['team_member'] = 1;

                            // Set where in
                            $where_in = array('crm_chatbot_websites.website_id', $websites);

                        } else {

                            // Prepare the false response
                            return FALSE;  

                        }

                    }

                }

            }

            // Set like
            $like = array();

            // Verify if key exists
            if ( isset($params['key']) ) {

                // Get the threads by key
                $the_threads_ids = $CI->crm_chatbot_websites_model->the_threads_search(array('key' => $params['key']));

                // Verify if the threads were found
                if ( $the_threads_ids ) {

                    // Set where in
                    $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

                } else {

                    // Prepare the false response
                    return FALSE;  
                    
                }

            }

            // Set join
            $join = array(
                array(
                    'table' => 'crm_chatbot_websites',
                    'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                    'join_from' => 'LEFT'
                )
            );

            // Set limit
            $limit = array(
                'order_by' =>  array('crm_chatbot_websites_threads.updated', 'DESC')
            );

            // Verify if start exists
            if ( isset($params['page']) ) {

                // Set the start
                $limit['start'] = $params['page'] * 10;

                // Set the limit
                $limit['limit'] = $params['limit'];

            }

            // Get the threads
            $the_threads = $CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Verify if the threads exists
            if ( $the_threads ) {

                // Get the total threads
                $the_total_threads = $CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );            

                // Items container
                $items = array();

                // List all threads
                foreach ( $the_threads as $the_thread ) {

                    // Set item
                    $items[] = array(
                        'item_id' => $the_thread['thread_id'],
                        'item_name' => '#' . $the_thread['thread_id'],
                        'item_description' => $the_thread['domain'],
                        'item_link' => site_url('user/app/crm_chatbot?thread=' . $the_thread['thread_id']),
                        'item_icon' => md_the_user_icon(array('icon' => 'forum'))
                    );

                }

                return array(
                    'items' => $items,
                    'total' => $the_total_threads[0]['total']
                );

            } else {

                return FALSE;

            }

        } else {

            return FALSE;

        }

    }

}

/* End of file chatbot_dashboard_search.php */