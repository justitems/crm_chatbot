<?php
/**
 * Quick_replies Update Helper
 *
 * This file update contains the methods
 * to update quick_replies
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
 * Update class extends the class Quick_replies to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Update {
    
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
     * The public method crm_chatbot_update_quick_reply updates a quick reply
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_update_quick_reply($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_quick_replies') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Verify if the reply parameter exists
        if ( empty($params['reply']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_found')
            );

        }

        // Get the quick reply
        $the_quick_reply = $this->CI->base_model->the_data_where('crm_chatbot_quick_replies', '*', array('reply_id' => $params['reply'], 'user_id' => $this->CI->user_id) );

        // Verify if the quick reply exists
        if ( !$the_quick_reply ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_found')
            );

        }

        // Get team's member
        $member = the_crm_current_team_member();
        
        // Quick reply container
        $quick_reply = array();

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

            // Set response_bot
            $quick_reply['response_bot'] = trim($the_quick_reply[0]['response_bot']);

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

            // Set response_text
            $quick_reply['response_text'] = trim($the_quick_reply[0]['response_text']);

        }

        // Verify if the quick reply has categories
        if ( $this->CI->base_model->the_data_where('crm_chatbot_quick_replies_categories', '*', array('reply_id' =>  $params['reply']) ) ) {

            // Delete the categories
            if ( !$this->CI->base_model->delete('crm_chatbot_quick_replies_categories', array('reply_id' =>  $params['reply']) ) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred_with_categories')
                );                
                
            }

        }

        // Update the quick reply
        $update = (($the_quick_reply[0]['keywords'] === $quick_reply['keywords']) && ($the_quick_reply[0]['accuracy'] === $quick_reply['accuracy']) && ($the_quick_reply[0]['response_type'] === $quick_reply['response_type']) && ($the_quick_reply[0]['response_text'] === $quick_reply['response_text']) && ($the_quick_reply[0]['response_bot'] === $quick_reply['response_bot']))?true:$this->CI->base_model->update('crm_chatbot_quick_replies', array('reply_id' => $params['reply']), $quick_reply);

        // Verify if the quick reply was created
        if ( $update ) {

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list');

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
                        'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_update')
                    ),                                  
                    array(
                        'meta_name' => 'actions',
                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_updated_the_quick_reply') . ' ' . trim($quick_reply['keywords']) . '.'
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
                        'for_id' => $params['reply'], 
                        'metas' => $metas
                    )

                );

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
                        if ( !$this->CI->base_model->insert('crm_chatbot_quick_replies_categories', array('reply_id' => $params['reply'], 'category_id' => $category) ) ) {
                            $categories_count++;
                        }

                    }

                }

                // Verify if $categories_count is positive
                if ( $categories_count ) {

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_updated_without_some_categories')
                    );                     

                } else {

                    // Prepare the success response
                    return array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_updated')
                    ); 
                    
                }

            } else {

                // Prepare the success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_updated')
                ); 

            }

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_updated')
            ); 

        }

    }

}

/* End of file update.php */