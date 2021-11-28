<?php
/**
 * Threads Helpers
 *
 * This file contains the class Threads
 * with methods to manage the threads
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

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Threads as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads;

/*
 * Threads class provides the methods to manage the threads
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Threads {
    
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
    // Ajax's methods for the Threads
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_threads gets threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_threads() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');

            // Get data
            $guest = $this->CI->input->post('guest', TRUE);
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $website = $this->CI->input->post('website', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'page' => $page,
                    'limit' => 10
                );
                
                // Verify if guest exists
                if ( $guest ) {

                    // Set guest
                    $params['guest'] = $guest;

                }

                // Verify if key exists
                if ( $key ) {

                    // Set key
                    $params['key'] = $key;

                }     
                
                // Verify if website exists
                if ( is_numeric($website) ) {

                    // Set website
                    $params['website'] = $website;

                }

                // Verify if status exists
                if ( is_numeric($status) ) {

                    // Set status
                    $params['status'] = $status;

                }                

                // Get the threads
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Read)->crm_chatbot_the_threads($params);

                // Verify if threads exists
                if ( !empty($data['threads']) ) {

                    // Set current time
                    $data['current_time'] = time();

                    // Set words
                    $data['words'] = array(
                        'of' => $this->CI->lang->line('crm_chatbot_of'),
                        'results' => $this->CI->lang->line('crm_chatbot_results')
                    );

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Display the success response
                    echo json_encode($data);
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
     * The public method crm_chatbot_check_for_new_threads checks for new threads in the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_check_for_new_threads() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('last', 'Last', 'trim');

            // Get data
            $last = $this->CI->input->post('last', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {      

                // Set params
                $params = array(
                    'last' => $last
                );

                // Get the threads
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Newest)->crm_chatbot_the_threads($params);

                // Verify if the response is success
                if ( !empty($data['success']) ) {

                    // Display the success response
                    echo json_encode($data);
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
     * The public method crm_chatbot_get_favorite_threads gets favorite threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_favorite_threads() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $website = $this->CI->input->post('website', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'page' => $page,
                    'limit' => 10
                );             

                // Verify if key exists
                if ( $key ) {

                    // Set key
                    $params['key'] = $key;

                }     
                
                // Verify if website exists
                if ( is_numeric($website) ) {

                    // Set website
                    $params['website'] = $website;

                }

                // Verify if status exists
                if ( is_numeric($status) ) {

                    // Set status
                    $params['status'] = $status;

                }                

                // Get the favorite threads
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Read)->crm_chatbot_the_favorite_threads($params);

                // Verify if threads exists
                if ( !empty($data['threads']) ) {

                    // Set current time
                    $data['current_time'] = time();

                    // Set words
                    $data['words'] = array(
                        'of' => $this->CI->lang->line('crm_chatbot_of'),
                        'results' => $this->CI->lang->line('crm_chatbot_results')
                    );

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Display the success response
                    echo json_encode($data);
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
    * The public method crm_chatbot_get_important_threads gets important threads from the database
    * 
    * @since 0.0.8.5
    * 
    * @return void
    */
    public function crm_chatbot_get_important_threads() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            $website = $this->CI->input->post('website', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'page' => $page,
                    'limit' => 10
                );             

                // Verify if key exists
                if ( $key ) {

                    // Set key
                    $params['key'] = $key;

                }     
                
                // Verify if website exists
                if ( is_numeric($website) ) {

                    // Set website
                    $params['website'] = $website;

                }

                // Verify if status exists
                if ( is_numeric($status) ) {

                    // Set status
                    $params['status'] = $status;

                }                

                // Get the important threads
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Read)->crm_chatbot_the_important_threads($params);

                // Verify if threads exists
                if ( !empty($data['threads']) ) {

                    // Set current time
                    $data['current_time'] = time();

                    // Set words
                    $data['words'] = array(
                        'of' => $this->CI->lang->line('crm_chatbot_of'),
                        'results' => $this->CI->lang->line('crm_chatbot_results')
                    );

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Display the success response
                    echo json_encode($data);
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
     * The public method crm_chatbot_bot_pause sets or removes the bot pause for a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_bot_pause() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim|numeric|required');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Set params
                $params = array(
                    'thread' => $thread
                );              

                // Sets or removes the bot pause for a thread
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Update)->crm_chatbot_bot_pause($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_block_thread blocks or unblocks a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_block_thread() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim|numeric|required');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Set params
                $params = array(
                    'thread' => $thread
                );              

                // Blocks or unblocks a thread
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Update)->crm_chatbot_block_thread($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_favorite_thread favorites or unfavorites a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_favorite_thread() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim|numeric|required');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Set params
                $params = array(
                    'thread' => $thread
                );              

                // Blocks or unblocks a thread
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Update)->crm_chatbot_favorite_thread($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_important_thread marks as important a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_important_thread() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim|numeric|required');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Set params
                $params = array(
                    'thread' => $thread
                );              

                // Marks or unmarks as important a thread
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsThreads\Update)->crm_chatbot_important_thread($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file threads.php */