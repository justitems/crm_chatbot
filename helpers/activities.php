<?php
/**
 * Activities Helpers
 *
 * This file contains the class Activities
 * with methods to manage the activities
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
 * Activities class provides the methods to manage the activities
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Activities {
    
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
    // Ajax's methods for the Activities
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_activities gets all activities by page for Chatbot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_activities() {

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_activities_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $page = $this->CI->input->post('page', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set the limit
                $limit = 10;

                // Verify if page is numeric
                if ( !is_numeric($page) ) {

                    // Set page's value
                    $page = 1;

                }

                // Decrease the page
                $page--;

                // Get parameters string
                $parameters_string = $this->generate_string(array(
                    'page' => $page
                ));

                // Verify if the cache exists for this query
                if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_chatbot_activities_' . $parameters_string) ) {

                    // Get activities
                    $get_activities = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_chatbot_activities_' . $parameters_string);

                } else {

                    // Set where parameters
                    $where = array(
                        'crm_activities.user_id' => md_the_user_id(),
                        'crm_activities.activity_type' => 'crm_chatbot'
                    );

                    // Use the base model for a simply sql query
                    $get_activities = $this->CI->base_model->the_data_where(
                        'crm_activities',
                        'crm_activities.*, crm_activities_meta.meta_value as title',
                        $where,
                        array(),
                        array(),
                        array(
                            array(
                                'table' => 'crm_activities_meta',
                                'condition' => "crm_activities.activity_id=crm_activities_meta.activity_id AND crm_activities_meta.meta_name='title'",
                                'join_from' => 'LEFT'
                            )                          
                        ),
                        array(
                            'order_by' => array('crm_activities.activity_id', 'DESC'),
                            'start' => ($page * $limit),
                            'limit' => $limit
                        )
                    );

                    // Save cache
                    md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_chatbot_activities_' . $parameters_string, $get_activities);

                    // Set saved cronology
                    update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list', 'crm_chatbot_user_' . md_the_user_id() . '_chatbot_activities_' . $parameters_string);

                }

                // Verify if activities exists
                if ( $get_activities ) {

                    // Verify if the cache exists for this query
                    if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_' . $parameters_string . '_total_activities_list_number') ) {

                        // Get total activities
                        $get_total = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_' . $parameters_string . '_total_activities_list_number');

                    } else {

                        // Use the base model for a simply sql query
                        $get_total = $this->CI->base_model->the_data_where(
                            'crm_activities',
                            'COUNT(crm_activities.activity_id) AS total',
                            $where,
                            array(),
                            array(),
                            array(
                                array(
                                    'table' => 'crm_activities_meta',
                                    'condition' => "crm_activities.activity_id=crm_activities_meta.activity_id AND crm_activities_meta.meta_name='title'",
                                    'join_from' => 'LEFT'
                                )
                            ),
                        );

                        // Save cache
                        md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_' . $parameters_string . '_total_activities_list_number', $get_total);

                        // Set saved cronology
                        update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list', 'crm_chatbot_user_' . md_the_user_id() . '_' . $parameters_string . '_total_activities_list_number');

                    }

                    // All activities container
                    $all_activities = array();

                    // List the activities
                    foreach ( $get_activities as $get_activity ) {

                        // Add activity to the container
                        $all_activities[] = array(
                            'activity_id' => $get_activity['activity_id'],
                            'title' => $get_activity['title'],
                            'created' => md_the_user_time(md_the_user_id(), $get_activity['created'])
                        );

                    }

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'activities' => $all_activities,
                        'total' => $get_total[0]['total'], 
                        'page' => ($page + 1),
                        'current_time' => time()
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
            'message' => $this->CI->lang->line('crm_chatbot_no_activities_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_activity loads a client's activity
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function crm_chatbot_get_activity() {

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_activity_not_found')
            );

            // Display the false response
            echo json_encode($data);
            exit();

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('activity', 'Activity ID', 'trim|numeric|required');

            // Get data
            $activity = $this->CI->input->post('activity', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Use the base model for a simply sql query
                $get_activity = $this->CI->base_model->the_data_where(
                    'crm_activities',
                    'crm_activities.*, crm_activities_meta.meta_value as title, content.meta_value actions',
                    array(
                        'crm_activities.activity_id' => $activity,
                        'crm_activities.user_id' => md_the_user_id(),
                        'crm_activities.activity_type' => 'crm_chatbot'
                    ),
                    array(),
                    array(),
                    array(
                        array(
                            'table' => 'crm_activities_meta',
                            'condition' => "crm_activities.activity_id=crm_activities_meta.activity_id AND crm_activities_meta.meta_name='title'",
                            'join_from' => 'LEFT'
                        ),
                        array(
                            'table' => 'crm_activities_meta content',
                            'condition' => "crm_activities.activity_id=content.activity_id AND content.meta_name='actions'",
                            'join_from' => 'LEFT'
                        )
                    )
                );

                // Verify if activity exists
                if ( $get_activity ) {

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'activity' => $get_activity
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
            'message' => $this->CI->lang->line('crm_chatbot_activity_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    //-----------------------------------------------------
    // Quick Helpers for the Websites
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

/* End of file activities.php */