<?php
/**
 * Bot Functions Inc
 *
 * This file contains the some functions
 * which are used in the bot's page
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bots as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots;

if ( !function_exists('the_crm_chatbot_bot') ) {
    
    /**
     * The function the_crm_chatbot_bot gets the bot's data by ID
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    function the_crm_chatbot_bot($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get the bots
        return (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBots\Read)->crm_chatbot_the_bot($params);

    }

}

/* End of file bot_functions.php */