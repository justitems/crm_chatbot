<?php
/**
 * Settings Helpers
 *
 * This file contains the class Settings
 * with methods to manage the settings
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

// Require the Website Functions Inc file
require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

/*
 * Settings class provides the methods to manage the settings
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Settings {
    
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
    // Ajax's methods for the Settings
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_chat_styles gets the chat styles
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_chat_styles() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Styles container
                $styles = array();                    

                // Verify if styles exists
                if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') ) {

                    // List all styles
                    foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') as $filename) {
                        
                        // Set class name
                        $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/', '.php'), '', $filename);

                        // Create an array
                        $array = array(
                            'CmsBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Crm_chatbot',
                            'Styles',
                            ucfirst($class_name)
                        );

                        // Implode the array above
                        $cl = implode('\\', $array);

                        // Get info
                        $info = (new $cl())->the_style_info();

                        // Verify if key exists
                        if ( $key ) {

                            // Verify if the style's name meets the key
                            if (strpos($info['style_name'], $key) === false) {
                                continue;
                            }

                        }

                        // Verify if is the expected slug
                        if ( !empty($info['style_slug']) ) {

                            // Set chat's style
                            $styles[] = array(
                                'style_slug' => $info['style_slug'],
                                'style_name' => $info['style_name']
                            );

                        }

                        if ( count($styles) > 9 ) {
                            break;
                        }
                        
                    }

                }

                // Verify if styles exists
                if ( $styles ) {

                    // Prepare the success response
                    $data = array(
                        'success' => TRUE,
                        'styles' => $styles
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();             

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_chat_styles_found')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_reset_style resets the chat styles
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_reset_style() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('style', 'Style', 'trim');

            // Get data
            $style = $this->CI->input->post('style', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Styles container
                $styles = array();                    

                // Verify if styles exists
                if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') ) {

                    // List all styles
                    foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') as $filename) {
                        
                        // Set class name
                        $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/', '.php'), '', $filename);

                        // Create an array
                        $array = array(
                            'CmsBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Crm_chatbot',
                            'Styles',
                            ucfirst($class_name)
                        );

                        // Implode the array above
                        $cl = implode('\\', $array);

                        // Get info
                        $info = (new $cl())->the_style_info();

                        // Verify if is the expected slug
                        if ( !empty($info['style_slug']) && !empty($info['style_button_icon']) && !empty($info['style_button_color']) ) {

                            // Verify if is the required chat's style
                            if ( $info['style_slug'] === $style ) {

                                // Prepare the success response
                                $data = array(
                                    'success' => TRUE,
                                    'style' => array(
                                        'style_button_icon' => $info['style_button_icon'],
                                        'style_button_color' => $info['style_button_color']
                                    )
                                );

                                // Display the success response
                                echo json_encode($data);
                                exit();  

                            }

                        }
                        
                    }

                }

            }

        }

    }

}

/* End of file settings.php */