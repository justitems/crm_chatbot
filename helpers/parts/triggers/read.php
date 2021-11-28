<?php
/**
 * Triggers Read Helper
 *
 * This file read contains the methods
 * to read the triggers
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Triggers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Triggers to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Read {
    
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
    // Ajax's methods for the Triggers
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_triggers gets the quick replies list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_triggers($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_triggers_found')
            );         

        }

        // Verify if the website's id parameter exists
        if ( empty($params['website']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );    
            
        } else if ( !is_numeric($params['website']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );

        }
        
        // Get the website
        if ( !$this->CI->base_model->the_data_where('crm_chatbot_websites', '*', array('website_id' => $params['website'], 'user_id' => $this->CI->user_id) ) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );
            
        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $params['website']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_triggers_found')
                    );   
                    
                }

            }

        }

        // Set where
        $where = array(
            'user_id' => $this->CI->user_id,
            'website_id' => $params['website']
        );

        // Set where in
        $where_in = array();

        // Set like
        $like = array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order' =>  array('trigger_id', 'DESC'),
        );

        // Verify if start exists
        if ( isset($params['page']) ) {

            // Set the start
            $limit['start'] = $params['page'] * 10;

            // Set the limit
            $limit['limit'] = $params['limit'];

        }

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_triggers_' . $parameters_string) ) {

            // Set the cache
            $the_triggers = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_triggers_' . $parameters_string);

        } else {

            // Get the triggers
            $the_triggers = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_triggers',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_triggers_' . $parameters_string, $the_triggers);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_triggers_list', 'crm_chatbot_user_' . $this->CI->user_id . '_triggers_' . $parameters_string);

        }

        // Verify if the triggers exists
        if ( $the_triggers ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_triggers_' . $parameters_string) ) {

                // Get total triggers
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_triggers_' . $parameters_string);

            } else {

                // Get the total triggers
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_triggers',
                    'COUNT(trigger_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_triggers_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_triggers_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_triggers_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'triggers' => $the_triggers,
                'total' => $the_total[0]['total']
            );

        }

        // Prepare the false response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_triggers_found')
        );            

    }

    /**
     * The public method crm_chatbot_the_trigger gets the trigger data
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_the_trigger($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the trigger's id parameter exists
        if ( empty($params['trigger']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
            );    
            
        } else if ( !is_numeric($params['trigger']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
            );

        }
        
        // Get the trigger's data
        $the_trigger = $this->CI->base_model->the_data_where('crm_chatbot_websites_triggers', '*', array('trigger_id' => $params['trigger'], 'user_id' => $this->CI->user_id) );

        // Verify if the trigger exists
        if ( !$the_trigger ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
            );
            
        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_trigger[0]['website_id']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
                    );   
                    
                }

            }

        }

        // Trigger container
        $trigger_data = array();

        // Verify if events exists
        if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') ) {

            // List all events
            foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') as $filename) {
                
                // Set class name
                $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/', '.php'), '', $filename);

                // Create an array
                $array = array(
                    'CmsBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Crm_chatbot',
                    'Events',
                    ucfirst($class_name)
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Get info
                $info = (new $cl())->the_event_info();

                // Verify if is the expected slug
                if ( $info['event_slug'] === $the_trigger[0]['event_slug'] ) {

                    // Set trigger's data
                    $trigger_data = array(
                        'event_info' => $info,
                        'event_fields' => (new $cl())->the_fields(array(
                            'trigger' => $the_trigger[0]['trigger_id'],
                            'website' => $the_trigger[0]['website_id']
                        ))
                    );
                    
                }
                
            }

        }

        // Verify if the trigger was found
        if ( !$trigger_data ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
            );   

        }

        // Prepare success response
        return array(
            'success' => TRUE,
            'trigger_id' => $params['trigger'],
            'trigger_name' => $the_trigger[0]['trigger_name'],
            'trigger' => $trigger_data
        );          

    }

    //-----------------------------------------------------
    // Quick Helpers for the Triggers
    //-----------------------------------------------------

    /**
     * The protected method generate_string generates a string for cache file
     * 
     * @param array $args contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return string
     */
    protected function generate_string($args) {

        // Create and return string
        return str_replace(' ', '_', implode('_', $args) );
        
    }

}

/* End of file read.php */