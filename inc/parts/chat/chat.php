<?php
/**
 * Chat Part Inc
 *
 * This file contains some functions used
 * in the chat
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('crm_chatbot_get_chat_from_parts') ) {
    
    /**
     * The function crm_chatbot_get_chat_from_parts shows the chat
     * 
     * @return void
     */
    function crm_chatbot_get_chat_from_parts() {

        // Assign the CodeIgniter super-object
        $CI =& get_instance();

        // Verify if get_chat parameter exists
        if ( is_numeric($CI->input->get('get_chat', TRUE)) && $CI->input->get('guest', TRUE) ) {

            // Get the website
            $the_website = $CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $CI->input->get('get_chat', TRUE)
                )
            );

            // Verify if the website exists
            if ( $the_website ) {

                // Require the Chat Styles Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/chat_styles.php';

                // Require the Website Functions Inc file
                require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

                // Get all styles
                $the_chat_style = the_crm_chatbot_chat_styles($CI->input->get('get_chat', TRUE));

                // Verify if chat style exists
                if ( !$the_chat_style ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $CI->lang->line('crm_chatbot_no_chat_style')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();

                }

                // Verify if style's url exists
                if ( empty($the_chat_style['style_css']['url']) ) {

                    // Display the error message
                    echo '<p>'
                        . $CI->lang->line('crm_chatbot_no_chat_css_file')
                    . '</p>';
                    exit();   
                    
                }

                // Verify if the website session already exists
                if ( $CI->session->userdata('crm_chatbot_website_session') ) {

                    // Remove the website session
                    $CI->session->unset_userdata('crm_chatbot_website_session');

                }

                // Register the website session
                $CI->session->set_userdata('crm_chatbot_website_session', $CI->input->get('get_chat', TRUE));

                // Verify if a message should be sent
                if ( is_numeric($CI->input->get('trigger', TRUE)) && ($CI->input->get('trigger_parent', TRUE) === 'send_message') && ($CI->input->get('trigger_name', TRUE) === 'message') ) {

                    // Require the Send Message Part Inc file
                    require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/parts/message/send.php';

                    // Send message
                    crm_chatbot_send_message_from_parts($the_website[0]);

                }

                // Views params
                $views_params = array(
                    'website_id' => $CI->input->get('get_chat', TRUE),
                    'website' => $the_website[0],
                    'chat_css_url' => $the_chat_style['style_css']['url'],
                    'guest_data' => 0,
                    'bot' => array(
                        'bot_name' => the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'bot_name'):$CI->lang->line('crm_chatbot_bot'),
                        'bot_photo' => the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                    )
                );

                // Verify if the cache exists for this query
                if ( md_the_cache('crm_chatbot_user_' . $the_website[0]['user_id'] . '_website_guest_' . $CI->input->get('guest', TRUE)) ) {

                    // Set the cache
                    $the_guest = md_the_cache('crm_chatbot_user_' . $the_website[0]['user_id'] . '_website_guest_' . $CI->input->get('guest', TRUE));

                } else {

                    // Get the guest
                    $the_guest = $CI->base_model->the_data_where(
                        'crm_chatbot_websites_guests',
                        '*',
                        array(
                            'user_id' => $the_website[0]['user_id'],
                            'id' => $CI->input->get('guest', TRUE)
                        )
                    );

                    // Save cache
                    md_create_cache('crm_chatbot_user_' . $the_website[0]['user_id'] . '_website_guest_' . $CI->input->get('guest', TRUE), $the_guest);

                    // Set saved cronology
                    update_crm_cache_cronology_for_user($the_website[0]['user_id'], 'crm_chatbot_websites_list', 'crm_chatbot_user_' . $the_website[0]['user_id'] . '_website_guest_' . $CI->input->get('guest', TRUE));

                }

                // Verify if the guest exists
                if ( $the_guest ) {

                    // Verify if the guest data is saved
                    if ( the_crm_chatbot_websites_guests_meta($the_guest[0]['guest_id'], 'guest_name') || the_crm_chatbot_websites_guests_meta($the_guest[0]['guest_id'], 'guest_email') || the_crm_chatbot_websites_guests_meta($the_guest[0]['guest_id'], 'guest_phone') ) {

                        // Set guest data
                        $views_params['guest_data'] = 1;

                    }

                    // Verify if the gdrp is approved
                    if ( the_crm_chatbot_websites_guests_meta($the_guest[0]['guest_id'], 'accept_gdrp') ) {

                        // Set gdrp
                        $views_params['accept_gdrp'] = 1;

                    }

                }
                
                // Get the assigned agent
                $the_assigned_agent = the_crm_chatbot_websites_meta($CI->input->get('get_chat', TRUE), 'member_agent');

                // Verify if the chat has assigned agent
                if ( $the_assigned_agent ) {



                } else {

                    // Verify if the agent was active in the last 5 minutes
                    if ( the_crm_user_session($the_website[0]['user_id']) ) {

                        if ( the_crm_user_session($the_website[0]['user_id']) > (time() - 300) ) {

                            // Get the user's information
                            $the_user_data = $CI->base_model->the_data_where(
                                'users',
                                'users.*, medias.body',
                                array(
                                    'users.user_id' => $the_website[0]['user_id']
                                ),
                                array(),
                                array(),
                                array(array(
                                    'table' => 'users_meta',
                                    'condition' => "users.user_id=users_meta.user_id AND users_meta.meta_name='profile_image'",
                                    'join_from' => 'LEFT'
                                ), array(
                                    'table' => 'medias',
                                    'condition' => "users_meta.meta_value=medias.media_id",
                                    'join_from' => 'LEFT'
                                ))
                            );

                            // Verify if user's data exists
                            if ( $the_user_data ) {

                                // Set as agent
                                $views_params['agent'] = array(
                                    'agent_name' => $the_user_data[0]['first_name']?$the_user_data[0]['first_name'] . ' ' . $the_user_data[0]['last_name']:$the_user_data[0]['username'],
                                    'agent_photo' => !empty($the_user_data[0]['body'])?$the_user_data[0]['body']:base_url('assets/img/avatar-placeholder.png')
                                );
                                
                            }

                        }

                    }

                }

                // Verify if agent missing
                if ( empty($views_params['agent']) ) {

                    // Verify if chat mode is enabled
                    if ( the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'chat_mode') ) {

                        // Set as agent
                        $views_params['agent'] = array(
                            'agent_name' => the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'agent_name')?the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'agent_name'):$CI->lang->line('crm_chatbot_agent'),
                            'agent_photo' => the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'agent_photo')?the_crm_chatbot_websites_meta($the_website[0]['website_id'], 'agent_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/agent-photo.png'),
                            'agent_is_gone' => 1
                        );                        

                    }

                }

                // Load the view
                $CI->load->ext_view(
                    CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/templates',
                    'chat',
                    $views_params
                );

            }

        }
        
    }

}