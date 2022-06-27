<?php
/**
 * Websites Helpers
 *
 * This file contains the class Websites
 * with methods to manage the websites
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Websites as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites;

// Require the Website Functions Inc file
require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

/*
 * Websites class provides the methods to manage the websites
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Websites {
    
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

        // Load the CRM Chatbot Websites Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_websites_model', 'crm_chatbot_websites_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Websites
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_save_website saves a website
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_save_website() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('website_url', 'Website Url', 'trim');

            // Get data
            $website_url = $this->CI->input->post('website_url', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website_url' => $website_url
                );

                // Save website
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites\Create)->crm_chatbot_save_website($params);

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
     * The public method crm_chatbot_update_website_url updates a website's url
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_website_url() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('website_url', 'Website Url', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $website_url = $this->CI->input->post('website_url', FALSE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'website_url' => $website_url
                );

                // Update website's url
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites\Update)->crm_chatbot_update_website_url($params);

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
     * The public method crm_chatbot_update_website updates a website
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_update_website() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('options', 'Options', 'trim');
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $options = $this->CI->input->post('options', FALSE);
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'options' => $options,
                    'categories' => $categories
                );

                // Update website
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites\Update)->crm_chatbot_update_website($params);

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
     * The public method crm_chatbot_get_websites gets quick replies from the database
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_websites() {

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

                // Get the websites
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites\Read)->crm_chatbot_the_websites($params);

                // Verify if websites exists
                if ( !empty($data['websites']) ) {

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

                    // Verify if websites delete is allowed
                    if ( md_the_team_role_permission('crm_chatbot_delete_websites') ) {

                        // Set delete websites
                        $data['permissions']['delete_websites'] = 1;                

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
            'message' => $this->CI->lang->line('crm_chatbot_no_websites_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_enable_website enables or disables a website
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_enable_website() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }
        
        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_websites') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('status', 'Status', 'trim');


            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $status = $this->CI->input->post('status', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {
                
                // Get the website
                $the_website = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'website_id' => $website,
                        'user_id' => md_the_user_id()
                    )
                );

                // Verify if the website exists
                if ( $the_website ) {

                    // Verify if the website should be enabled
                    if ( $status ) {

                        // Try to enable the website
                        if (  $this->CI->base_model->update('crm_chatbot_websites', array('website_id' => $website), array('status' => 1)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_website_was_enabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_on')
                            );

                            // Display the success response
                            echo json_encode($data);
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $website
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_website_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_website') . ' ' . trim($the_website[0]['domain']) . '.'
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
                                        'for_id' => $website, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_website_was_not_enabled')
                            );

                            // Display the false response
                            echo json_encode($data);

                        }

                        exit();

                    } else {

                        // Try to disable the website
                        if (  $this->CI->base_model->update('crm_chatbot_websites', array('website_id' => $website), array('status' => 0)) ) {

                            // Prepare the success response
                            $data = array(
                                'success' => TRUE,
                                'message' => $this->CI->lang->line('crm_chatbot_website_was_disabled'),
                                'status' => $this->CI->lang->line('crm_chatbot_off')
                            );

                            // Display the success response
                            echo json_encode($data);         
                            
                            // Delete the user's cache
                            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list');

                            // Get team's member
                            $member = the_crm_current_team_member();

                            // Verify if member's name exists
                            if ( isset($member['member_name']) ) {

                                // Metas container
                                $metas = array(
                                    array(
                                        'meta_name' => 'activity_scope',
                                        'meta_value' => $website
                                    ),
                                    array(
                                        'meta_name' => 'title',
                                        'meta_value' => $this->CI->lang->line('crm_chatbot_website_update')
                                    ),                                  
                                    array(
                                        'meta_name' => 'actions',
                                        'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_changed_status_of_the_website') . ' ' . trim($the_website[0]['domain']) . '.'
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
                                        'for_id' => $website, 
                                        'metas' => $metas
                                    )

                                );

                            }
                            
                        } else {
                            
                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_website_was_not_disabled')
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
            'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_delete_website deletes a website
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_website() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website ID', 'trim|numeric|required');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website
                );            

                // Try to delete the bot
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsWebsites\Delete)->crm_chatbot_delete_website($params);

                // Display the response
                echo json_encode($response);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_website_was_not_deleted_successfully')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file websites.php */