<?php
/**
 * Plans Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks to 
 * display the CRM Chatbot app in the plans pages.
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * The public md_set_plans_options registers the dashboard plans options
 * 
 * @since 0.0.8.3
 */
md_set_plans_options(

    array(
        'name' => $this->lang->line('crm_chatbot'),
        'icon' => md_the_admin_icon(array('icon' => 'chat_small')),
        'slug' => 'crm_chatbot',
        'fields' => array(
            array (
                'field_type' => 'checkbox',
                'field_slug' => 'app_crm_chatbot_enabled',
                'field_words' => array(
                    'field_title' => $this->lang->line('crm_chatbot_enable_app'),
                    'field_description' => $this->lang->line('crm_chatbot_if_is_enabled_plan')
                ),
                'field_params' => array(
                    'checked' => md_the_plan_feature('app_crm_chatbot_enabled', $this->input->get('plan_id'))?1:0
                )

            ),
            array(
                'field_slug' => 'app_crm_chatbot_allowed_websites',
                'field_type' => 'text',
                'field_words' => array(
                    'field_title' => $this->lang->line('crm_chatbot_allowed_websites'),
                    'field_description' => $this->lang->line('crm_chatbot_allowed_websites_description')
                ),
                'field_params' => array(
                    'placeholder' => $this->lang->line('crm_chatbot_enter_allowed_websites'),
                    'value' => md_the_plan_feature('app_crm_chatbot_allowed_websites', $this->input->get('plan_id'))?md_the_plan_feature('app_crm_chatbot_allowed_websites', $this->input->get('plan_id')):0,
                    'disabled' => false
                )

            ),
            array(
                'field_slug' => 'app_crm_chatbot_allowed_automatic_replies',
                'field_type' => 'text',
                'field_words' => array(
                    'field_title' => $this->lang->line('crm_chatbot_allowed_automatic_replies'),
                    'field_description' => $this->lang->line('crm_chatbot_allowed_automatic_replies_description')
                ),
                'field_params' => array(
                    'placeholder' => $this->lang->line('crm_chatbot_enter_allowed_automatic_replies'),
                    'value' => md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies', $this->input->get('plan_id'))?md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies', $this->input->get('plan_id')):0,
                    'disabled' => false
                )

            ),
            array (
                'field_type' => 'checkbox',
                'field_slug' => 'app_crm_chatbot_guests_attachments',
                'field_words' => array(
                    'field_title' => $this->lang->line('app_crm_guests_attachments'),
                    'field_description' => $this->lang->line('app_crm_guests_attachments_description')
                ),
                'field_params' => array(
                    'checked' => md_the_plan_feature('app_crm_chatbot_guests_attachments', $this->input->get('plan_id'))?1:0
                )

            ),
            array(
                'field_slug' => 'app_crm_chatbot_price',
                'field_type' => 'text',
                'field_words' => array(
                    'field_title' => $this->lang->line('app_crm_chatbot_price'),
                    'field_description' => $this->lang->line('app_crm_chatbot_price_description')
                ),
                'field_params' => array(
                    'placeholder' => $this->lang->line('crm_chatbot_enter_price'),
                    'value' => md_the_plan_feature('app_crm_chatbot_price', $this->input->get('plan_id'))?md_the_plan_feature('app_crm_chatbot_price', $this->input->get('plan_id')):0,
                    'disabled' => false
                )

            )         

        )

    )
    
);

/* End of file plans.php */
