<?php
/**
 * Chat Styles Inc
 *
 * PHP Version 7.3
 *
 * This files contains contains the chat's styles
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('the_crm_chatbot_chat_styles') ) {
    
    /**
     * The function the_crm_chatbot_chat_styles provides the chat styles
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.4
     * 
     * @return array with chat's styles
     */
    function the_crm_chatbot_chat_styles($website_id = 0) {

        // Load language
        get_instance()->lang->load( 'crm_chatbot_chat_styles', get_instance()->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );

        // Get chat's style
        $chat_style = the_crm_chatbot_websites_meta($website_id, 'chat_style');

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
                $info = (new $cl())->the_style_info($website_id);

                // Verify if is the expected slug
                if ( $info['style_slug'] === $chat_style ) {
                    return $info;
                }
                
            }

        }
        
        return false;

    }

}

/* End of file chat_styles.php */