<?php
/**
 * Threads Newest Helper
 *
 * This file newest contains the methods
 * to read the newest threads
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
 * Newest class extends the class Threads to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Newest {
    
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
     * The public method crm_chatbot_the_threads checks for newest threads in the database
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

        // Verify if the last parameter exists
        if ( empty($params['last']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_last_parameter_missing')
            );

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_threads.user_id' => $this->CI->user_id,
            'crm_chatbot_websites_threads.thread_id >' => $params['last']
        );   

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

        // Set join
        $join = array(
            array(
                'table' => 'crm_chatbot_websites',
                'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                'join_from' => 'LEFT'
            )
        );

        // Get the threads
        $the_threads = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
            $where,
            $where_in,
            $like,
            $join
        );

        // Verify if the threads exists
        if ( $the_threads ) {

            // Prepare success response
            return array(
                'success' => TRUE
            );

        }

        // Prepare the false response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
        );    

    }

}

/* End of file newest.php */