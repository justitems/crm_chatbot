<?php
/**
 * CRM Chatbot Seen Event
 *
 * This file contains the Seen event
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Events;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Interfaces as CmsBaseUserAppsCollectionCrm_chatbotInterfaces;

/*
 * Seen class manages the Seen event
 * 
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm/blob/master/LICENSE.md CRM License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */
class Seen implements CmsBaseUserAppsCollectionCrm_chatbotInterfaces\Events {
   
    /**
     * Class variables
     *
     * @since 0.0.8.5
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.5
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }

    /**
     * The public method the_fields provides the fields for configuration
     * 
     * @param array $params contains the data
     * 
     * @since 0.0.8.5
     * 
     * @return array with the fields
     */
    public function the_fields($params = array()) {

        // Condition container
        $condition = array(
            'fields' => array()
        );

        // Set seen_element_path
        $seen_element_path = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'seen_element_path', 'text_input'):FALSE;

        // Append condition
        $condition['fields'][] = array(
            'type' => 'text',
            'slug' => 'seen_element_path',
            'words' => array (
                'label' => $this->CI->lang->line('crm_chatbot_element'),
                'description' => $this->CI->lang->line('crm_chatbot_element_description'),
                'placeholder' => $this->CI->lang->line('crm_chatbot_enter_element')
            ),
            'params' => array(
                'value' => $seen_element_path?$seen_element_path:'',
                'required' => TRUE
            )            
        );

        // Actions container
        $actions = array(
            'fields' => array()
        );

        // Message params
        $message_params = array(
            'required' => TRUE,
            'type' => 0
        );

        // Verify if trigger exists
        if ( !empty($params['trigger']) ) {

            // Verify if the chat message a bot 
            if ( the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message') ) {

                // Get the bot
                $the_bot = $this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE), 'user_id' => md_the_user_id()) );

                // Verify if the bot exists
                if ( $the_bot ) {

                    // Set type
                    $message_params['type'] = the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message');

                    // Set bot
                    $message_params['bot'] = array(
                        'bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE),
                        'bot_name' => $the_bot[0]['bot_name']
                    );

                }

            } else {

                // Set value
                $message_params['value'] = the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE);

            }

        }

        // Append send_message
        $actions['fields'][] = array(
            'type' => 'message',
            'slug' => 'send_message',
            'words' => array (
                'label' => $this->CI->lang->line('crm_chatbot_chat_message'),
                'description' => $this->CI->lang->line('crm_chatbot_chat_message_description')
            ),
            'params' => $message_params
        );        
        
        return array(

            'condition' => array(

                'fields' => $condition['fields'],
                'instructions' => array(
                    'text' => $this->CI->lang->line('crm_chatbot_seen_condition_instructions')
                )

            ),
            'actions' => array(

                'fields' => $actions['fields'],
                'instructions' => array(
                    'text' => $this->CI->lang->line('crm_chatbot_seen_actions_instructions')
                )

            )            
        );
        
    }

    /**
     * The public method save_data saves the data for configuration
     * 
     * @param array $params contains the data
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function save_data($params) {

        // Verify if condition exists
        if ( empty($params['trigger_fields']['condition']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_events_fields_wrong_data')
            ); 

        }

        // Verify if actions exists
        if ( empty($params['trigger_fields']['actions']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_events_fields_wrong_data')
            ); 

        }  
        
        // Verify if condition is array
        if ( !is_array($params['trigger_fields']['condition']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_events_fields_wrong_data')
            ); 

        }

        // Verify if condition is array
        if ( !is_array($params['trigger_fields']['actions']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_events_fields_wrong_data')
            ); 

        }

        // List all condition's fields
        foreach ( $params['trigger_fields']['condition'] AS $condition_field ) {

            // Verify if field parameter exists
            if ( empty($condition_field['field']) ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_field_was_not_found')
                );                     

            }            

            // Verify if field is seen_element_path
            if ( $condition_field['field'] === 'seen_element_path' ) {

                // Verify if type is correct
                if ( empty($condition_field['type']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_url_field_wrong_type')
                    );                     

                } else if ( $condition_field['type'] !== 'text_input' ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_url_field_wrong_type')
                    );                      

                }

                // Verify if value exists
                if ( !isset($condition_field['value']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_url_field_wrong_value')
                    );

                }

            }

        }

        // List all actions fields
        foreach ( $params['trigger_fields']['actions'] AS $actions_field ) {

            // Verify if field parameter exists
            if ( empty($actions_field['field']) ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_field_was_not_found')
                );                     

            } 
            
            // Execute data by field
            if ( $actions_field['field'] === 'send_message' ) {

                // Verify if type is correct
                if ( empty($actions_field['type']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_send_message_field_wrong_type')
                    );                     

                } else if ( $actions_field['type'] !== 'message' ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_send_message_field_wrong_type')
                    );                      

                }
                
                // Verify if message_type is correct
                if ( ($actions_field['message_type'] !== '0') && ($actions_field['message_type'] !== '1') ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_message_type_field_wrong_value')
                    );

                } 
                
                // Verify if chat message has a bot
                if ( $actions_field['message_type'] === '1' ) {

                    // Verify if id exists
                    if ( empty($actions_field['id']) ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_please_select_bot')
                        );                        

                    }

                    // Verify if id is numeric
                    if ( !is_numeric($actions_field['id']) ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_please_select_bot')
                        );                        

                    } 
                    
                    // Verify if the bot exists
                    if ( !$this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => $actions_field['id'], 'user_id' => md_the_user_id()) ) ) {

                        // Prepare error response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_the_selected_bot_was_not_found') . '1'
                        );
                        
                    }

                } else {

                    // Verify if value exists
                    if ( empty($actions_field['value']) ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
                        );                        

                    } else if ( strlen(trim(strip_tags($actions_field['value']))) < 2 ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
                        );                        

                    }                  

                }

            }

        }

        // List all condition's fields
        foreach ( $params['trigger_fields']['condition'] AS $condition_field ) {         

            // Verify if field is seen_element_path
            if ( $condition_field['field'] === 'seen_element_path' ) {

                // Try to save the condition
                if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'seen_element_path', 'text_input', trim($condition_field['value'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'seen_element_path', 'text_input') !== trim($condition_field['value'])) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                    );

                }

            }

        }

        // List all actions fields
        foreach ( $params['trigger_fields']['actions'] AS $actions_field ) {

            // Execute data by field
            if ( $actions_field['field'] === 'send_message' ) {
                
                // Verify if chat message has a bot
                if ( $actions_field['message_type'] === '1' ) {

                    // Try to save the action
                    if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', trim($actions_field['message_type']), trim($actions_field['id'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE) !== trim($actions_field['id'])) ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                        );

                    }

                } else {

                    // Try to save the action
                    if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', trim($actions_field['message_type']), trim($actions_field['value'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE) !== trim($actions_field['value'])) ) {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                        );

                    }
                    
                }

            }

        }

        // Prepare the true response
        return array(
            'success' => TRUE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_updated')
        );         
        
    }

    /**
     * The public method the_data returns the data for the chat
     * 
     * @since 0.0.8.5
     * 
     * @return array with configured data
     */
    public function the_data($params) {
        
        // Condition container
        $condition = array(
            'fields' => array()
        );

        // Set seen_element_path
        $seen_element_path = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'seen_element_path', 'text_input'):FALSE;

        if ( !$seen_element_path ) {
            return false;
        }

        // Append condition
        $condition['fields'][] = array(
            'value' => $seen_element_path,
            'action' => 'seen'
        );

        // Actions container
        $actions = array(
            'fields' => array()
        );

        // Message params
        $message_params = array(
            'type' => 0
        );

        // Verify if trigger exists
        if ( !empty($params['trigger']) ) {

            // Verify if the chat message a bot 
            if ( the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message') ) {

                // Get the bot
                $the_bot = $this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE), 'user_id' => md_the_user_id()) );

                // Verify if the bot exists
                if ( $the_bot ) {

                    // Set type
                    $message_params['type'] = the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message');

                    // Set bot
                    $message_params['bot'] = array(
                        'bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE),
                        'bot_name' => $the_bot[0]['bot_name']
                    );

                }

            } else {

                // Set value
                $message_params['value'] = the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE);

            }

        }

        // Append send_message
        $actions['fields'][] = array(
            'action' => 'new_message',
            'parent' => 'send_message',
            'name' => 'message',
            'trigger' => $params['trigger']
        );        
        
        return array(

            'trigger' => $params['trigger'],

            'condition' => array(

                'fields' => $condition['fields']

            ),

            'actions' => array(

                'fields' => $actions['fields']

            )   
                     
        );
        
    }
    
    /**
     * The public method the_event_info provides the event's info
     * 
     * @since 0.0.8.5
     * 
     * @return array with event's info
     */
    public function the_event_info() {
        
        // Return event's information
        return array(
            'event_name' => $this->CI->lang->line('crm_chatbot_element_seen'),
            'event_slug' => 'crm_chatbot_element_seen'
        );
        
    }

}

/* End of file seen.php */