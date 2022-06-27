<?php
/**
 * Website Functions Inc
 *
 * This file contains the some functions
 * which are used in the website's page
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
*/

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('get_crm_chatbot_website_settings') ) {
    
    /**
     * The function get_crm_chatbot_website_settings shows the website settings
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    function get_crm_chatbot_website_settings($params) {

        // Get CodeIgniter object instance
        $CI =& get_instance();
        
        // Display the settings options
        echo '<ul class="theme-settings-options-list">';

        // List the parameters
        foreach ( $params as $param ) {

            // Verify if the type exists
            if ( empty($param['id']) || empty($param['type']) ) {
                continue;
            }

            // Show the option by type
            switch ( $param['type'] ) {

                case 'category':

                    // Verify if title exists
                    if ( !empty($param['title']) ) {

                        // Display the option
                        echo '<li class="crm-settings-option theme-settings-option-category" data-id="' . $param['id'] . '">'
                            . '<div class="row">'
                                . '<div class="col-12">'
                                    . '<h2>'
                                        . $param['title']
                                    . '</h2>'
                                . '</div>'
                            . '</div>'
                        . '</li>';

                    }

                    break;

                case 'dropdown':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['button_id']) && !empty($param['menu']) ) {

                        // Verify if the title, description and input exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && !empty($param['info']['text']) && isset($param['info']['id']) && !empty($param['menu']['input']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Display the dropdown
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right">'
                                        . '<div id="crm-settings-option-' . $unique_id . '-dropdown" class="dropdown m-0 theme-dropdown-1 theme-dropdown-icon-1">'
                                            . '<a href="#" role="button" id="crm-settings-option-' . $unique_id . '-btn" class="btn btn-link dropdown-toggle theme-color-black crm-settings-option-btn" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" data-option="' . $param['button_id'] . '" data-id="' . $param['info']['id'] . '">'
                                                . $param['info']['text']
                                                . md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'mr-0 ml-3 theme-dropdown-arrow-icon theme-color-black'))
                                            . '</a>'
            
                                            . '<div class="dropdown-menu crm-settings-option-menu">'
                                                . '<input type="text" placeholder="' . $param['menu']['input'] . '" class="crm-settings-search-for-data" />'
                                                . '<div>'
                                                    . '<ul class="list-group">'
                                                    . '</ul>'
                                                . '</div>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'checkbox':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['checkbox_id']) ) {

                        // Verify if the title and description exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && isset($param['info']['checked']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Set checked
                            $checked = !empty($param['info']['checked'])?' checked':'';

                            // Display the dropdown
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right">'
                                        . '<div class="theme-checkbox-input-2">'
                                            . '<input type="checkbox" name="crm-settings-option-checkbox-input-' . $unique_id . '" id="crm-settings-option-checkbox-input-' . $unique_id . '" class="crm-settings-option-checkbox-input" data-option="' . $param['checkbox_id'] . '"' . $checked . ' />'
                                            . '<label for="crm-settings-option-checkbox-input-' . $unique_id . '"></label>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'textarea':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['textarea_id']) ) {

                        // Verify if the title, description and placeholder exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && !empty($param['info']['placeholder']) && isset($param['info']['value']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Display the dropdown
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right theme-form">'
                                        . '<textarea placeholder="' . $param['info']['placeholder'] . '" name="crm-settings-option-textarea-' . $unique_id . '" id="crm-settings-option-textarea-' . $unique_id . '" class="m-0 crm-settings-option-text-input" data-option="' . $param['textarea_id'] . '">' . $param['info']['value'] . '</textarea>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'text_editor':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['text_editor_id']) ) {

                        // Verify if the title and description exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && isset($param['info']['value']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Display the text editor
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-5">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-7">'
                                        . '<div class="card mb-0 p-0 theme-minimal-text-editor-box theme-box-2">'
                                            . '<div class="card-header">'
                                                . '<div class="row">'
                                                    . '<div class="col-12 theme-minimal-text-editor-toolbar">'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="justifyLeft">'
                                                            . md_the_user_icon(array('icon' => 'fa_align_left'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="justifyCenter">'
                                                            . md_the_user_icon(array('icon' => 'fa_align_center'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="justifyRight">'
                                                            . md_the_user_icon(array('icon' => 'fa_align_right'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="justifyFull">'
                                                            . md_the_user_icon(array('icon' => 'fa_align_justify'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="bold">'
                                                            . md_the_user_icon(array('icon' => 'fa_bold'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="italic">'
                                                            . md_the_user_icon(array('icon' => 'fa_italic'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="underline">'
                                                            . md_the_user_icon(array('icon' => 'fa_underline'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-link-add">'
                                                            . md_the_user_icon(array('icon' => 'fa_link'))
                                                        . '</button>'
                                                        . '<button type="button" class="btn btn-light btn-option" data-type="unlink">'
                                                            . md_the_user_icon(array('icon' => 'fa_unlink'))
                                                        . '</button>'
                                                    . '</div>'
                                                . '</div>'
                                            . '</div>'
                                            . '<div class="card-body">'
                                                . '<div class="theme-minimal-text-editor crm-settings-option-text-editor" contenteditable="true" data-option="' . $param['text_editor_id'] . '">'
                                                    . $param['info']['value']
                                                . '</div>'
                                            . '</div>'
                                        . '</div>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'number':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['number_id']) ) {

                        // Verify if the title, description and placeholder exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && !empty($param['info']['placeholder']) && isset($param['info']['value']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Display the number input
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right theme-form">'
                                        . '<textarea placeholder="' . $param['info']['placeholder'] . '" name="crm-settings-option-number-' . $unique_id . '" id="crm-settings-option-number-' . $unique_id . '" class="m-0 crm-settings-option-number-input" data-option="' . $param['number_id'] . '">' . $param['info']['value'] . '</textarea>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'color':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['color_id']) ) {

                        // Verify if the title, description and color exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && !empty($param['info']['color']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Display the color input
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right theme-form">'
                                        . '<button type="button" style="background-color: ' . $param['info']['color'] . ';" class="theme-color-input-btn" data-option="' . $param['color_id'] . '" data-value="' . $param['info']['color'] . '"></button>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

                case 'image':

                    // Verify if required parameters exists
                    if ( !empty($param['info']) && !empty($param['image_id']) ) {

                        // Verify if the title, description and media's URL exists
                        if ( !empty($param['info']['title']) && !empty($param['info']['description']) && !empty($param['info']['media_url']) ) {

                            // Get unique id
                            $unique_id = uniqid(time());

                            // Set media's ID
                            $media_id = !empty($param['info']['media_id'])?' data-media="' . $param['info']['media_id'] . '"':'';

                            // Display the image input
                            echo '<li class="crm-settings-option" data-id="' . $param['id'] . '">'
                                . '<div class="row">'
                                    . '<div class="col-7">'
                                        . '<h4>'
                                            . $param['info']['title']
                                        . '</h4>'
                                        . '<p>'
                                            . $param['info']['description']
                                        . '</p>'
                                    . '</div>'
                                    . '<div class="col-5 text-right theme-form">'
                                        . '<button type="button" class="theme-image-input-btn" data-option="' . $param['image_id'] . '" data-toggle="modal" data-target="#crm-chatbot-change-image-modal"' . $media_id . '>'
                                            . '<img src="' . $param['info']['media_url'] . '" alt="Chat Button">'
                                        . '</button>'
                                    . '</div>'
                                . '</div>'
                            . '</li>';

                        }

                    }

                    break;

            }

        }
            
        echo '</ul>';

    }

}

if ( !function_exists('the_crm_chatbot_websites_meta') ) {
    
    /**
     * The function the_crm_chatbot_websites_meta gets the website's meta
     * 
     * @param integer $website_id contains the website's ID
     * @param string $name contains the meta's name
     * @param boolean $extra contains the condition to return extra instead value
     * @param integer $user_id contains the user's ID
     * 
     * @return string with meta value or boolean false
     */
    function the_crm_chatbot_websites_meta($website_id, $name, $extra = FALSE, $user_id = 0) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Verify if user's ID exists
        if ( !$user_id ) {

            // Set user's ID
            $user_id = md_the_user_id();

        }

        // Get the website's meta
        $the_website_meta = md_the_data('crm_chatbot_website_meta')?md_the_data('crm_chatbot_website_meta'):array();

        // Verify if meta exists
        if ( !isset($the_website_meta[$website_id]) ) {

            // Get the website's meta
            $the_meta = $CI->base_model->the_data_where(
                'crm_chatbot_websites_meta',
                '*',
                array(
                    'website_id' => $website_id
                )
            );

            // Verify if meta exists
            if ( $the_meta ) {

                // Prepare the meta
                $the_website_meta[$website_id] = $the_meta;

                // Save the meta
                md_set_data('crm_chatbot_website_meta', $the_website_meta);

            }

        }

        // Verify if the meta exists
        if ( isset($the_website_meta[$website_id]) ) {

            // Group meta
            $meta = $extra?array_column($the_website_meta[$website_id], 'meta_extra', 'meta_name'):array_column($the_website_meta[$website_id], 'meta_value', 'meta_name');

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

if (!function_exists('update_crm_chatbot_websites_meta')) {
    
    /**
     * The function update_crm_chatbot_websites_meta updates the website's meta
     * 
     * @param integer $website_id contains the website's ID
     * @param string $name contains the meta's name
     * @param string $value contains the meta's value
     * @param string $extra contains the meta's extra
     * 
     * @return boolean true or false
     */
    function update_crm_chatbot_websites_meta($website_id, $name, $value, $extra=NULL) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get website's meta
        $the_meta = $CI->base_model->the_data_where(
            'crm_chatbot_websites_meta',
            'meta_id',
            array(
                'website_id' => $website_id,
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

            // Update the website's meta
            if (  $CI->base_model->update('crm_chatbot_websites_meta', $where, $update) ) {
                return true;
            } else {
                return false;
            }

        } else {

            // Prepare the meta
            $meta_args = array(
                'website_id' => $website_id,
                'meta_name' => $name,
                'meta_value' => $value
            );

            // Verify if extra exists
            if ( $extra ) {

                // Set the meta's extra
                $meta_args['meta_extra'] = $extra;

            }

            // Save the website's meta by using the Base's Model
            if ( $CI->base_model->insert('crm_chatbot_websites_meta', $meta_args) ) {
                return true;
            } else {
                return false;
            }
            
        }

    }

}

if ( !function_exists('delete_crm_chatbot_websites_meta') ) {
    
    /**
     * The function delete_crm_chatbot_websites_meta deletes a website's meta
     * 
     * @param integer $website_id contains the website's ID
     * @param string $name contains the meta's name
     * 
     * @return boolean true or false
     */
    function delete_crm_chatbot_websites_meta($website_id, $name) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Get website
        $get_website = $CI->base_model->the_data_where(
            'crm_chatbot_websites_meta',
            'website_id',
            array(
                'website_id' => $website_id
            )
        );

        // Verify if the website exists
        if ( $get_website ) {

            // Delete the meta
            if ( $CI->base_model->delete('crm_chatbot_websites_meta', array('website_id' => $website_id, 'meta_name' => $name)) ) {

                return true;

            } else {

                return false;

            }
        
        } else {
            return false;
        }

    }

}

if ( !function_exists('the_crm_chatbot_website_chat_style_name') ) {
    
    /**
     * The function the_crm_chatbot_website_chat_style_name gets the chat style name
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.4
     * 
     * @return string with the chat style name
     */
    function the_crm_chatbot_website_chat_style_name($website_id) {

        // Get CodeIgniter object instance
        $CI =& get_instance();
        
        // Get the chat's style
        $the_chat_style = the_crm_chatbot_websites_meta($website_id, 'chat_style');

        // Verify if styles exists
        if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') ) {

            // List all styles
            foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') as $filename) {
                
                // Set class name
                $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/', '.php'), '', $filename);

                // Create an array
                $array = array(
                    'CmsBase',
                    'User',
                    'Apps',
                    'Collection',
                    'Crm_chatbot',
                    'Styles',
                    ucfirst($class_name)
                );

                // Implode the array above
                $cl = implode('\\', $array);

                // Get info
                $info = (new $cl())->the_style_info($website_id);

                // Verify if is the expected slug
                if ( $info['style_slug'] === $the_chat_style ) {

                    // Set chat's name
                    return $info['style_name'];

                }
                
            }

        }

        return $CI->lang->line('crm_chatbot_select_style');

    }

}

if ( !function_exists('the_crm_chatbot_website_chat_button_icon') ) {
    
    /**
     * The function the_crm_chatbot_website_chat_button_icon gets the chat style icon
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.4
     * 
     * @return string with the chat style icon
     */
    function the_crm_chatbot_website_chat_button_icon($website_id) {

        // Get CodeIgniter object instance
        $CI =& get_instance();
        
        // Get the chat's style
        $the_chat_style = the_crm_chatbot_websites_meta($website_id, 'chat_style')?the_crm_chatbot_websites_meta($website_id, 'chat_style'):'default_style';

        // Verify if an icon is already selected
        if ( the_crm_chatbot_websites_meta($website_id, 'icon_button') ) {

            // Return icon
            return the_crm_chatbot_websites_meta($website_id, 'icon_button');

        } else {

            // Verify if styles exists
            if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') ) {

                // List all styles
                foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/*.php') as $filename) {
                    
                    // Set class name
                    $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'styles/', '.php'), '', $filename);

                    // Create an array
                    $array = array(
                        'CmsBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Crm_chatbot',
                        'Styles',
                        ucfirst($class_name)
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get info
                    $info = (new $cl())->the_style_info($website_id);

                    // Verify if is the expected slug
                    if ( $info['style_slug'] === $the_chat_style ) {

                        // Verify if the style has icon
                        if ( !empty($info['style_button_icon']) ) {

                            // Set style's icon
                            return $info['style_button_icon'];

                        }

                    }
                    
                }

            }

        }

    }

}

if ( !function_exists('the_crm_chatbot_website_chat_welcome_message_bot') ) {
    
    /**
     * The function the_crm_chatbot_website_chat_welcome_message_bot gets the bot's name
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.4
     * 
     * @return string with the chat bot's name
     */
    function the_crm_chatbot_website_chat_welcome_message_bot($website_id) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Verify if a bot was selected
        if ( !the_crm_chatbot_websites_meta($website_id, 'welcome_message_bot') ) {
            return $CI->lang->line('crm_chatbot_select_a_bot');
        }
        
        // Get bot's data
        $the_bot = $CI->base_model->the_data_where(
            'crm_chatbot_bots',
            '*',
            array(
                'bot_id' => the_crm_chatbot_websites_meta($website_id, 'welcome_message_bot')
            )
        );

        return $the_bot?$the_bot[0]['bot_name']:$CI->lang->line('crm_chatbot_select_a_bot');

    }

}

if ( !function_exists('the_crm_chatbot_website_chat_member_agent') ) {
    
    /**
     * The function the_crm_chatbot_website_chat_member_agent gets the team's member name
     * 
     * @param integer $website_id contains the website's ID
     * 
     * @since 0.0.8.4
     * 
     * @return string with the team's member name
     */
    function the_crm_chatbot_website_chat_member_agent($website_id) {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Verify if a member was selected
        if ( !the_crm_chatbot_websites_meta($website_id, 'member_agent') ) {
            return $CI->lang->line('crm_chatbot_select_a_member');
        }
        
        // Get member's data
        $the_team_member = $CI->base_model->the_data_where(
            'teams',
            'teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name',
            array(
                'teams.member_id' => the_crm_chatbot_websites_meta($website_id, 'member_agent')
            ),
            array(),
            array(),
            array(array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ))
        );

        // Verify if the team's member was found
        if ( !$the_team_member ) {
            return $CI->lang->line('crm_chatbot_select_a_member');
        }

        return (!empty($the_team_member[0]['member_first_name']) && !empty($the_team_member[0]['member_last_name']))?$the_team_member[0]['member_first_name'] . ' ' . $the_team_member[0]['member_last_name']:$the_team_member[0]['member_username'];

    }

    if ( !function_exists('the_crm_chatbot_websites_guests_meta') ) {
    
        /**
         * The function the_crm_chatbot_websites_guests_meta gets the guest's meta
         * 
         * @param integer $guest_id contains the guest's ID
         * @param string $name contains the meta's name
         * @param boolean $extra contains the condition to return extra instead value
         * @param integer $user_id contains the user's ID
         * 
         * @return string with meta value or boolean false
         */
        function the_crm_chatbot_websites_guests_meta($guest_id, $name, $extra = FALSE, $user_id = 0) {
    
            // Get CodeIgniter object instance
            $CI =& get_instance();
    
            // Verify if user's ID exists
            if ( !$user_id ) {
    
                // Set user's ID
                $user_id = md_the_user_id();
    
            }
    
            // Get the guest's meta
            $the_guest_meta = md_the_data('crm_chatbot_guest_meta')?md_the_data('crm_chatbot_guest_meta'):array();
    
            // Verify if meta exists
            if ( !isset($the_guest_meta[$guest_id]) ) {
    
                // Get the guest's meta
                $the_meta = $CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests_meta',
                    '*',
                    array(
                        'guest_id' => $guest_id
                    )
                );
    
                // Verify if meta exists
                if ( $the_meta ) {
    
                    // Prepare the meta
                    $the_guest_meta[$guest_id] = $the_meta;
    
                    // Save the meta
                    md_set_data('crm_chatbot_guest_meta', $the_guest_meta);
    
                }
    
            }
    
            // Verify if the meta exists
            if ( isset($the_guest_meta[$guest_id]) ) {
    
                // Group meta
                $meta = $extra?array_column($the_guest_meta[$guest_id], 'meta_extra', 'meta_name'):array_column($the_guest_meta[$guest_id], 'meta_value', 'meta_name');
    
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
    
    if (!function_exists('update_crm_chatbot_websites_guests_meta')) {
        
        /**
         * The function update_crm_chatbot_websites_guests_meta updates the guest's meta
         * 
         * @param integer $guest_id contains the guest's ID
         * @param string $name contains the meta's name
         * @param string $value contains the meta's value
         * @param string $extra contains the meta's extra
         * 
         * @return boolean true or false
         */
        function update_crm_chatbot_websites_guests_meta($guest_id, $name, $value, $extra=NULL) {
    
            // Get CodeIgniter object instance
            $CI =& get_instance();
    
            // Get guest's meta
            $the_meta = $CI->base_model->the_data_where(
                'crm_chatbot_websites_guests_meta',
                'meta_id',
                array(
                    'guest_id' => $guest_id,
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
    
                // Update the guest's meta
                if (  $CI->base_model->update('crm_chatbot_websites_guests_meta', $where, $update) ) {
                    return true;
                } else {
                    return false;
                }
    
            } else {
    
                // Prepare the meta
                $meta_args = array(
                    'guest_id' => $guest_id,
                    'meta_name' => $name,
                    'meta_value' => $value
                );
    
                // Verify if extra exists
                if ( $extra ) {
    
                    // Set the meta's extra
                    $meta_args['meta_extra'] = $extra;
    
                }
    
                // Save the guest's meta by using the Base's Model
                if ( $CI->base_model->insert('crm_chatbot_websites_guests_meta', $meta_args) ) {
                    return true;
                } else {
                    return false;
                }
                
            }
    
        }
    
    }

}

/* End of file website_functions.php */