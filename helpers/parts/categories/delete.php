<?php
/**
 * Categories Delete Helper
 *
 * This file delete contains the methods
 * to delete the categories
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Categories;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Delete class extends the class Categories to make it lighter
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
    // Ajax's methods for the Categories
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_delete_category deletes a category
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_category($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_delete_categories') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the category's exists
        if ( empty($params['category']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            );        
            
        }

        // Get the category
        $the_category = $this->CI->base_model->the_data_where('crm_chatbot_categories', '*', array('category_id' => $params['category'], 'user_id' => $this->CI->user_id ) );
        
        // Try to delete the category
        if ( $this->CI->base_model->delete('crm_chatbot_categories', array('category_id' => $params['category'], 'user_id' => $this->CI->user_id)) ) {

            // Delete the category's replies
            $this->CI->base_model->delete('crm_chatbot_quick_replies_categories', array('category_id' => $params['category']));

            // Delete the category's bots
            $this->CI->base_model->delete('crm_chatbot_bots_categories', array('category_id' => $params['category']));

            // Delete the category's guests
            $this->CI->base_model->delete('crm_chatbot_websites_guests_categories', array('category_id' => $params['category']));            

            // Delete all category's records
            md_run_hook(
                'crm_chatbot_bots_delete_category',
                array(
                    'category_id' => $params['category']
                )
            );

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_categories_list');

            // Get member
            $member = the_crm_current_team_member();

            // Verify if member's name exists
            if ( isset($member['member_name']) ) {

                // Metas container
                $metas = array(
                    array(
                        'meta_name' => 'activity_scope',
                        'meta_value' => $params['category']
                    ),
                    array(
                        'meta_name' => 'title',
                        'meta_value' => $this->CI->lang->line('crm_chatbot_category_deletion')
                    ),                                  
                    array(
                        'meta_name' => 'actions',
                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_deleted_the_category') . ' ' . trim($the_category[0]['category_name']) . '.'
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
                        'for_id' => $params['category'], 
                        'metas' => $metas
                    )

                );

                // Delete the user's cache
                delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

            }

            // Prepare the success response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_category_was_deleted')
            );

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_category_was_not_deleted')
            );

        }

    }

}

/* End of file delete.php */