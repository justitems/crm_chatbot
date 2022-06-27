<?php
/**
 * Websites Create Helper
 *
 * This file create contains the methods
 * to create websites
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
 * Create class extends the class Websites to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Create {
    
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
     * The public method crm_chatbot_save_website saves a website
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_save_website($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );         

        }

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_create_websites') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
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

        // Set number of allowed websites
        $allowed_websites = md_the_plan_feature('app_crm_chatbot_allowed_websites')?md_the_plan_feature('app_crm_chatbot_allowed_websites'):0;

        // Get all websites
        $the_websites = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites',
            'COUNT(*) AS total',
            array(
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the user has websites
        if ( $the_websites ) {

            // Verify if user has permission to add new website
            if ( $the_websites[0]['total'] >= $allowed_websites ) {

                // Prepare error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_you_have_added_maximum_number_of_websites')
                );                   

            }

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

        // Verify if host's exists
        if ( !empty($parse_domain['host']) ) {

            // Get the website's data
            $the_website_data = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                array(
                    'domain' => $parse_domain['host']
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

            // Prepare the website
            $website_params = array(
                'user_id' => md_the_user_id(),
                'domain' => trim($parse_domain['host']),
                'url' => trim($params['website_url']),
                'status' => 1,
                'created' => time()
            );

            // Get team's member
            $member = the_crm_current_team_member();   

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Verify if member's id exists
                if ( isset($member['member_id']) ) {

                    // Set member's ID
                    $website_params['member_id'] = $member['member_id'];

                }

            }

            // Save the website
            $website_id = $this->CI->base_model->insert('crm_chatbot_websites', $website_params);

            // Verify if the website was created
            if ( $website_id ) {

                // Chat style
                $chat_style = '';

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
                        if ( !empty($info['style_slug']) ) {

                            // Set chat's style
                            $chat_style = $info['style_slug'];
                            break;

                        }
                        
                    }

                }

                // Verify if chat's style exists
                if ( empty($chat_style) ) {

                    // Delete the website
                    $this->CI->base_model->delete('crm_chatbot_websites', array('website_id' => $website_id));

                    // Prepare error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_chat_styles_found')
                    ); 

                }

                // Save the chat's style
                update_crm_chatbot_websites_meta($website_id, 'chat_style', $chat_style);

                // Verify if member exists
                if ( $this->CI->session->userdata( 'member' ) ) {

                    // Verify if member's role exists
                    if ( isset($member['role_id']) ) {

                        // Allow to the team's member website access
                        save_crm_team_roles_multioptions_list(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $website_id);

                    }

                }

                // Verify if member's name exists
                if ( isset($member['member_name']) ) {

                    // Metas container
                    $metas = array(
                        array(
                            'meta_name' => 'activity_scope',
                            'meta_value' => $website_id
                        ),
                        array(
                            'meta_name' => 'title',
                            'meta_value' => $this->CI->lang->line('crm_chatbot_website_creation')
                        ),                                  
                        array(
                            'meta_name' => 'actions',
                            'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_created_website') . ' ' . trim($parse_domain['host']) . '.'
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
                            'for_id' => $website_id, 
                            'metas' => $metas
                        )

                    );

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_activities_list');

                }

                // Delete the user's cache
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list');
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_threads_teams_members');
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_guests_list');
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_emails_list');
                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_numbers_list');

                // Prepare success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_website_was_saved_successfully'),
                    'website_id' => $website_id
                ); 

            } else {

                // Prepare error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_website_was_not_saved_successfully')
                ); 

            }

        } else {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_domain_not_valid')
            ); 

        }
        
    }

}

/* End of file create.php */