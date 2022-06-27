<?php
/**
 * Threads Update Helper
 *
 * This file update contains the methods
 * to update threads
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Threads;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed'); 

/*
 * Update class extends the class Threads to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Update {
    
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
    // Ajax's methods for the Threads
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_bot_pause sets or removes the bot pause for a thread
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_bot_pause($params) {

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'thread_id' => $params['thread'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the thread exists
        if ( $the_thread ) {

            // Get team's member
            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                    );                  
                    
                }

            }

            // Verify if the automatic quick replies is enabled
            if ( empty($the_thread[0]['auto']) ) {

                // Set bot on pause
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('auto' => 1)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_set_bot_on_pause') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_quick_replies_set_on_pause')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_quick_replies_not_set_on_pause')
                    );

                }

            } else {

                // Enable automatic quick replies
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('auto' => 0)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_enabled_bot_for_thread') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_quick_replies_were_enabled')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_quick_replies_were_not_enabled')
                    );

                }

            }

        } else {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );

        }

    }

    /**
     * The public method crm_chatbot_block_thread blocks or unblocks a thread
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_block_thread($params) {

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'thread_id' => $params['thread'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the thread exists
        if ( $the_thread ) {

            // Get team's member
            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                    );                  
                    
                }

            }

            // Verify if the thread is blocked
            if ( $the_thread[0]['status'] < 2 ) {

                // Set bot on pause
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('status' => 2)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_blocked_the_thread') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_blocked')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_blocked')
                    );

                }

            } else {

                // Unblock the thread
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('status' => 1)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_unblocked_the_thread') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_unblocked')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_unblocked')
                    );

                }

            }

        } else {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );

        }

    }

    /**
     * The public method crm_chatbot_favorite_thread favorites or unfavorites a thread
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_favorite_thread($params) {

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'thread_id' => $params['thread'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the thread exists
        if ( $the_thread ) {

            // Get team's member
            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                    );                  
                    
                }

            }

            // Verify if the thread is favorited
            if ( $the_thread[0]['favorite'] < 1 ) {

                // Set thread as favorite
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('favorite' => 1)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_favorited_the_thread') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_saved_as_favorite')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_saved_as_favorite')
                    );

                }

            } else {

                // Unfavorite the thread
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('favorite' => 0)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_unfavorited_the_thread') . ' #' . $params['thread'] . '.'
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_unfavorited')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_unfavorited')
                    );

                }

            }

        } else {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );

        }

    }

    /**
     * The public method crm_chatbot_important_thread marks as important a thread
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_important_thread($params) {

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'thread_id' => $params['thread'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the thread exists
        if ( $the_thread ) {

            // Get team's member
            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Verify if the website is allowed
                if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                    );                  
                    
                }

            }

            // Verify if the thread is important
            if ( $the_thread[0]['important'] < 1 ) {

                // Set thread as important
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('important' => 1)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . str_replace('[important]', ' #' . $params['thread'], $this->CI->lang->line('crm_chatbot_has_important_the_thread'))
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_saved_as_important')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_saved_as_important')
                    );

                }

            } else {

                // Remove the thread
                if (  $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('important' => 0)) ) {

                    // Get team's member
                    $member = the_crm_current_team_member();

                    // Verify if member's name exists
                    if ( isset($member['member_name']) ) {

                        // Metas container
                        $metas = array(
                            array(
                                'meta_name' => 'activity_scope',
                                'meta_value' => $params['thread']
                            ),
                            array(
                                'meta_name' => 'title',
                                'meta_value' => $this->CI->lang->line('crm_chatbot_thread_update')
                            ),                                  
                            array(
                                'meta_name' => 'actions',
                                'meta_value' => $member['member_name'] . ' ' . str_replace('[important]', ' #' . $params['thread'], $this->CI->lang->line('crm_chatbot_has_removed_important_the_thread'))
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
                                'for_id' => $params['thread'], 
                                'metas' => $metas
                            )

                        );

                        // Delete the user's cache
                        delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_removed_as_important')
                    );
                    
                } else {
                    
                    // Prepare the false response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_removed_as_important')
                    );

                }

            }

        } else {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );

        }

    }

}

/* End of file update.php */