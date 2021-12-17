<?php
/**
 * Messages Helpers
 *
 * This file contains the class Messages
 * with methods to manage the messages
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Messages as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages;

/*
 * Messages class provides the methods to manage the messages
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Messages {
    
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
    // Ajax's methods for the Messages
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_send_thread_message sends a new message
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_send_thread_message() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('message', 'Message', 'trim');
            $this->CI->form_validation->set_rules('attachments', 'Attachments', 'trim');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            $message = $this->CI->input->post('message', TRUE);
            $attachments = $this->CI->input->post('attachments', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'thread' => $thread,
                    'message' => $message,
                    'attachments' => $attachments
                );

                // Save website
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Create)->crm_chatbot_send_thread_message($params);

                // Display the response
                echo json_encode($response);
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
     * The public method crm_chatbot_upload_file attaches a file to a message
     * 
     * @param array $website contains the website's parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_upload_file($website) {

        // Verify if the chat is enabled
        if ( empty($website['status']) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_chat_disabled_this_website')
            );

            // Display the false response
            echo json_encode($data);
            exit();
            
        }

        // Verify if the attachments are enabled
        if ( !the_crm_chatbot_websites_meta($website['website_id'], 'enable_attachments') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            );

            // Display the false response
            echo json_encode($data);
            exit();            

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('covers', 'Covers', 'trim');
            $this->CI->form_validation->set_rules('timezone', 'Timezone', 'trim');
            $this->CI->form_validation->set_rules('urls', 'Urls', 'trim');
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim');


            // Get data
            $covers = $this->CI->input->post('covers', TRUE);
            $timezone = $this->CI->input->post('timezone', TRUE);
            $urls = $this->CI->input->post('urls', TRUE);
            $guest = $this->CI->input->post('guest', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'covers' => $covers,
                    'timezone' => $timezone,
                    'visited_urls' => $urls,
                    'guest' => $guest
                );

                // Save message
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Create)->crm_chatbot_upload_file($params);

                // Display the response
                echo json_encode($response);
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
     * The public method crm_chatbot_send_message sends message
     * 
     * @param array $website contains the website's parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_send_message($website) {

        // Verify if the chat is enabled
        if ( empty($website['status']) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_chat_disabled_this_website')
            );

            // Display the false response
            echo json_encode($data);
            exit();
            
        }

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('message', 'Message', 'trim');
            $this->CI->form_validation->set_rules('timezone', 'Timezone', 'trim');
            $this->CI->form_validation->set_rules('urls', 'Urls', 'trim');
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim');

            // Get data
            $message = $this->CI->input->post('message', TRUE);
            $timezone = $this->CI->input->post('timezone', TRUE);
            $urls = $this->CI->input->post('urls', TRUE);
            $guest = $this->CI->input->post('guest', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'message' => $message,
                    'timezone' => $timezone,
                    'visited_urls' => $urls,
                    'guest' => $guest
                );

                // Save message
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Create)->crm_chatbot_send_message($params);

                // Display the response
                echo json_encode($response);
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
     * The public method crm_chatbot_get_private_messages gets the thread's messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_private_messages() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            $page = $this->CI->input->post('page', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'thread' => $thread,
                    'page' => $page
                );

                // Gets messages
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_get_private_messages($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_public_messages gets the public messages
     * 
     * @param array $website contains the website's parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_public_messages($website) {

        // Verify if the chat is enabled
        if ( empty($website['status']) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_chat_disabled_this_website')
            );

            // Display the false response
            echo json_encode($data);
            exit();
            
        }

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim');

            // Get data
            $page = $this->CI->input->post('page', TRUE);
            $guest = $this->CI->input->post('guest', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'page' => $page,
                    'guest' => $guest
                );

                // Gets messages
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_get_public_messages($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_check_for_new_thread_messages checks for new thread's messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_check_for_new_thread_messages() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread', 'trim');
            $this->CI->form_validation->set_rules('reply_on_hold', 'Reply On Hold', 'trim');
            $this->CI->form_validation->set_rules('last', 'Last', 'trim');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            $reply_on_hold = $this->CI->input->post('reply_on_hold', TRUE);
            $last = $this->CI->input->post('last', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'thread' => $thread,
                    'reply_on_hold' => $reply_on_hold,
                    'last' => $last
                );

                // Gets messages
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_check_for_new_thread_messages($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_updates gets updates
     * 
     * @param array $website contains the website's parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_updates($website) {

        // Verify if the chat is enabled
        if ( empty($website['status']) ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_chat_disabled_this_website')
            );

            // Display the false response
            echo json_encode($data);
            exit();
            
        }

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('last', 'Last', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim');

            // Get data
            $last = $this->CI->input->post('last', TRUE);
            $guest = $this->CI->input->post('guest', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'last' => $last,
                    'guest' => $guest
                );

                // Gets messages
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_get_updates($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
        );

        // Display the false response
        echo json_encode($data);

    }

}

/* End of file messages.php */