<?php
/**
 * Guests Helpers
 *
 * This file contains the class Guests
 * with methods to manage the guests
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Guests as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsGuests;

/*
 * Guests class provides the methods to manage the guests
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Guests {
    
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
     * The public method crm_chatbot_get_guests gets the guests
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_guests() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            
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

                // Get the guests
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsGuests\Read)->crm_chatbot_the_guests($params);

                // Verify if guests exists
                if ( !empty($data['guests']) ) {

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
            'message' => $this->CI->lang->line('crm_chatbot_no_guests_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_save_guest_data saves the guest's data
     * 
     * @param array $website contains the website's parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_save_guest_data($website) {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('name', 'Name', 'trim');
            $this->CI->form_validation->set_rules('email', 'Email', 'trim');
            $this->CI->form_validation->set_rules('phone', 'Phone', 'trim');

            // Get data
            $name = $this->CI->input->post('name', TRUE);
            $email = $this->CI->input->post('email', TRUE);
            $phone = $this->CI->input->post('phone', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone
                );             

                // Get the visited urls
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsGuests\Create)->crm_chatbot_save_guest_data($params);

                // Display the returned data
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_visited_urls gets the visited urls
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_visited_urls() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('guest', 'Guest ID', 'trim');
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $guest = $this->CI->input->post('guest', TRUE);
            $thread = $this->CI->input->post('thread', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'thread' => $thread,
                    'page' => $page,
                    'limit' => 10
                );
                
                // Verify if guest exists
                if ( $guest ) {

                    // Set guest
                    $params['guest'] = $guest;

                }

                // Verify if thread exists
                if ( $thread ) {

                    // Set thread
                    $params['thread'] = $thread;

                }                

                // Get the visited urls
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsGuests\Read)->crm_chatbot_the_visited_urls($params);

                // Verify if urls exists
                if ( !empty($data['urls']) ) {

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
            'message' => $this->CI->lang->line('crm_chatbot_no_visited_urls_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_delete_guest deletes a guest
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_guest() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('guest', 'Guest ID', 'trim|numeric|required');

            // Get data
            $guest = $this->CI->input->post('guest', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'guest' => $guest
                );            

                // Try to delete the guest
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsGuests\Delete)->crm_chatbot_delete_guest($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_guest_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file guests.php */