<?php
/**
 * Chat Controller
 *
 * This file processes the app's chat calls
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers as CmsBaseUserAppsCollectionCrm_chatbotHelpers;
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bot as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot;

// Load the General Inc file
md_include_component_file(CMS_BASE_USER . 'inc/general.php');

/*
 * Ajaz class processes the app's chat calls
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
 */
class Chat {
    
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
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'crm_chatbot_chat', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );
        
    } 

    /**
     * The public method process processes the received requests
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function process() {
        
        // Verify if get_chatbot parameter exists
        if ( $this->CI->input->get('get_chatbox', TRUE) ) {

            // Load chat's box
            $this->get_code();

        } else if ($this->CI->input->get('get_chat', TRUE)) {

            // Load chat
            $this->get_chat();  

        } else if ( $this->CI->input->get('upload_file', TRUE) ) {

            // Upload a file
            $this->upload_file(); 

        } else if ( $this->CI->input->get('send_message', TRUE) ) {

            // Send message
            $this->send_message(); 

        } else if ( $this->CI->input->get('get_messages', TRUE) ) {
            
            // Get the messages
            $this->get_messages(); 

        } else if ( $this->CI->input->get('get_updates', TRUE) ) {
            
            // Get the updates
            $this->get_updates(); 

        } else if ( $this->CI->input->get('save_guest_data', TRUE) ) {

            // Save guest data
            $this->save_guest_data(); 

        }
        
    }

    /**
     * The public method get_code gets the code
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function get_code() {
        
        // Verify if get_chatbot parameter exists
        if ( is_numeric($this->CI->input->get('get_chatbox', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('get_chatbox', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Verify if the chat is enabled
                if ( empty($the_website[0]['status']) ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_chat_disabled_this_website')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();
                    
                }

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Get all styles
                $the_chat_style = the_crm_chatbot_chat_styles($this->CI->input->get('get_chatbox', TRUE));

                // Verify if chat style exists
                if ( !$the_chat_style ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_chat_style')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();

                }

                // Prepare the success response
                $response = array(
                    'success' => TRUE,
                    'css' => $the_chat_style['style_css']['code'],
                    'html' => $the_chat_style['style_html']
                );

                // Verify if trigger exists
                if ( !empty($the_chat_style['triggers']) ) {

                    // Set trigger
                    $response['triggers'] = $the_chat_style['triggers'];

                }

                // Verify if the welcome message is enabled
                if ( the_crm_chatbot_websites_meta($this->CI->input->get('get_chatbox', TRUE), 'show_welcome') ) {

                    // Get the message's bot if exists
                    $bot = the_crm_chatbot_websites_meta($this->CI->input->get('get_chatbox', TRUE), 'welcome_message_bot');

                    // Verify if the bot's exists
                    if ( $bot ) {

                        // Set params
                        $params = array(
                            'bot' => $bot
                        );

                        // Get bot start
                        $bot_start = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_start($params);

                        // Verify if the bot was found
                        if ( !empty($bot_start['success']) ) {

                            // Set bot's content
                            $response['welcome_bot'] = $bot_start;

                        }

                    }

                }

                // Display the success response
                echo json_encode($response);
                exit();                     

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method get_chat gets the chat
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function get_chat() {
        
        // Require the Chat Part Inc file
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/chat/chat.php';

        // Request the chat
        crm_chatbot_get_chat_from_parts();
        
    }

    /**
     * The public method upload_file uploads a file
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function upload_file() {
        
        // Verify if upload_file parameter exists
        if ( is_numeric($this->CI->input->get('upload_file', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('upload_file', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Send message and attach file
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_upload_file($the_website[0]);

            }

        }
        
    }

    /**
     * The public method send_message sends message
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function send_message() {
        
        // Verify if send_message parameter exists
        if ( is_numeric($this->CI->input->get('send_message', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('send_message', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Send message
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_send_message($the_website[0]);

            }

        }
        
    }

    /**
     * The public method get_messages gets the messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function get_messages() {
        
        // Verify if get_messages parameter exists
        if ( is_numeric($this->CI->input->get('get_messages', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('get_messages', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Get messages
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_get_public_messages($the_website[0]);

            }

        }
        
    }

    /**
     * The public method get_updates checks for new messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function get_updates() {
        
        // Verify if get_updates parameter exists
        if ( is_numeric($this->CI->input->get('get_updates', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('get_updates', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Get messages
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_get_updates($the_website[0]);

            }

        }
        
    }

    /**
     * The public method save_guest_data saves the user's data
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function save_guest_data() {

        // Verify if save_guest_data parameter exists
        if ( is_numeric($this->CI->input->get('save_guest_data', TRUE)) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $this->CI->input->get('save_guest_data', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {        

                // Verify if the guest session already exists
                if ( !$this->CI->session->userdata('crm_chatbot_guest_session') ) {

                    // Create the quest session
                    $this->CI->session->set_userdata('crm_chatbot_guest_session', uniqid(microtime(true).mt_Rand()));

                }

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Save data
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Guests)->crm_chatbot_save_guest_data($the_website[0]);

            }

        }
        
    }

}

/* End of file chat.php */