<?php
/**
 * CRM Chatbot Visit Event
 *
 * This file contains the Visit event
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
 * Visit class manages the Visit event
 * 
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm/blob/master/LICENSE.md CRM License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */
class Visit implements CmsBaseUserAppsCollectionCrm_chatbotInterfaces\Events {
   
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

        // Set condition_url
        $condition_url = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_url', 'text_input'):FALSE;

        // Append condition
        $condition['fields'][] = array(
            'type' => 'text',
            'slug' => 'condition_url',
            'words' => array (
                'label' => $this->CI->lang->line('crm_chatbot_url'),
                'description' => $this->CI->lang->line('crm_chatbot_url_description'),
                'placeholder' => $this->CI->lang->line('crm_chatbot_enter_url')
            ),
            'params' => array(
                'value' => $condition_url?$condition_url:'',
                'required' => TRUE
            )            
        );

        // Set condition_operator
        $condition_operator = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_operator', 'select'):FALSE;

        // Append condition
        $condition['fields'][] = array(
            'type' => 'select',
            'slug' => 'condition_operator',
            'words' => array (
                'label' => $this->CI->lang->line('crm_chatbot_operator'),
                'description' => $this->CI->lang->line('crm_chatbot_operator_description'),
                'button_text' => $this->CI->lang->line('crm_chatbot_equal')
            ),
            'params' => array(
                'id' => $condition_operator?$condition_operator:0,
                'required' => TRUE
            ),
            'items' => array(
                
                array(
                    'id' => 0,
                    'text' => $this->CI->lang->line('crm_chatbot_equal')
                ),
                array(
                    'id' => 1,
                    'text' => $this->CI->lang->line('crm_chatbot_like')
                )
                
            )

        );

        // Actions container
        $actions = array(
            'fields' => array()
        );

        // Set time value
        $time_delay = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'time'):FALSE;

        // Set time unit
        $time_unit = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'unit'):FALSE;        

        // Append actions_delay_time
        $actions['fields'][] = array(
            'type' => 'time',
            'slug' => 'actions_delay_time',
            'words' => array (
                'label' => $this->CI->lang->line('crm_chatbot_run_actions_after'),
                'description' => $this->CI->lang->line('crm_chatbot_run_actions_after_description'),
                'placeholder' => $this->CI->lang->line('crm_chatbot_enter_numeric_delay_time'),
                'button_text' => $this->CI->lang->line('crm_chatbot_seconds'),
                'menu_placeholder' => $this->CI->lang->line('crm_chatbot_search_for_units')
            ),
            'params' => array(
                'value' => $time_delay?$time_delay:0,
                'id' => $time_unit?$time_unit:0,
                'required' => TRUE
            ),
            'items' => array(
                
                array(
                    'id' => 0,
                    'text' => $this->CI->lang->line('crm_chatbot_seconds')
                ),
                array(
                    'id' => 1,
                    'text' => $this->CI->lang->line('crm_chatbot_minutes')
                )
                
            )

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
                $the_bot = $this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE), 'user_id' => $this->CI->user_id) );

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
                    'text' => $this->CI->lang->line('crm_chatbot_visit_condition_instructions')
                )

            ),
            'actions' => array(

                'fields' => $actions['fields'],
                'instructions' => array(
                    'text' => $this->CI->lang->line('crm_chatbot_visit_actions_instructions')
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

            // Verify if field is condition_url
            if ( $condition_field['field'] === 'condition_url' ) {

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

            } else if ( $condition_field['field'] === 'condition_operator' ) {

                // Verify if type is correct
                if ( empty($condition_field['type']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_operator_field_wrong_type')
                    );                     

                } else if ( $condition_field['type'] !== 'select' ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_operator_field_wrong_type')
                    );                      

                } 
                
                // Verify if value is correct
                if ( !isset($condition_field['value']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_operator_field_wrong_value')
                    );

                }
                
                // Verify if value is correct
                if ( ($condition_field['value'] !== '0') && ($condition_field['value'] !== '1') ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_operator_field_wrong_value')
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

            // Verify if field is actions_delay_time
            if ( $actions_field['field'] === 'actions_delay_time' ) {

                // Verify if type is correct
                if ( empty($actions_field['type']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_field_wrong_type')
                    );                     

                } else if ( $actions_field['type'] !== 'time' ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_field_wrong_type')
                    );                      

                }

                // Verify if value exists
                if ( !isset($actions_field['value']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_field_wrong_value')
                    );

                }

                // Verify if value is correct
                if ( !is_numeric($actions_field['value']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_field_wrong_value')
                    );

                }  
                
                // Verify if time's unit exists
                if ( !isset($actions_field['unit']) ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_unit_field_wrong_value')
                    );

                }  
                
                // Verify if time's unit contains correct value
                if ( ($actions_field['unit'] !== '0') && ($actions_field['unit'] !== '1') ) {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_time_unit_field_wrong_value')
                    );

                }                 

            } else if ( $actions_field['field'] === 'send_message' ) {

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
                    if ( !$this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => $actions_field['id'], 'user_id' => $this->CI->user_id) ) ) {

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

            // Verify if field is condition_url
            if ( $condition_field['field'] === 'condition_url' ) {

                // Try to save the condition
                if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_url', 'text_input', trim($condition_field['value'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_url', 'text_input') !== trim($condition_field['value'])) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                    );

                }

            } else if ( $condition_field['field'] === 'condition_operator' ) {

                // Try to save the condition
                if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_operator', 'select', trim($condition_field['value'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_operator', 'select') !== trim($condition_field['value'])) ) {

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

            // Verify if field is actions_delay_time
            if ( $actions_field['field'] === 'actions_delay_time' ) {

                // Try to save the action
                if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'time', trim($actions_field['value'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'time') !== trim($actions_field['value'])) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                    );

                }

                // Try to save the action
                if ( !update_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'unit', trim($actions_field['unit'])) && (the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'unit') !== trim($actions_field['unit'])) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
                    );

                } 

            } else if ( $actions_field['field'] === 'send_message' ) {
                
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

        // Set condition_url
        $condition_url = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_url', 'text_input'):FALSE;

        // Set condition_operator
        $condition_operator = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'condition_operator', 'select'):FALSE;        

        // Verify if parameters exists
        if ( ($condition_url === FALSE) || ($condition_operator === FALSE) ) {
            return false;
        }

        // Append condition
        $condition['fields'][] = array(
            'value' => $condition_url,
            'action' => 'watch',
            'condition' => $condition_operator?$condition_operator:0
        );

        // Actions container
        $actions = array(
            'fields' => array()
        );

        // Set time value
        $time_delay = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'time'):FALSE;

        // Set time unit
        $time_unit = !empty($params['trigger'])?the_crm_chatbot_websites_triggers_meta($params['trigger'], 'actions_delay_time', 'unit'):FALSE;  
        
        // Verify if parameters exists
        if ( ($time_delay === FALSE) || ($time_unit === FALSE) ) {
            return false;
        }        

        // Append action
        $actions['fields'][] = array(
            'time' => $time_delay?$time_delay:0,
            'unit' => $time_unit?$time_unit:0,
            'action' => 'wait'
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
                $the_bot = $this->CI->base_model->the_data_where('crm_chatbot_bots', '*', array('bot_id' => the_crm_chatbot_websites_triggers_meta($params['trigger'], 'send_message', 'message', TRUE), 'user_id' => $this->CI->user_id) );

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
            'event_name' => $this->CI->lang->line('crm_chatbot_page_access'),
            'event_slug' => 'crm_chatbot_page_access'
        );
        
    }

}

/* End of file visit.php */