<?php
/**
 * Overview Helpers
 *
 * This file contains the class Overview
 * with methods to manage the overview
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
 * Overview class provides the methods to manage the overview
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Overview {
    
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
    // Ajax's methods for the Overview
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_overview gets overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_overview() {

        // Create the response
        $response = array(
            'guests' => array(),
            'total' => array(),
            'actions' => array(),
            'agents' => array()
        );

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Prepare response
            $data = array(
                'success' => FALSE
            );

            // Display the response
            echo json_encode($data);
            exit();

        }

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('date_from', 'Date From', 'trim');
            $this->CI->form_validation->set_rules('date_to', 'Date To', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $date_from = $this->CI->input->post('date_from', TRUE);
            $date_to = $this->CI->input->post('date_to', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {    

                // Verify if $date_from is not empty
                if ( $date_from ) {

                    // Turn $date_from to timestamp
                    $date_from = strtotime($date_from);

                } else {

                    // Set current time
                    $date_from = strtotime('-30 days');

                }

                // Verify if $date_to is not empty
                if ( $date_to ) {

                    // Turn $date_to to timestamp
                    $date_to = strtotime($date_to);

                } else {

                    // Set current time
                    $date_to = time();

                }

                // Set guests where
                $guests_where = array(
                    'crm_chatbot_websites_guests.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_guests.created >=' => $date_from,
                    'crm_chatbot_websites_guests.created <=' => $date_to
                );

                // Verify if website exists
                if ( $website ) {

                    // Set website's ID
                    $guests_where['crm_chatbot_websites_threads.website_id'] = $website;

                }

                // Get the guests
                $the_guests = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests',
                    'COUNT(crm_chatbot_websites_guests.guest_id) AS total, LEFT(FROM_UNIXTIME(crm_chatbot_websites_guests.created),10) AS date',
                    $guests_where,
                    array(),
                    array(),
                    array(
                        array(
                            'table' => 'crm_chatbot_websites_threads',
                            'condition' => 'crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_threads.guest_id',
                            'join_from' => 'LEFT'
                        )
                    ),
                    array(
                        'group_by' => array('LEFT(FROM_UNIXTIME(crm_chatbot_websites_guests.created),10)')
                    )
                );

                // Verify if guests exists
                if ( $the_guests ) {

                    // Change guests value
                    $response['guests'] = array(
                        'date' => array_column($the_guests, 'date'),
                        'total' => array_column($the_guests, 'total')
                    );

                }

                // Set where messages
                $messages_where = array(
                    'crm_chatbot_websites_messages.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_messages.created >=' => $date_from,
                    'crm_chatbot_websites_messages.created <=' => $date_to
                );

                // Verify if website exists
                if ( $website ) {

                    // Set website's ID
                    $messages_where['crm_chatbot_websites_threads.website_id'] = $website;

                }

                // Get the messages
                $the_messages = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_messages',
                    "COUNT(crm_chatbot_websites_messages.message_id) AS total",
                    $messages_where,
                    array(),
                    array(),
                    array(
                        array(
                            'table' => 'crm_chatbot_websites_threads',
                            'condition' => 'crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id',
                            'join_from' => 'LEFT'
                        )
                    )
                );

                // Verify if messages exists
                if ( $the_messages ) {

                    // Set where threads
                    $threads_where = array(
                        'user_id' => $this->CI->user_id,
                        'created >=' => $date_from,
                        'created <=' => $date_to
                    );

                    // Verify if website exists
                    if ( $website ) {

                        // Set website's ID
                        $threads_where['website_id'] = $website;

                    }                    

                    // Get the threads
                    $the_threads = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_threads',
                        "COUNT(thread_id) AS total",
                        $threads_where
                    );  

                    // Set quick replies where
                    $where_quick_replies = array(
                        'crm_chatbot_websites_messages.user_id' => $this->CI->user_id,
                        'crm_chatbot_websites_messages.created >=' => $date_from,
                        'crm_chatbot_websites_messages.created <=' => $date_to,
                        'crm_chatbot_websites_messages.bot' => '2'
                    );

                    // Verify if website exists
                    if ( $website ) {

                        // Set website's ID
                        $the_quick_replies['crm_chatbot_websites_threads.website_id'] = $website;

                    }
                    
                    // Get the quick replies
                    $the_quick_replies = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_messages',
                        "COUNT(crm_chatbot_websites_messages.message_id) AS total",
                        $where_quick_replies,
                        array(),
                        array(),
                        array(
                            array(
                                'table' => 'crm_chatbot_websites_threads',
                                'condition' => 'crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id',
                                'join_from' => 'LEFT'
                            )
                        )
                    );                    

                    // Change total value
                    $response['total'] = array(
                        'messages' => $the_messages[0]['total'],
                        'threads' => !empty($the_threads)?$the_threads[0]['total']:0,
                        'quick_replies' => !empty($the_quick_replies)?$the_quick_replies[0]['total']:0,
                        'actions' => 0
                    );

                }                

            }

        }

        // Prepare response
        $data = array(
            'success' => TRUE,
            'response' => $response,
            'words' => array(
                'fore_color' => '#999',
                'colors' => '#2E92FA',
                'stroke_color' => '#fff',
                'border_color' => '#f7f9fc',
                'guests' => $this->CI->lang->line('crm_chatbot_guests'),
                'threads' => $this->CI->lang->line('crm_chatbot_guests'),
                'quick_replies' => $this->CI->lang->line('crm_chatbot_quick_replies'),
                'messages' => $this->CI->lang->line('crm_chatbot_messages'),
                'actions' => $this->CI->lang->line('crm_chatbot_actions'),
            )
        );

        // Display the response
        echo json_encode($data); 

    }

    /**
     * The public method crm_chatbot_get_actions gets actions for overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_actions() {

        // Verify if trigger exists
        if ( $this->CI->session->userdata( 'trigger' ) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_actions_were_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();

        }

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('date_from', 'Date From', 'trim');
            $this->CI->form_validation->set_rules('date_to', 'Date To', 'trim');

            // Get data
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $website = $this->CI->input->post('website', TRUE);
            $date_from = $this->CI->input->post('date_from', TRUE);
            $date_to = $this->CI->input->post('date_to', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Verify if $date_from is not empty
                if ( $date_from ) {

                    // Turn $date_from to timestamp
                    $date_from = strtotime($date_from);

                } else {

                    // Set current time
                    $date_from = strtotime('-30 days');

                }

                // Verify if $date_to is not empty
                if ( $date_to ) {

                    // Turn $date_to to timestamp
                    $date_to = strtotime($date_to);

                } else {

                    // Set current time
                    $date_to = time();

                }

                // Set where
                $where = array(
                    'crm_chatbot_websites_triggers.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_triggers_guests.trigger_id >' => 0,
                    'crm_chatbot_websites_triggers_guests.created >=' => $date_from,
                    'crm_chatbot_websites_triggers_guests.created <=' => $date_to
                );

                // Verify if website exists
                if ( $website ) {

                    // Set website's ID
                    $where['crm_chatbot_websites_triggers.website_id'] = $website;

                }

                // Get the actions
                $the_actions = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_triggers_guests',
                    'count(DISTINCT crm_chatbot_websites_triggers_guests.trigger_id) AS actions_count, crm_chatbot_websites_triggers.trigger_name, crm_chatbot_websites.website_id, crm_chatbot_websites.domain',
                    $where,
                    array(),
                    array(),
                    array(
                        array(
                            'table' => 'crm_chatbot_websites_triggers',
                            'condition' => 'crm_chatbot_websites_triggers_guests.trigger_id=crm_chatbot_websites_triggers.trigger_id',
                            'join_from' => 'LEFT'
                        ),
                        array(
                            'table' => 'crm_chatbot_websites',
                            'condition' => 'crm_chatbot_websites_triggers.website_id=crm_chatbot_websites.website_id',
                            'join_from' => 'LEFT'
                        )                        
                    ),
                    array(
                        'group_by' => array('crm_chatbot_websites_triggers_guests.trigger_id'),
                        'order_by' =>  array('actions_count', 'DESC'),
                        'start' => ($page * 10),
                        'limit' => 10
                    )
                );

                // Verify if actions exists
                if ( $the_actions ) {

                    // Get the total number of actions
                    $the_total = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_triggers_guests',
                        'COUNT(crm_chatbot_websites_triggers_guests.trigger_id) AS total',
                        $where,
                        array(),
                        array(),
                        array(
                            array(
                                'table' => 'crm_chatbot_websites_triggers',
                                'condition' => 'crm_chatbot_websites_triggers_guests.trigger_id=crm_chatbot_websites_triggers.trigger_id',
                                'join_from' => 'LEFT'
                            )
                        ),                        
                        array(
                            'group_by' => array('crm_chatbot_websites_triggers_guests.trigger_id')
                        )
                    );

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'actions' => $the_actions,
                        'total' => $the_total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_actions_were_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_agents gets agents for overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_agents() {

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_agents_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();

        }

        // Check if data was submitted
        if ( $this->CI->input->post() ) {

            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('date_from', 'Date From', 'trim');
            $this->CI->form_validation->set_rules('date_to', 'Date To', 'trim');

            // Get data
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $website = $this->CI->input->post('website', TRUE);
            $date_from = $this->CI->input->post('date_from', TRUE);
            $date_to = $this->CI->input->post('date_to', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Verify if $date_from is not empty
                if ( $date_from ) {

                    // Turn $date_from to timestamp
                    $date_from = strtotime($date_from);

                } else {

                    // Set current time
                    $date_from = strtotime('-30 days');

                }

                // Verify if $date_to is not empty
                if ( $date_to ) {

                    // Turn $date_to to timestamp
                    $date_to = strtotime($date_to);

                } else {

                    // Set current time
                    $date_to = time();

                }

                // Set where
                $where = array(
                    'crm_chatbot_websites_messages.user_id' => $this->CI->user_id,
                    'crm_chatbot_websites_messages.member_id >' => 0,
                    'crm_chatbot_websites_messages.created >=' => $date_from,
                    'crm_chatbot_websites_messages.created <=' => $date_to
                );

                // Verify if website exists
                if ( $website ) {

                    // Set website's ID
                    $where['crm_chatbot_websites_threads.website_id'] = $website;

                }

                // Get the agents
                $the_agents = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_messages',
                    'count(DISTINCT crm_chatbot_websites_messages.message_id) AS messages_count, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name, medias.body AS profile_image',
                    $where,
                    array(),
                    array(),
                    array(
                        array(
                            'table' => 'crm_chatbot_websites_threads',
                            'condition' => 'crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id',
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'teams',
                            'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'teams_meta first_name',
                            'condition' => "crm_chatbot_websites_messages.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'teams_meta last_name',
                            'condition' => "crm_chatbot_websites_messages.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'teams_meta member_profile_image',
                            'condition' => "crm_chatbot_websites_messages.member_id=member_profile_image.member_id AND member_profile_image.meta_name='profile_image'",
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'medias',
                            'condition' => 'member_profile_image.meta_value=medias.media_id',
                            'join_from' => 'LEFT'
                        )
                    ),
                    array(
                        'group_by' => array('crm_chatbot_websites_messages.member_id'),
                        'order_by' =>  array('messages_count', 'DESC'),
                        'start' => ($page * 10),
                        'limit' => 10
                    )
                );

                // Verify if agents exists
                if ( $the_agents ) {

                    // Get the total number of agents
                    $the_total = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_messages',
                        'COUNT(crm_chatbot_websites_messages.member_id) AS total',
                        $where,
                        array(),
                        array(),
                        array(),
                        array(
                            'group_by' => array('member_id')
                        )
                    );

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'agents' => $the_agents,
                        'total' => $the_total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_agents_found')
        );

        // Display the false response
        echo json_encode($data);

    }

}

/* End of file overview.php */