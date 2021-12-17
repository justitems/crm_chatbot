<?php
/**
 * Threads Read Helper
 *
 * This file read contains the methods
 * to read the threads
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Threads;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Threads to make it lighter
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
    // Ajax's methods for the Threads
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_threads gets the threads list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_threads($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_threads.user_id' => $this->CI->user_id
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
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
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
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = array();

        // Verify if key exists
        if ( isset($params['key']) ) {

            // Get the threads by key
            $the_threads_ids = $this->CI->crm_chatbot_websites_model->the_threads_search(array('key' => $params['key']));

            // Verify if the threads were found
            if ( $the_threads_ids ) {

                // Set where in
                $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

            } else {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                );  
                
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

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_threads_' . $parameters_string) ) {

            // Set the cache
            $the_threads = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_threads_' . $parameters_string);

        } else {

            // Get the threads
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                $where,
                $where_in,
                $like,
                $join,
                $limit,
                array(
                    'order_by' => array('crm_chatbot_websites_threads.updated', 'DESC')
                )
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_threads_' . $parameters_string, $the_threads);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_threads_' . $parameters_string);

        }

        // Verify if the threads exists
        if ( $the_threads ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_threads_' . $parameters_string) ) {

                // Get total threads
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_threads_' . $parameters_string);

            } else {

                // Get the total threads
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_threads_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_threads_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'threads' => $the_threads,
                'total' => $the_total[0]['total']
            );

        }

    }

    /**
     * The public method crm_chatbot_the_favorite_threads gets the favorite threads list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_favorite_threads($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_threads.user_id' => $this->CI->user_id,
            'crm_chatbot_websites_threads.favorite >' => 0
        );

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
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
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
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = array();

        // Verify if key exists
        if ( isset($params['key']) ) {

            // Get the threads by key
            $the_threads_ids = $this->CI->crm_chatbot_websites_model->the_threads_search(array('key' => $params['key']));

            // Verify if the threads were found
            if ( $the_threads_ids ) {

                // Set where in
                $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

            } else {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                );  
                
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

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_favorite_threads_' . $parameters_string) ) {

            // Set the cache
            $the_threads = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_favorite_threads_' . $parameters_string);

        } else {

            // Get the threads
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_favorite_threads_' . $parameters_string, $the_threads);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_favorite_threads_' . $parameters_string);

        }

        // Verify if the threads exists
        if ( $the_threads ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_favorite_threads_' . $parameters_string) ) {

                // Get total threads
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_favorite_threads_' . $parameters_string);

            } else {

                // Get the total threads
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_favorite_threads_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_favorite_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_favorite_threads_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'threads' => $the_threads,
                'total' => $the_total[0]['total']
            );

        }

    }

    /**
     * The public method crm_chatbot_the_important_threads gets the important threads list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_important_threads($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_threads.user_id' => $this->CI->user_id,
            'crm_chatbot_websites_threads.important >' => 0
        );

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
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => $this->CI->user_id
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
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
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = array();

        // Verify if key exists
        if ( isset($params['key']) ) {

            // Get the threads by key
            $the_threads_ids = $this->CI->crm_chatbot_websites_model->the_threads_search(array('key' => $params['key']));

            // Verify if the threads were found
            if ( $the_threads_ids ) {

                // Set where in
                $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

            } else {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                );  
                
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

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_important_threads_' . $parameters_string) ) {

            // Set the cache
            $the_threads = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_important_threads_' . $parameters_string);

        } else {

            // Get the threads
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_important_threads_' . $parameters_string, $the_threads);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_important_threads_' . $parameters_string);

        }

        // Verify if the threads exists
        if ( $the_threads ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_important_threads_' . $parameters_string) ) {

                // Get total threads
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_important_threads_' . $parameters_string);

            } else {

                // Get the total threads
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_important_threads_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_important_threads_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_important_threads_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'threads' => $the_threads,
                'total' => $the_total[0]['total']
            );

        }

    }

    //-----------------------------------------------------
    // Quick Helpers for the Threads
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