<?php
/**
 * Emails Delete Helper
 *
 * This file delete contains the methods
 * to delete the emails
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Emails;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Emails to make it lighter
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
    // Ajax's methods for the Emails
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_email deletes a quick email by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_email($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_emails') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the email exists
        if ( isset($params['email']) ) {

            // Get the email
            $the_email = $this->CI->base_model->the_data_where(
                'crm_chatbot_emails',
                '*',
                array(
                    'email_id' => $params['email'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the quick email exists
            if ( $the_email ) {

                // Try to delete the quick email
                if ( $this->delete_email($params['email']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['email']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_email_deletion')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_email') . ' ' . trim($the_email[0]['email']) . '.'
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
                                'for_id' => $params['email'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_email_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_email_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Emails
    //-----------------------------------------------------

    /**
     * The protected method delete_email deletes a quick email
     * 
     * @param integer $email_id contains the email's identifier
     * 
     * @since 0.0.8.5
     * 
     * @return boolean true or false
     */
    protected function delete_email($email_id) {

        // Delete the quick email
        if ( $this->CI->base_model->delete('crm_chatbot_emails', array('email_id' => $email_id) ) ) {

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_emails_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            // Delete all quick email's records
            md_run_hook(
                'crm_chatbot_delete_email',
                array(
                    'email_id' => $email_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */