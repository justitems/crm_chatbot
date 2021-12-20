<?php
/**
 * Email Templates Inc
 *
 * PHP Version 7.4
 *
 * This files contains the the email templates
 * for CRM Chatbot
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Register email template
set_admin_notifications_email_template(
    'crm_chatbot_new_thread_notification',
    array(
        'template_name' => get_instance()->lang->line('crm_chatbot_new_thread_notification'),
        'template_placeholders' => array(
            array(
                'code' => '[first_name]',
                'description' => get_instance()->lang->line('crm_chatbot_first_name_placeholder_description')
            ),    
            array(
                'code' => '[last_name]',
                'description' => get_instance()->lang->line('crm_chatbot_last_name_placeholder_description')
            ),   
            array(
                'code' => '[thread_id]',
                'description' => get_instance()->lang->line('crm_chatbot_thread_id_placeholder_description')
            ),                             
            array(
                'code' => '[thread_link]',
                'description' => get_instance()->lang->line('crm_chatbot_thread_link_placeholder_description')
            ),            
            array(
                'code' => '[website_url]',
                'description' => get_instance()->lang->line('crm_chatbot_website_url_placeholder_description')
            ),
            array(
                'code' => '[website_name]',
                'description' => get_instance()->lang->line('crm_chatbot_website_name_placeholder_description')
            )

        )

    )

);

// Register email template
set_admin_notifications_email_template(
    'crm_chatbot_new_message_notification',
    array(
        'template_name' => get_instance()->lang->line('crm_chatbot_new_message_notification'),
        'template_placeholders' => array(
            array(
                'code' => '[first_name]',
                'description' => get_instance()->lang->line('crm_chatbot_first_name_placeholder_description')
            ),    
            array(
                'code' => '[last_name]',
                'description' => get_instance()->lang->line('crm_chatbot_last_name_placeholder_description')
            ),   
            array(
                'code' => '[thread_id]',
                'description' => get_instance()->lang->line('crm_chatbot_thread_id_placeholder_description')
            ),                             
            array(
                'code' => '[thread_link]',
                'description' => get_instance()->lang->line('crm_chatbot_thread_link_placeholder_description')
            ),            
            array(
                'code' => '[website_url]',
                'description' => get_instance()->lang->line('crm_chatbot_website_url_placeholder_description')
            ),
            array(
                'code' => '[website_name]',
                'description' => get_instance()->lang->line('crm_chatbot_website_name_placeholder_description')
            )

        )

    )

);

/* End of file email_templates.php */