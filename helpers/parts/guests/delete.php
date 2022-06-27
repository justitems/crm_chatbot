<?php
/**
 * Guests Delete Helper
 *
 * This file delete contains the methods
 * to delete the guests
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Guests;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Guests to make it lighter
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
    // Ajax's methods for the Guests
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_guest deletes a guest by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_guest($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_guests') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the guest exists
        if ( isset($params['guest']) ) {

            // Get the guest
            $the_guest = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_guests',
                '*',
                array(
                    'guest_id' => $params['guest'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the guest exists
            if ( $the_guest ) {

                // Try to delete the guest
                if ( $this->delete_guest($params['guest']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['guest']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_guest_deletion')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_number') . ' #' . trim($the_guest[0]['guest_id']) . '.'
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
                                'for_id' => $params['guest'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_guest_was_deleted')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_guest_was_not_deleted')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Guests
    //-----------------------------------------------------

    /**
     * The protected method delete_guest deletes a guest
     * 
     * @param integer $guest_id contains the guest's identifier
     * 
     * @since 0.0.8.5
     * 
     * @return boolean true or false
     */
    protected function delete_guest($guest_id) {

        // Delete the guest
        if ( $this->CI->base_model->delete('crm_chatbot_websites_guests', array('guest_id' => $guest_id) ) ) {

            // Delete guest's meta
            $this->CI->base_model->delete('crm_chatbot_websites_guests_meta', array('guest_id' => $guest_id) );

            // Delete guest's visited urls
            $this->CI->base_model->delete('crm_chatbot_websites_guests_visited_urls', array('guest_id' => $guest_id) );  

            // Delete guest's triggers
            $this->CI->base_model->delete('crm_chatbot_websites_triggers_guests', array('guest_id' => $guest_id) );  
            
            // Delete the guest's categories
            $this->CI->base_model->delete('crm_chatbot_websites_guests_categories', array('guest_id' => $guest_id));   
            
            // Get all guest's threads
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                '*',
                array(
                    'guest_id' => $guest_id
                )
            );

            // Verify if the threads exists
            if ( $the_threads ) {

                // List all threads
                foreach ( $the_threads as $the_thread ) {

                    // Delete the thread
                    $this->CI->base_model->delete('crm_chatbot_websites_threads', array('thread_id' => $the_thread['thread_id']) );

                    // Delete the thread's messages
                    $this->CI->base_model->delete('crm_chatbot_websites_messages', array('thread_id' => $the_thread['thread_id']) );

                }

            }

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_guests_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_guests_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_visited_urls_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_emails_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_numbers_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            // Delete all guest's records
            md_run_hook(
                'crm_chatbot_delete_guest',
                array(
                    'guest_id' => $guest_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */