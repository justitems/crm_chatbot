<?php
/**
 * Websites Update Helper
 *
 * This file update contains the methods
 * to update websites
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Websites;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed'); 

/*
 * Update class extends the class Websites to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Update {
    
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
        
        // Get CodeIgniter object instance
        $this->CI =& get_instance();
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Websites
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_update_website_url updates a website's url
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_update_website_url($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_websites') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Verify if website's ID exists
        if ( empty($params['website']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );

        }

        // Get the website
        $the_website = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites',
            '*',
            array(
                'website_id' => $params['website'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the website exists
        if ( !$the_website ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );            

        }

        // Verify if the website_url parameter exists
        if ( empty($params['website_url']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_website_url')
            );  
            
        }

        // Verify if the url is valid
        if (filter_var($params['website_url'], FILTER_VALIDATE_URL) === FALSE) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_website_url')
            );             
            
        }

        // Verify if the url is same
        if ( $the_website[0]['url'] === trim($params['website_url']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_url_was_not_updated')
            ); 

        }

        // Set url
        $curl = curl_init($params['website_url']);
        
        // Set nobody option
        curl_setopt($curl, CURLOPT_NOBODY, true);
        
        // Execute request
        $result = curl_exec($curl);
        
        // Verify if the url exists
        if ($result === false) {
            
            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_website_url_not_valid')
            ); 

        }

        // Get the web's domain
        $parse_domain = parse_url($params['website_url']);

        // Get the website's data
        $the_website_data = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites',
            '*',
            array(
                'url' => trim($params['website_url'])
            )
        );

        // Verify if the domain already exists
        if ( $the_website_data ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_website_is_already_in_use')
            );                 

        }

        // Verify if host's exists
        if ( empty($parse_domain['host']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_domain_not_valid')
            ); 

        }

        // Prepare the url
        $url_params = array(
            'domain' => trim($parse_domain['host']),
            'url' => trim($params['website_url'])
        );

        // Update the url
        $update = $this->CI->base_model->update('crm_chatbot_websites', array('website_id' => $params['website']), $url_params);

        // Verify if the url was updated
        if ( $update ) {

            // Prepare the success response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_url_was_updated')
            );

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_url_was_not_updated')
            ); 

        }

    }

    /**
     * The public method crm_chatbot_update_website updates a website
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_update_website($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_websites') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Verify if website's ID exists
        if ( empty($params['website']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );

        }

        // Get the website
        $the_website = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites',
            '*',
            array(
                'website_id' => $params['website'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the website exists
        if ( !$the_website ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_found')
            );            

        }

        // Update counter
        $update_count = 0;

        // Errors counter
        $errors_count = 0;

        // Verify if options exists
        if ( !empty($params['options']) ) {

            // Allowed options
            $allowed_options = array(
                'chat_styles',
                'show_chat',
                'chat_mode',
                'show_welcome',
                'remove_shadow_button',
                'background_color_button',
                'icon_button',
                'disable_chat_input',
                'welcome_message_title',
                'welcome_message_content',
                'welcome_message_bot',
                'member_agent',
                'bot_name',
                'bot_photo',
                'guest_name',
                'guest_email',
                'guest_phone',
                'gdrp',
                'gdrp_title',
                'gdrp_content',
                'gdrp_accept_button',
                'gdrp_reject_button'
            );

            // Verify if the guests attachments are enabled
            if ( md_the_plan_feature('app_crm_chatbot_guests_attachments') ) {

                // Merge arrays
                $allowed_options = array_merge($allowed_options, array('enable_attachments', 'thread_attachments'));

            }

            // List all colors
            foreach ( $params['options']['colors'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {                

                    // Try to update the meta
                    if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), trim($option[strip_tags(trim(key($option)))])) ) {
                        $update_count++;
                    } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                        $update_count++;
                    } else {
                        $errors_count++;
                    }

                }

            }

            // Delete the website's media files
            $this->CI->base_model->delete('crm_chatbot_websites_meta', array('website_id' => $params['website'], 'meta_name' => 'icon_button') );
            $this->CI->base_model->delete('crm_chatbot_websites_meta', array('website_id' => $params['website'], 'meta_name' => 'bot_photo') );

            // Verify if images exists
            if ( !empty($params['options']['images']) ) {

                // List all images
                foreach ( $params['options']['images'] as $option ) {

                    // Verify if the option is allowed
                    if ( in_array(key($option), $allowed_options) ) {

                        // Verify of the option's value is numeric
                        if ( is_numeric(trim($option[strip_tags(trim(key($option)))])) ) {

                            // Get the media
                            $the_media = $this->CI->base_model->the_data_where('medias', '*', array('media_id' => trim($option[strip_tags(trim(key($option)))]), 'user_id' => md_the_user_id()) );

                            // Verify if the user has the media
                            if ( !$the_media ) {
                                continue;
                            }

                            // Try to update the meta
                            if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), $the_media[0]['body'], trim($option[strip_tags(trim(key($option)))])) ) {
                                $update_count++;
                            } else if ( $the_media[0]['body'] === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                                $update_count++;
                            } else {
                                $errors_count++;
                            }

                        }

                    }

                }

            }

            // List all checkboxes
            foreach ( $params['options']['checkboxes'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {

                    // Verify of the option's value is numeric
                    if ( is_numeric(trim($option[strip_tags(trim(key($option)))])) ) {

                        // Try to update the meta
                        if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), trim($option[strip_tags(trim(key($option)))])) ) {
                            $update_count++;
                        } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                            $update_count++;
                        } else {
                            $errors_count++;
                        }    

                    }

                }

            }

            // Allowed html
            $allowed_html = array(
                'welcome_message_content',
                'gdrp_content'
            );

            // List all text inputs
            foreach ( $params['options']['texts'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {

                    // Prepare the value
                    $value = in_array(strip_tags(trim(key($option))), $allowed_html)?strip_tags(trim($option[strip_tags(trim(key($option)))]), array('b', 'i', 'u', 'a')):strip_tags(trim($option[strip_tags(trim(key($option)))]));

                    // Try to update the meta
                    if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), $value) ) {
                        $update_count++;
                    } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                        $update_count++;
                    } else {
                        $errors_count++;
                    }

                }

            }

            // List all text editors
            foreach ( $params['options']['text_editors'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {

                    // Prepare the value
                    $value = in_array(strip_tags(trim(key($option))), $allowed_html)?strip_tags(trim($option[strip_tags(trim(key($option)))]), array('div', 'b', 'i', 'u', 'a')):strip_tags(trim($option[strip_tags(trim(key($option)))]));

                    // Try to update the meta
                    if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), $value) ) {
                        $update_count++;
                    } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                        $update_count++;
                    } else {
                        $errors_count++;        
                    }

                }

            }

            // List all numeric inputs
            foreach ( $params['options']['numbers'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {

                    // Prepare the value
                    $value = strip_tags(trim($option[strip_tags(trim(key($option)))]));

                    // Verify if the value is numeric
                    if ( !is_numeric($value) ) {
                        continue;
                    }

                    // Try to update the meta
                    if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), $value) ) {
                        $update_count++;
                    } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                        $update_count++;
                    } else {
                        $errors_count++;
                    }

                }

            }

            // List all dropdowns
            foreach ( $params['options']['dropdowns'] as $option ) {

                // Verify if the option is allowed
                if ( in_array(key($option), $allowed_options) ) {

                    // Verify if is the chat styles
                    if ( key($option) === 'chat_styles' ) {

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
                                $info = (new $cl())->the_style_info($params['website']);

                                // Verify if is the expected slug
                                if ( $info['style_slug'] === trim(strip_tags($option[strip_tags(trim(key($option)))])) ) {

                                    // Save the chat's style
                                    if ( update_crm_chatbot_websites_meta($params['website'], 'chat_style', trim(strip_tags($option[strip_tags(trim(key($option)))]))) ) {
                                        $update_count++;
                                    } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], 'chat_style') ) {
                                        $update_count++;
                                    } else {
                                        $errors_count++;
                                    }

                                }
                                
                            }

                        }

                    } else {

                        // Try to update the meta
                        if ( update_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option))), trim(strip_tags($option[strip_tags(trim(key($option)))]))) ) {
                            $update_count++;
                        } else if ( trim($option[strip_tags(trim(key($option)))]) === the_crm_chatbot_websites_meta($params['website'], strip_tags(trim(key($option)))) ) {
                            $update_count++;
                        } else {
                            $errors_count++;
                        }

                    }

                }

            }

        }

        // Verify if the website has categories
        if ( $this->CI->base_model->the_data_where('crm_chatbot_websites_categories', '*', array('website_id' => $params['website']) ) ) {

            // Delete the categories
            if ( !$this->CI->base_model->delete('crm_chatbot_websites_categories', array('website_id' => $params['website']) ) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred_with_categories')
                );                
                
            }

        }

        // Verify if categories exists
        if ( !empty($params['categories']) ) {

            // List all categories
            foreach ( $params['categories'] as $category ) {

                // Verify if the category is numeric
                if ( !is_numeric($category) ) {
                    $errors_count++;
                    continue;
                }

                // Verify if the category exists
                if ( $this->CI->base_model->the_data_where('crm_chatbot_categories', '*', array('category_id' => $category, 'user_id' => md_the_user_id() ) ) ) {

                    // Save the category
                    if ( $this->CI->base_model->insert('crm_chatbot_websites_categories', array('website_id' => $params['website'], 'category_id' => $category) ) ) {
                        $update_count++;
                    } else {
                        $errors_count++;
                    }

                } else {
                    $errors_count++;
                }

            }

        }



        // Verify if at least a field was updated
        if ( $update_count ) {

            // Verify if something is not saved correctly
            if ( $errors_count ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_some_changes_were_not_saved')
                ); 

            } else {

                // Get team's member
                $member = the_crm_current_team_member(); 

                // Verify if member's name exists
                if ( isset($member['member_name']) ) {

                    // Metas container
                    $metas = array(
                        array(
                            'meta_name' => 'activity_scope',
                            'meta_value' => $params['website']
                        ),
                        array(
                            'meta_name' => 'title',
                            'meta_value' => $this->CI->lang->line('crm_chatbot_website_update')
                        ),                                  
                        array(
                            'meta_name' => 'actions',
                            'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_updated_website') . ' ' . trim($the_website[0]['domain']) . '.'
                        )
                        
                    );

                    // Verify if member exists
                    if ( $this->CI->session->userdata( 'member' ) ) {

                        // Set team's member
                        $metas[] = array(
                            'meta_name' => 'team_member',
                            'meta_value' => $member['member_id']
                        );

                    }

                    // Create the activity
                    create_crm_activity(
                        array(
                            'user_id' => md_the_user_id(),
                            'activity_type' => 'crm_chatbot',
                            'for_id' => $params['website'], 
                            'metas' => $metas
                        )

                    );

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list');

                }

                // Prepare the success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_website_was_updated')
                ); 

            }

        } else {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_the_website_was_not_updated')
            ); 

        }

    }

}

/* End of file update.php */