<?php
/**
 * User Controller
 *
 * This file loads the CRM Chatbot app in the user panel
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

/*
 * User class loads the CRM Chatbot app loader
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
 */
class User {
    
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
        
        // Get codeigniter object instance
        $this->CI =& get_instance();
        
        // Load language
        $this->CI->lang->load( 'crm_chatbot_user', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );
        
    }
    
    /**
     * The public method view loads the app's template
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function view() {

        // Set the page's title
        set_the_title($this->CI->lang->line('crm_chatbot'));

        // Set the CRM Chatbot Main's Styles
        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/main.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

        // Set the Default Quick Guide Js
        set_js_urls(array(base_url('assets/base/user/default/js/libs/texts/quick-guide.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));        

        // Verify if is a page
        if ( $this->CI->input->get('p', TRUE) && ($this->CI->input->get('p', TRUE) !== 'threads') ) {

            switch ( $this->CI->input->get('p', TRUE) ) {

                case 'favorites':

                    // Set the CRM Chatbot Favorites Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/favorites.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                            'favorites',
                            array(
                                'important' => $this->the_important_threads()
                            ),
                            true
                        )
                    ); 

                    break;

                case 'important':

                    // Set the CRM Chatbot Important Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/important.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                            'important',
                            array(
                                'important' => $this->the_important_threads()
                            ),
                            true
                        )
                    ); 

                    break;

                case 'numbers':

                    // Set the CRM Chatbot Numbers Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/numbers.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                            'numbers',
                            array(
                                'important' => $this->the_important_threads()
                            ),
                            true
                        )
                    ); 

                    break;

                case 'emails':

                    // Set the CRM Chatbot Emails Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/emails.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                            'emails',
                            array(
                                'important' => $this->the_important_threads()
                            ),
                            true
                        )
                    ); 

                    break;

                case 'guests':

                    // Verify if the guest parameter exists
                    if ( $this->CI->input->get('guest', TRUE) ) {

                        // Get the guest
                        $the_guest = $this->CI->base_model->the_data_where(
                            'crm_chatbot_websites_guests',
                            '*',
                            array(
                                'guest_id' => $this->CI->input->get('guest', TRUE),
                                'user_id' => md_the_user_id()
                            )
                        );

                        // Verify if the guest exists
                        if ( $the_guest ) {

                            // Set the CRM Chatbot Guest's Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/guest.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));     

                            // Set the CRM Guest's Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/guest.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));               

                            // Require the Website Functions Inc file
                            require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                            // Params container
                            $params = array(
                                'guest_id' => $the_guest[0]['guest_id'],
                                'important' => $this->the_important_threads()
                            );

                            // Get the guest's categories
                            $categories = $this->CI->base_model->the_data_where(
                                'crm_chatbot_websites_guests_categories',
                                'crm_chatbot_categories.*',
                                array(
                                    'crm_chatbot_websites_guests_categories.guest_id' => $the_guest[0]['guest_id']
                                ),
                                array(),
                                array(),
                                array(array(
                                    'table' => 'crm_chatbot_categories',
                                    'condition' => 'crm_chatbot_websites_guests_categories.category_id=crm_chatbot_categories.category_id',
                                    'join_from' => 'LEFT'
                                ))
                            );

                            // Verify if categories exists
                            if ( $categories ) {

                                // Set categories
                                $params['categories'] = $categories;

                            }

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'guest',
                                    $params,
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }

                    } else {

                        // Set the CRM Chatbot Guests Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/guests.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'guests',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                case 'websites':

                    // Verify if the new parameter exists
                    if ( $this->CI->input->get('new', TRUE) ) {

                        // Set the CRM Chatbot Website Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/website.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the CRM Chatbot Website Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/website.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'new_website',
                                array(),
                                true
                            )
                        );

                    } else if ( $this->CI->input->get('website_info', TRUE) ) {
                        
                        // Get the website
                        $the_website = $this->CI->base_model->the_data_where(
                            'crm_chatbot_websites',
                            'crm_chatbot_websites.*, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
                            users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
                            array(
                                'crm_chatbot_websites.website_id' => $this->CI->input->get('website_info', TRUE),
                                'crm_chatbot_websites.user_id' => md_the_user_id()
                            ),
                            array(),
                            array(),
                            array(
                                array(
                                    'table' => 'teams',
                                    'condition' => "crm_chatbot_websites.member_id=teams.member_id",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta first_name',
                                    'condition' => "crm_chatbot_websites.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta last_name',
                                    'condition' => "crm_chatbot_websites.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'users',
                                    'condition' => "crm_chatbot_websites.user_id=users.user_id",
                                    'join_from' => 'LEFT'
                                )
                            )
                        );

                        // Verify if the website exists
                        if ( $the_website ) {

                            // Get team's member
                            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

                            // Verify if member exists
                            if ( $this->CI->session->userdata( 'member' ) ) {

                                // Verify if member's role exists
                                if ( isset($member['role_id']) ) {

                                    // Verify if the website is allowed
                                    if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website[0]['website']) ) {

                                        // Display 404 page
                                        show_404(); 
                                        
                                    }

                                }

                            }

                            // Set the CRM Chatbot Website Info Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/website-info.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                            // Params for view
                            $params = array(
                                'website_id' => $the_website[0]['website_id'],
                                'website' => $the_website[0],
                                'status' => $the_website[0]['status'],
                                'created' => md_the_user_time(md_the_user_id(), $the_website[0]['created'])
                            );

                            // Set the author
                            $params['author'] = array (
                                'member_id' => !empty($the_website[0]['member_id'])?$the_website[0]['member_id']:$the_website[0]['user_id'],
                                'member_username' => !empty($the_website[0]['member_id'])?$the_website[0]['member_username']:$the_website[0]['user_username'],
                                'first_name' => !empty($the_website[0]['member_id'])?$the_website[0]['member_first_name']:$the_website[0]['user_first_name'],
                                'last_name' => !empty($the_website[0]['member_id'])?$the_website[0]['member_last_name']:$the_website[0]['user_last_name'],
                                'team_member' => !empty($the_website[0]['member_id'])?true:false
                            );

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'website_info',
                                    $params,
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }                        
                        
                    } else if ( $this->CI->input->get('website', TRUE) ) {

                        // Require the Chat Styles Inc file
                        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                        // Require the Website Functions Inc file
                        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                        // Get the website
                        $the_website = $this->CI->base_model->the_data_where(
                            'crm_chatbot_websites',
                            '*',
                            array(
                                'website_id' => $this->CI->input->get('website', TRUE),
                                'user_id' => md_the_user_id()
                            )
                        );

                        // Verify if the website exists
                        if ( $the_website ) {

                            // Get team's member
                            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

                            // Verify if member exists
                            if ( $this->CI->session->userdata( 'member' ) ) {

                                // Verify if member's role exists
                                if ( isset($member['role_id']) ) {

                                    // Verify if the website is allowed
                                    if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website[0]['website']) ) {

                                        // Display 404 page
                                        show_404(); 
                                        
                                    }

                                }

                            }

                            // Set the Font Awesome Styles
                            set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', 'text/css', 'all'));

                            // Set the CRM Minimal Text Editor Js
                            set_js_urls(array(base_url('assets/base/user/default/js/minimal-text-editor.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                            // Set the CRM Chatbot Website Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/website.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                            // Set the Default Upload Box Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all')); 

                            // Set the CRM Chatbot Website Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/website.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                            // Set the Default Upload Box Js
                            set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));  

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'website',
                                    array(
                                        'website_id' => $the_website[0]['website_id'],
                                        'website' => $the_website[0],
                                        'status' => $the_website[0]['status']
                                    ),
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }
                        
                    } else {

                        // Set the Default Upload Box Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the Default Upload Box Js
                        set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                        // Set the CRM Chatbot Websites Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/websites.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'websites',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                case 'bots':

                    // Verify if the new parameter exists
                    if ( $this->CI->input->get('new', TRUE) ) {

                        // Set the CRM Chatbot Bot Builder Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/bot-builder.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the Default Upload Box Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));                        

                        // Set the jquery.flowchart Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/flowchart/jquery.flowchart.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the Font Awesome Styles
                        set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', 'text/css', 'all'));

                        // Set the jQuery Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery.js')));  
                        
                        // Set the jQuery UI Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery-ui.js')));  

                        // Set the PanoZoom Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery.panzoom.min.js')));                      
                        
                        // Set the jquery.flowchart Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/flowchart/jquery.flowchart.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 
                        
                        // Set the CRM Chatbot Bot Builder Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/bot-builder.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                        // Set the CRM Minimal Text Editor Js
                        set_js_urls(array(base_url('assets/base/user/default/js/minimal-text-editor.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   
                        
                        // Set the Default Upload Box Js
                        set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));                           

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'new_bot',
                                array(),
                                true
                            )
                        ); 

                    } else if ( $this->CI->input->get('bot_info', TRUE) ) {
                        
                        // Get the bot
                        $the_bot = $this->CI->base_model->the_data_where(
                            'crm_chatbot_bots',
                            'crm_chatbot_bots.*, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
                            users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
                            array(
                                'crm_chatbot_bots.bot_id' => $this->CI->input->get('bot_info', TRUE),
                                'crm_chatbot_bots.user_id' => md_the_user_id()
                            ),
                            array(),
                            array(),
                            array(
                                array(
                                    'table' => 'teams',
                                    'condition' => "crm_chatbot_bots.member_id=teams.member_id",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta first_name',
                                    'condition' => "crm_chatbot_bots.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta last_name',
                                    'condition' => "crm_chatbot_bots.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'users',
                                    'condition' => "crm_chatbot_bots.user_id=users.user_id",
                                    'join_from' => 'LEFT'
                                )
                            )
                        );

                        // Verify if the bot exists
                        if ( $the_bot ) {

                            // Set the CRM Chatbot Bot Info Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/bot-info.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                            // Params for view
                            $params = array(
                                'bot_id' => $the_bot[0]['bot_id'],
                                'bot' => $the_bot[0],
                                'status' => $the_bot[0]['status'],
                                'created' => md_the_user_time(md_the_user_id(), $the_bot[0]['created'])
                            );

                            // Set the author
                            $params['author'] = array (
                                'member_id' => !empty($the_bot[0]['member_id'])?$the_bot[0]['member_id']:$the_bot[0]['user_id'],
                                'member_username' => !empty($the_bot[0]['member_id'])?$the_bot[0]['member_username']:$the_bot[0]['user_username'],
                                'first_name' => !empty($the_bot[0]['member_id'])?$the_bot[0]['member_first_name']:$the_bot[0]['user_first_name'],
                                'last_name' => !empty($the_bot[0]['member_id'])?$the_bot[0]['member_last_name']:$the_bot[0]['user_last_name'],
                                'team_member' => !empty($the_bot[0]['member_id'])?true:false
                            );

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'bot_info',
                                    $params,
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }                        
                        
                    } else if ( $this->CI->input->get('bot', TRUE) ) {

                        // Get the bot
                        $the_bot = $this->CI->base_model->the_data_where(
                            'crm_chatbot_bots',
                            '*',
                            array(
                                'bot_id' => $this->CI->input->get('bot', TRUE),
                                'user_id' => md_the_user_id()
                            )
                        );

                        // Verify if the bot exists
                        if ( $the_bot ) {

                            // Require the Bot Functions Inc file
                            require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/bot_functions.php';

                            // Set the CRM Chatbot Bot Builder Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/bot-builder.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                            // Set the Default Upload Box Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));                        

                            // Set the jquery.flowchart Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/flowchart/jquery.flowchart.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                            // Set the Font Awesome Styles
                            set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', 'text/css', 'all'));

                            // Set the jQuery Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery.js')));  
                            
                            // Set the jQuery UI Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery-ui.js')));  

                            // Set the PanoZoom Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/jquery.panzoom.min.js')));                      
                            
                            // Set the jquery.flowchart Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/flowchart/jquery.flowchart.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 
                            
                            // Set the CRM Chatbot Bot Builder Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/bot-builder.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                            // Set the CRM Minimal Text Editor Js
                            set_js_urls(array(base_url('assets/base/user/default/js/minimal-text-editor.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   
                            
                            // Set the Default Upload Box Js
                            set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));                           

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'bot',
                                    array(
                                        'bot_id' => $the_bot[0]['bot_id'],
                                        'bot_name' => $the_bot[0]['bot_name'],
                                        'status' => $the_bot[0]['status']
                                    ),
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }
                        
                    } else {

                        // Set the CRM Chatbot Bots Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/bots.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the CRM Chatbot Bots Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/bots.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'bots',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                case 'quick_replies':

                    // Verify if the new parameter exists
                    if ( $this->CI->input->get('new', TRUE) ) {

                        // Set the Font Awesome Styles
                        set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', 'text/css', 'all'));

                        // Set the CRM Minimal Text Editor Js
                        set_js_urls(array(base_url('assets/base/user/default/js/minimal-text-editor.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                        // Set the CRM Chatbot Quick Replies Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/quick-reply.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the CRM Chatbot Quick Reply Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/quick-reply.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'new_quick_reply',
                                array(),
                                true
                            )
                        );
                        
                    } else if ( $this->CI->input->get('reply_info', TRUE) ) {
                        
                        // Get the reply
                        $the_reply = $this->CI->base_model->the_data_where(
                            'crm_chatbot_quick_replies',
                            'crm_chatbot_quick_replies.*, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
                            users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
                            array(
                                'crm_chatbot_quick_replies.reply_id' => $this->CI->input->get('reply_info', TRUE),
                                'crm_chatbot_quick_replies.user_id' => md_the_user_id()
                            ),
                            array(),
                            array(),
                            array(
                                array(
                                    'table' => 'teams',
                                    'condition' => "crm_chatbot_quick_replies.member_id=teams.member_id",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta first_name',
                                    'condition' => "crm_chatbot_quick_replies.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'teams_meta last_name',
                                    'condition' => "crm_chatbot_quick_replies.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'users',
                                    'condition' => "crm_chatbot_quick_replies.user_id=users.user_id",
                                    'join_from' => 'LEFT'
                                )
                            )
                        );

                        // Verify if the reply exists
                        if ( $the_reply ) {

                            // Get team's member
                            $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

                            // Verify if member exists
                            if ( $this->CI->session->userdata( 'member' ) ) {

                                // Verify if member's role exists
                                if ( isset($member['role_id']) ) {

                                    // Verify if the reply is allowed
                                    if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_replys', $the_reply[0]['reply']) ) {

                                        // Display 404 page
                                        show_404(); 
                                        
                                    }

                                }

                            }

                            // Set the CRM Chatbot Reply Info Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/reply-info.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                            // Params for view
                            $params = array(
                                'reply_id' => $the_reply[0]['reply_id'],
                                'reply' => $the_reply[0],
                                'status' => $the_reply[0]['status'],
                                'created' => md_the_user_time(md_the_user_id(), $the_reply[0]['created'])
                            );

                            // Set the author
                            $params['author'] = array (
                                'member_id' => !empty($the_reply[0]['member_id'])?$the_reply[0]['member_id']:$the_reply[0]['user_id'],
                                'member_username' => !empty($the_reply[0]['member_id'])?$the_reply[0]['member_username']:$the_reply[0]['user_username'],
                                'first_name' => !empty($the_reply[0]['member_id'])?$the_reply[0]['member_first_name']:$the_reply[0]['user_first_name'],
                                'last_name' => !empty($the_reply[0]['member_id'])?$the_reply[0]['member_last_name']:$the_reply[0]['user_last_name'],
                                'team_member' => !empty($the_reply[0]['member_id'])?true:false
                            );

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'reply_info',
                                    $params,
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }                        
                        
                    } else if ( $this->CI->input->get('reply', TRUE) ) {

                        // Get the reply
                        $the_reply = $this->CI->base_model->the_data_where(
                            'crm_chatbot_quick_replies',
                            '*',
                            array(
                                'reply_id' => $this->CI->input->get('reply', TRUE),
                                'user_id' => md_the_user_id()
                            )
                        );

                        // Verify if the reply exists
                        if ( $the_reply ) {

                            // Set the Font Awesome Styles
                            set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css', 'text/css', 'all'));

                            // Set the CRM Minimal Text Editor Js
                            set_js_urls(array(base_url('assets/base/user/default/js/minimal-text-editor.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                            // Set the CRM Chatbot Quick Replies Styles
                            set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/quick-reply.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                            // Set the CRM Chatbot Quick Reply Js
                            set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/quick-reply.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                            // Set views params
                            set_user_view(
                                $this->CI->load->ext_view(
                                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                    'quick_reply',
                                    array(
                                        'reply_id' => $the_reply[0]['reply_id'],
                                        'reply' => $the_reply[0],
                                        'status' => $the_reply[0]['status']
                                    ),
                                    true
                                )
                            ); 

                        } else {

                            // Display 404 page
                            show_404();

                        }
                        
                    } else {

                        // Set the Default Upload Box Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the CRM Chatbot Quick Replies Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/quick-replies.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));

                        // Set the Default Upload Box Js
                        set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                        // Set the CRM Chatbot Quick Reply Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/quick-replies.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION))); 

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'quick_replies',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                case 'overview':

                    // Verify if member exists
                    if ( $this->CI->session->userdata( 'member' ) ) {

                        // Display 404 page
                        show_404();

                    } else {

                        // Set the Theme Time Picker Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/calendars/css/time-picker.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all')); 

                        // Set the CRM Chatbot Overview Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/overview.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));   
                        
                        // Set the Default Minimal Calendar Js
                        set_js_urls(array(base_url('assets/base/user/default/js/libs/calendars/minimal-calendar.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));
                        
                        // Set the Apexcharts Js
                        set_js_urls(array('//cdn.jsdelivr.net/npm/apexcharts'));                       

                        // Set the CRM Chatbot Overview Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/overview.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'overview',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                case 'activities':

                    // Verify if member exists
                    if ( $this->CI->session->userdata( 'member' ) ) {

                        // Display 404 page
                        show_404();

                    } else {

                        // Set the Activities Styles
                        set_css_urls(array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/activities.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));                

                        // Set the CRM Chatbot Activities Js
                        set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/activities.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                        // Set views params
                        set_user_view(
                            $this->CI->load->ext_view(
                                CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                                'activities',
                                array(
                                    'important' => $this->the_important_threads()
                                ),
                                true
                            )
                        ); 

                    }

                    break;

                default:

                    // Display 404 page
                    show_404();

                    break;

            }

        } else {

            // Verify if the thread parameter exists
            if ( $this->CI->input->get('thread', TRUE) ) {

                // Get the thread
                $the_thread = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                    array(
                        'crm_chatbot_websites_threads.thread_id' => $this->CI->input->get('thread', TRUE),
                        'crm_chatbot_websites_threads.user_id' => md_the_user_id()
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'crm_chatbot_websites',
                        'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                        'join_from' => 'LEFT'
                    ))
                );

                // Verify if the thread exists
                if ( $the_thread ) {

                    // Set the CRM Chatbot Thread Styles
                    set_css_urls(array('stylesheet', base_url('assets/base/user/apps/collection/crm-chatbot/styles/css/thread.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));     

                    // Set the Default Upload Box's Styles
                    set_css_urls(array('stylesheet', base_url('assets/base/user/default/styles/libs/boxes/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all'));
                    
                    // Set the Theme Chat Styles
                    set_css_urls(array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/chat.css?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION), 'text/css', 'all')); 
    
                    // Set the EmojioneArea's Styles
                    set_css_urls(array('stylesheet', '//cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css', 'text/css', 'all'));                    

                    // Set the Default Upload Box's Js
                    set_js_urls(array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));

                    // Set the CRM Thread's Js
                    set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/thread.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));                    
                    
                    // Set the EmojioneArea's Js
                    set_js_urls(array('//cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js'));                    

                    // Require the Website Functions Inc file
                    require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                    // Get the alert by bot's ID
                    $the_alerts = $this->CI->base_model->the_data_where(
                        'notifications_alerts_fields',
                        'notifications_alerts_users.id, notifications_alerts_users.banner_seen',
                        array(
                            'notifications_alerts_fields.field_name' => 'crm_chatbot_thread_id',
                            'notifications_alerts_fields.field_value' => $this->CI->input->get('thread', TRUE)
                        ),
                        array(),
                        array(),
                        array(array(
                            'table' => 'notifications_alerts_users',
                            'condition' => 'notifications_alerts_fields.alert_id=notifications_alerts_users.alert_id',
                            'join_from' => 'LEFT'
                        ))
                    );

                    // Verify if alerts exists
                    if ( $the_alerts ) {

                        // List alerts
                        foreach ( $the_alerts as $the_alert ) {

                            // Verify if the banner is unseen
                            if ( empty($the_alert['banner_seen']) ) {

                                // Mark the banner as seen
                                $this->CI->base_model->update(
                                    'notifications_alerts_users',
                                    array(
                                        'id' => $the_alert['id']
                                    ), array(
                                        'banner_seen' => 1
                                    )
                                );

                            }

                        }

                    }

                    // Set views params
                    set_user_view(
                        $this->CI->load->ext_view(
                            CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                            'thread',
                            array(
                                'guest_id' => $the_thread[0]['guest_id'],
                                'thread' => $the_thread[0],
                                'important' => $this->the_important_threads()
                            ),
                            true
                        )
                    ); 

                } else {

                    // Display 404 page
                    show_404();

                }

            } else {
                
                // Set the CRM Chatbot Threads Js
                set_js_urls(array(base_url('assets/base/user/apps/collection/crm-chatbot/js/threads.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION)));   

                // Set views params
                set_user_view(
                    $this->CI->load->ext_view(
                        CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                        'main',
                        array(
                            'important' => $this->the_important_threads()
                        ),
                        true
                    )
                ); 

            }

        }
        
    }

    /**
     * The protected method the_important_threads provides the number of important threads
     * 
     * @since 0.0.8.5
     * 
     * @return integer with number of threads
     */
    protected function the_important_threads() {

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_important_threads_number') ) {

            // Set the cache
            $the_threads = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_important_threads_number');

        } else {

            // Get the threads threads
            $the_threads = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_threads',
                'COUNT(thread_id) AS total',
                array(
                    'user_id' => md_the_user_id(),
                    'important >' => 0
                )
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_important_threads_number', $the_threads);

            // Set saved cronology
            update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . md_the_user_id() . '_important_threads_number');

        }

        return $the_threads?$the_threads[0]['total']:0;
        
    }
    
}

/* End of file user.php */