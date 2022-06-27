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

        // Verify if urls exists
        if ( !empty($params['visited_urls']) ) {

            // If is array
            if ( is_array($params['visited_urls']) ) {

                // Get already saved urls
                $saved_urls = $CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests_visited_urls',
                    '*',
                    array(
                        'guest_id' => $guest_id
                    )
                );

                // Prepare the saved urls
                $the_saved_urls = !empty($saved_urls)?array_column($saved_urls, 'url'):array();

                // New saved urls
                $new_saved_url = array();

                // List urls
                foreach ( $params['visited_urls'] as $url ) {

                    // Verify if the url has correct parameters
                    if ( !empty($url['title']) && !empty($url['url']) ) {

                        // Verify if the url is valid and is not saved already
                        if ( (filter_var($url['url'], FILTER_VALIDATE_URL) !== FALSE) && !in_array(trim($url['url']), $the_saved_urls) && !in_array(trim($url['url']), $new_saved_url) ) {

                            // Prepare url
                            $url_params = array(
                                'user_id' => $params['user_id'],
                                'guest_id' => $guest_id,
                                'website_id' => $params['website_id'],
                                'title' => $url['title'],
                                'url' => trim($url['url'])
                            );

                            // Verify if url was saved
                            if ( $CI->base_model->insert('crm_chatbot_websites_guests_visited_urls', $url_params) ) {
                                $new_saved_url[] = trim($url['url']);
                            }

                        }

                    }

                }

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

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($params['user_id'], 'crm_chatbot_websites_threads_list');
            delete_crm_cache_cronology_for_user($params['user_id'], 'crm_chatbot_numbers_list');
            delete_crm_cache_cronology_for_user($params['user_id'], 'crm_chatbot_emails_list');
            delete_crm_cache_cronology_for_user($params['user_id'], 'crm_chatbot_websites_guests_list');
            delete_crm_cache_cronology_for_user($params['user_id'], 'crm_chatbot_websites_visited_urls_list');   
            
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

            // Verify if the guest don't has saved the latitude
            if ( !the_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude') ) {

                // Verify if the ip2location is enabled
                if ( md_the_option('app_crm_chatbot_ip2location_enabled') ) {

                    // Verify if api key exists
                    if ( md_the_option('app_crm_chatbot_ip2location_api_key') ) {

                        // Get guest information
                        $the_guest_info = json_decode(file_get_contents('https://api.ip2location.com/v2/?ip=' . $CI->input->ip_address() . '&key=' . md_the_option('app_crm_chatbot_ip2location_api_key') . '&package=WS25'), TRUE);

                        // Verify if response key exists
                        if ( !empty($the_guest_info['response']) ) {

                            // Verify if response value is OK
                            if ( $the_guest_info['response'] === 'OK' ) {

                                // Verify if latitude exists
                                if ( !empty($the_guest_info['latitude']) ) {

                                    // Save the latitude
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude', $the_guest_info['latitude']); 

                                }

                                // Verify if longitude exists
                                if ( !empty($the_guest_info['longitude']) ) {

                                    // Save the longitude
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_longitude', $the_guest_info['longitude']); 

                                }
                                
                                // Verify if country code exists
                                if ( !empty($the_guest_info['country_code']) ) {

                                    // Save the country code
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_code', $the_guest_info['country_code']); 

                                }
                                
                                // Verify if country name exists
                                if ( !empty($the_guest_info['country_name']) ) {

                                    // Save the country name
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_name', $the_guest_info['country_name']); 

                                }
                                
                                // Verify if city name exists
                                if ( !empty($the_guest_info['city_name']) ) {

                                    // Save the city name
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_city_name', $the_guest_info['city_name']); 

                                }                             

                            }

                        }

                    }

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