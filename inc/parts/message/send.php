<?php
/**
 * Message Send Part Inc
 *
 * This file contains some functions used
 * in to send messages
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bot as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot;

if ( !function_exists('crm_chatbot_send_message_from_parts') ) {
    
    /**
     * The function crm_chatbot_send_message_from_parts sends a message from trigger
     * 
     * @param array $website contains the website's parameters
     * 
     * @return void
     */
    function crm_chatbot_send_message_from_parts($website) {

        // Assign the CodeIgniter super-object
        $CI =& get_instance();

        // Verify if the guest exists
        if ( $CI->input->get('guest', TRUE) ) {

            // Require the Website Functions Inc file
            require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

            // Get the guest
            $the_guest = $CI->base_model->the_data_where(
                'crm_chatbot_websites_guests',
                '*',
                array(
                    'user_id' => $website['user_id'],
                    'id' => $CI->input->get('guest', TRUE)
                )
            );

            // Set quest's ID
            $guest_id = !empty($the_guest)?$the_guest[0]['guest_id']:0;

            // Verify if the quest exists
            if ( !$the_guest ) {

                // Guest params
                $guest_params = array(
                    'user_id' => $website['user_id'],
                    'id' => $CI->input->get('guest', TRUE),
                    'created' => time()
                );

                // Save guest
                $the_guest = $CI->base_model->insert('crm_chatbot_websites_guests', $guest_params);

                // Verify if the quest was saved
                if ( $the_guest ) {

                    // Require the Automations Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php');

                    // Require the Automations Hooks Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations_hooks.php');

                    // Run hook when a guest is created
                    md_run_hook(
                        'crm_chatbot_new_guest',
                        array(
                            'guest_id' => $the_guest,
                            'website_id' => $website['website_id']
                        )
                    );

                    // Save the guest's IP
                    update_crm_chatbot_websites_guests_meta($the_guest, 'guest_ip', $CI->input->ip_address());

                    

                    // Verify if the timezone exists
                    if ( !empty($CI->input->get('timezone', TRUE)) ) {

                        // Verify if the timezone is valid
                        if ( in_array($CI->input->get('timezone', TRUE), timezone_identifiers_list()) ) {

                            // Save the guest's timezone
                            update_crm_chatbot_websites_guests_meta($the_guest, 'guest_timezone', $CI->input->get('timezone', TRUE));                    

                        }

                    }

                    // Set guest's ID
                    $guest_id = $the_guest;
                    
                }

            }
            
            // Verify if guest's ID exists
            if ( $guest_id ) { 

                // Verify if the guest has received the message
                if ( !$CI->base_model->the_data_where('crm_chatbot_websites_triggers_guests', '*', array('trigger_id' => $CI->input->get('trigger', TRUE), 'guest_id' => $guest_id) ) ) {

                    // Get the trigger's message
                    $the_message = $CI->base_model->the_data_where(
                        'crm_chatbot_websites_triggers',
                        'crm_chatbot_websites_triggers_meta.*, crm_chatbot_websites_triggers.website_id, crm_chatbot_websites_triggers.user_id',
                        array(
                            'crm_chatbot_websites_triggers.trigger_id' => $CI->input->get('trigger', TRUE),
                            'crm_chatbot_websites_triggers.website_id' => $CI->input->get('get_chat', TRUE)
                        ),
                        array(),
                        array(),
                        array(array(
                            'table' => 'crm_chatbot_websites_triggers_meta',
                            'condition' => "crm_chatbot_websites_triggers.trigger_id=crm_chatbot_websites_triggers_meta.trigger_id AND crm_chatbot_websites_triggers_meta.meta_parent='send_message'",
                            'join_from' => 'LEFT'
                        ))
                    );  
                    
                    // Verify if the message was found
                    if ( $the_message ) {

                        // Get the thread
                        $the_thread = $CI->base_model->the_data_where(
                            'crm_chatbot_websites_threads',
                            '*',
                            array(
                                'user_id' => $the_message[0]['user_id'],
                                'guest_id' => $guest_id,
                                'website_id' => $the_message[0]['website_id']
                            )
                        );

                        // Set thread's ID
                        $thread_id = !empty($the_thread)?$the_thread[0]['thread_id']:0;
                        
                        // Verify if the thread exists
                        if ( !$the_thread ) {

                            // Thread params
                            $thread_params = array(
                                'user_id' => $the_message[0]['user_id'],
                                'guest_id' => $guest_id,
                                'website_id' => $the_message[0]['website_id'],
                                'status' => 1,         
                                'created' => time(),
                                'updated' => time()
                            );

                            // Save thread
                            $the_thread = $CI->base_model->insert('crm_chatbot_websites_threads', $thread_params);

                            // Verify if the thread was saved
                            if ( $the_thread ) {

                                // Set thread's ID
                                $thread_id = $the_thread;
                                
                            }

                        }

                        // Verify if $thread_id is positive
                        if ( $thread_id ) {

                            // Save the response
                            $CI->base_model->insert('crm_chatbot_websites_triggers_guests', array('trigger_id' => $CI->input->get('trigger', TRUE), 'guest_id' => $guest_id, 'created' => time()));

                            // Verify if is a text message
                            if ( (int)$the_message[0]['meta_value'] === 0 ) {

                                // Prepare the response
                                $message_params = array(
                                    'user_id' => $the_message[0]['user_id'],
                                    'thread_id' => $thread_id,
                                    'bot' => 1,
                                    'message_body' => $the_message[0]['meta_extra'],
                                    'created' => time()
                                );

                                // Try to save the quick reply's response
                                $CI->base_model->insert('crm_chatbot_websites_messages', $message_params);

                            } else if ( (int)$the_message[0]['meta_value'] === 1 ) {

                                // Set params
                                $bot_params = array(
                                    'bot' => $the_message[0]['meta_extra']
                                );

                                // Get response
                                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_start($bot_params);

                                // Verify if the bot exists
                                if ( !empty($response['success']) ) {

                                    // Prepare the response
                                    $message_params = array(
                                        'user_id' => $the_message[0]['user_id'],
                                        'thread_id' => $thread_id,
                                        'bot' => 2,
                                        'message_body' => json_encode($response['element']),
                                        'created' => time()
                                    );

                                    // Try to save the quick reply's response
                                    $CI->base_model->insert('crm_chatbot_websites_messages', $message_params);                                

                                } else {

                                    // Disable the quick reply
                                    $CI->base_model->update('crm_chatbot_quick_replies', array('reply_id' => $best_replies[$key]['reply_id']), array('status' => 0));

                                }

                            }

                        }

                    }

                }

            }

        }
        
    }

}

if ( !function_exists('crm_chatbot_send_bot_from_parts') ) {
    
    /**
     * The function crm_chatbot_send_bot_from_parts sends a bot
     * 
     * @param array $params contains the bot's parameters
     * 
     * @return void
     */
    function crm_chatbot_send_bot_from_parts($params) {

        // Assign the CodeIgniter super-object
        $CI =& get_instance();

        // Require the Website Functions Inc file
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

        // Get the guest
        $the_guest = $CI->base_model->the_data_where(
            'crm_chatbot_websites_guests',
            '*',
            array(
                'user_id' => $params['user_id'],
                'id' => $params['guest']
            )
        );

        // Set quest's ID
        $guest_id = !empty($the_guest)?$the_guest[0]['guest_id']:0;

        // Verify if the quest exists
        if ( !$the_guest ) {

            // Guest params
            $guest_params = array(
                'user_id' => $params['user_id'],
                'id' => $params['guest'],
                'created' => time()
            );

            // Save guest
            $the_guest = $CI->base_model->insert('crm_chatbot_websites_guests', $guest_params);

            // Verify if the quest was saved
            if ( $the_guest ) {

                // Require the Automations Inc
                md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php');

                // Require the Automations Hooks Inc
                md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations_hooks.php');

                // Run hook when a guest is created
                md_run_hook(
                    'crm_chatbot_new_guest',
                    array(
                        'guest_id' => $the_guest,
                        'website_id' => $params['website_id']
                    )
                );

                // Save the guest's IP
                update_crm_chatbot_websites_guests_meta($the_guest, 'guest_ip', $CI->input->ip_address());

                // Verify if the timezone exists
                if ( !empty($params['timezone']) ) {

                    // Verify if the timezone is valid
                    if ( in_array($params['timezone'], timezone_identifiers_list()) ) {

                        // Save the guest's timezone
                        update_crm_chatbot_websites_guests_meta($the_guest, 'guest_timezone', $params['timezone']);                    

                    }

                }

                // Set guest's ID
                $guest_id = $the_guest;
                
            }

        }
        
        // Verify if guest's ID exists
        if ( $guest_id ) { 

            // Get the thread
            $the_thread = $CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                '*',
                array(
                    'user_id' => $params['user_id'],
                    'guest_id' => $guest_id,
                    'website_id' => $params['website_id']
                )
            );

            // Set thread's ID
            $thread_id = !empty($the_thread)?$the_thread[0]['thread_id']:0;
            
            // Verify if the thread exists
            if ( !$the_thread ) {

                // Thread params
                $thread_params = array(
                    'user_id' => $params['user_id'],
                    'guest_id' => $guest_id,
                    'website_id' => $params['website_id'],
                    'status' => 1,         
                    'created' => time(),
                    'updated' => time()
                );

                // Save thread
                $the_thread = $CI->base_model->insert('crm_chatbot_websites_threads', $thread_params);

                // Verify if the thread was saved
                if ( $the_thread ) {

                    // Set thread's ID
                    $thread_id = $the_thread;
                    
                }

            }

            // Verify if $thread_id is positive
            if ( $thread_id ) {

                // Set params
                $bot_params = array(
                    'bot' => $params['bot'],
                    'connector' => $params['connector']
                );

                // Get response
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_message($bot_params);

                // Verify if the bot exists
                if ( !empty($response['success']) ) {
                    
                    // Prepare the response
                    $message_params = array(
                        'user_id' => $params['user_id'],
                        'thread_id' => $thread_id,
                        'bot' => 2,
                        'message_body' => json_encode($response['element']),
                        'created' => time()
                    );

                    // Try to save the quick reply's response
                    $test = $CI->base_model->insert('crm_chatbot_websites_messages', $message_params);  
                             

                }

            }

        }
        
    }

}

/* End of file send.php */