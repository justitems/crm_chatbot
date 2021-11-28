<?php
/**
 * Quick_replies Helpers
 *
 * This file contains the class Quick_replies
 * with methods to manage the quick replies
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Quick_replies as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies;

/*
 * Quick_replies class provides the methods to manage the quick_replies
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Quick_replies {
    
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

        // Load the CRM Chatbot Quick_replies Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_quick_replies_model', 'crm_chatbot_quick_replies_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Quick_replies
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_create_quick_reply creates a quick reply
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_create_quick_reply() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('keywords', 'Keywords', 'trim');
            $this->CI->form_validation->set_rules('accuracy', 'Accuracy', 'trim');
            $this->CI->form_validation->set_rules('response_type', 'Response Type', 'trim');
            $this->CI->form_validation->set_rules('response_text', 'Response Text', 'trim');
            $this->CI->form_validation->set_rules('response_bot', 'Response Bot', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $keywords = $this->CI->input->post('keywords', TRUE);
            $accuracy = $this->CI->input->post('accuracy', TRUE);
            $response_type = $this->CI->input->post('response_type', TRUE);
            $response_text = $this->CI->input->post('response_text', TRUE);
            $response_bot = $this->CI->input->post('response_bot', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'keywords' => $keywords,
                    'accuracy' => $accuracy,
                    'response_type' => $response_type,
                    'response_text' => $response_text,
                    'response_bot' => $response_bot,
                    'categories' => $categories
                );

                // Create quick reply
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies\Create)->crm_chatbot_create_quick_reply($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_update_quick_reply updates a quick reply
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_update_quick_reply() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('reply', 'Reply', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('keywords', 'Keywords', 'trim');
            $this->CI->form_validation->set_rules('accuracy', 'Accuracy', 'trim');
            $this->CI->form_validation->set_rules('response_type', 'Response Type', 'trim');
            $this->CI->form_validation->set_rules('response_text', 'Response Text', 'trim');
            $this->CI->form_validation->set_rules('response_bot', 'Response Bot', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $reply = $this->CI->input->post('reply', TRUE);
            $keywords = $this->CI->input->post('keywords', TRUE);
            $accuracy = $this->CI->input->post('accuracy', TRUE);
            $response_type = $this->CI->input->post('response_type', TRUE);
            $response_text = $this->CI->input->post('response_text', TRUE);
            $response_bot = $this->CI->input->post('response_bot', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'reply' => $reply,
                    'keywords' => $keywords,
                    'accuracy' => $accuracy,
                    'response_type' => $response_type,
                    'response_text' => $response_text,
                    'response_bot' => $response_bot,
                    'categories' => $categories
                );

                // Update quick reply
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies\Update)->crm_chatbot_update_quick_reply($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_quick_replies gets quick replies from the database
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_quick_replies() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'page' => $page,
                    'limit' => 10
                );             

                // Verify if key exists
                if ( $key ) {

                    // Set key
                    $params['key'] = $key;

                }              

                // Get the quick_replies
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies\Read)->crm_chatbot_the_quick_replies($params);

                // Verify if quick_replies exists
                if ( !empty($data['quick_replies']) ) {

                    // Set words
                    $data['words'] = array(
                        'of' => $this->CI->lang->line('crm_chatbot_of'),
                        'results' => $this->CI->lang->line('crm_chatbot_results')
                    );

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Permissions
                    $data['permissions'] = array();

                    // Verify if quick_replies delete is allowed
                    if ( md_the_team_role_permission('crm_chatbot_delete_quick_replies') ) {

                        // Set delete quick_replies
                        $data['permissions']['delete_quick_replies'] = 1;                

                    }

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_quick_replies_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_reply_data gets reply's data
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_reply_data() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('reply', 'Reply ID', 'trim|numeric|required');

            // Get data
            $reply = $this->CI->input->post('reply', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'reply' => $reply
                );         

                // Get the quick_reply
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies\Read)->crm_chatbot_the_quick_reply($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_quick_replies_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_enable_quick_reply enables or disables a quick reply
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_enable_quick_reply() {

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

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('reply', 'reply ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');


            // Get data
            $reply = $this->CI->input->post('reply', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get the quick reply
                $the_quick_reply = $this->CI->base_model->the_data_where(
                    'crm_chatbot_quick_replies',
                    '*',
                    array(
                        'reply_id' => $reply,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if the quick reply exists
                if ( $the_quick_reply ) {

                    // Verify if the quick reply should be enabled
                    if ( $status ) {

                        // Try to enable the quick reply
                        if (  $this->CI->base_model->update('crm_chatbot_quick_replies', array('reply_id' => $reply), array('status' => 1)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_quick_reply_was_enabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_on')
                            );

                            // Display the success response
                            echo json_encode($data);
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $reply
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_quick_reply') . ' ' . trim($the_quick_reply[0]['keywords']) . '.'
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
                                        'for_id' => $reply, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_quick_reply_was_not_enabled')
                            );

                            // Display the false response
                            echo json_encode($data);

                        }

                        exit();

                    } else {

                        // Try to disable the quick reply
                        if (  $this->CI->base_model->update('crm_chatbot_quick_replies', array('reply_id' => $reply), array('status' => 0)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_quick_reply_was_disabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_off')
                            );

                            // Display the success response
                            echo json_encode($data);         
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $reply
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_quick_reply_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_quick_reply') . ' ' . trim($the_quick_reply[0]['keywords']) . '.'
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
                                        'for_id' => $reply, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_quick_reply_was_not_disabled')
                            );

                            // Display the false response
                            echo json_encode($data);

                        }

                        exit();

                    }

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_delete_quick_reply deletes a quick reply
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_quick_reply() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('reply', 'Reply ID', 'trim|numeric|required');

            // Get data
            $reply = $this->CI->input->post('reply', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'reply' => $reply
                );            

                // Try to delete the bot
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsQuick_replies\Delete)->crm_chatbot_delete_quick_reply($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_quick_reply_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file quick_replies.php */