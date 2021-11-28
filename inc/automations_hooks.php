<?php
/**
 * Automations Hooks Inc
 *
 * This file contains the hooks used
 * to create new automations tasks
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
 * The public method md_set_hook registers a hook
 * 
 * @since 0.0.8.5
 */
md_set_hook(
    'crm_chatbot_new_guest',
    function ($params) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Verify if guest's ID and website's ID exists
        if ( !empty($params['guest_id']) && !empty($params['website_id']) ) {

            // Get the automations
            $the_automations = $CI->base_model->the_data_where(
                'crm_automations_triggers_meta',
                'crm_automations_triggers_meta.automation_id',
                array(
                    'crm_automations_triggers_meta.meta_name' => 'website',
                    'crm_automations_triggers_meta.meta_value' => $params['website_id'],
                    'app.meta_value' => 'crm_chatbot',
                    'event.meta_value' => 'crm_chatbot_new_guest',
                    'crm_automations.status' => 1
                ),
                array(),
                array(),
                array(array(
                    'table' => 'crm_automations_triggers_meta app',
                    'condition' => "crm_automations_triggers_meta.automation_id=app.automation_id AND app.meta_name='app'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_automations_triggers_meta event',
                    'condition' => "crm_automations_triggers_meta.automation_id=event.automation_id AND event.meta_name='event'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_automations',
                    'condition' => "crm_automations_triggers_meta.automation_id=crm_automations.automation_id",
                    'join_from' => 'LEFT'
                ))
            );
            
            // Verify if the automations were found
            if ( $the_automations ) {

                // List all automations
                foreach ( $the_automations as $the_automation ) {

                    // Save task
                    $task = save_crm_automations_automation_task(array(
                        'automation_id' => $the_automation['automation_id']
                    ));

                    // Verify if the task was saved exists
                    if ( !empty($task['success']) ) {

                        // Save the guest's ID
                        save_crm_automations_meta($task['task_id'], 'guest_id', $params['guest_id']);

                    }

                }

            }

        }

    }

);

/**
 * The public method md_set_hook registers a hook
 * 
 * @since 0.0.8.5
 */
md_set_hook(
    'crm_chatbot_new_assigned_category',
    function ($params) {

        // Get codeigniter object instance
        $CI = get_instance();

        // Verify if category's ID and thread's ID exists
        if ( !empty($params['category_id']) && !empty($params['thread_id']) ) {

            // Get the automations
            $the_automations = $CI->base_model->the_data_where(
                'crm_automations_triggers_meta',
                'crm_automations_triggers_meta.automation_id',
                array(
                    'crm_automations_triggers_meta.meta_name' => 'category',
                    'crm_automations_triggers_meta.meta_value' => $params['category_id'],
                    'app.meta_value' => 'crm_chatbot',
                    'event.meta_value' => 'crm_chatbot_new_assigned_category',
                    'crm_automations.status' => 1
                ),
                array(),
                array(),
                array(array(
                    'table' => 'crm_automations_triggers_meta app',
                    'condition' => "crm_automations_triggers_meta.automation_id=app.automation_id AND app.meta_name='app'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_automations_triggers_meta event',
                    'condition' => "crm_automations_triggers_meta.automation_id=event.automation_id AND event.meta_name='event'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_automations',
                    'condition' => "crm_automations_triggers_meta.automation_id=crm_automations.automation_id",
                    'join_from' => 'LEFT'
                ))
            );
            
            // Verify if the automations were found
            if ( $the_automations ) {

                // List all automations
                foreach ( $the_automations as $the_automation ) {

                    // Save task
                    $task = save_crm_automations_automation_task(array(
                        'automation_id' => $the_automation['automation_id']
                    ));

                    // Verify if the task was saved exists
                    if ( !empty($task['success']) ) {

                        // Save the thread's ID
                        save_crm_automations_meta($task['task_id'], 'thread_id', $params['thread_id']);

                        // Save the guest's ID
                        save_crm_automations_meta($task['task_id'], 'guest_id', $params['guest_id']);     
                        
                        // Save the category's ID
                        save_crm_automations_meta($task['task_id'], 'category_id', $params['category_id']);                          

                    }

                }

            }

        }

    }

);

/* End of file automations_hooks.php */