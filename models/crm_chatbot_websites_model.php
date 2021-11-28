<?php
/**
 * CRM Chatbot Websites Model
 *
 * PHP Version 7.3
 *
 * Crm_chatbot_websites_model contains the CRM Chatbot Websites Model
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
 * Crm_chatbot_websites_model class - operates the crm_chatbot_websites table
 *
 * @since 0.0.8.4
 * 
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */
class Crm_chatbot_websites_model extends CI_MODEL {
    
    /**
     * Class variables
     */
    private $table = 'crm_chatbot_websites';

    /**
     * Initialise the model
     */
    public function __construct() {
        
        // Call the Model constructor
        parent::__construct();
        
        // Get crm_chatbot_websites table
        $crm_chatbot_websites = $this->db->table_exists('crm_chatbot_websites');
        
        // Verify if the crm_chatbot_websites table exists
        if ( !$crm_chatbot_websites ) {
            
            // Create the crm_chatbot_websites table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites` (
                `website_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` int(11) NOT NULL,
                `member_id` bigint(20) NOT NULL,
                `domain` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `url` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `status` tinyint(1) NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_meta table
        $crm_chatbot_websites_meta = $this->db->table_exists('crm_chatbot_websites_meta');
        
        // Verify if the crm_chatbot_websites_meta table exists
        if ( !$crm_chatbot_websites_meta ) {
            
            // Create the crm_chatbot_websites_meta table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_meta` (
                `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `website_id` bigint(20) NOT NULL,
                `meta_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `meta_value` VARBINARY(4000) NOT NULL,
                `meta_extra` VARBINARY(4000) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_categories table
        $crm_chatbot_websites_categories = $this->db->table_exists('crm_chatbot_websites_categories');
        
        // Verify if the crm_chatbot_websites_categories table exists
        if ( !$crm_chatbot_websites_categories ) {
            
            // Create the crm_chatbot_websites_categories table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_categories` (
                `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `website_id` bigint(20) NOT NULL,
                `category_id` bigint(20) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_triggers table
        $crm_chatbot_websites_triggers = $this->db->table_exists('crm_chatbot_websites_triggers');
        
        // Verify if the crm_chatbot_websites_triggers table exists
        if ( !$crm_chatbot_websites_triggers ) {
            
            // Create the crm_chatbot_websites_triggers table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_triggers` (
                `trigger_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` int(11) NOT NULL,
                `member_id` bigint(20) NOT NULL,
                `website_id` bigint(20) NOT NULL,
                `trigger_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `event_slug` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_triggers_meta table
        $crm_chatbot_websites_triggers_meta = $this->db->table_exists('crm_chatbot_websites_triggers_meta');

        // Verify if the crm_chatbot_websites_triggers_meta table exists
        if ( !$crm_chatbot_websites_triggers_meta ) {
            
            // Create the crm_chatbot_websites_triggers_meta table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_triggers_meta` (
                `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `trigger_id` bigint(20) NOT NULL,
                `meta_parent` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `meta_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `meta_value` VARBINARY(4000) NOT NULL,
                `meta_extra` VARBINARY(4000) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_triggers_guests table
        $crm_chatbot_websites_triggers_guests = $this->db->table_exists('crm_chatbot_websites_triggers_guests');

        // Verify if the crm_chatbot_websites_triggers_guests table exists
        if ( !$crm_chatbot_websites_triggers_guests ) {
            
            // Create the crm_chatbot_websites_triggers_guests table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_triggers_guests` (
                `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `trigger_id` bigint(20) NOT NULL,
                `guest_id` bigint(20) NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_guests table
        $crm_chatbot_websites_guests = $this->db->table_exists('crm_chatbot_websites_guests');
                                
        // Verify if the crm_chatbot_websites_guests table exists
        if ( !$crm_chatbot_websites_guests ) {
            
            // Create the crm_chatbot_websites_guests table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_guests` (
                `guest_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` bigint(20) NOT NULL,
                `id` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_guests_meta table
        $crm_chatbot_websites_guests_meta = $this->db->table_exists('crm_chatbot_websites_guests_meta');

        // Verify if the crm_chatbot_websites_guests_meta table exists
        if ( !$crm_chatbot_websites_guests_meta ) {
            
            // Create the crm_chatbot_websites_guests_meta table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_guests_meta` (
                `meta_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `guest_id` bigint(20) NOT NULL,
                `meta_name` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `meta_value` VARBINARY(4000) NOT NULL,
                `meta_extra` VARBINARY(4000) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_guests_categories table
        $crm_chatbot_websites_guests_categories = $this->db->table_exists('crm_chatbot_websites_guests_categories');

        // Verify if the crm_chatbot_websites_guests_categories table exists
        if ( !$crm_chatbot_websites_guests_categories ) {
            
            // Create the crm_chatbot_websites_guests_categories table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_guests_categories` (
                `id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `guest_id` bigint(20) NOT NULL,
                `category_id` bigint(20) NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_guests_visited_urls table
        $crm_chatbot_websites_guests_visited_urls = $this->db->table_exists('crm_chatbot_websites_guests_visited_urls');

        // Verify if the crm_chatbot_websites_guests_visited_urls table exists
        if ( !$crm_chatbot_websites_guests_visited_urls ) {
            
            // Create the crm_chatbot_websites_guests_visited_urls table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_guests_visited_urls` (
                `url_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` bigint(20) NOT NULL,
                `guest_id` bigint(20) NOT NULL,
                `website_id` bigint(20) NOT NULL,
                `title` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
                `url` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_threads table
        $crm_chatbot_websites_threads = $this->db->table_exists('crm_chatbot_websites_threads');
                        
        // Verify if the crm_chatbot_websites_threads table exists
        if ( !$crm_chatbot_websites_threads ) {
            
            // Create the crm_chatbot_websites_threads table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_threads` (
                `thread_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` bigint(20) NOT NULL,
                `member_id` bigint(20) NOT NULL,
                `guest_id` bigint(20) NOT NULL,
                `website_id` bigint(20) NOT NULL,
                `favorite` tinyint(1) NOT NULL,
                `important` tinyint(1) NOT NULL,
                `auto` tinyint(1) NOT NULL,
                `status` tinyint(1) NOT NULL,
                `created` varchar(30) NOT NULL,
                `updated` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_messages table
        $crm_chatbot_websites_messages = $this->db->table_exists('crm_chatbot_websites_messages');
                
        // Verify if the crm_chatbot_websites_messages table exists
        if ( !$crm_chatbot_websites_messages ) {
            
            // Create the crm_chatbot_websites_messages table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_messages` (
                `message_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `user_id` int(11) NOT NULL,
                `member_id` bigint(20) NOT NULL,
                `guest_id` bigint(20) NOT NULL,
                `thread_id` bigint(20) NOT NULL,
                `bot` tinyint(1) NOT NULL,
                `bot_id` bigint(20) NOT NULL,
                `reply_id` bigint(20) NOT NULL,
                `message_type` tinyint(1) NOT NULL,
                `message_body` VARBINARY(4000) NOT NULL,
                `media_id` bigint(20) NOT NULL,
                `created` varchar(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }

        // Get crm_chatbot_websites_messages_attachments table
        $crm_chatbot_websites_messages_attachments = $this->db->table_exists('crm_chatbot_websites_messages_attachments');
                        
        // Verify if the crm_chatbot_websites_messages_attachments table exists
        if ( !$crm_chatbot_websites_messages_attachments ) {
            
            // Create the crm_chatbot_websites_messages_attachments table
            $this->db->query('CREATE TABLE IF NOT EXISTS `crm_chatbot_websites_messages_attachments` (
                `attachment_id` bigint(20) AUTO_INCREMENT PRIMARY KEY,
                `message_id` bigint(20) NOT NULL,
                `media_id` bigint(20) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
            
        }
        
    }

    /**
     * The public method the_threads_search gets threads ids by key
     *
     * @param array $params contains the parameters
     * 
     * @return array 
     */
    public function the_threads_search($params) {

        // Select threads ids
        $this->db->select('crm_chatbot_websites_threads.thread_id');

        // Select the crm_chatbot_websites_threads table
        $this->db->from('crm_chatbot_websites_threads');

        // Join crm_chatbot_websites
        $this->db->join('crm_chatbot_websites', 'crm_chatbot_websites.website_id=crm_chatbot_websites_threads.website_id');

        // Join crm_chatbot_websites_messages
        $this->db->join('crm_chatbot_websites_messages', 'crm_chatbot_websites_threads.thread_id=crm_chatbot_websites_messages.thread_id');        

        // Set like
        $this->db->like('crm_chatbot_websites_threads.thread_id', trim(str_replace('!_', '_', $this->db->escape_like_str($params['key']))));

        // Set or like
        $this->db->or_like('crm_chatbot_websites.domain', trim(str_replace('!_', '_', $this->db->escape_like_str($params['key']))));  
        
        // Set or like
        $this->db->or_like('crm_chatbot_websites_messages.message_body', trim(str_replace('!_', '_', $this->db->escape_like_str($params['key']))));          
        
        // Get data
        $query = $this->db->get();

        // Verify if threadswere found
        if ( $query->num_rows() > 0 ) {
            
            // Return array with threads ids
            return $query->result_array();
            
        } else {
            
            return false;
            
        }

    }
    
}

/* End of file crm_chatbot_websites_model.php */