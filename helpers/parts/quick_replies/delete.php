<?php
/**
 * Quick_replies Delete Helper
 *
 * This file delete contains the methods
 * to delete the quick_replies
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Quick_replies;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Quick_replies to make it lighter
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
    // Ajax's methods for the Quick_replies
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_quick_reply deletes a quick reply by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_quick_reply($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_quick_replies') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the reply exists
        if ( isset($params['reply']) ) {

            // Get the reply
            $the_quick_reply = $this->CI->base_model->the_data_where(
                'crm_chatbot_quick_replies',
                '*',
                array(
                    'reply_id' => $params['reply'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the quick reply exists
            if ( $the_quick_reply ) {

                // Try to delete the quick reply
                if ( $this->delete_quick_reply($params['reply']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['reply']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_deletion')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_quick_reply') . ' ' . trim($the_quick_reply[0]['keywords']) . '.'
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
                                'for_id' => $params['reply'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Quick_replies
    //-----------------------------------------------------

    /**
     * The protected method delete_quick_reply deletes a quick reply
     * 
     * @param integer $reply_id contains the reply's identifier
     * 
     * @since 0.0.8.4
     * 
     * @return boolean true or false
     */
    protected function delete_quick_reply($reply_id) {

        // Delete the quick reply
        if ( $this->CI->base_model->delete('crm_chatbot_quick_replies', array('reply_id' => $reply_id) ) ) {

            // Delete the quick reply categories
            $this->CI->base_model->delete('crm_chatbot_quick_replies_categories', array('reply_id' => $reply_id) );

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_quick_replies_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            // Delete all quick reply's records
            md_run_hook(
                'crm_chatbot_delete_quick_reply',
                array(
                    'reply_id' => $reply_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */