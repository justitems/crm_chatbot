<?php
/**
 * Styles
 *
 * PHP Version 7.4
 *
 * Styles Interface for Chatbot's Styles
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

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Styles interface - allows to create styles for Chatbot
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm/blob/master/LICENSE.md CRM License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */
interface Styles {
    
    /**
     * The public method the_style_info shows the chat's style
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.5
     * 
     * @return array with the style
     */
    public function the_style_info($website_id=0);
    
}

/* End of file styles.php */
