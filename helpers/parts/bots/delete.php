<?php
/**
 * Bots Delete Helper
 *
 * This file delete contains the methods
 * to delete the bots
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bots;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Bots to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Delete {
    
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
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Bots
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_bot deletes a bot by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_bot($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_bots') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the bot exists
        if ( isset($params['bot']) ) {

            // Get the bot
            $the_bot = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots',
                '*',
                array(
                    'bot_id' => $params['bot'],
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the bot exists
            if ( $the_bot ) {

                // Try to delete an bot
                if ( $this->delete_bot($params['bot']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['bot']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_bot_deletion')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_bot') . ' ' . trim($the_bot[0]['bot_name']) . '.'
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
                                'for_id' => $params['bot'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Bots
    //-----------------------------------------------------

    /**
     * The protected method delete_bot deletes an bot
     * 
     * @param integer $bot_id contains the bot's identifier
     * 
     * @since 0.0.8.4
     * 
     * @return boolean true or false
     */
    protected function delete_bot($bot_id) {

        // Delete the bot
        if ( $this->CI->base_model->delete('crm_chatbot_bots', array('bot_id' => $bot_id) ) ) {

            // Get bot's elements
            $the_elements = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots_elements',
                '*',
                array(
                    'bot_id' => $bot_id
                )
            );

            // Verify if the bot has elements
            if ( $the_elements ) {

                // List all bot's elements
                foreach ( $the_elements as $the_element ) {

                    // Delete the bot's elements
                    $this->CI->base_model->delete('crm_chatbot_bots_elements', array('element_id' => $the_element['element_id']));

                    // Delete the bot's elements property
                    $this->CI->base_model->delete('crm_chatbot_bots_elements_properties', array('element_id' => $the_element['element_id']));                       

                }

            }

            // Delete the bot's links
            $this->CI->base_model->delete('crm_chatbot_bots_elements_links', array('bot_id' => $bot_id) );

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list');
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

            // Delete all bot's records
            md_run_hook(
                'crm_chatbot_bots_delete_bot',
                array(
                    'bot_id' => $bot_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */