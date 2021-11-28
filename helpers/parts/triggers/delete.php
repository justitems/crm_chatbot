<?php
/**
 * Triggers Delete Helper
 *
 * This file delete contains the methods
 * to delete the triggers
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
 * Delete class extends the class Triggers to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Delete {
    
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
     * The public method crm_chatbot_delete_trigger deletes a trigger by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_trigger($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the trigger exists
        if ( isset($params['trigger']) ) {

            // Get the trigger
            $the_trigger = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_triggers',
                '*',
                array(
                    'trigger_id' => $params['trigger'],
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the trigger exists
            if ( $the_trigger ) {

                // Try to delete the trigger
                if ( $this->delete_trigger($params['trigger']) ) {

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user($the_trigger[0]['website_id'], 'crm_chatbot_triggers_chat');

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['trigger']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_trigger_delete')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_delete_trigger') . ' ' . trim($the_trigger[0]['trigger_name']) . '.'
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
                                'user_id' => $this->CI->user_id,
                                'activity_type' => 'crm_chatbot',
                                'for_id' => $params['trigger'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Triggers
    //-----------------------------------------------------

    /**
     * The public method delete_trigger deletes a trigger
     * 
     * @param integer $trigger_id contains the trigger's identifier
     * 
     * @since 0.0.8.5
     * 
     * @return boolean true or false
     */
    public function delete_trigger($trigger_id) {

        // Delete the trigger
        if ( $this->CI->base_model->delete('crm_chatbot_websites_triggers', array('trigger_id' => $trigger_id) ) ) {

            // Delete the trigger's meta
            $this->CI->base_model->delete('crm_chatbot_websites_triggers_meta', array('trigger_id' => $trigger_id) );

            // Delete the trigger's guest
            $this->CI->base_model->delete('crm_chatbot_websites_triggers_guests', array('trigger_id' => $trigger_id) );    

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_triggers_list');
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

            // Delete all trigger's records
            md_run_hook(
                'crm_chatbot_delete_trigger',
                array(
                    'trigger_id' => $trigger_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */