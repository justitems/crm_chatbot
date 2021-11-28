<?php
/**
 * Bots Helpers
 *
 * This file contains the class Bots
 * with methods to manage the bots
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bots as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots;

/*
 * Bots class provides the methods to manage the bots
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Bots {
    
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

        // Load the CRM Chatbot Bots Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_bots_model', 'crm_chatbot_bots_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Bots
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_create_bot creates a bot
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_create_bot() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('name', 'Name', 'trim');
            $this->CI->form_validation->set_rules('operators', 'Operators', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $name = $this->CI->input->post('name', TRUE);
            $operators = $this->CI->input->post('operators', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'name' => $name,
                    'operators' => $operators,
                    'categories' => $categories
                );

                // Create bot
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots\Create)->crm_chatbot_create_bot($params);

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
     * The public method crm_chatbot_update_bot updates a bot
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_update_bot() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot', 'trim');
            $this->CI->form_validation->set_rules('operators', 'Operators', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $bot = $this->CI->input->post('bot', TRUE);
            $operators = $this->CI->input->post('operators', TRUE);
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'bot' => $bot,
                    'operators' => $operators,
                    'categories' => $categories
                );

                // Update bot
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots\Update)->crm_chatbot_update_bot($params);

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
     * The public method crm_chatbot_get_bots gets the bots list by page
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_bots() {

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

                // Get the bots
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots\Read)->crm_chatbot_the_bots($params);

                // Verify if bots exists
                if ( !empty($data['bots']) ) {

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

                    // Verify if bots delete is allowed
                    if ( md_the_team_role_permission('crm_chatbot_delete_bots') ) {

                        // Set delete bots
                        $data['permissions']['delete_bots'] = 1;                

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
            'message' => $this->CI->lang->line('crm_chatbot_no_bots_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_enable_bot enables or disables an bot
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_enable_bot() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_bots') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');


            // Get data
            $bot = $this->CI->input->post('bot', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get the bot
                $the_bot = $this->CI->base_model->the_data_where(
                    'crm_chatbot_bots',
                    '*',
                    array(
                        'bot_id' => $bot,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if the bot exists
                if ( $the_bot ) {

                    // Verify if the bot should be enabled
                    if ( $status ) {

                        // Try to enable the bot
                        if (  $this->CI->base_model->update('crm_chatbot_bots', array('bot_id' => $bot), array('status' => 1)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_bot_was_enabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_on')
                            );

                            // Display the success response
                            echo json_encode($data);
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $bot
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_bot_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_bot') . ' ' . trim($the_bot[0]['bot_name']) . '.'
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
                                        'for_id' => $bot, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_bot_was_not_enabled')
                            );

                            // Display the false response
                            echo json_encode($data);

                        }

                        exit();

                    } else {

                        // Try to disable the bot
                        if (  $this->CI->base_model->update('crm_chatbot_bots', array('bot_id' => $bot), array('status' => 0)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_bot_was_disabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_off')
                            );

                            // Display the success response
                            echo json_encode($data);         
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $bot
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_bot_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_bot') . ' ' . trim($the_bot[0]['bot_name']) . '.'
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
                                        'for_id' => $bot, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_bot_was_not_disabled')
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
            'message' => $this->CI->lang->line('crm_chatbot_bot_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_delete_bot deletes a bot
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_bot() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot ID', 'trim|numeric|required');

            // Get data
            $bot = $this->CI->input->post('bot', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'bot' => $bot
                );            

                // Try to delete the bot
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots\Delete)->crm_chatbot_delete_bot($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file bots.php */