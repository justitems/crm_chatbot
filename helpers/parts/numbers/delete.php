<?php
/**
 * Numbers Delete Helper
 *
 * This file delete contains the methods
 * to delete the numbers
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Numbers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Numbers to make it lighter
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
    // Ajax's methods for the Numbers
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_number deletes a quick number by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_number($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_numbers') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the number exists
        if ( isset($params['number']) ) {

            // Get the number
            $the_number = $this->CI->base_model->the_data_where(
                'crm_chatbot_numbers',
                '*',
                array(
                    'number_id' => $params['number'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the quick number exists
            if ( $the_number ) {

                // Try to delete the quick number
                if ( $this->delete_number($params['number']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['number']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_number_deletion')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_number') . ' ' . trim($the_number[0]['number']) . '.'
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
                                'for_id' => $params['number'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_number_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_number_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Numbers
    //-----------------------------------------------------

    /**
     * The protected method delete_number deletes a quick number
     * 
     * @param integer $number_id contains the number's identifier
     * 
     * @since 0.0.8.5
     * 
     * @return boolean true or false
     */
    protected function delete_number($number_id) {

        // Delete the quick number
        if ( $this->CI->base_model->delete('crm_chatbot_numbers', array('number_id' => $number_id) ) ) {

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_numbers_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            // Delete all quick number's records
            md_run_hook(
                'crm_chatbot_delete_number',
                array(
                    'number_id' => $number_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */