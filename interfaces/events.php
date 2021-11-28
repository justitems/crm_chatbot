<?php
/**
 * Events
 *
 * PHP Version 7.4
 *
 * Events Interface for Chatbot's Events
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Interfaces;

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Events interface - allows to create events for Chatbot
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm/blob/master/LICENSE.md CRM License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */
interface Events {
    
    /**
     * The public method the_fields provides the fields for configuration
     * 
     * @param array $params contains the data
     * 
     * @since 0.0.8.5
     * 
     * @return array with the fields
     */
    public function the_fields($params = array());

    /**
     * The public method save_data saves the data for configuration
     * 
     * @param array $params contains the data
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function save_data($params);

    /**
     * The public method the_data returns the data for the chat
     * 
     * @param array $params contains the data
     * 
     * @since 0.0.8.5
     * 
     * @return array with configured data
     */
    public function the_data($params);
    
    /**
     * The public method the_event_info provides the event's info
     * 
     * @since 0.0.8.5
     * 
     * @return array with event's info
     */
    public function the_event_info();
    
}

/* End of file events.php */
