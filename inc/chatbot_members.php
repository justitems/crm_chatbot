<?php
/**
 * Members Inc
 *
 * PHP Version 7.3
 *
 * This files contains the hooks for
 * the CRM Chatbot App
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Verify if the app is enabled
if ( the_crm_apps_installed_app('crm_chatbot') ) {

    /**
     * The public md_set_member_permissions registers the team's permissions
     * 
     * @since 0.0.8.4
     */
    md_set_member_permissions(
        array(
            'name' => $this->lang->line('crm_chatbot'),
            'icon' => '<img src="' . base_url('assets/base/user/apps/collection/crm-chatbot/img/cover.png') . '" alt="' . $this->lang->line('crm_chatbot') . '">',
            'slug' => 'crm_chatbot',
            'fields' => array(
                array (
                    'type' => 'category',
                    'slug' => 'crm_chatbot_general_category',
                    'label' => $this->lang->line('crm_chatbot_general')
                ),
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot',
                    'label' => $this->lang->line('crm_chatbot_allow'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_description')
                ),
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_configuration',
                    'label' => $this->lang->line('crm_chatbot_configuration_allow'),
                    'label_description' => $this->lang->line('crm_chatbot_configuration_allow_description')
                ),
                array (
                    'type' => 'category',
                    'slug' => 'crm_chatbot_websites_category',
                    'label' => $this->lang->line('crm_chatbot_websites')
                ),    
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_create_websites',
                    'label' => $this->lang->line('crm_chatbot_allow_websites_creation'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_websites_creation_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_edit_websites',
                    'label' => $this->lang->line('crm_chatbot_allow_websites_editing'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_websites_editing_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_delete_websites',
                    'label' => $this->lang->line('crm_chatbot_allow_websites_deletion'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_websites_deletion_description')
                ),
                array (
                    'type' => 'multioptions_list',
                    'slug' => 'crm_chatbot_allowed_websites',
                    'label' => $this->lang->line('crm_chatbot_websites_access'),
                    'label_description' => $this->lang->line('crm_chatbot_websites_access_description'),
                    'select_text' => $this->lang->line('crm_chatbot_websites'),
                    'search_placeholder' => $this->lang->line('crm_chatbot_search_for_websites'),
                    'endpoint_url' => site_url('user/app-ajax/crm_chatbot'),
                    'endpoint_method' => 'crm_chatbot_get_permission_websites'
                ),                              
                array (
                    'type' => 'category',
                    'slug' => 'crm_chatbot_bots_category',
                    'label' => $this->lang->line('crm_chatbot_bots')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_create_bots',
                    'label' => $this->lang->line('crm_chatbot_allow_bots_creation'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_bots_creation_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_edit_bots',
                    'label' => $this->lang->line('crm_chatbot_allow_bots_editing'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_bots_editing_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_delete_bots',
                    'label' => $this->lang->line('crm_chatbot_allow_bots_deletion'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_bots_deletion_description')
                ),
                array (
                    'type' => 'category',
                    'slug' => 'crm_chatbot_quick_replies_category',
                    'label' => $this->lang->line('crm_chatbot_quick_replies')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_create_quick_replies',
                    'label' => $this->lang->line('crm_chatbot_allow_quick_replies_creation'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_quick_replies_creation_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_edit_quick_replies',
                    'label' => $this->lang->line('crm_chatbot_allow_quick_replies_editing'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_quick_replies_editing_description')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_delete_quick_replies',
                    'label' => $this->lang->line('crm_chatbot_allow_quick_replies_deletion'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_quick_replies_deletion_description')
                ),
                array (
                    'type' => 'category',
                    'slug' => 'crm_chatbot_categories_category',
                    'label' => $this->lang->line('crm_chatbot_categories')
                ),                
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_create_categories',
                    'label' => $this->lang->line('crm_chatbot_allow_categories_creation'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_categories_creation_description')
                ),               
                array (
                    'type' => 'checkbox_input',
                    'slug' => 'crm_chatbot_delete_categories',
                    'label' => $this->lang->line('crm_chatbot_allow_categories_deletion'),
                    'label_description' => $this->lang->line('crm_chatbot_allow_categories_deletion_description')
                )

            ),
            'position' => 10,
            'scope' => 'apps'

        )

    );

}

/* End of file chatbot_members.php */