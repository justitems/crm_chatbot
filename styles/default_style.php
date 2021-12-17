<?php
/**
 * CRM Chatbot Default Style
 *
 * This file contains the Default_style class
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
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Styles;

// Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Interfaces as CmsBaseUserAppsCollectionCrm_chatbotInterfaces;

/*
 * Default_style class manages the Default style
 * 
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 * 
 * @since 0.0.8.5
 */
class Default_style implements CmsBaseUserAppsCollectionCrm_chatbotInterfaces\Styles {
   
    /**
     * Class variables
     *
     * @since 0.0.8.5
     */
    protected
            $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.5
     */
    public function __construct() {
        
        // Assign the CodeIgniter super-object
        $this->CI =& get_instance();
        
    }

    /**
     * The public method the_style_info shows the chat's style
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.5
     * 
     * @return array with the style
     */
    public function the_style_info($website_id=0) {

        // Set button shadow
        $button_shadow = the_crm_chatbot_websites_meta($website_id, 'remove_shadow_button')?'box-shadow: none !important;':'box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;';

        // Set button background color
        $button_background_color = the_crm_chatbot_websites_meta($website_id, 'background_color_button')?the_crm_chatbot_websites_meta($website_id, 'background_color_button'):'rgb(44, 115, 210)';

        // Triggers container
        $triggers = array();

        // Verify if website's id exists
        if ( $website_id ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_' . $website_id . '_load_triggers_website') ) {

                // Set triggers
                $triggers = md_the_cache('crm_chatbot_' . $website_id . '_load_triggers_website');

            } else {

                // Get website's triggers
                $the_triggers = $this->CI->base_model->the_data_where('crm_chatbot_websites_triggers', '*', array('website_id' => $website_id) );
                
                // Verify if the website has triggers
                if ( $the_triggers ) {

                    // Require the Triggers Functions Inc file
                    require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/triggers_functions.php';

                    // List the triggers
                    foreach ( $the_triggers as $the_trigger ) {

                        // Verify if events exists
                        if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') ) {

                            // List all events
                            foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') as $filename) {
                                
                                // Set class name
                                $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/', '.php'), '', $filename);

                                // Create an array
                                $array = array(
                                    'CmsBase',
                                    'User',
                                    'Apps',
                                    'Collection',
                                    'Crm_chatbot',
                                    'Events',
                                    ucfirst($class_name)
                                );

                                // Implode the array above
                                $cl = implode('\\', $array);

                                // Get the trigger's data
                                $the_data = (new $cl())->the_data(array(
                                    'trigger' => $the_trigger['trigger_id']
                                ));
                                
                                // Verify if data exists
                                if ( $the_data ) {

                                    // Set trigger
                                    $triggers[] = $the_data;

                                }
                                
                            }

                        }

                    }

                }

                // Save cache
                md_create_cache('crm_chatbot_' . $website_id . '_load_triggers_website', $triggers);

                // Set saved cronology
                update_crm_cache_cronology_for_user($website_id, 'crm_chatbot_triggers_chat', 'crm_chatbot_' . $website_id . '_load_triggers_website');

            }

        }

        // Default button's icon
        $default_button_icon = the_crm_chatbot_websites_meta($website_id, 'icon_button')?'<img src="' . the_crm_chatbot_websites_meta($website_id, 'icon_button') . '" alt="Chat Button">':'<img src="' . base_url('assets/base/user/apps/collection/crm-chatbot/img/chat-button-1.png') . '" alt="Chat Button">';

        // Welcome message
        $welcome_message = '';

        // Verify if the welcome message is enabled
        if ( the_crm_chatbot_websites_meta($website_id, 'show_welcome') && !the_crm_chatbot_websites_meta($website_id, 'show_chat') ) {

            // Set the message title
            $messgage_title = the_crm_chatbot_websites_meta($website_id, 'welcome_message_title')?'<h5>' . the_crm_chatbot_websites_meta($website_id, 'welcome_message_title') . '</h5>':'';
            
            // Set the message body
            $messgage_body = the_crm_chatbot_websites_meta($website_id, 'welcome_message_content')?'<p>' . the_crm_chatbot_websites_meta($website_id, 'welcome_message_content') . '</p>':'';

            // Get the message's bot if exists
            $bot = the_crm_chatbot_websites_meta($website_id, 'welcome_message_bot');

            // Set welcome message
            $welcome_message = '<div class="crm-chatbot-chat-style-welcome-message">'
                . '<button type="button" id="crm-chatbot-chat-style-welcome-message-hide-btn">'
                    . '&#10006;'
                . '</button>'
                . $messgage_title
                . $messgage_body
                . '<div class="crm-chatbot-chat-style-welcome-message-bot">'
                . '</div>'
            . '</div>';

        }

        // Set chat open
        $chat_open = the_crm_chatbot_websites_meta($website_id, 'show_chat')?' crm-chatbot-chat-open':'';

        return array(
            'style_slug' => 'default_style',
            'style_name' => $this->CI->lang->line('crm_chatbot_default'),
            'style_button_icon' => base_url('assets/base/user/apps/collection/crm-chatbot/img/chat-button-1.png'),
            'style_button_color' => $button_background_color,
            'style_css' => array(
                'url' => base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/chat-style-1.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION),
                'code' => '<style>'
                    . '@import url("//fonts.googleapis.com/css2?family=Noto+Sans&display=swap");'
                    . '.crm-chatbot-chat-style {'
                        . 'position: fixed !important;'
                        . 'right: 20px !important;'
                        . 'bottom: 20px !important;'
                        . 'z-index: 9999;'
                    . '}'                       
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message {'
                        . 'position: absolute !important;'
                        . 'right: 0 !important;'
                        . 'bottom: 72px;'
                        . 'padding: 10px !important;'
                        . 'width: 250px !important;'
                        . 'max-height: 0;'
                        . 'border-radius: 4px !important;'
                        . 'background-color: #FFFFFF !important;'
                        . 'box-shadow: 0 3px 4px 0 rgba(176, 183, 193, 0.35) !important;'
                        . 'transition-property: all;'
                        . 'transition-duration: .5s;'
                        . 'transition-timing-function: cubic-bezier(0, 1, 0.5, 1);'    
                        . 'animation-name: crm-chatbot-chat-style-show-welcome-message;'
                        . 'animation-iteration-count: 1;'
                        . 'animation-timing-function: ease-in;'
                        . 'animation-duration: 1s;'
                        . 'animation-fill-mode: forwards;'
                        . 'overflow-y: hidden;'                     
                    . '}'
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot > .crm-chatbot-message {'
                        . 'margin: 10px 0 0 !important;'
                        . 'padding: 0 !important;'                     
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot > .crm-chatbot-message img,
                    .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot > .crm-chatbot-message video {'
                        . 'max-width: 100% !important;'                     
                    . '}'                         
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot > .crm-chatbot-message > p {'
                        . 'padding: 0 !important;'
                        . 'line-height: 25px !important;'
                        . 'font-size: 12px !important;'
                        . 'color: #404756 !important;'                    
                    . '}'                                                
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-links {'
                        . 'margin: 10px 0 0 !important;'
                        . 'padding: 0 !important;'                     
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-links li {'
                        . 'display: inline-block !important;'  
                        . 'margin: 0 10px 6px 0 !important;'                              
                        . 'list-style: none !important;'
                    . '}'  
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-links li a {'
                        . 'padding: 3px 10px !important;'
                        . 'border: 1px solid #404756 !important;'
                        . 'border-radius: 3px !important;'                            
                        . 'line-height: 30px !important;'
                        . 'text-decoration: none !important;'
                        . 'font-family: "Noto Sans", sans-serif !important;'
                        . 'font-size: 13px !important;'
                        . 'font-weight: 400 !important;'
                        . 'color: #404756 !important;'
                        . 'box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);'
                    . '}'   
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items {'
                        . 'margin: 10px 0 0 !important;'
                        . 'padding: 0 !important;'                     
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li {'
                        . 'margin: 0 !important;'  
                        . 'padding: 0 !important;'                              
                        . 'list-style: none !important;'
                    . '}'  
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a {'
                        . 'display: inline-table !important;'
                        . 'margin-bottom: 10px !important;'
                        . 'min-height: 40px !important;'
                        . 'border: 1px solid #404756 !important;'
                        . 'border-radius: 5px !important;'
                        . 'text-decoration: none !important;'
                        . 'color: #404756 !important;'
                        . 'background-color: #FFFFFF !important;'
                    . '}'          
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a:hover, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a:focus, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a:active {'
                        . 'outline: 0 !important;'
                        . 'opacity: 1 !important;'
                    . '}'                                 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item {'
                        . 'display: flex !important;'
                        . '-ms-flex-align: start !important;'
                        . 'align-items: flex-start !important;'
                        . 'padding: 10px !important;'
                    . '}'                          
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item img {'
                        . 'vertical-align: middle !important;'
                        . 'width: 40px !important;'
                        . 'height: 40px !important;'
                        . 'border-radius: 4px !important;'
                        . 'border-style: none !important;'
                        . 'object-fit: cover !important;'
                    . '}'    
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item .crm-chatbot-message-item-body {'
                        . '-ms-flex: 1 !important;'
                        . 'flex: 1 !important;'
                        . 'padding-left: 10px !important;'
                        . 'text-align: left !important;'
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item .crm-chatbot-message-item-body h5 {'
                        . 'margin: -5px 0 0 !important;'
                        . 'width: 155px !important;'
                        . 'white-space: nowrap !important;'
                        . 'line-height: 20px !important;'
                        . 'text-overflow: ellipsis !important;'
                        . 'font-size: 12px !important;'
                        . 'font-weight: 700 !important;'
                        . 'color: #404756 !important;'
                        . 'overflow: hidden !important;'
                    . '}'   
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item .crm-chatbot-message-item-body h6 {'
                        . 'margin: 0 !important;'
                        . 'width: 155px !important;'
                        . 'white-space: nowrap !important;'
                        . 'line-height: 20px !important;'
                        . 'text-overflow: ellipsis !important;'
                        . 'font-size: 13px !important;'
                        . 'color: #00C6BC !important;'
                        . 'overflow: hidden !important;'
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item .crm-chatbot-message-item-body p {'
                        . 'margin: 0 !important;'
                        . 'width: 155px !important;'
                        . 'white-space: nowrap !important;'
                        . 'line-height: 20px !important;'
                        . 'text-overflow: ellipsis !important;'
                        . 'font-size: 12px !important;'
                        . 'color: #404756 !important;'
                        . 'overflow: hidden !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item.crm-chatbot-message-item-no-image .crm-chatbot-message-item-body {'
                        . 'padding-left: 0 !important;'
                    . '}'  
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item.crm-chatbot-message-item-no-image .crm-chatbot-message-item-body h5,
                    .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-items li a .crm-chatbot-message-item.crm-chatbot-message-item-no-image .crm-chatbot-message-item-body p {'
                        . 'width: 205px !important;'
                    . '}'   
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel {'
                        . 'padding: 0 !important;'                     
                        . 'border-radius: 4px !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel img {'
                        . 'width: 260px !important;'                     
                        . 'height: 150px !important;'
                        . 'border-radius: 4px !important;'
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-prev, 
                    .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-next {'
                        . 'display: block !important;'  
                        . 'position: absolute !important;'                   
                        . 'z-index: 1000 !important;'
                        . 'top: 55px !important;'
                        . 'padding: 4% 3% !important;'
                        . 'width: auto !important;'
                        . 'height: auto !important;'
                        . 'text-decoration: none !important;'
                        . 'font-size: 20px !important;'
                        . 'color: #FFFFFF !important;'
                        . 'opacity: 0.7 !important;'
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-prev:hover, 
                    .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-next:hover {'
                        . 'text-decoration: none !important;'
                        . 'opacity: 1 !important;'
                    . '}'     
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-prev {'
                        . 'left: 5px !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-prev span {'
                        . 'display: block !important;'
                        . 'pointer-events: none;'
                    . '}'                        
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-navigation-next {'
                        . 'right: 5px !important;'
                        . 'left: inherit !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-collection {'
                        . 'position: relative !important;'
                        . 'border-radius: 4px !important;'
                        . 'overflow: hidden !important;'
                    . '}'  
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-collection ul {'
                        . 'position: relative !important;'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'
                        . 'height: auto !important;'
                        . 'list-style: none !important;'                          
                    . '}'                        
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message-bot .crm-chatbot-message-carousel .crm-chatbot-message-carousel-collection ul li {'
                        . 'display: block !important;'
                        . 'position: relative !important;'
                        . 'float: left !important;'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'
                        . 'width: 230px !important;'
                        . 'height: auto !important;'
                    . '}'                                                                                                                                                                                                                                                                                                                                                                                                                                     
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message button {'
                        . 'position: absolute !important;'
                        . 'top: 5px !important;'
                        . 'right: 6px !important;'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'                            
                        . 'width: 16px !important;'
                        . 'height: 16px !important;'
                        . 'border: 0 !important;'
                        . 'border-radius: 50% !important;'
                        . 'text-align: center !important;'
                        . 'font-size: 12px;'                          
                        . 'background-color: transparent !important;'
                        . 'box-shadow: none !important;'
                        . 'transition: .1s all linear !important;'
                        . 'animation-name: crm-chatbot-chat-style-open-chat-btn;'
                        . 'animation-iteration-count: 1;'
                        . 'animation-timing-function: ease-in;'
                        . 'animation-duration: 0.3s;'
                        . 'opacity: 0.4 !important;'
                    . '}'                
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message button:hover, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message button:focus, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message button:active {'
                        . 'outline: 0 !important;'
                        . 'opacity: 1 !important;'
                    . '}'      
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message h5 {'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'
                        . 'line-height: 30px !important;'
                        . 'font-family: "Noto Sans", sans-serif !important;'
                        . 'font-size: 13px !important;'
                        . 'font-weight: 700 !important;'
                        . 'color: #404756 !important;'
                    . '}'                         
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message p {'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'
                        . 'line-height: 25px !important;'
                        . 'font-family: "Noto Sans", sans-serif !important;'
                        . 'font-size: 13px !important;'
                        . 'font-weight: 400 !important;'
                        . 'color: #404756 !important;'
                    . '}'  
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message p a {'
                        . 'text-decoration: underline !important;'
                        . 'color: #404756 !important;'
                    . '}' 
                    . '.crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message p a:hover, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message p a:focus, .crm-chatbot-chat-style-top .crm-chatbot-chat-style-welcome-message p a:active {'
                        . 'text-decoration: none !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-body .crm-chatbot-chat-style-body-chat-box {'
                        . 'position: fixed !important;'  
                        . 'bottom: 100px !important;' 
                        . 'right: 20px !important;'                                                                                  
                        . 'width: 330px !important;'
                        . 'height: 0 !important;'
                        . 'min-width: 330px !important;'
                        . 'max-width: 330px !important;'
                        . 'border: 0 !important;'
                        . 'border-radius: 9px !important;' 
                        . 'background-color: #FFFFFF !important;'
                        . 'box-shadow: 0 5px 40px rgba(0, 0, 0, 0.15) !important;'
                        . 'transition: height 0.3s ease-out !important;'   
                        . 'overflow: hidden !important;'                                                                             
                    . '}' 
                    . '.crm-chatbot-chat-style.crm-chatbot-chat-open .crm-chatbot-chat-style-body .crm-chatbot-chat-style-body-chat-box {'
                        . 'height: 450px !important;'
                    . '}'
                    . '.crm-chatbot-chat-style-body .crm-chatbot-chat-style-body-chat-box iframe {'
                        . 'vertical-align: bottom !important;'
                        . 'width: 100% !important;'
                        . 'height: 450px !important;'
                        . 'border: 0 !important;'
                        . 'border-radius: 9px !important;'                                                           
                    . '}'    
                    . '.crm-chatbot-chat-style-footer .crm-chatbot-chat-style-open-chat-btn {'
                        . 'display: flex !important;'
                        . 'justify-content: center !important;'
                        . 'margin: 0 !important;'
                        . 'padding: 0 !important;'                            
                        . 'width: 60px !important;'
                        . 'height: 60px !important;'
                        . 'border: 0 !important;'
                        . 'border-radius: 50% !important;'
                        . 'text-align: center !important;'                            
                        . 'background-color: ' . $button_background_color . ' !important;'
                        . $button_shadow
                        . 'transition: .1s all linear !important;'
                        . 'animation-name: crm-chatbot-chat-style-open-chat-btn;'
                        . 'animation-iteration-count: 1;'
                        . 'animation-timing-function: ease-in;'
                        . 'animation-duration: 0.3s;'
                    . '}'
                    . '.crm-chatbot-chat-style-footer .crm-chatbot-chat-style-open-chat-btn:hover, .crm-chatbot-chat-style-open-chat-btn:focus, .crm-chatbot-chat-style-open-chat-btn:active {'
                        . 'transform: scale(1.05) !important;'
                        . 'outline: 0 !important;'
                    . '}'                                             
                    . '.crm-chatbot-chat-style-footer .crm-chatbot-chat-style-open-chat-btn img {'
                        . 'position: absolute !important;'
                        . 'top: 50% !important;'
                        . 'left: 50% !important;'
                        . 'margin: 0 !important;'
                        . 'width: auto !important;'
                        . 'height: auto !important;'
                        . 'min-width: auto !important;'
                        . 'min-height: auto !important;'
                        . 'max-width: 100% !important;'
                        . 'max-height: 60px !important;'
                        . 'transform: translate(-50%, -50%) !important;'
                        . 'pointer-events: none !important;'                                              
                    . '}'                        
                    . '@keyframes crm-chatbot-chat-style-open-chat-btn {'
                        . '0% {'
                            . 'transform: scale(0.70);'
                        . '}'
                        . '80% {'
                            . 'transform: scale(1.05);'
                        . '}'
                        . '100% {'
                            . 'transform: scale(1.00);'
                        . '}'
                    . '}'
                    . '@keyframes crm-chatbot-chat-style-show-welcome-message {'
                        . '0% {'
                            . 'max-height: 0;'
                            . 'opacity: 0;'
                        . '}'
                        . '94% {'
                            . 'max-height: 0;'
                            . 'opacity: 0;'
                        . '}'                                                        
                        . '95% {'
                            . 'max-height: 0;'
                            . 'opacity: 8;'
                        . '}'
                        . '100% {'
                            . 'max-height: 500px;'
                            . 'opacity: 1;'
                        . '}'
                    . '}'                                                           
                . '</style>'
            ),
            'style_html' => '<div class="crm-chatbot-chat-style' . $chat_open . '">'
                . '<div class="crm-chatbot-chat-style-top">'
                    . nl2br($welcome_message)
                . '</div>'
                . '<div class="crm-chatbot-chat-style-body">'
                    . '<div class="crm-chatbot-chat-style-body-chat-box">'
                    . '</div>'
                . '</div>'
                . '<div class="crm-chatbot-chat-style-footer">'
                    . '<button class="crm-chatbot-chat-style-open-chat-btn">'
                        . $default_button_icon
                    . '</button>'
                . '</div>'                     
            . '</div>',
            'triggers' => $triggers
        );
        
    }

}

/* End of file default_style.php */