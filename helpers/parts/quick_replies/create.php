<?php
/**
 * Quick_replies Create Helper
 *
 * This file create contains the methods
 * to create quick_replies
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
 * Create class extends the class Quick_replies to make it lighter
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
    // Ajax's methods for the Quick_replies
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_create_quick_reply creates a quick reply
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_create_quick_reply($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_create_quick_replies') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Get team's member
        $member = the_crm_current_team_member();

        // Quick reply container
        $quick_reply = array(
            'user_id' => $this->CI->user_id,
            'status' => 1,
            'created' => time()
        );

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's id exists
            if ( isset($member['member_id']) ) {

                // Set member's ID
                $quick_reply['member_id'] = $member['member_id'];

            }

        }

        // Verify if keywords exists
        if ( empty($params['keywords']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_at_least_keyword')
            );

        }

        // Verify if the keywords value has more than 250 characters
        if ( strlen(trim($params['keywords'])) > 250 ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_keywords_too_long')
            ); 
            
        }        
        
        // Set keywords
        $quick_reply['keywords'] = trim($params['keywords']);        

        // Verify if accuracy exists
        if ( !isset($params['accuracy']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_accuracy_is_wrong')
            );

        }

        // Verify if accuracy has correct data
        if ( !in_array($params['accuracy'], array('0', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100')) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_accuracy_is_wrong')
            );

        }

        // Set accuracy
        $quick_reply['accuracy'] = trim($params['accuracy']);        
        
        // Verify if response_type exists
        if ( !isset($params['response_type']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_response_type_is_wrong')
            );

        }

        // Verify if response_type has correct data
        if ( !in_array($params['response_type'], array('0', '1')) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_response_type_is_wrong')
            );

        }

        // Set response_type
        $quick_reply['response_type'] = trim($params['response_type']);
        
        // Verify if response_type is 0
        if ( $params['response_type'] === '0' ) {

            // Verify if response_text parameter exists
            if ( empty($params['response_text']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_please_enter_a_response')
                );
                
            }

            // Verify if the response_text parameter has at least 2 characters
            if ( strlen(trim($params['response_text'])) < 2 ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_please_enter_a_response')
                ); 
                
            }

            // Set response_text
            $quick_reply['response_text'] = trim($params['response_text']);

        } else {

            // Verify if the response_bot parameter exists
            if ( empty($params['response_bot']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_please_select_bot')
                );
                
            }
            
            // Get the bot
            $the_bot = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots',
                '*',
                array(
                    'bot_id' => $params['response_bot'],
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the bot's exists
            if ( !$the_bot ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_please_select_bot')
                );
                
            }
            
            // Set response_bot
            $quick_reply['response_bot'] = trim($params['response_bot']);

        }

        // Save the quick reply
        $reply_id = $this->CI->base_model->insert('crm_chatbot_quick_replies', $quick_reply);

        // Verify if the quick reply was created
        if ( $reply_id ) {

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list');

            // Verify if member's name exists
            if ( isset($member['member_name']) ) {

                // Metas container
                $metas = array(
                    array(
                        'meta_name' => 'activity_scope',
                        'meta_value' => $reply_id
                    ),
                    array(
                        'meta_name' => 'title',
                        'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_creation')
                    ),                                  
                    array(
                        'meta_name' => 'actions',
                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_created_quick_reply') . ' ' . trim($quick_reply['keywords']) . '.'
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
                        'for_id' => $reply_id, 
                        'metas' => $metas
                    )

                );

                // Delete the user's cache
                delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

            }

            // Verify if categories exists
            if ( !empty($params['categories']) ) {

                // Categories errors counter
                $categories_count = 0;

                // List all categories
                foreach ( $params['categories'] as $category ) {

                    // Verify if the category is numeric
                    if ( !is_numeric($category) ) {
                        continue;
                    }

                    // Verify if the category exists
                    if ( $this->CI->base_model->the_data_where('crm_chatbot_categories', '*', array('category_id' => $category, 'user_id' => $this->CI->user_id ) ) ) {

                        // Save the category
                        if ( !$this->CI->base_model->insert('crm_chatbot_quick_replies_categories', array('reply_id' => $reply_id, 'category_id' => $category) ) ) {
                            $categories_count++;
                        }

                    }

                }

                // Verify if $categories_count is positive
                if ( $categories_count ) {

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_saved_without_some_categories')
                    );                     

                } else {

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_saved')
                    ); 
                    
                }

            } else {

                // Prepare the success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_saved')
                ); 

            }

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_saved')
            ); 

        }

    }

}

/* End of file create.php */