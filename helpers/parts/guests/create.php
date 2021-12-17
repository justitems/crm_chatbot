<?php
/**
 * Guests Create Helper
 *
 * This file create contains the methods
 * to create guests
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Guests;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Create class extends the class guests to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Create {
    
    /**
     * Class variables
     *
     * @since 0.0.8.5
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.5
     */
    public function __construct() {
        
        // Get CodeIgniter object instance
        $this->CI =& get_instance();
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the guests
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_save_guest_data saves the guest's data
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_save_guest_data($params) {

        // Verify if the session exists
        if ( empty($params['guest']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            ); 

        }

        // Get the guest
        $the_guest = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_guests',
            '*',
            array(
                'user_id' => $params['website']['user_id'],
                'id' => $params['guest']
            )
        );

        // Set quest's ID
        $guest_id = !empty($the_guest)?$the_guest[0]['guest_id']:0;

        // Verify if the quest exists
        if ( !$the_guest ) {

            // Guest params
            $guest_params = array(
                'user_id' => $params['website']['user_id'],
                'id' => $params['guest'],
                'created' => time()
            );

            // Save guest
            $the_guest = $this->CI->base_model->insert('crm_chatbot_websites_guests', $guest_params);

            // Verify if the quest was saved
            if ( !$the_guest ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );     
                
            }

            // Require the Automations Inc
            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php');

            // Require the Automations Hooks Inc
            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations_hooks.php');

            // Run hook when a guest is created
            md_run_hook(
                'crm_chatbot_new_guest',
                array(
                    'guest_id' => $the_guest,
                    'website_id' => $params['website']['website_id']
                )
            );

            // Save the guest's IP
            update_crm_chatbot_websites_guests_meta($the_guest, 'guest_ip', $this->CI->input->ip_address());

            // Verify if the timezone exists
            if ( !empty($params['timezone']) ) {

                // Verify if the timezone is valid
                if ( in_array($params['timezone'], timezone_identifiers_list()) ) {

                    // Save the guest's timezone
                    update_crm_chatbot_websites_guests_meta($the_guest, 'guest_timezone', $params['timezone']);                    

                }

            }

            // Set guest's ID
            $guest_id = $the_guest;

        }

        // Verify if the guest was found
        if ( !$guest_id ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            ); 
            
        }

        // Verify if the name exists
        if ( empty($params['name']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_name') ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_your_name')
            );             
            
        } else if ( !empty($params['name']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_name') ) {

            // Verify if the name has at least 2 characters
            if ( strlen(trim($params['name'])) < 2 ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_name_too_short')
                );                        

            }

            // Try to save the name
            if ( !update_crm_chatbot_websites_guests_meta($guest_id, 'guest_name', trim($params['name'])) ) {

                if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_name') !== trim($params['name']) ) {

                    // Return error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_name_was_not_saved')
                    );     

                }

            }

        }

        // Verify if the email exists
        if ( empty($params['email']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_email') ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_your_email')
            );             
            
        } else if ( !empty($params['email']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_email') ) {

            // Verify if the email is valid
            if ( filter_var($params['email'], FILTER_VALIDATE_EMAIL) === FALSE ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_email_not_valid')
                );                        

            }

            // Try to save the email
            if ( !update_crm_chatbot_websites_guests_meta($guest_id, 'guest_email', trim($params['email'])) ) {

                if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_email') !== trim($params['email']) ) {

                    // Return error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_email_was_not_saved')
                    );      

                }

            }

        }

        // Verify if the phone exists
        if ( empty($params['phone']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_phone') ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_please_enter_your_phone')
            );             
            
        } else if ( !empty($params['phone']) && the_crm_chatbot_websites_meta($params['website']['website_id'], 'guest_phone') ) {

            // Verify if the phone has at least 2 characters
            if ( strlen(trim($params['phone'])) < 2 ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_phone_too_short')
                );                        

            }

            // Try to save the phone
            if ( !update_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone', trim($params['phone'])) ) {

                if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone') !== trim($params['phone']) ) {

                    // Return error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_phone_was_not_saved')
                    );     

                }

            }

        }

        // Return success response
        return array(
            'success' => TRUE
        );           
        
    }

}

/* End of file create.php */