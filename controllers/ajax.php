<?php
/**
 * Ajax Controller
 *
 * This file processes the app's ajax calls
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Controllers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers as CmsBaseUserAppsCollectionCrm_chatbotHelpers;

/*
 * Ajax class processes the app's ajax calls
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
 */
class Ajax {
    
    /**
     * Class variables
     *
     * @since 0.0.8.5
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.5
     */
    public function __construct() {
        
        // Get codeigniter object instance
        $this->CI =& get_instance();

        // Load language
        $this->CI->lang->load( 'crm_chatbot_user', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );
        
    } 

    /**
     * The public method crm_chatbot_get_threads gets threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_threads() {

        // Gets threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_get_threads();

    }

    /**
     * The public method crm_chatbot_check_for_new_threads checks for new threads in the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_check_for_new_threads() {

        // Checks for new threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_check_for_new_threads();

    }

    /**
     * The public method crm_chatbot_get_favorite_threads gets favorite threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_favorite_threads() {

        // Gets favorite threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_get_favorite_threads();

    }

    /**
     * The public method crm_chatbot_get_important_threads gets important threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_important_threads() {

        // Gets important threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_get_important_threads();

    }

    /**
     * The public method crm_chatbot_bot_pause sets or removes the bot pause for a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_bot_pause() {

        // Sets or removes the bot pause for a thread
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_bot_pause();

    }

    /**
     * The public method crm_chatbot_block_thread blocks or unblocks a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_block_thread() {

        // Blocks or unblocks a thread
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_block_thread();

    }

    /**
     * The public method crm_chatbot_favorite_thread favorites or unfavorites a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_favorite_thread() {

        // Favorites or unfavorites a thread
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_favorite_thread();

    }

    /**
     * The public method crm_chatbot_important_thread marks as important a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_important_thread() {

        // Marks or unmarks as important a thread
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Threads)->crm_chatbot_important_thread();

    }

    /**
     * The public method crm_chatbot_get_bot_threads gets bot's threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_bot_threads() {

        // Gets threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Info)->crm_chatbot_get_bot_threads();

    }

    /**
     * The public method crm_chatbot_get_reply_threads gets reply's threads from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_reply_threads() {

        // Gets threads
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Info)->crm_chatbot_get_reply_threads();

    }

    /**
     * The public method crm_chatbot_send_thread_message creates a new message
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_send_thread_message() {

        // Create message
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_send_thread_message();

    }

    /**
     * The public method crm_chatbot_get_thread_messages gets thread's messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_thread_messages() {

        // Gets thread's messages
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_get_private_messages();

    }

    /**
     * The public method crm_chatbot_check_for_new_thread_messages checks for new thread's messages
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_check_for_new_thread_messages() {

        // Gets new thread's messages
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Messages)->crm_chatbot_check_for_new_thread_messages();

    }

    /**
     * The public method crm_chatbot_get_numbers gets phone numbers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_numbers() {

        // Gets the phone numbers
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Numbers)->crm_chatbot_get_numbers();

    }

    /**
     * The public method crm_chatbot_delete_number deletes phone numbers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_number() {

        // Deletes the phone numbers
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Numbers)->crm_chatbot_delete_number();

    }

    /**
     * The public method crm_chatbot_get_emails gets email addresses
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_emails() {

        // Gets the email addresses
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Emails)->crm_chatbot_get_emails();

    }

    /**
     * The public method crm_chatbot_delete_email deletes email addresses
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_email() {

        // Deletes the email addresses
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Emails)->crm_chatbot_delete_email();

    }

    /**
     * The public method crm_chatbot_create_bot creates a bot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_create_bot() {

        // Create bot
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bots)->crm_chatbot_create_bot();
        
    }

    /**
     * The public method crm_chatbot_update_bot updates a bot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_bot() {

        // Update bot
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bots)->crm_chatbot_update_bot();
        
    }

    /**
     * The public method crm_chatbot_get_bots gets the bots list by page
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_bots() {

        // Gets the bots list
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bots)->crm_chatbot_get_bots();

    }

    /**
     * The public method crm_chatbot_enable_bot enables or disables an bot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_enable_bot() {

        // Enable/disable the bot
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bots)->crm_chatbot_enable_bot();

    }
    
    /**
     * The public method crm_chatbot_delete_bot deletes a bot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_bot() {

        // Delete bot
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bots)->crm_chatbot_delete_bot();

    }

    /**
     * The public method crm_chatbot_get_preview_bot_start shows the bot start in the chat's preview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_preview_bot_start() {

        // Get the bot start
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bot)->crm_chatbot_get_bot_start();

    }
    
    /**
     * The public method crm_chatbot_get_preview_bot_message gets a bot message in the chat's preview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_preview_bot_message() {

        // Get the bot message
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Bot)->crm_chatbot_get_bot_message();

    }

    /**
     * The public method crm_chatbot_create_category creates a new category
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_create_category() {

        // Create category
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Categories)->crm_chatbot_create_category();

    }

    /**
     * The public method crm_chatbot_get_categories gets the categories
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_categories() {

        // Gets categories
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Categories)->crm_chatbot_get_categories();

    }

    /**
     * The public method crm_chatbot_get_all_categories gets all categories
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_all_categories() {

        // Gets all categories
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Categories)->crm_chatbot_get_all_categories();

    }

    /**
     * The public method crm_chatbot_delete_category deletes a category
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_category() {

        // Delete category
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Categories)->crm_chatbot_delete_category();

    }

    /**
     * The public method crm_chatbot_create_quick_reply creates a quick reply
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_create_quick_reply() {

        // Create quick reply
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_create_quick_reply();

    }

    /**
     * The public method crm_chatbot_update_quick_reply updates a quick reply
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_quick_reply() {

        // Update quick reply
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_update_quick_reply();

    }

    /**
     * The public method crm_chatbot_get_quick_replies gets quick replies from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_quick_replies() {

        // Gets quick replies
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_get_quick_replies();

    }

    /**
     * The public method crm_chatbot_get_reply_data gets reply's data
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_reply_data() {

        // Gets quick reply data
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_get_reply_data();

    }

    /**
     * The public method crm_chatbot_enable_quick_reply enables or disables a quick reply
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_enable_quick_reply() {

        // Enable/disable the quick reply
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_enable_quick_reply();

    }

    /**
     * The public method crm_chatbot_delete_quick_reply deletes a quick reply
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_quick_reply() {

        // Delete a quick reply
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Quick_replies)->crm_chatbot_delete_quick_reply();

    }

    /**
     * The public method crm_chatbot_import_quick_replies_from_csv imports quick replies from CSV
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_import_quick_replies_from_csv() {

        // Imports quick replies
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Csv)->crm_chatbot_import_quick_replies_from_csv();

    }

    /**
     * The public method crm_chatbot_download_quick_replies exports quick replies in a CSV
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_quick_replies() {

        // Export quick replies
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Csv)->crm_chatbot_download_quick_replies();

    }

    /**
     * The public method crm_chatbot_download_phone_numbers exports the phone numbers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_phone_numbers() {

        // Gets the phone numbers
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Csv)->crm_chatbot_download_phone_numbers();

    }
    
    /**
     * The public method crm_chatbot_download_email_addresses exports the email addresses
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_email_addresses() {

        // Gets the email addresses
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Csv)->crm_chatbot_download_email_addresses();

    } 
    
    /**
     * The public method crm_chatbot_download_guests exports the guests
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_guests() {

        // Gets the guests
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Csv)->crm_chatbot_download_guests();

    }

    /**
     * The public method crm_chatbot_save_website saves a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_save_website() {

        // Save website
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_save_website();

    }

    /**
     * The public method crm_chatbot_update_website_url updates a website's url
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_website_url() {

        // Update website's url
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_update_website_url();

    }

    /**
     * The public method crm_chatbot_update_website updates a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_website() {

        // Update website
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_update_website();

    }

    /**
     * The public method crm_chatbot_get_websites gets websites from the database
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_websites() {

        // Gets websites
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_get_websites();

    }

    /**
     * The public method crm_chatbot_enable_website enables or disables a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_enable_website() {

        // Enable/disable the website
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_enable_website();

    }

    /**
     * The public method crm_chatbot_delete_website deletes a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_website() {

        // Delete a website
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Websites)->crm_chatbot_delete_website();

    }

    /**
     * The public method crm_chatbot_create_trigger creates a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_create_trigger() {

        // Create a trigger
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_create_trigger();

    }

    /**
     * The public method crm_chatbot_update_trigger updates a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_trigger() {

        // Update a trigger
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_update_trigger();

    }

    /**
     * The public method crm_chatbot_get_triggers gets the triggers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_triggers() {

        // Gets triggers
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_get_triggers();

    }

    /**
     * The public method crm_chatbot_get_trigger_data gets the trigger's data
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_trigger_data() {

        // Gets trigger's data
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_get_trigger_data();

    }

    /**
     * The public method crm_chatbot_get_triggers_events gets the triggers events
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_triggers_events() {

        // Gets all triggers events
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_get_triggers_events();

    }

    /**
     * The public method crm_chatbot_get_event_fields gets the event's fields
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_event_fields() {

        // Gets all event's fields
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_get_event_fields();

    }

    /**
     * The public method crm_chatbot_delete_trigger deletes a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_trigger() {

        // Delete trigger
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Triggers)->crm_chatbot_delete_trigger();

    }

    /**
     * The public method crm_chatbot_get_guests gets the guests
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_guests() {

        // Gets guests
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Guests)->crm_chatbot_get_guests();

    }

    /**
     * The public method crm_chatbot_get_visited_urls gets the visited urls
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_visited_urls() {

        // Gets visited urls
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Guests)->crm_chatbot_get_visited_urls();

    }

    /**
     * The public method crm_chatbot_delete_guest deletes a guest
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_guest() {

        // Delete a guest
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Guests)->crm_chatbot_delete_guest();

    }

    /**
     * The public method crm_chatbot_get_team_members gets the team's members
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_team_members() {

        // Gets the team's members
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Members)->crm_chatbot_get_team_members();

    }

    /**
     * The public method crm_chatbot_get_website_members gets the team members for a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_website_members() {

        // Gets the team members
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Members)->crm_chatbot_get_website_members();

    }

    /**
     * The public method crm_chatbot_send_member_invite sends team's member invite to join a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_send_member_invite() {

        // Send invite
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Members)->crm_chatbot_send_member_invite();

    }

    /**
     * The public method crm_chatbot_get_chat_styles gets the chat styles
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_chat_styles() {

        // Gets settings
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Settings)->crm_chatbot_get_chat_styles();

    }

    /**
     * The public method crm_chatbot_reset_style resets the chat styles
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_reset_style() {

        // Reset styles
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Settings)->crm_chatbot_reset_style();

    }

    /**
     * The public method crm_chatbot_get_permission_websites gets websites
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_permission_websites() {

        // Gets websites for multioptions list
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Multioptions_list)->crm_chatbot_get_permission_websites();

    }

    /**
     * The public method crm_chatbot_upload_bot_element_images uploads element's files
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_upload_bot_element_images() {

        // Upload files
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_upload_bot_element_images();
        
    } 

    /**
     * The public method crm_chatbot_upload_bot_element_videos uploads element's files
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_upload_bot_element_videos() {

        // Upload files
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_upload_bot_element_videos();
        
    } 

    /**
     * The public method crm_chatbot_get_items_selected_medias gets items selected media files
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_items_selected_medias() {

        // Get files
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_get_items_selected_medias();
        
    } 

    /**
     * The public method crm_chatbot_get_selected_medias gets selected media files
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_selected_medias() {

        // Get files
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_get_selected_medias();
        
    }

    /**
     * The public method crm_chatbot_change_icon changes an icon in the website's settings
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_change_icon() {

        // Upload image
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_change_icon();
        
    } 

    /**
     * The public method crm_chatbot_upload_message_files uploads files to a message
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_upload_message_files() {

        // Upload files
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Media)->crm_chatbot_upload_message_files();
        
    }

    /**
     * The public method crm_chatbot_get_overview gets overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_overview() {

        // Gets overview for Chatbot
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Overview)->crm_chatbot_get_overview();
        
    }

    /**
     * The public method crm_chatbot_get_actions gets actions for overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_actions() {

        // Gets actions for overview
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Overview)->crm_chatbot_get_actions();
        
    }

    /**
     * The public method crm_chatbot_get_agents gets agents for overview
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_agents() {

        // Gets agents for overview
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Overview)->crm_chatbot_get_agents();
        
    }

    /**
     * The public method crm_chatbot_get_activities gets all activities by page for Chatbot
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_activities() {

        // Gets activities
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Activities)->crm_chatbot_get_activities();
        
    }

    /**
     * The public method crm_chatbot_get_activity gets the chatbot's activity
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_activity() {

        // Loads activity
        (new CmsBaseUserAppsCollectionCrm_chatbotHelpers\Activities)->crm_chatbot_get_activity();
        
    }

}

/* End of file ajax.php */