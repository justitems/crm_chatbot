<?php
/**
 * Triggers Functions Inc
 *
 * This file contains the some functions
 * which are used for triggers meta
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('the_crm_chatbot_websites_triggers_meta') ) {
    
    /**
     * The function the_crm_chatbot_websites_triggers_meta gets the trigger's meta
     * 
     * @param integer $trigger_id contains the trigger's ID
     * @param string $parent contains the trigger's parent
     * @param string $name contains the meta's name
     * @param boolean $extra contains the condition to return extra instead value
     * @param integer $user_id contains the user's ID
     * 
     * @return string with meta value or boolean false
     */
    function the_crm_chatbot_websites_triggers_meta($trigger_id, $parent, $name, $extra = FALSE, $user_id = 0) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Verify if user's ID exists
        if ( !$user_id ) {

            // Set user's ID
            $user_id = $CI->user_id;

        }

        // Get the trigger's meta
        $the_website_trigger_meta = md_the_data('crm_chatbot_website_trigger_meta_' . $parent)?md_the_data('crm_chatbot_website_trigger_meta_' . $parent):array();

        // Verify if meta exists
        if ( !isset($the_website_trigger_meta[$trigger_id]) ) {

            // Get the trigger's meta
            $the_meta = $CI->base_model->the_data_where(
                'crm_chatbot_websites_triggers_meta',
                '*',
                array(
                    'trigger_id' => $trigger_id,
                    'meta_parent' => $parent
                )
            );

            // Verify if meta exists
            if ( $the_meta ) {

                // Prepare the meta
                $the_website_trigger_meta[$trigger_id] = $the_meta;

                // Save the meta
                md_set_data('crm_chatbot_website_trigger_meta_' . $parent, $the_website_trigger_meta);

            }

        }

        // Verify if the meta exists
        if ( isset($the_website_trigger_meta[$trigger_id]) ) {

            // Group meta
            $meta = $extra?array_column($the_website_trigger_meta[$trigger_id], 'meta_extra', 'meta_name'):array_column($the_website_trigger_meta[$trigger_id], 'meta_value', 'meta_name');

            // Verify if meta name exists
            if ( isset($meta[$name]) ) {

                return $meta[$name];

            } else {

                return false;

            }

        } else {

            return false;

        }

    }

}

if (!function_exists('update_crm_chatbot_websites_triggers_meta')) {
    
    /**
     * The function update_crm_chatbot_websites_triggers_meta updates the trigger's meta
     * 
     * @param integer $trigger_id contains the trigger's ID
     * @param string $parent contains the trigger's parent
     * @param string $name contains the meta's name
     * @param string $value contains the meta's value
     * @param string $extra contains the meta's extra
     * 
     * @return boolean true or false
     */
    function update_crm_chatbot_websites_triggers_meta($trigger_id, $parent, $name, $value, $extra=NULL) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get trigger's meta
        $the_meta = $CI->base_model->the_data_where(
            'crm_chatbot_websites_triggers_meta',
            'meta_id',
            array(
                'trigger_id' => $trigger_id,
                'meta_parent' => $parent,
                'meta_name' => $name
            )
        );

        // Verify if the meta exists
        if ( $the_meta ) {

            // Prepare the where data
            $where = array(
                'meta_id' => $the_meta[0]['meta_id']
            );

            // Prepare the update's data
            $update = array(
                'meta_value' => $value
            );

            // Verify if extra exists
            if ( $extra ) {

                // Set the meta's extra
                $update['meta_extra'] = $extra;

            }

            // Update the trigger's meta
            if (  $CI->base_model->update('crm_chatbot_websites_triggers_meta', $where, $update) ) {
                return true;
            } else {
                return false;
            }

        } else {

            // Prepare the meta
            $meta_args = array(
                'trigger_id' => $trigger_id,
                'meta_parent' => $parent,
                'meta_name' => $name,
                'meta_value' => $value
            );

            // Verify if extra exists
            if ( $extra ) {

                // Set the meta's extra
                $meta_args['meta_extra'] = $extra;

            }

            // Save the trigger's meta by using the Base's Model
            if ( $CI->base_model->insert('crm_chatbot_websites_triggers_meta', $meta_args) ) {
                return true;
            } else {
                return false;
            }
            
        }

    }

}

/* End of file triggers_functions.php */