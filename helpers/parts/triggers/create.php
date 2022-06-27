<?php
/**
 * Triggers Create Helper
 *
 * This file create contains the methods
 * to create triggers
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

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Triggers as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers;

/*
 * Create class extends the class Triggers to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Create {
    
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
     * The public method crm_chatbot_create_trigger creates a trigger
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_create_trigger($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
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
        if ( !$this->CI->base_model->the_data_where('crm_chatbot_websites', '*', array('website_id' => $params['website'], 'user_id' => md_the_user_id()) ) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );
            
        }

        // Verify if the trigger's name parameter exists
        if ( empty($params['trigger_name']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_trigger_name_short')
            );    
            
        }

        // Verify if the trigger's name has at least 4 characters
        if ( strlen(trim($params['trigger_name'])) < 4 ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_trigger_name_short')
            );   
            
        } 

        // Verify if the event's slug parameter exists
        if ( empty($params['event_slug']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_select_an_event')
            );    
            
        }  

        // Verify if the trigger_fields parameter exists
        if ( empty($params['trigger_fields']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_events_fields_missing')
            );    
            
        }  
        
        // Event class name
        $event_class_name = '';

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
                if ( $info['event_slug'] === $params['event_slug'] ) {
                    $event_class_name = $class_name;
                }
                
            }

        }

        // Verify if class name exists
        if ( !$event_class_name ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_select_an_event')
            );   

        }

        // Get team's member
        $member = the_crm_current_team_member();

        // Trigger container
        $trigger_params = array(
            'user_id' => md_the_user_id(),
            'website_id' => $params['website'],
            'trigger_name' => $params['trigger_name'],
            'event_slug' => $params['event_slug'],
            'created' => time()
        );

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's id exists
            if ( isset($member['member_id']) ) {

                // Set member's ID
                $trigger_params['member_id'] = $member['member_id'];

            }

        }

        // Save the trigger
        $trigger_id = $this->CI->base_model->insert('crm_chatbot_websites_triggers', $trigger_params);

        // Verify if the trigger was created
        if ( $trigger_id ) {

            // Verify if member's name exists
            if ( isset($member['member_name']) ) {

                // Metas container
                $metas = array(
                    array(
                        'meta_name' => 'activity_scope',
                        'meta_value' => $trigger_id
                    ),
                    array(
                        'meta_name' => 'title',
                        'meta_value' => $this->CI->lang->line('crm_chatbot_trigger_creation')
                    ),                                  
                    array(
                        'meta_name' => 'actions',
                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_created_trigger') . ' ' . trim($params['trigger_name']) . '.'
                    )
                    
                );

                // Verify if member exists
                if ( $this->CI->session->userdata( 'member' ) ) {

                    // Set team's member
                    $metas[] = array(
                        'meta_name' => 'team_member',
                        'meta_value' => $member['member_id']
                    );

                }

                // Create the activity
                create_crm_activity(
                    array(
                        'user_id' => md_the_user_id(),
                        'activity_type' => 'crm_chatbot',
                        'for_id' => $trigger_id, 
                        'metas' => $metas
                    )

                );

                // Delete the user's cache
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            }

            // Set trigger
            $params['trigger'] = $trigger_id;

            // Create an array
            $array = array(
                'CmsBase',
                'User',
                'Apps',
                'Collection',
                'Crm_chatbot',
                'Events',
                ucfirst($event_class_name)
            );

            // Implode the array above
            $cl = implode('\\', $array);

            // Save trigger's data
            $response = (new $cl())->save_data($params);

            // Verify if the response contains error
            if ( empty($response['success']) ) {

                // Delete the trigger
                (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Delete)->delete_trigger($trigger_id);

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $response['message']
                );                 

            }

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_triggers_list');
            delete_crm_cache_cronology_for_user($params['website'], 'crm_chatbot_triggers_chat');

            // Prepare the success response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_created')
            );

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_created')
            ); 

        }

    }

}

/* End of file create.php */