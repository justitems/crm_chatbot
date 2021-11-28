<?php
/**
 * Bot Helpers
 *
 * This file contains the class Bot
 * with methods to provide the bot's replies
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bot as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot;

/*
 * Bot class provides the methods to provide the bot's replies
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Bot {
    
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Get CodeIgniter object instance
        $this->CI =& get_instance();

        // Load the CRM Chatbot Bots Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_bots_model', 'crm_chatbot_bots_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Bot
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_bot_start shows the bot's start in the chat
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_bot_start() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot', 'trim|numeric|required');

            // Get data
            $bot = $this->CI->input->post('bot', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'bot' => $bot
                );

                // Get response
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_start($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_bot_message shows the bot's message in the chat
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_bot_message() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('connector', 'Connector', 'trim|required');

            // Get data
            $bot = $this->CI->input->post('bot', TRUE);
            $connector = $this->CI->input->post('connector', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'bot' => $bot,
                    'connector' => $connector
                );

                // Get response
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_message($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
        );

        // Display the false response
        echo json_encode($data);

    }

}

/* End of file bot.php */