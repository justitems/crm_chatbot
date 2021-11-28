<?php
/**
 * CRM Chatbot Emails Model
 *
 * PHP Version 7.3
 *
 * Crm_chatbot_emails_model contains the CRM Chatbot Emails Model
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Crm_chatbot_emails_model class - operates the crm_chatbot_emails table
 *
 * @since 0.0.8.5
 * 
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */
class Crm_chatbot_emails_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'crm_chatbot_emails';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Get crm_chatbot_emails table
        $crm_chatbot_emails = $this->db->table_exists('crm_chatbot_emails');
        
        // Verify if the crm_chatbot_emails table exists
        if ( !$crm_chatbot_emails ) {
            
            // Create the crm_chatbot_emails table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_emails` (
                `email_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` int(11) NOT NULL,
                `guest_id` int(11) NOT NULL,
                `website_id` bigint(20) NOT NULL,
                `thread_id` bigint(20) NOT NULL,
                `message_id` bigint(20) NOT NULL,
                `email` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
    }
    
}

/* End of file crm_chatbot_emails_model.php */