<?php
/**
 * Info Helpers
 *
 * This file contains the class Info
 * with methods to manage the threads for bots and quick replies
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Info class provides the methods to manage the threads for bots and quick replies
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Info {
    
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

        // Load the CRM Chatbot Websites Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_websites_model', 'crm_chatbot_websites_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the threads
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_reply_threads gets reply's threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_reply_threads() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();      

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('reply', 'Reply', 'trim|numeric|required');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $reply = $this->CI->input->post('reply', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get team's member
                $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

                // Set where
                $where = array(
                    'crm_chatbot_websites_threads.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_messages.reply_id' => $reply
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

                                // Set team's member to params
                                $params['team_member'] = 1;

                                // Set where in
                                $where_in = array('crm_chatbot_websites.website_id', $websites);

                            } else {

                                // Prepare the false response
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                                );

                                // Display the false response
                                echo json_encode($data);
                                exit();

                            }

                        }

                    }

                }

                // Set like
                $like = array();

                // Verify if key exists
                if ( $key ) {

                    // Get the threads by key
                    $the_threads_ids = $this->CI->crm_chatbot_websites_model->the_threads_search(array('key' => $key));

                    // Verify if the threads were found
                    if ( $the_threads_ids ) {

                        // Set where in
                        $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

                    } else {

                        // Prepare the false response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                        );

                        // Display the false response
                        echo json_encode($data);
                        exit(); 
                        
                    }

                }

                // Set join
                $join = array(
                    array(
                        'table' => 'crm_chatbot_websites',
                        'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                        'join_from' => 'LEFT'
                    ),
                    array(
                        'table' => 'crm_chatbot_websites_messages',
                        'condition' => 'crm_chatbot_websites_threads.thread_id=crm_chatbot_websites_messages.thread_id',
                        'join_from' => 'LEFT'
                    )
                );

                // Set limit
                $limit = array(
                    'order' =>  array('crm_chatbot_websites_threads.updated', 'DESC'),
                    'start' => ($page * 10),
                    'limit' => 10
                );

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

                // Verify if the threads exists
                if ( $the_threads ) {

                    // Get the total threads
                    $the_total = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_threads',
                        'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                        $where,
                        $where_in,
                        $like,
                        $join
                    );

                    // Display the success response
                    echo json_encode(array(
                        'success' => TRUE,
                        'threads' => $the_threads,
                        'total' => $the_total[0]['total'],
                        'page' => ($page + 1),
                        'words' => array(
                            'of' => $this->CI->lang->line('crm_chatbot_of'),
                            'results' => $this->CI->lang->line('crm_chatbot_results')
                        )
                    ));

                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_bot_threads gets bot's threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_bot_threads() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();      

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('bot', 'Bot ID', 'trim|numeric|required');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $bot = $this->CI->input->post('bot', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get team's member
                $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

                // Set where
                $where = array(
                    'crm_chatbot_websites_threads.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_messages.bot_id' => $bot
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

                                // Set team's member to params
                                $params['team_member'] = 1;

                                // Set where in
                                $where_in = array('crm_chatbot_websites.website_id', $websites);

                            } else {

                                // Prepare the false response
                                $data = array(
                                    'success' => FALSE,
                                    'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                                );

                                // Display the false response
                                echo json_encode($data);
                                exit();

                            }

                        }

                    }

                }

                // Set like
                $like = array();

                // Verify if key exists
                if ( $key ) {

                    // Get the threads by key
                    $the_threads_ids = $this->CI->crm_chatbot_websites_model->the_threads_search(array('key' => $key));

                    // Verify if the threads were found
                    if ( $the_threads_ids ) {

                        // Set where in
                        $where_in = array('crm_chatbot_websites_threads.thread_id', array_column($the_threads_ids, 'thread_id'));

                    } else {

                        // Prepare the false response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
                        );

                        // Display the false response
                        echo json_encode($data);
                        exit(); 
                        
                    }

                }

                // Set join
                $join = array(
                    array(
                        'table' => 'crm_chatbot_websites',
                        'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                        'join_from' => 'LEFT'
                    ),
                    array(
                        'table' => 'crm_chatbot_websites_messages',
                        'condition' => 'crm_chatbot_websites_threads.thread_id=crm_chatbot_websites_messages.thread_id',
                        'join_from' => 'LEFT'
                    )
                );

                // Set limit
                $limit = array(
                    'order' =>  array('crm_chatbot_websites_threads.updated', 'DESC'),
                    'start' => ($page * 10),
                    'limit' => 10
                );

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

                // Verify if the threads exists
                if ( $the_threads ) {

                    // Get the total threads
                    $the_total = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_threads',
                        'COUNT(crm_chatbot_websites_threads.thread_id) AS total',
                        $where,
                        $where_in,
                        $like,
                        $join
                    );

                    // Display the success response
                    echo json_encode(array(
                        'success' => TRUE,
                        'threads' => $the_threads,
                        'total' => $the_total[0]['total'],
                        'page' => ($page + 1),
                        'words' => array(
                            'of' => $this->CI->lang->line('crm_chatbot_of'),
                            'results' => $this->CI->lang->line('crm_chatbot_results')
                        )
                    ));

                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_threads_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file info.php */