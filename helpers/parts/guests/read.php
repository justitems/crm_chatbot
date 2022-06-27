<?php
/**
 * Guests Read Helper
 *
 * This file read contains the methods
 * to read the guests
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Guests;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Guests to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Read {
    
    /**
     * Class variables
     *
     * @since 0.0.8.5
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.5
     */
    public function __construct() {
        
        // Get CodeIgniter object instance
        $this->CI =& get_instance();
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Guests
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_guests gets the guests
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_guests($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_guests_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_guests.user_id' => md_the_user_id()
        );      

        // Set where in
        $where_in = array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Set team role
                $params['team_role'] = $member['role_id'];                

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => md_the_user_id()
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
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
                        $where_in = array('crm_chatbot_websites_threads.website_id', $websites);

                    } else {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_guests_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = isset($params['key'])?array('crm_chatbot_websites_guests_meta.meta_value' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Set join
            $join = array(
                array(
                    'table' => 'crm_chatbot_websites_guests_meta',
                    'condition' => 'crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_guests_meta.guest_id',
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta name',
                    'condition' => "crm_chatbot_websites_guests.guest_id=name.guest_id AND name.meta_name='guest_name'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_messages',
                    'condition' => 'crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_messages.guest_id',
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_threads',
                    'condition' => 'crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id',
                    'join_from' => 'LEFT'
                )
            );

        } else {

            // Set join
            $join = array(
                array(
                    'table' => 'crm_chatbot_websites_guests_meta',
                    'condition' => 'crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_guests_meta.guest_id',
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta name',
                    'condition' => "crm_chatbot_websites_guests.guest_id=name.guest_id AND name.meta_name='guest_name'",
                    'join_from' => 'LEFT'
                )
            );
            
        }

        // Set limit
        $limit = array(
            'group_by' =>  array('crm_chatbot_websites_guests.guest_id'),
            'order_by' =>  array('crm_chatbot_websites_guests.created', 'DESC')
        );

        // Verify if start exists
        if ( isset($params['page']) ) {

            // Set the start
            $limit['start'] = $params['page'] * 10;

            // Set the limit
            $limit['limit'] = $params['limit'];

        }

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_guests_' . $parameters_string) ) {

            // Set the cache
            $the_guests = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_guests_' . $parameters_string);

        } else {

            // Get the guests guests
            $the_guests = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_guests',
                'crm_chatbot_websites_guests.*, name.meta_value AS full_name',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_guests_' . $parameters_string, $the_guests);

            // Set saved cronology
            update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_guests_list', 'crm_chatbot_user_' . md_the_user_id() . '_guests_' . $parameters_string);

        }

        // Verify if the guests exists
        if ( $the_guests ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_guests_' . $parameters_string) ) {

                // Get total guests
                $the_total = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_guests_' . $parameters_string);

            } else {

                // Get the total guests
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests',
                    'COUNT(crm_chatbot_websites_guests.guest_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_guests_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_guests_list', 'crm_chatbot_user_' . md_the_user_id() . '_load_total_guests_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'guests' => $the_guests,
                'total' => $the_total[0]['total']
            );

        }

    }

    /**
     * The public method crm_chatbot_get_visited_urls gets the visited urls
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_visited_urls($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_visited_urls_found')
            );         

        }

        // Verify if the thread or guest parameter exists
        if ( empty($params['thread']) && empty($params['guest']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );               

        }
    
        // Verify if thread parameter exists
        if ( !empty($params['thread']) ) {

            // Get the thread
            $the_thread = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                '*',
                array(
                    'thread_id' => $params['thread'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the thread exists
            if ( !$the_thread ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                );  

            }

            // Set where
            $where = array(
                'guest_id' => $the_thread[0]['guest_id'],
                'website_id' => $the_thread[0]['website_id']
            );              

        } else {

            // Get the guest
            $the_guest = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_guests',
                '*',
                array(
                    'guest_id' => $params['guest'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the guest exists
            if ( !$the_guest ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_no_guest_was_found')
                );  

            }

            // Set where
            $where = array(
                'guest_id' => $params['guest']
            );  
            
        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where in
        $where_in = array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_visited_urls_found')
                    );  

                }

            }

        }

        // Set like
        $like = array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order_by' =>  array('url_id', 'DESC'),
        );

        // Verify if start exists
        if ( isset($params['page']) ) {

            // Set the start
            $limit['start'] = $params['page'] * 10;

            // Set the limit
            $limit['limit'] = $params['limit'];

        }

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_visited_urls_' . $parameters_string) ) {

            // Set the cache
            $the_visited_urls = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_visited_urls_' . $parameters_string);

        } else {

            // Get the visited urls
            $the_visited_urls = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_guests_visited_urls',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_visited_urls_' . $parameters_string, $the_visited_urls);

            // Set saved cronology
            update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_visited_urls_list', 'crm_chatbot_user_' . md_the_user_id() . '_visited_urls_' . $parameters_string);

        }

        // Verify if the visited urls exists
        if ( $the_visited_urls ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_visited_urls_' . $parameters_string) ) {

                // Get total visited urls
                $the_total = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_visited_urls_' . $parameters_string);

            } else {

                // Get the total visited urls
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests_visited_urls',
                    'COUNT(url_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_visited_urls_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_visited_urls_list', 'crm_chatbot_user_' . md_the_user_id() . '_load_total_visited_urls_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'urls' => $the_visited_urls,
                'total' => $the_total[0]['total']
            );

        }

        // Prepare the false response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_visited_urls_found')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Guests
    //-----------------------------------------------------

    /**
     * The protected method generate_string generates a string for cache file
     * 
     * @param array $args contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return string
     */
    protected function generate_string($args) {

        // Create and return string
        return str_replace(' ', '_', implode('_', $args) );
        
    }

}

/* End of file read.php */