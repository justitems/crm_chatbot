<?php
/**
 * Categories Create Helper
 *
 * This file create contains the methods
 * to create categories
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
 * Create class extends the class Categories to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Create {
    
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
     * The public method crm_chatbot_create_category creates a new category
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_create_category($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_create_categories') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Verify if the category's name parameter exists
        if ( empty($params['category_name']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_category_name_short')
            );    
            
        }

        // Verify if the category's name has at least 4 characters
        if ( strlen(trim($params['category_name'])) < 4 ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_category_name_short')
            );   
            
        } 

        // Get member
        $member = the_crm_current_team_member();

        // Prepare the category
        $category_params = array(
            'user_id' => $this->CI->user_id,
            'category_name' => trim($params['category_name']),
            'created' => time()
        );

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's id exists
            if ( isset($member['member_id']) ) {

                // Set member's ID
                $category_params['member_id'] = $member['member_id'];

            }

        }

        // Save the category
        $category_id = $this->CI->base_model->insert('crm_chatbot_categories', $category_params);

        // Verify if the bot was created
        if ( $category_id ) {

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_categories_list');

            // Verify if member's name exists
            if ( isset($member['member_name']) ) {

                // Metas container
                $metas = array(
                    array(
                        'meta_name' => 'activity_scope',
                        'meta_value' => $category_id
                    ),
                    array(
                        'meta_name' => 'title',
                        'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_update')
                    ),                                  
                    array(
                        'meta_name' => 'actions',
                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_category_creation') . ' ' . trim($params['category_name']) . '.'
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
                        'for_id' => $category_id, 
                        'metas' => $metas
                    )

                );

                // Delete the user's cache
                delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

            }

            // Prepare the success response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_category_was_saved')
            ); 

        } else {

            // Prepare the error response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_category_was_not_saved')
            ); 

        }

    }

}

/* End of file create.php */