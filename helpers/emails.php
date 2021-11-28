<?php
/**
 * Emails Helpers
 *
 * This file contains the class Emails
 * with methods to manage the emails
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Emails as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsEmails;

/*
 * Emails class provides the methods to manage the emails
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Emails {
    
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

        // Load the CRM Chatbot Emails Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_emails_model', 'crm_chatbot_emails_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Emails
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_emails gets emails from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_emails() {

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

                // Get the emails
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsEmails\Read)->crm_chatbot_the_emails($params);

                // Verify if emails exists
                if ( !empty($data['emails']) ) {

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
            'message' => $this->CI->lang->line('crm_chatbot_no_emails_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
    * The public method crm_chatbot_delete_email deletes a email
    * 
    * @since 0.0.8.4
    * 
    * @return void
    */
    public function crm_chatbot_delete_email() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('email', 'Email ID', 'trim|numeric|required');

            // Get data
            $email = $this->CI->input->post('email', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'email' => $email
                );            

                // Try to delete the email
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsEmails\Delete)->crm_chatbot_delete_email($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_email_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file emails.php */