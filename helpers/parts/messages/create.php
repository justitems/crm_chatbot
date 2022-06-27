<?php
/**
 * Messages Create Helper
 *
 * This file create contains the methods
 * to create messages
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Messages;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed'); 

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Messages as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages;
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bot as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot;
use CmsBase\Classes\Media as CmsBaseClassesMedia;
use CmsBase\Classes\Email as CmsBaseClassesEmail;

/*
 * Create class extends the class Messages to make it lighter
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

        // Verify if CRM Automations exists
        if ( file_exists(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php') ) {

            // Require the Automations Inc
            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations.php');

            // Require the Automations Hooks Inc
            md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/automations_hooks.php');

        }

        // Load the component's language files
        $this->CI->lang->load( 'crm_chatbot_email_templates', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT);
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Messages
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_send_thread_message creates a thread's message
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_send_thread_message($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );         

        }

        // Verify if the thread parameter exists
        if ( empty($params['thread']) ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );               

        }

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'thread_id' => $params['thread'],
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the thread exists
        if ( !$the_thread ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
            );  

        }

        // Verify if message exists
        if ( empty($params['message']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
            );             

        }

        // Verify if the message has at least 2 characters
        if ( strlen($params['message']) < 2 ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
            );               

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if the website is allowed
            if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                );                  
                
            }

        }

        // Require the Website Functions Inc file
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

        // Update the thread
        $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $params['thread']), array('updated' => time()));

        // Prepare the message
        $message_params = array(
            'user_id' => md_the_user_id(),
            'guest_id' => 0,
            'thread_id' => $params['thread'],
            'message_body' => $params['message'],
            'created' => time()
        );

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Set member
            $message_params['member_id'] = $member['member_id'];

        }

        // Save the message
        $message_id = $this->CI->base_model->insert('crm_chatbot_websites_messages', $message_params);

        // Try to send the message
        if ( $message_id ) {

            // Verify if attachments exists
            if ( !empty($params['attachments']) ) {

                // List all attachments
                foreach ( $params['attachments'] as $attachment ) {

                    // Verify if $attachment is numeric
                    if ( is_numeric($attachment) ) {

                        // Verify if the user has this attachment
                        if ( $this->CI->base_model->the_data_where('medias', '*', array('media_id' => $attachment, 'user_id' => md_the_user_id()) ) ) {

                            // Prepare the attachment
                            $attachment_params = array(
                                'message_id' => $message_id,
                                'media_id' => $attachment
                            );

                            // Save the attachment
                            $this->CI->base_model->insert('crm_chatbot_websites_messages_attachments', $attachment_params);

                        }

                    }

                }

            }

            // Create data to return
            $data = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_message_was_sent')
            );

            // Delete the user's cache
            delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list');

            // Return success response
            return $data;             

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent')
            ); 
            
        }
        
    }

    /**
     * The public method crm_chatbot_upload_file attaches a file to a message
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_upload_file($params) {

        // Verify if the session exists
        if ( empty($params['guest']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_file_was_not_uploaded_successfully')
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
                    'message' => $this->CI->lang->line('crm_chatbot_file_was_not_uploaded_successfully')
                ); 
                
            }

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

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'website_id' => $params['website']['website_id']
            )
        );

        // Set thread's ID
        $thread_id = !empty($the_thread)?$the_thread[0]['thread_id']:0;
        
        // Verify if the thread exists
        if ( !$the_thread ) {

            // Thread params
            $thread_params = array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'website_id' => $params['website']['website_id'],
                'status' => 1,         
                'created' => time(),
                'updated' => time()
            );

            // Save thread
            $the_thread = $this->CI->base_model->insert('crm_chatbot_websites_threads', $thread_params);

            // Verify if the thread was saved
            if ( !$the_thread ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_file_was_not_uploaded_successfully')
                ); 
                
            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_threads_alerts') ) {

                // Save the notification
                $this->save_notification(
                    $params['website']['user_id'],
                    $this->CI->lang->line('crm_chatbot_a_new_thread_created'),
                    $this->CI->lang->line('crm_chatbot_a_new_thread_was_created') . ' <a href="' . site_url('user/app/crm_chatbot?thread=' . $the_thread) . '">'
                        . $this->CI->lang->line('crm_chatbot_click_here_more_details')
                    . '</a>',
                    'crm_chatbot_thread_id',
                    $the_thread
                );

            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_threads_notifications') ) {

                // Send the notification
                $this->send_notification( $params['website']['user_id'],
                    $params['website']['website_id'],
                    array(array(
                        'placeholder' => '[thread_id]',
                        'replacer' => $the_thread
                    ), array(
                        'placeholder' => '[thread_link]',
                        'replacer' => '<a href="' . site_url('user/app/crm_chatbot?thread=' . $the_thread) . '">'
                            . site_url('user/app/crm_chatbot?thread=' . $the_thread)
                        . '</a>'
                    )),
                    'crm_chatbot_new_thread_notification',
                    $this->CI->lang->line('crm_chatbot_a_new_thread_was_created'),
                    $this->CI->lang->line('crm_chatbot_new_thread_was_created')
                );

            }

            // Set thread's ID
            $thread_id = $the_thread;

        } else {

            // Verify if the thread is blocked
            if ( $the_thread[0]['status'] > 1 ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_chat_is_blocked')
                );       

            }  

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_messages_alerts') ) {

                // Save the notification
                $this->save_notification(
                    $params['website']['user_id'],
                    $this->CI->lang->line('crm_chatbot_new_message_received'),
                    $this->CI->lang->line('crm_chatbot_you_have_received_new_message') . ' <a href="' . site_url('user/app/crm_chatbot?thread=' . $thread_id) . '">'
                        . $this->CI->lang->line('crm_chatbot_click_here_more_details')
                    . '</a>',
                    'crm_chatbot_thread_id',
                    $thread_id
                );

            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_messages_notifications') ) {

                // Send the notification
                $this->send_notification( $params['website']['user_id'],
                    $params['website']['website_id'],
                    array(array(
                        'placeholder' => '[thread_id]',
                        'replacer' => $thread_id
                    ), array(
                        'placeholder' => '[thread_link]',
                        'replacer' => '<a href="' . site_url('user/app/crm_chatbot?thread=' . $thread_id) . '">'
                            . site_url('user/app/crm_chatbot?thread=' . $thread_id)
                        . '</a>'
                    )),
                    'crm_chatbot_new_message_notification',
                    $this->CI->lang->line('crm_chatbot_a_new_message_was_created'),
                    $this->CI->lang->line('crm_chatbot_new_message_was_created')
                );

            }

            // Update the thread
            $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $thread_id), array('updated' => time()));

        }

        // Verify if the guest don't has saved the latitude
        if ( !the_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude') ) {

            // Verify if the ip2location is enabled
            if ( md_the_option('app_crm_chatbot_ip2location_enabled') ) {

                // Verify if api key exists
                if ( md_the_option('app_crm_chatbot_ip2location_api_key') ) {

                    // Get guest information
                    $the_guest_info = json_decode(file_get_contents('https://api.ip2location.com/v2/?ip=' . $this->CI->input->ip_address() . '&key=' . md_the_option('app_crm_chatbot_ip2location_api_key') . '&package=WS25'), TRUE);

                    // Verify if response key exists
                    if ( !empty($the_guest_info['response']) ) {

                        // Verify if response value is OK
                        if ( $the_guest_info['response'] === 'OK' ) {

                            // Verify if latitude exists
                            if ( !empty($the_guest_info['latitude']) ) {

                                // Save the latitude
                                update_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude', $the_guest_info['latitude']); 

                            }

                            // Verify if longitude exists
                            if ( !empty($the_guest_info['longitude']) ) {

                                // Save the longitude
                                update_crm_chatbot_websites_guests_meta($guest_id, 'guest_longitude', $the_guest_info['longitude']); 

                            }
                            
                            // Verify if country code exists
                            if ( !empty($the_guest_info['country_code']) ) {

                                // Save the country code
                                update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_code', $the_guest_info['country_code']); 

                            }
                            
                            // Verify if country name exists
                            if ( !empty($the_guest_info['country_name']) ) {

                                // Save the country name
                                update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_name', $the_guest_info['country_name']); 

                            }
                            
                            // Verify if city name exists
                            if ( !empty($the_guest_info['city_name']) ) {

                                // Save the city name
                                update_crm_chatbot_websites_guests_meta($guest_id, 'guest_city_name', $the_guest_info['city_name']); 

                            }                             

                        }

                    }

                }

            }

        }

        // Get total number of uploaded files
        $the_uploaded_files = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages_attachments',
            'COUNT(crm_chatbot_websites_messages_attachments.message_id) AS total',
            array(
                'crm_chatbot_websites_messages.thread_id' => $thread_id,
                'crm_chatbot_websites_messages.guest_id >' => 0
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_messages',
                'condition' => 'crm_chatbot_websites_messages_attachments.message_id=crm_chatbot_websites_messages.message_id',
                'join_from' => 'LEFT'
            ))
        );

        // Verify if files were uploaded
        if ( $the_uploaded_files ) {

            // Verify if the limit is reached
            if ( $the_uploaded_files[0]['total'] >= the_crm_chatbot_websites_meta($params['website']['website_id'], 'thread_attachments') ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_maximum_upload_limit_reached')
                ); 
                
            }

        } else if ( !the_crm_chatbot_websites_meta($params['website']['website_id'], 'thread_attachments') ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_maximum_upload_limit_reached')
            );             

        }

        // Verify if urls exists
        if ( !empty($params['visited_urls']) ) {

            // Decode the urls
            $params['visited_urls'] = json_decode($params['visited_urls'], TRUE);

            // If is array
            if ( is_array($params['visited_urls']) ) {

                // Get already saved urls
                $saved_urls = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests_visited_urls',
                    '*',
                    array(
                        'guest_id' => $guest_id
                    )
                );

                // Prepare the saved urls
                $the_saved_urls = !empty($saved_urls)?array_column($saved_urls, 'url'):array();

                // New saved urls
                $new_saved_url = array();

                // List urls
                foreach ( $params['visited_urls'] as $url ) {

                    // Verify if the url has correct parameters
                    if ( !empty($url['title']) && !empty($url['url']) ) {

                        // Verify if the url is valid and is not saved already
                        if ( (filter_var($url['url'], FILTER_VALIDATE_URL) !== FALSE) && !in_array(trim($url['url']), $the_saved_urls) && !in_array(trim($url['url']), $new_saved_url) ) {

                            // Prepare url
                            $url_params = array(
                                'user_id' => $params['website']['user_id'],
                                'guest_id' => $guest_id,
                                'website_id' => $params['website']['website_id'],
                                'title' => $url['title'],
                                'url' => trim($url['url'])
                            );

                            // Verify if url was saved
                            if ( $this->CI->base_model->insert('crm_chatbot_websites_guests_visited_urls', $url_params) ) {
                                $new_saved_url[] = trim($url['url']);
                            }

                        }

                    }

                }

                // Delete the user's cache
                delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_websites_visited_urls_list');

            }

        }

        // Verify if the file was uploaded
        if ( empty($_FILES['files']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            );                 

        }

        // Verify if the uploaded file is an image
        if ( !in_array($_FILES['files']['type'][0], array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'application/pdf', 'application/doc', 'application/ms-doc', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) ) {

            // Set cover
            $cover = file_get_contents($_FILES['files']['tmp_name'][0]);                    

        } else {

            // Set default cover
            $cover = file_get_contents(base_url('assets/img/no-image.png'));

        }

        // Set file's data
        $_FILES['file']['name'] = $_FILES['files']['name'][0];
        $_FILES['file']['type'] = $_FILES['files']['type'][0];
        $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
        $_FILES['file']['error'] = $_FILES['files']['error'][0];
        $_FILES['file']['size'] = $_FILES['files']['size'][0];

        // Upload media
        $response = (new CmsBaseClassesMedia\Upload)->upload(array(
            'user_id' => $params['website']['user_id'],
            'cover' => $cover,
            'allowed_extensions' => array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'application/pdf', 'application/doc', 'application/ms-doc', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ), true);

        // Verify if the file was uploaded
        if ( !empty($response['success']) ) {

            // Get file type
            $get_type = explode('/', $_FILES['file']['type']);

            // Get the file url
            $file_url = $this->CI->base_model->the_data_where(
            'medias',
            '*',
            array(
                'media_id' => $response['media_id']
            ));

            // Prepare the message
            $message_params = array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'thread_id' => $thread_id,
                'message_body' => '',
                'created' => time()
            );

            // Save the message
            $message_id = $this->CI->base_model->insert('crm_chatbot_websites_messages', $message_params);

            // Try to send the message
            if ( $message_id ) {

                // Prepare the attachment
                $attachment_params = array(
                    'message_id' => $message_id,
                    'media_id' => $response['media_id']
                );

                // Save the attachment
                if ( $this->CI->base_model->insert('crm_chatbot_websites_messages_attachments', $attachment_params) ) {

                    // Create data to return
                    $data = array(
                        'success' => TRUE,
                        'message' => $this->CI->lang->line('crm_chatbot_message_was_sent')
                    );

                    // Get the last messages
                    $the_messages = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_get_unseen_messages(array(
                        'user_id' => $params['website']['user_id'],
                        'guest_id' => $guest_id,
                        'website_id' => $params['website']['website_id'],
                        'last' => ($message_id - 1)
                    ));

                    // Verify if messages were found
                    if ( !empty($the_messages['success']) ) {

                        // Set messages
                        $data['messages'] = $the_messages['messages'];

                        // Set bot
                        $data['bot'] = $the_messages['bot'];

                        // Verify if attachments exists
                        if ( !empty($the_messages['attachments']) ) {

                            // Set the attachments
                            $data['attachments'] = $the_messages['attachments'];

                        }

                    }

                    // Delete the user's cache
                    delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_websites_threads_list');

                    // Return success response
                    return $data;    

                } else {

                    // Delete the message
                    $this->CI->base_model->delete('crm_chatbot_websites_messages', array('message_id' => $message_id));

                    // Prepare the error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                    );   
                    
                }

            } else {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent')
                ); 

            }

        } else {

            // Set error
            return array(
                'success' => FALSE,
                'message' => !empty($response['message'])?$response['message']:$this->CI->lang->line('crm_chatbot_the_file') . ' ' . $_FILES['files']['name'][0] . ' ' . $this->CI->lang->line('crm_chatbot_was_not_uploaded_successfully')
            );

        }
        
        // Prepare the error response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );   

    }

    /**
     * The public method crm_chatbot_send_message sends a message
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_send_message($params) {

        // Verify if the session exists
        if ( empty($params['guest']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent') . 2
            ); 

        }

        // Verify if message exists
        if ( empty($params['message']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
            );             

        }

        // Verify if the message has at least 2 characters
        if ( strlen($params['message']) < 2 ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_too_short')
            );               

        }

        // Get user's plan
        $user_plan = md_the_user_option($params['website']['user_id'], 'plan');

        // Verify if user's plan exists
        if ( !$user_plan ) {

            // Prepare the error response
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
                    'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent')
                ); 
                
            }

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

        // Verify if urls exists
        if ( !empty($params['visited_urls']) ) {

            // If is array
            if ( is_array($params['visited_urls']) ) {

                // Get already saved urls
                $saved_urls = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_guests_visited_urls',
                    '*',
                    array(
                        'guest_id' => $guest_id
                    )
                );

                // Prepare the saved urls
                $the_saved_urls = !empty($saved_urls)?array_column($saved_urls, 'url'):array();

                // New saved urls
                $new_saved_url = array();

                // List urls
                foreach ( $params['visited_urls'] as $url ) {

                    // Verify if the url has correct parameters
                    if ( !empty($url['title']) && !empty($url['url']) ) {

                        // Verify if the url is valid and is not saved already
                        if ( (filter_var($url['url'], FILTER_VALIDATE_URL) !== FALSE) && !in_array(trim($url['url']), $the_saved_urls) && !in_array(trim($url['url']), $new_saved_url) ) {

                            // Prepare url
                            $url_params = array(
                                'user_id' => $params['website']['user_id'],
                                'guest_id' => $guest_id,
                                'website_id' => $params['website']['website_id'],
                                'title' => $url['title'],
                                'url' => trim($url['url'])
                            );

                            // Verify if url was saved
                            if ( $this->CI->base_model->insert('crm_chatbot_websites_guests_visited_urls', $url_params) ) {
                                $new_saved_url[] = trim($url['url']);
                            }

                        }

                    }

                }

            }

        }

        // Get the thread
        $the_thread = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_threads',
            '*',
            array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'website_id' => $params['website']['website_id']
            )
        );

        // Set thread's ID
        $thread_id = !empty($the_thread)?$the_thread[0]['thread_id']:0;
        
        // Verify if the thread exists
        if ( !$the_thread ) {

            // Thread params
            $thread_params = array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'website_id' => $params['website']['website_id'],
                'status' => 1,         
                'created' => time(),
                'updated' => time()
            );

            // Save thread
            $the_thread = $this->CI->base_model->insert('crm_chatbot_websites_threads', $thread_params);

            // Verify if the thread was saved
            if ( !$the_thread ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent')
                ); 
                
            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_threads_alerts') ) {

                // Save the notification
                $this->save_notification(
                    $params['website']['user_id'],
                    $this->CI->lang->line('crm_chatbot_a_new_thread_created'),
                    $this->CI->lang->line('crm_chatbot_a_new_thread_was_created') . ' <a href="' . site_url('user/app/crm_chatbot?thread=' . $the_thread) . '">'
                        . $this->CI->lang->line('crm_chatbot_click_here_more_details')
                    . '</a>',
                    'crm_chatbot_thread_id',
                    $the_thread
                );

            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_threads_notifications') ) {

                // Send the notification
                $this->send_notification( $params['website']['user_id'],
                    $params['website']['website_id'],
                    array(array(
                        'placeholder' => '[thread_id]',
                        'replacer' => $the_thread
                    ), array(
                        'placeholder' => '[thread_link]',
                        'replacer' => '<a href="' . site_url('user/app/crm_chatbot?thread=' . $the_thread) . '">'
                            . site_url('user/app/crm_chatbot?thread=' . $the_thread)
                        . '</a>'
                    )),
                    'crm_chatbot_new_thread_notification',
                    $this->CI->lang->line('crm_chatbot_a_new_thread_was_created'),
                    $this->CI->lang->line('crm_chatbot_new_thread_was_created')
                );

            }

            // Set thread's ID
            $thread_id = $the_thread;

        } else {

            // Verify if the thread is blocked
            if ( $the_thread[0]['status'] > 1 ) {

                // Return error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_chat_is_blocked')
                );       

            }   
            
            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_messages_alerts') ) {

                // Save the notification
                $this->save_notification(
                    $params['website']['user_id'],
                    $this->CI->lang->line('crm_chatbot_new_message_received'),
                    $this->CI->lang->line('crm_chatbot_you_have_received_new_message') . ' <a href="' . site_url('user/app/crm_chatbot?thread=' . $thread_id) . '">'
                        . $this->CI->lang->line('crm_chatbot_click_here_more_details')
                    . '</a>',
                    'crm_chatbot_thread_id',
                    $thread_id
                );

            }

            // Verify if the user wants to receive notifications
            if ( md_the_user_option($params['website']['user_id'], 'crm_chatbot_new_messages_notifications') ) {

                // Send the notification
                $this->send_notification( $params['website']['user_id'],
                    $params['website']['website_id'],
                    array(array(
                        'placeholder' => '[thread_id]',
                        'replacer' => $thread_id
                    ), array(
                        'placeholder' => '[thread_link]',
                        'replacer' => '<a href="' . site_url('user/app/crm_chatbot?thread=' . $thread_id) . '">'
                            . site_url('user/app/crm_chatbot?thread=' . $thread_id)
                        . '</a>'
                    )),
                    'crm_chatbot_new_message_notification',
                    $this->CI->lang->line('crm_chatbot_a_new_message_was_created'),
                    $this->CI->lang->line('crm_chatbot_new_message_was_created')
                );

            }

            // Update the thread
            $this->CI->base_model->update('crm_chatbot_websites_threads', array('thread_id' => $thread_id), array('updated' => time()));

        }

        // Prepare the message
        $message_params = array(
            'user_id' => $params['website']['user_id'],
            'guest_id' => $guest_id,
            'thread_id' => $thread_id,
            'message_body' => trim($params['message']),
            'created' => time()
        );

        // Save the message
        $message_id = $this->CI->base_model->insert('crm_chatbot_websites_messages', $message_params);

        // Try to send the message
        if ( $message_id ) {

            // Verify if the guest don't has saved the latitude
            if ( !the_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude') ) {

                // Verify if the ip2location is enabled
                if ( md_the_option('app_crm_chatbot_ip2location_enabled') ) {

                    // Verify if api key exists
                    if ( md_the_option('app_crm_chatbot_ip2location_api_key') ) {

                        // Get guest information
                        $the_guest_info = json_decode(file_get_contents('https://api.ip2location.com/v2/?ip=' . $this->CI->input->ip_address() . '&key=' . md_the_option('app_crm_chatbot_ip2location_api_key') . '&package=WS25'), TRUE);

                        // Verify if response key exists
                        if ( !empty($the_guest_info['response']) ) {

                            // Verify if response value is OK
                            if ( $the_guest_info['response'] === 'OK' ) {

                                // Verify if latitude exists
                                if ( !empty($the_guest_info['latitude']) ) {

                                    // Save the latitude
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude', $the_guest_info['latitude']); 

                                }

                                // Verify if longitude exists
                                if ( !empty($the_guest_info['longitude']) ) {

                                    // Save the longitude
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_longitude', $the_guest_info['longitude']); 

                                }
                                
                                // Verify if country code exists
                                if ( !empty($the_guest_info['country_code']) ) {

                                    // Save the country code
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_code', $the_guest_info['country_code']); 

                                }
                                
                                // Verify if country name exists
                                if ( !empty($the_guest_info['country_name']) ) {

                                    // Save the country name
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_name', $the_guest_info['country_name']); 

                                }
                                
                                // Verify if city name exists
                                if ( !empty($the_guest_info['city_name']) ) {

                                    // Save the city name
                                    update_crm_chatbot_websites_guests_meta($guest_id, 'guest_city_name', $the_guest_info['city_name']); 

                                }                             

                            }

                        }

                    }

                }

            }

            // Found array
            $found_numbers = array();

            // Save all phone numbers in $found_numbers
            preg_match_all('@[^a-zA-Z]{7,}@is', trim($params['message']), $found_numbers);

            // Verify if phone numbers exists
            if ( !empty($found_numbers[0]) ) {

                // List all phone numbers
                foreach ( $found_numbers[0] as $phone ) {

                    // Verify if number is greater than 5 characters
                    if ( strlen($phone) > 5 ) {

                        // Verify if the number is already saved
                        if ( !$this->CI->base_model->the_data_where(
                            'crm_chatbot_numbers',
                            '*',
                            array(
                                'user_id' => $params['website']['user_id'],
                                'number' => trim($phone),                                
                            )
                        ) ) {

                            // Prepare the phone
                            $phone_data = array(
                                'user_id' => $params['website']['user_id'],
                                'message_id' => $message_id,
                                'website_id' => $params['website']['website_id'],
                                'thread_id' => $thread_id,
                                'message_id' => $message_id,
                                'number' => trim($phone),
                                'created' => time()
                            );

                            // Save the phone number
                            $this->CI->base_model->insert('crm_chatbot_numbers', $phone_data);

                        }

                    }

                }

            }

            // Found array
            $found_emails = array();

            // Save all email addresses in $found_emails
            preg_match_all('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i', trim($params['message']), $found_emails);

            // Verify if email addresses exists
            if ( !empty($found_emails[0]) ) {

                // List all email addresses
                foreach ( $found_emails[0] as $email ) {

                    // Prepare the email
                    $email_data = array(
                        'user_id' => $params['website']['user_id'],
                        'message_id' => $message_id,
                        'website_id' => $params['website']['website_id'],
                        'thread_id' => $thread_id,
                        'message_id' => $message_id,
                        'email' => trim($email),
                        'created' => time()
                    );

                    // Save the email
                    $this->CI->base_model->insert('crm_chatbot_emails', $email_data);

                }

            }

            // Set number of allowed automatic replies
            $allowed_replies = md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies', $user_plan)?md_the_plan_feature('app_crm_chatbot_allowed_automatic_replies', $user_plan):0; 
            
            // Set done automatic replies
            $automatic_replies = md_the_user_option($params['website']['user_id'], 'crm_chatbot_automatic_replies');

            // The automatic replies container
            $the_automatic_replies = 0;

            // Verify if automatic replies exists
            if ( $automatic_replies ) {

                // Unserialize array
                $replies_array = unserialize($automatic_replies);

                // Set replies
                $the_automatic_replies = $replies_array['replies'];

            }

            // Verify if the quick replies are enabled
            if ( empty($the_thread[0]['auto']) && ($the_automatic_replies < $allowed_replies) ) {

                // Get the website's categories
                $the_website_categories = $this->CI->base_model->the_data_where('crm_chatbot_websites_categories', '*', array('website_id' => $params['website']['website_id']) );

                // Verify if categories exists
                if ( $the_website_categories ) {

                    // Get the quick replies
                    $the_quick_replies = $this->CI->base_model->the_data_where(
                        'crm_chatbot_quick_replies',
                        'crm_chatbot_quick_replies.*, crm_chatbot_quick_replies_categories.category_id',
                        array(),
                        array(
                            'crm_chatbot_quick_replies_categories.category_id', array_column($the_website_categories, 'category_id'),
                            'crm_chatbot_quick_replies.status >' => 0
                        ),
                        array(),
                        array(array(
                            'table' => 'crm_chatbot_quick_replies_categories',
                            'condition' => "crm_chatbot_quick_replies.reply_id=crm_chatbot_quick_replies_categories.reply_id",
                            'join_from' => 'LEFT'
                        ))
                    );
                    
                    // Verify if the quick replies exists
                    if ( $the_quick_replies ) {

                        // Best replies
                        $best_replies = array();

                        // List all quick replies
                        foreach ( $the_quick_replies as $the_quick_reply ) {

                            // Get the keywords
                            $the_keywords = explode(' ', trim(strtolower($the_quick_reply['keywords'])));

                            // Verify if the keywords exists
                            if ($the_keywords) {
                                
                                // Get the difference
                                $the_difference = count(array_diff(explode(' ', trim(strtolower($params['message']))), $the_keywords)) + count(array_diff($the_keywords, explode(' ', trim(strtolower($params['message'])))));

                                // Verify if the difference is 100%
                                if ( $the_difference === (count(explode(' ', trim(strtolower($params['message'])))) + count($the_keywords)) ) {
                                    continue;
                                }

                                // Calculate percentage
                                $the_percent = (1 - $the_difference / (count($the_keywords) + count(explode(' ', trim(strtolower($params['message'])))))) * 100;

                                // Verify if the keyword is good
                                if ($the_percent >= $the_quick_reply['accuracy']) {

                                    $best_replies[$the_percent] = $the_quick_reply;

                                }

                            }

                        }
                        
                        // Verify if the best replies exists
                        if ( $best_replies ) {

                            // Get the best match
                            $key = max(array_keys($best_replies));

                            // Verify if is a text response
                            if ( empty($best_replies[$key]['response_type']) ) {

                                // Prepare the response
                                $message_params = array(
                                    'user_id' => $params['website']['user_id'],
                                    'thread_id' => $thread_id,
                                    'bot' => 1,
                                    'reply_id' => $best_replies[$key]['reply_id'],
                                    'message_body' => $best_replies[$key]['response_text'],
                                    'created' => time()
                                );

                                // Try to save the quick reply's response
                                if ( $this->CI->base_model->insert('crm_chatbot_websites_messages', $message_params) ) {

                                    // Save new reply
                                    $this->set_automatic_reply_number($params['website']['user_id']);

                                    // Verify if the guest has category
                                    if ( !$this->CI->base_model->the_data_where(
                                        'crm_chatbot_websites_guests_categories',
                                        '*',
                                        array(
                                            'guest_id' => $guest_id,
                                            'category_id' => $best_replies[$key]['category_id']
                                        )
                                    ) ) {

                                        // Assign the category
                                        if ( $this->CI->base_model->insert('crm_chatbot_websites_guests_categories', array(
                                            'guest_id' => $guest_id,
                                            'category_id' => $best_replies[$key]['category_id']
                                        )) ) {

                                            // Run hook when a category is assigned
                                            md_run_hook(
                                                'crm_chatbot_new_assigned_category',
                                                array(
                                                    'guest_id' => $guest_id,
                                                    'thread_id' => $thread_id,
                                                    'category_id' => $best_replies[$key]['category_id']
                                                )
                                            );

                                        }

                                    }
                                    
                                }

                            } else {

                                // Set params
                                $bot_params = array(
                                    'bot' => $best_replies[$key]['response_bot']
                                );

                                // Get response
                                $response = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsBot\Read)->crm_chatbot_get_bot_start($bot_params);

                                // Verify if the bot exists
                                if ( !empty($response['success']) ) {

                                    // Prepare the response
                                    $message_params = array(
                                        'user_id' => $params['website']['user_id'],
                                        'thread_id' => $thread_id,
                                        'bot' => 2,
                                        'bot_id' => $best_replies[$key]['response_bot'],
                                        'reply_id' => $best_replies[$key]['reply_id'],
                                        'message_body' => json_encode($response['element']),
                                        'created' => time()
                                    );

                                    // Try to save the quick reply's response
                                    $this->CI->base_model->insert('crm_chatbot_websites_messages', $message_params);

                                    // Save new reply
                                    $this->set_automatic_reply_number($params['website']['user_id']);

                                    // Verify if the guest has category
                                    if ( !$this->CI->base_model->the_data_where(
                                        'crm_chatbot_websites_guests_categories',
                                        '*',
                                        array(
                                            'guest_id' => $guest_id,
                                            'category_id' => $best_replies[$key]['category_id']
                                        )
                                    ) ) {

                                        // Assign the category
                                        if ( $this->CI->base_model->insert('crm_chatbot_websites_guests_categories', array(
                                            'guest_id' => $guest_id,
                                            'category_id' => $best_replies[$key]['category_id']
                                        )) ) {

                                            // Run hook when a category is assigned
                                            md_run_hook(
                                                'crm_chatbot_new_assigned_category',
                                                array(
                                                    'guest_id' => $guest_id,
                                                    'thread_id' => $thread_id,
                                                    'category_id' => $best_replies[$key]['category_id']
                                                )
                                            );

                                        }

                                    }

                                } else {

                                    // Disable the quick reply
                                    $this->CI->base_model->update('crm_chatbot_quick_replies', array('reply_id' => $best_replies[$key]['reply_id']), array('status' => 0));

                                }

                            }

                        }

                    }

                }

            }

            // Create data to return
            $data = array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_message_was_sent')
            );

            // Get the last messages
            $the_messages = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsMessages\Read)->crm_chatbot_get_unseen_messages(array(
                'user_id' => $params['website']['user_id'],
                'guest_id' => $guest_id,
                'website_id' => $params['website']['website_id'],
                'last' => ($message_id - 1)
            ));

            // Verify if messages were found
            if ( !empty($the_messages['success']) ) {

                // Set messages
                $data['messages'] = $the_messages['messages'];

                // Set bot
                $data['bot'] = $the_messages['bot'];

                // Verify if attachments exists
                if ( !empty($the_messages['attachments']) ) {

                    // Set the attachments
                    $data['attachments'] = $the_messages['attachments'];

                }

            }

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_websites_threads_list');
            delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_numbers_list');
            delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_emails_list');
            delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_websites_guests_list');
            delete_crm_cache_cronology_for_user($params['website']['user_id'], 'crm_chatbot_websites_visited_urls_list');

            // Return success response
            return $data;             

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_message_was_not_sent')
            ); 
            
        }
        
    }

    //-----------------------------------------------------
    // Quick Helpers for the Messages
    //-----------------------------------------------------

    /**
     * The pretected method clear removes special characters
     * 
     * @param string $string contains the string to clear
     * 
     * @since 0.0.8.5
     * 
     * @return string with clean string
     */
    protected function clear($string) {

        // Removes empty space
        return trim($string);

    }

    /**
     * The protected method set_automatic_reply_number adds a new message count
     *
     * @param integer $user_id contains user_id
     * 
     * @return boolean true or false
     */ 
    protected function set_automatic_reply_number( $user_id ) {
        
        // Get number of automatic replies
        $sent_replies = md_the_user_option($user_id, 'crm_chatbot_automatic_replies');
        
        // Verify if bot has replied before
        if ( $sent_replies ) {
            
            // Unserialize array
            $replies_array = unserialize($sent_replies);
            
            // Verify if the bot has replies in this month
            if ( $replies_array['date'] === date('Y-m') ) {
                
                // Get number of replies
                $replies = $replies_array['replies'];
                
                // Increase the number
                $replies++;
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'replies' => $replies
                    )
                );
                
            } else {
                
                // Set new record
                $record = serialize(
                    array(
                        'date' => date('Y-m'),
                        'replies' => 1
                    )
                );  
                
            }
            
        } else {
            
            // Set new record
            $record = serialize(
                array(
                    'date' => date('Y-m'),
                    'replies' => 1
                )
            );
            
        }
        
        md_update_user_option($user_id, 'crm_chatbot_automatic_replies', $record);
        
    }

    /**
     * The protected method save_notification saves a notification
     *
     * @param integer $user_id contains the user id
     * @param string $alert_title contains the alert's title
     * @param string $alert_message contains the alert's message
     * @param string $alert_scope contains the alert's scope
     * @param string $alert_scope_id contains the alert's scope id
     * 
     * @return void
     */ 
    protected function save_notification( $user_id, $alert_title, $alert_message, $alert_scope, $alert_scope_id ) {

        // Alert fields
        $alert_fields = array();

        // Get all languages
        $languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

        // List all languages
        foreach ($languages as $language) {

            // Get lang dir name
            $lang = str_replace(APPPATH . 'language' . '/', '', $language);

            // Set alert's banner enabled
            $alert_fields[] = array(
                'field_name' => 'banner_enabled',
                'field_value' => 1,
                'language' => $lang
            );
            
            // Set alert's id
            $alert_fields[] = array(
                'field_name' => $alert_scope,
                'field_value' => $alert_scope_id,
                'language' => $lang
            );

            // Set alert's banner
            $alert_fields[] = array(
                'field_name' => 'banner_content',
                'field_value' => $alert_message,
                'language' => $lang
            );

            // Set alert's page title
            $alert_fields[] = array(
                'field_name' => 'page_title',
                'field_value' => $alert_title,
                'language' => $lang
            );
            
            // Set alert's page content
            $alert_fields[] = array(
                'field_name' => 'page_content',
                'field_value' => $alert_message,
                'language' => $lang
            );                     

        }

        // Save the alert
        md_save_admin_notifications_alert(
            array(
                'alert_name' => $this->CI->lang->line('crm_chatbot_new_chatbot_notification'),
                'alert_type' => 0,
                'alert_audience' => 0,
                'alert_fields' => $alert_fields,
                'alert_filters' => array(),
                'alert_users' => array(
                    array(
                        'user_id' => $user_id
                    )
                )
            )
        );
        
    }

    /**
     * The protected method send_notification sends a notification
     *
     * @param integer $user_id contains the user id
     * @param integer $website_id contains the website's id
     * @param array $additional_placeholders contains additional placeholders
     * @param string $template contains the template's string
     * @param string $subject contains the email's subject
     * @param string $body contains the email's body
     * 
     * @return void
     */ 
    protected function send_notification( $user_id, $website_id, $additional_placeholders, $template, $subject, $body) {

        // Set language
        $user_language = md_the_user_option($user_id, 'user_language')?md_the_user_option($user_id, 'user_language'):$this->CI->config->item('language');

        // Receivers list
        $receivers_list = array(
            array(
                'first_name' => md_the_user_option($user_id, 'first_name'),
                'last_name' => md_the_user_option($user_id, 'last_name'),
                'email' => md_the_user_option($user_id, 'email')
            )
        );

        // Get the team's members
        $the_members = $this->CI->base_model->the_data_where(
            'teams',
            'teams.member_username, teams.member_email, teams.role_id, teams_meta.meta_value AS first_name, last.meta_value AS last_name',
            array(
                'teams.user_id' => $user_id
            ),
            array(),
            array(),
            array(array(
                'table' => 'teams_meta',
                'condition' => "teams.member_id=teams_meta.member_id AND teams_meta.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last',
                'condition' => "teams.member_id=last.member_id AND last.meta_name='last_name'",
                'join_from' => 'LEFT'
            ))
        );

        // Verify if members exists
        if ( $the_members ) {

            // List members
            foreach ( $the_members as $the_member ) {

                // Verify if member has access
                if ( !the_crm_team_roles_multioptions_list_item($user_id,  $the_member['role_id'], 'crm_chatbot_allowed_websites', $website_id) ) {
                    continue;
                }

                // Set new receiver
                $receivers_list[] = array(
                    'first_name' => $the_member['first_name'],
                    'last_name' => $the_member['last_name'],
                    'email' => $the_member['member_email']
                );

            }

        }

        // Verify if receivers exists
        if ( $receivers_list ) {

            // List the receivers
            foreach ( $receivers_list as $receiver ) {

                // Placeholders
                $placeholders = array(
                    '[first_name]',
                    '[last_name]',
                    '[website_url]',
                    '[website_name]'
                );

                // Replacers
                $replacers = array(
                    $receiver['first_name'],
                    $receiver['last_name'],
                    site_url(),
                    $this->CI->config->item('site_name')
                );

                // Verify if placeholders exists
                if ( $additional_placeholders ) {

                    // List the placeholders
                    foreach ( $additional_placeholders as $placeholder ) {

                        // Add placeholder
                        $placeholders[] = $placeholder['placeholder'];

                        // Add replacer
                        $replacers[] = $placeholder['replacer'];                        

                    }

                }

                // Get template
                $the_template = the_admin_notifications_email_template($template, $user_language);

                // Verify if $the_template exists
                if ( $the_template ) {

                    // New subject
                    $subject = $the_template[0]['template_title'];

                    // New body
                    $body = $the_template[0]['template_body'];

                }

                // Create email
                $email_args = array(
                    'from_name' => $this->CI->config->item('site_name'),
                    'from_email' => $this->CI->config->item('contact_mail'),
                    'to_email' => $receiver['email'],
                    'subject' => str_replace($placeholders, $replacers, $subject),
                    'body' => str_replace($placeholders, $replacers, $body)
                );

                // Send notification template
                (new CmsBaseClassesEmail\Send())->send_mail($email_args);

            }

        }
        
    }

}

/* End of file create.php */