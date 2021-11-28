<?php
/**
 * Multioptions List Helpers
 *
 * This file contains the class Multioptions_list
 * with methods to provide content in the multioptions list template of the CRM Team app
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

/*
 * Multioptions_list class provides content in the multioptions list template of the CRM Team app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Multioptions_list {
    
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

    /**
     * The public method crm_chatbot_get_permission_websites gets automations websites
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_permission_websites() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('role', 'Role', 'trim');
            $this->CI->form_validation->set_rules('type', 'Type', 'trim');
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');
            $this->CI->form_validation->set_rules('option', 'Option', 'trim');
            $this->CI->form_validation->set_rules('item', 'Item', 'trim');
            $this->CI->form_validation->set_rules('enabled', 'Enabled', 'trim');

            // Get data
            $role = $this->CI->input->post('role', TRUE);
            $type = $this->CI->input->post('type', TRUE);
            $key = $this->CI->input->post('key', TRUE);
            $page = $this->CI->input->post('page', TRUE);
            $option = $this->CI->input->post('option', TRUE);
            $item = $this->CI->input->post('item', TRUE);
            $enabled = $this->CI->input->post('enabled', TRUE)?'1':'0';

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get role
                $get_role = $this->CI->base_model->the_data_where(
                    'teams_roles',
                    'role_id',
                    array(
                        'role_id' => $role,
                        'user_id' => $this->CI->user_id
                    )
                );

                // Verify if the role exists
                if ( !$get_role ) {

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_role_found')
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();                    

                }

                // Verify if $type is list
                if ( $type === 'list' ) {

                    // Get all enabled websites
                    $the_enabled_websites = the_crm_team_roles_multioptions_list($this->CI->user_id, $role, 'crm_chatbot_allowed_websites');

                    // Websites container
                    $websites = array();

                    // Verify if the website has enabled websites
                    if ( $the_enabled_websites ) {

                        // Set websites
                        $websites = array_column($the_enabled_websites, 'option_value');

                    }

                    // Verify if page is numeric
                    if ( !is_numeric($page) ) {

                        // Set page's value
                        $page = 1;

                    }

                    // Decrease the page
                    $page--;

                    // Limit
                    $limit = 5;

                    // Set like
                    $like = !empty($key)?array('domain' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($key)))):array();

                    // Get the websites
                    $the_websites = $this->CI->base_model->the_data_where(
                        'crm_chatbot_websites',
                        '*',
                        array(
                            'user_id' => $this->CI->user_id
                        ),
                        array(),
                        $like,
                        array(),
                        array(
                            'start' => ($page * $limit),
                            'limit' => $limit
                        )
                    );

                    // Verify if websites exists
                    if ( $the_websites ) {

                        // Get total number of items
                        $the_total_websites = $this->CI->base_model->the_data_where(
                            'crm_chatbot_websites',
                            'COUNT(website_id) as total',
                            array(
                                'user_id' => $this->CI->user_id
                            ),
                            array(),
                            $like
                        );                    

                        // Items container
                        $items = array();

                        // List all websites
                        foreach ( $the_websites as $the_website ) {

                            // Set item
                            $items[] = array(
                                'item_id' => $the_website['website_id'],
                                'item_name' => $the_website['domain'],
                                'enabled' => in_array($the_website['website_id'], $websites)?1:0
                            );

                        }
                        
                        // Prepare success response
                        $data = array(
                            'success' => TRUE,
                            'items' => $items,
                            'total' => $the_total_websites[0]['total'],
                            'page' => ($page + 1)
                        );

                        // Display the success response
                        echo json_encode($data);
                        exit();

                    } else {

                        // Prepare error response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_websites_were_found')
                        );

                        // Display the error response
                        echo json_encode($data);
                        exit();

                    }

                } elseif ( $type === 'actions' ) {

                    // Verify if the submitted data has correct value
                    if ( ($option === 'crm_chatbot_allowed_websites') && is_numeric($item) ) {

                        // Verify if the item exists and if the user is owner
                        if ( $this->CI->base_model->the_data_where('crm_chatbot_websites', '*', array('website_id' => $item, 'user_id' => $this->CI->user_id) ) ) {

                            // Verify if the option is enabled
                            if ( $enabled ) {

                                // Try to disable the website
                                if ( delete_crm_team_roles_multioptions_list($this->CI->user_id, $role, 'crm_chatbot_allowed_websites', $item) ) {

                                    // Delete the user's cache
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_threads_teams_members');

                                    // Prepare the success message
                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('crm_chatbot_website_was_disabled_successfully'),
                                        'item' => $item,
                                        'enabled' => 0
                                    );

                                    // Display the success message
                                    echo json_encode($data);

                                } else {

                                    // Prepare the error message
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('crm_chatbot_website_was_not_disabled_successfully')
                                    );

                                    // Display the error message
                                    echo json_encode($data);
                                }

                            } else {

                                // Try to enable the website
                                if ( save_crm_team_roles_multioptions_list($this->CI->user_id, $role, 'crm_chatbot_allowed_websites', $item) ) {

                                    // Delete the user's cache
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_threads_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_threads_teams_members');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_websites_guests_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_emails_list');
                                    delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_numbers_list');

                                    // Prepare the success message
                                    $data = array(
                                        'success' => TRUE,
                                        'message' => $this->CI->lang->line('crm_chatbot_website_was_enabled_successfully'),
                                        'item' => $item,
                                        'enabled' => 1
                                    );

                                    // Display the success message
                                    echo json_encode($data);

                                } else {

                                    // Prepare the error message
                                    $data = array(
                                        'success' => FALSE,
                                        'message' => $this->CI->lang->line('crm_chatbot_website_was_not_enabled_successfully')
                                    );

                                    // Display the error message
                                    echo json_encode($data);
                                }

                            }

                            exit();

                        }

                    }

                }

            }

        }
        
        // Prepare error response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Display the error response
        echo json_encode($data); 
        
    }

}

/* End of file multioptions_list.php */