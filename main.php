<?php
/**
 * CRM Chatbot App
 *
 * This file loads the CRM Chatbot app
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');
defined('CMS_BASE_USER_APPS_CRM_CHATBOT') OR define('CMS_BASE_USER_APPS_CRM_CHATBOT', CMS_BASE_USER . 'apps/collection/crm_chatbot/');
defined('CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION') OR define('CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION', '0.0.4');

// Define the namespaces to use
use CmsBase\User\Interfaces as CmsBaseUserInterfaces;
use CmsBase\User\Apps\Collection\Crm_chatbot\Controllers as CmsBaseUserAppsCollectionCrm_chatbotControllers;

/*
 * Main class loads the Chatbot app loader
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
 */
class Main implements CmsBaseUserInterfaces\Apps {
   
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }

    /**
     * The public method check_availability checks if the app is available
     *
     * @return boolean true or false
     */
    public function check_availability() {

        if ( !md_the_option('app_crm_chatbot_enabled') || !md_the_plan_feature('app_crm_chatbot_enabled') || !md_the_team_role_permission('crm_chatbot') || !the_crm_apps_installed_app('crm_chatbot') ) {
            return false;
        } else {
            return true;
        }
        
    }
    
    /**
     * The public method user loads the app's main page in the user panel
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function user() {

        // Verify if the app is enabled
        if ( !md_the_option('app_crm_chatbot_enabled') || !md_the_plan_feature('app_crm_chatbot_enabled') || !md_the_team_role_permission('crm_chatbot') || !the_crm_apps_installed_app('crm_chatbot') ) {
            show_404();
        }

        // Instantiate the class
        (new CmsBaseUserAppsCollectionCrm_chatbotControllers\User)->view();
        
    }
    
    /**
     * The public method ajax processes the ajax's requests
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function ajax() {

        // Verify if the app is enabled
        if ( !md_the_option('app_crm_chatbot_enabled') || !md_the_plan_feature('app_crm_chatbot_enabled') || !md_the_team_role_permission('crm_chatbot') || !the_crm_apps_installed_app('crm_chatbot') ) {
            show_404();
        }        
        
        // Get action's get input
        $action = $this->CI->input->get('action', TRUE);

        if ( !$action ) {
            $action = $this->CI->input->post('action', TRUE);
        }

        try {
            
            // Call method if exists
            (new CmsBaseUserAppsCollectionCrm_chatbotControllers\Ajax)->$action();
            
        } catch (Exception $ex) {
            
            $data = array(
                'success' => FALSE,
                'message' => $ex->getMessage()
            );
            
            echo json_encode($data);
            
        }
        
    }

    /**
     * The public method rest processes the rest's requests
     * 
     * @param string $endpoint contains the requested endpoint
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function rest($endpoint) {

    }
    
    /**
     * The public method cron_jobs loads the cron jobs commands
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function cron_jobs() {
        
    }
    
    /**
     * The public method delete_account is called when user's account is deleted
     * 
     * @param integer $user_id contains the user's ID
     * 
     * @since 0.0.7.4
     * 
     * @return void
     */
    public function delete_account($user_id) {
        
    }
    
    /**
     * The public method hooks contains the app's hooks
     * 
     * @param string $category contains the hooks category
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function load_hooks( $category, $static_slug=NULL, $dynamic_slug=NULL ) {

        // Verify if action parameter exists
        if ( substr($this->CI->input->post('action', TRUE), 0, 12) === 'crm_chatbot' ) {

            // Set loaded app
            md_set_data('loaded_app', 'crm_chatbot');

        }

        // Register the hooks by category
        switch ( $category ) {

            case 'init':

                // Require the CHATBOT Init Inc file
                //require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chatbot_init.php';

                break;

            case 'admin_init':

                // Verify if user has opened the user component
                if ( (md_the_data('component') === 'user') ) {

                    // Register the hooks for administrator
                    md_set_hook(
                        'admin_init',
                        function ($args) {

                            // Load the admin app's language file
                            $this->CI->lang->load('crm_chatbot_admin', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);

                            // Require the Admin Inc
                            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/admin.php');

                            // Require the Plans Inc
                            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/plans.php');


                        }

                    );   

                } else if (md_the_data('component') === 'notifications') {

                    // Load the component's language files
                    $this->CI->lang->load( 'crm_chatbot_email_templates', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);

                    // Require the email_templates file
                    require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/email_templates.php';

                }

                break;

            case 'user_init':

                // Load code only for CRM Apps
                if ( (md_the_data('loaded_app') === 'crm_apps') || (substr($this->CI->input->post('action', TRUE), 0, 13) === 'crm_dashboard') ) {

                    // Load the app's language file
                    $this->CI->lang->load('crm_chatbot_app', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);

                    // Require the App Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/app.php');

                } else if ( md_the_data('loaded_app') === 'crm_team' ) {

                    // Load the Members app's language file
                    $this->CI->lang->load('crm_chatbot_members', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);
                    
                    // Require the Members Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chatbot_members.php');

                } else if ( md_the_data('loaded_app') === 'crm_settings' ) {

                    // Load the settings app's language file
                    $this->CI->lang->load('crm_chatbot_settings', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);
                    
                    // Require the Settings Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/settings.php');

                }

                if ( (md_the_data('loaded_app') === 'crm_dashboard') || (md_the_data('loaded_app') === 'crm_settings') ) {

                    // Load the dashboard app's language file
                    $this->CI->lang->load('crm_chatbot_dashboard', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);
                    
                    // Require the Dashboard Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chatbot_dashboard.php');

                } else if ( md_the_data('loaded_app') === 'crm_automations' ) {

                    // Load the automations app's language file
                    $this->CI->lang->load('crm_chatbot_automations', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);
                    
                    // Require the Automations Inc
                    md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php');

                }
                
                if ( md_the_data('loaded_app') === 'crm_settings' ) {

                    if ( $this->CI->input->get('p', TRUE) === 'plan' ) {

                        // Require the Plan Usage Inc
                        md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/plan_usage.php');

                    }

                }

                break;

        }

    }

    /**
     * The public method guest contains the app's access for guests
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function guest() {

        // Process requests
        (new CmsBaseUserAppsCollectionCrm_chatbotControllers\Chat)->process(); 

    }
    
    /**
     * The public method app_info contains the app's info
     * 
     * @since 0.0.8.4
     * 
     * @return array with app's information
     */
    public function app_info() {

        // Load the app's language files
        $this->CI->lang->load( 'crm_chatbot_admin', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );
        
        // Return app information
        return array(
            'app_name' => $this->CI->lang->line('crm_chatbot'),
            'app_slug' => 'crm_chatbot',
            'app_icon' => md_the_admin_icon(array('icon' => 'chat_small')),
            'version' => CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION,
            'update_url' => 'https://raw.githubusercontent.com/scrisoft/crm_chatbot/main/update',
            'update_code' => FALSE,
            'min_version' => '0.0.8.5',
            'max_version' => '0.0.8.5'
        );
        
    }

}

/* End of file main.php */