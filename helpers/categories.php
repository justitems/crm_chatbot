<?php
/**
 * Categories Helpers
 *
 * This file contains the class Categories
 * with methods to manage the categories
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
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Categories as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCategories;

/*
 * Categories class provides the methods to manage the categories
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Categories {
    
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

        // Load the CRM Chatbot Categories Model
        $this->CI->load->ext_model( CMS_BASE_USER_APPS_CRM_CHATBOT . 'models/', 'Crm_chatbot_categories_model', 'crm_chatbot_categories_model' );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Categories
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_create_category creates a new category
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_create_category() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('category_name', 'Category Name', 'trim');

            // Get data
            $category_name = $this->CI->input->post('category_name', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'category_name' => $category_name
                );

                // Create category
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCategories\Create)->crm_chatbot_create_category($params);

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
     * The public method crm_chatbot_get_categories gets the categories
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_categories() {

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

                // Get categories
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCategories\Read)->crm_chatbot_the_categories($params);

                // Verify if categories exists
                if ( !empty($data['success']) ) {

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Permissions
                    $data['permissions'] = array();

                    // Verify if categories delete is allowed
                    if ( md_the_team_role_permission('crm_chatbot_delete_categories') ) {

                        // Set delete categories
                        $data['permissions']['delete_categories'] = 1;                

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
            'message' => $this->CI->lang->line('crm_chatbot_no_categories_were_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_get_all_categories gets all categories
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_all_categories() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('bot', 'Bot', 'trim');
            $this->CI->form_validation->set_rules('reply', 'Reply', 'trim');
            $this->CI->form_validation->set_rules('website', 'Website', 'trim');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get data
            $bot = $this->CI->input->post('bot', TRUE);
            $reply = $this->CI->input->post('reply', TRUE);
            $website = $this->CI->input->post('website', TRUE);
            $key = $this->CI->input->post('key', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'key' => $key
                ); 

                // Verify if bot exists
                if ( $bot ) {

                    // Set bot's id
                    $params['bot'] = $bot;

                }

                // Verify if reply exists
                if ( $reply ) {

                    // Set reply's id
                    $params['reply'] = $reply;

                } 
                
                // Verify if website exists
                if ( $website ) {

                    // Set reply's id
                    $params['website'] = $website;

                } 

                // Get categories
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCategories\Read)->crm_chatbot_the_all_categories($params);

                // Display the success response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_categories_were_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_delete_category deletes a category
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_delete_category() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('category', 'Category', 'trim');

            // Get data
            $category = $this->CI->input->post('category', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'category' => $category
                );

                // Delete category
                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCategories\Delete)->crm_chatbot_delete_category($params);

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

}

/* End of file categories.php */