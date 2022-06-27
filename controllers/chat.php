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
 * Ajax class processes the app's chat calls
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

            header('Access-Control-Allow-Origin: *');

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

        } else if ( $this->CI->input->get('send_bot_element', TRUE) ) {

            // Send bot element
            $this->send_bot_element(); 

        } else if ( $this->CI->input->get('get_messages', TRUE) ) {
            
            // Get the messages
            $this->get_messages(); 

        } else if ( $this->CI->input->get('get_updates', TRUE) ) {

            header('Access-Control-Allow-Origin: *');
            
            // Get the updates
            $this->get_updates(); 

        } else if ( $this->CI->input->get('save_guest_data', TRUE) ) {

            // Save guest data
            $this->save_guest_data(); 

        } else if ( $this->CI->input->get('accept_gdrp', TRUE) ) {

            // Save gdrp option
            $this->accept_gdrp(); 

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
     * The public method send_bot_element sends a bot's element
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function send_bot_element() {

        // Verify if send_bot_element parameter exists
        if ( is_numeric($this->CI->input->get('send_bot_element', TRUE)) ) {

            // Check if data was submitted
            if ($this->CI->input->post()) {

                // Add form validation
                $this->CI->form_validation->set_rules('guest', 'Guest', 'trim|required');
                $this->CI->form_validation->set_rules('bot', 'Bot', 'trim|required');
                $this->CI->form_validation->set_rules('connector', 'Connector', 'trim|required');
                $this->CI->form_validation->set_rules('timezone', 'Timezone', 'trim|required');
                $this->CI->form_validation->set_rules('urls', 'Urls', 'trim');

                // Get data
                $guest = $this->CI->input->post('guest', TRUE);
                $bot = $this->CI->input->post('bot', TRUE);
                $connector = $this->CI->input->post('connector', TRUE);
                $timezone = $this->CI->input->post('timezone', TRUE);
                $urls = $this->CI->input->post('urls', TRUE);

                // Verify if the submitted data is correct
                if ( $this->CI->form_validation->run() !== false ) {

                    // Get the website
                    $the_website = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites',
                        '*',
                        array(
                            'website_id' => $this->CI->input->get('send_bot_element', TRUE)
                        )
                    );

                    // Verify if the website exists
                    if ( $the_website ) {

                        // Require the Send Message Part Inc file
                        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/message/send.php';

                        // Send bot 
                        crm_chatbot_send_bot_from_parts(array('website_id' => $the_website[0]['website_id'], 'user_id' => $the_website[0]['user_id'], 'bot' => $bot, 'connector' => $connector, 'guest' => $guest, 'timezone' => $timezone, 'visited_urls' => $urls));
                        exit(); 

                    }

                }

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

    /**
     * The public method accept_gdrp saves gdrp option
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function accept_gdrp() {

        // Check if data was submitted
        if ($this->CI->input->post() && is_numeric($this->CI->input->get('accept_gdrp', TRUE))) {

            // Add form validation
            $this->CI->form_validation->set_rules('guest', 'Guest', 'trim|required');
            $this->CI->form_validation->set_rules('timezone', 'Timezone', 'trim|required');

            // Get data
            $guest = $this->CI->input->post('guest', TRUE);
            $timezone = $this->CI->input->post('timezone', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Get the website
                $the_website = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'website_id' => $this->CI->input->get('accept_gdrp', TRUE)
                    )
                );

                // Verify if the website exists
                if ( $the_website ) { 
                    
                    // Get the guest
                    $the_guest = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_guests',
                        '*',
                        array(
                            'id' => $guest
                        )
                    );

                    // Verify if guest exists
                    if ( $the_guest ) {

                        // Accept gdrp
                        if ( update_crm_chatbot_websites_guests_meta($the_guest[0]['guest_id'], 'accept_gdrp', 1) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE
                            );

                            // Display the success response
                            echo json_encode($data); 
                            exit();

                        }

                    } else {

                        // Guest params
                        $guest_params = array(
                            'user_id' => $the_website[0]['user_id'],
                            'id' => $guest,
                            'created' => time()
                        );

                        // Save guest
                        $guest_id = $this->CI->base_model->insert('crm_chatbot_websites_guests', $guest_params);

                        // Verify if the guest was saved
                        if ( $guest_id ) {

                            // Save the guest's IP
                            update_crm_chatbot_websites_guests_meta($guest_id, 'guest_ip', $this->CI->input->ip_address());

                            // Verify if the timezone exists
                            if ( !empty($timezone) ) {

                                // Verify if the timezone is valid
                                if ( in_array($timezone, timezone_identifiers_list()) ) {

                                    // Save the guest's timezone
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_timezone', $timezone);                    

                                }

                            }

                            // Accept gdrp
                            if ( update_crm_chatbot_websites_guests_meta($guest_id, 'accept_gdrp', 1) ) {

                                // Prepare the success response
                                $data = array(
                                    'success' => TRUE
                                );

                                // Display the success response
                                echo json_encode($data); 
                                exit();

                            }
                            
                        }

                    }

                }

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

}

/* End of file chat.php */