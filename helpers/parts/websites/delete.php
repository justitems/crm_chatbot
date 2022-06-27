<?php
/**
 * Websites Delete Helper
 *
 * This file delete contains the methods
 * to delete the websites
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Websites;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Websites to make it lighter
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
    // Ajax's methods for the Websites
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_website deletes a website by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_website($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_websites') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Verify if the website exists
        if ( isset($params['website']) ) {

            // Get the website
            $the_website = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $params['website'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Try to delete an website
                if ( $this->delete_website($params['website']) ) {

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['website']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_website_delete')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_website') . ' ' . trim($the_website[0]['domain']) . '.'
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
                                'for_id' => $params['website'], 
                                'metas' => $metas
                            )

                        );

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_website_was_deleted_successfully')
                    );

                }

            }

        }

        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_website_was_not_deleted_successfully')
        );

    }

    //-----------------------------------------------------
    // Quick Helpers for the Websites
    //-----------------------------------------------------

    /**
     * The protected method delete_website deletes an website
     * 
     * @param integer $website_id contains the website's identifier
     * 
     * @since 0.0.8.4
     * 
     * @return boolean true or false
     */
    protected function delete_website($website_id) {

        // Delete the website
        if ( $this->CI->base_model->delete('crm_chatbot_websites', array('website_id' => $website_id) ) ) {

            // Delete the meta
            $this->CI->base_model->delete('crm_chatbot_websites_meta', array('website_id' => $website_id) );

            // Delete the categories
            $this->CI->base_model->delete('crm_chatbot_websites_categories', array('website_id' => $website_id) );          
            
            // Get all threads for the deleted website
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                '*',
                array(
                    'website_id' => $website_id,
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the treads exists
            if ( $the_threads ) {

                // List all threads
                foreach ( $the_threads as $the_thread ) {

                    // Delete the phone numbers
                    $this->CI->base_model->delete('crm_chatbot_numbers', array('thread_id' => $the_thread['thread_id']) );

                    // Delete the emails
                    $this->CI->base_model->delete('crm_chatbot_emails', array('thread_id' => $the_thread['thread_id']) );                    

                    // Get all guest threads
                    $the_guest_threads = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_threads',
                        '*',
                        array(
                            'guest_id' => $the_thread['guest_id']
                        )
                    );   
                    
                    // Verify if there is more than 1 thread
                    if ( count($the_guest_threads) < 2 ) {

                        // Delete the guest
                        $this->CI->base_model->delete('crm_chatbot_websites_guests', array('guest_id' => $the_thread['guest_id']) );    
                        
                        // Delete the guest's meta
                        $this->CI->base_model->delete('crm_chatbot_websites_guests_meta', array('guest_id' => $the_thread['guest_id']) );
                        
                        // Delete the guest's visited urls
                        $this->CI->base_model->delete('crm_chatbot_websites_guests_visited_urls', array('guest_id' => $the_thread['guest_id']) );                        

                    }

                    // Get all messages
                    $the_messages = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites_messages',
                        '*',
                        array(
                            'thread_id' => $the_thread['thread_id']
                        )
                    );
                    
                    // Delete the thread
                    $this->CI->base_model->delete('crm_chatbot_websites_threads', array('thread_id' => $the_thread['thread_id']) );                      
                    
                    // Verify if messages exists
                    if ( $the_messages ) {

                        // List all messages
                        foreach ( $the_messages as $the_message ) {

                            // Delete the message
                            $this->CI->base_model->delete('crm_chatbot_websites_messages', array('message_id' => $the_message['message_id']) );    
                            
                            // Delete the message's attachments
                            $this->CI->base_model->delete('crm_chatbot_websites_messages_attachments', array('message_id' => $the_message['message_id']) );                            

                        }

                    }

                }

            }

            // Get all website's triggers
            $the_triggers = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_triggers',
                '*',
                array(
                    'website_id' => $website_id
                )
            );                 
            
            // Verify if website's triggers exists
            if ( $the_triggers ) {

                // List all website's triggers
                foreach ( $the_triggers as $the_trigger ) {

                    // Delete the trigger
                    $this->CI->base_model->delete('crm_chatbot_websites_triggers', array('trigger_id' => $the_trigger['trigger_id']) );
                    
                    // Delete the trigger's meta
                    $this->CI->base_model->delete('crm_chatbot_websites_triggers_meta', array('trigger_id' => $the_trigger['trigger_id']) );    
                    
                    // Delete the trigger's guest
                    $this->CI->base_model->delete('crm_chatbot_websites_triggers_guests', array('trigger_id' => $the_trigger['trigger_id']) );                       

                }

            }

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_threads_teams_members');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_guests_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_emails_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_numbers_list');
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

            // Delete all website's records
            md_run_hook(
                'crm_chatbot_delete_website',
                array(
                    'website_id' => $website_id
                )
            );

            return true;

        } else {

            return false;

        }
        
    }

}

/* End of file delete.php */