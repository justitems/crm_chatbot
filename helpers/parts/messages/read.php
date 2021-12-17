<?php
/**
 * Messages Read Helper
 *
 * This file read contains the methods
 * to read the messages
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

/*
 * Read class extends the class Messages to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Read {
    
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
    // Ajax's methods for the Messages
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_private_messages gets the thread's messages
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return array with response
     */
    public function crm_chatbot_get_private_messages($params) {

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
                'user_id' => $this->CI->user_id
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

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if the website is allowed
            if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                );                  
                
            }

        }

        // Require the Website Functions Inc file
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

        // Set page
        $page = !empty($params['page'])?($params['page'] - 1):0;

        // Set limit
        $limit = 0;

        // Get messages if exists
        $the_messages = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages',
            'crm_chatbot_websites_messages.*, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
            users.user_id, users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
            array(
                'crm_chatbot_websites_threads.thread_id' => $params['thread']
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_threads',
                'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams',
                'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'users',
                'condition' => "crm_chatbot_websites_messages.user_id=users.user_id",
                'join_from' => 'LEFT'
            )),
            array(
                'order_by' => array('crm_chatbot_websites_messages.message_id', 'DESC'),
                'start' => $page,
                'limit' => $limit
            )
        );

        // Verify if messages exists
        if ( $the_messages ) {

            // Get total number of messages
            $the_total_messages = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages',
                'COUNT(crm_chatbot_websites_messages.message_id) AS total',
                array(
                    'crm_chatbot_websites_threads.thread_id' => $params['thread']
                ),
                array(),
                array(),
                array(array(
                    'table' => 'crm_chatbot_websites_threads',
                    'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                    'join_from' => 'LEFT'
                ))
            );

            // Messages ids
            $messages_ids = array();
            
            // Messages container
            $messages = array();

            // User date
            $user_date = the_crm_date_format($this->CI->user_id);

            // User time
            $user_time = the_crm_time_format($this->CI->user_id);

            // Set wanted date format
            $date_format = str_replace(array('dd', 'mm', 'yyyy'), array('d', 'm', 'Y'), $user_date);

            // Set profile images
            $profile_images = array();

            // List the found messages
            foreach ( $the_messages AS $the_message ) {

                // Set message id
                $messages_ids[] = $the_message['message_id'];

                // Set created
                $the_message['created'] = (($the_message['created'] + 86400) < time())?date($date_format, $the_message['created']):the_crm_calculate_time($this->CI->user_id, $the_message['created']);

                // Verify if member's ID exists
                if ( !empty($the_message['member_id']) ) {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['member_id']])?$profile_images[$the_message['member_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = the_crm_team_member_option('profile_image', $the_message['member_id']);

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['member_id']] = $the_image[0]['body'];

                            }

                        }

                    }
                    
                } else {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['user_id']])?$profile_images[$the_message['user_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = md_the_user_option($the_message['user_id'], 'profile_image');

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['user_id']] = $the_image[0]['body'];

                            }

                        }

                    }

                }

                // Author data
                $author = array(
                    'user_id' => $the_message['user_id'],
                    'username' => $the_message['user_username'],
                    'first_name' => $the_message['user_first_name'],
                    'last_name' => $the_message['user_last_name']
                );

                // Verify if the author is a team's member
                if ( !empty($the_message['member_id']) ) {

                    // Author data
                    $author = array(
                        'member_id' => $the_message['member_id'],
                        'member_username' => $the_message['member_username'],
                        'member_first_name' => $the_message['member_first_name'],
                        'member_last_name' => $the_message['member_last_name']
                    );                        

                }

                // Create author's data
                $author = array(
                    'member_id' => !empty($the_message['member_id'])?$author['member_id']:$author['user_id'],
                    'member_username' => !empty($the_message['member_id'])?$author['member_username']:$author['username'],
                    'first_name' => !empty($the_message['member_id'])?$author['member_first_name']:$author['first_name'],
                    'last_name' => !empty($the_message['member_id'])?$author['member_last_name']:$author['last_name'],
                    'team_member' => !empty($the_message['member_id'])?true:false
                );

                // Verify if author has profile image
                if ( !empty($the_message['profile_image']) ) {

                    // Set profile image
                    $author['profile_image'] = $the_message['profile_image'];

                } 

                // Set author
                $the_message['author'] = $author;

                // Set message
                $messages[] = array(
                    'message_id' => $the_message['message_id'],
                    'guest_id' => $the_message['guest_id'],
                    'bot' => $the_message['bot'],
                    'message_body' => $the_message['message_body'],
                    'created' => $the_message['created'],
                    'author' => $author
                );

            }

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'messages' => $messages,
                'total' => $the_total_messages[0]['total'],
                'page' => ($page + 1),
                'bot' => array(
                    'name' => the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_name'):$this->CI->lang->line('crm_chatbot_bot'),
                    'photo' => the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                ),
                'words' => array(
                    'guest' => $this->CI->lang->line('crm_chatbot_guest')
                )
            );

            // Get all attachments
            $the_attachments = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages_attachments',
                'crm_chatbot_websites_messages_attachments.message_id, medias.*',
                array(),
                array(
                    'crm_chatbot_websites_messages_attachments.message_id', $messages_ids
                ),
                array(),
                array(array(
                    'table' => 'medias',
                    'condition' => 'crm_chatbot_websites_messages_attachments.media_id=medias.media_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if attachments exists
            if ( $the_attachments ) {

                // Reduce the array
                $response['attachments'] = array_reduce($the_attachments, function($accumulator, $attachment) {

                    // Verify if message id already exists
                    if ( empty($accumulator[$attachment['message_id']]) ) {

                        // Set message id
                        $accumulator[$attachment['message_id']] = array();

                    }

                    // Set message
                    $accumulator[$attachment['message_id']][] = array(
                        'media_id' => $attachment['media_id'],
                        'name' => $attachment['name'],
                        'body' => $attachment['body'],
                    );

                    return $accumulator;

                }, []);

            }

            // Return success response
            return $response;

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );      
            
        }

    }

    /**
     * The public method crm_chatbot_get_public_messages gets the public messages
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_get_public_messages($params) {

        // Verify if the session exists
        if ( empty($params['guest']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
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

        // Verify if the quest exists
        if ( !$the_guest ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );             

        }

        // Set page
        $page = !empty($params['page'])?($params['page'] - 1):0;

        // Set limit
        $limit = 0;

        // Get messages if exists
        $the_messages = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages',
            'crm_chatbot_websites_messages.*, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
            users.user_id, users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
            array(
                'crm_chatbot_websites_threads.user_id' => $params['website']['user_id'],
                'crm_chatbot_websites_threads.guest_id' => $the_guest[0]['guest_id'],
                'crm_chatbot_websites_threads.website_id' => $params['website']['website_id']
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_threads',
                'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams',
                'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'users',
                'condition' => "crm_chatbot_websites_messages.user_id=users.user_id",
                'join_from' => 'LEFT'
            )),
            array(
                'order_by' => array('crm_chatbot_websites_messages.message_id', 'DESC'),
                'start' => $page,
                'limit' => $limit
            )
        );

        // Verify if messages exists
        if ( $the_messages ) {

            // Get total number of messages
            $the_total_messages = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages',
                'COUNT(crm_chatbot_websites_messages.message_id) AS total',
                array(
                    'crm_chatbot_websites_threads.user_id' => $params['website']['user_id'],
                    'crm_chatbot_websites_threads.guest_id' => $the_guest[0]['guest_id'],
                    'crm_chatbot_websites_threads.website_id' => $params['website']['website_id']
                ),
                array(),
                array(),
                array(array(
                    'table' => 'crm_chatbot_websites_threads',
                    'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                    'join_from' => 'LEFT'
                ))
            );

            // Messages ids
            $messages_ids = array();
            
            // Messages container
            $messages = array();

            // User date
            $user_date = the_crm_date_format($params['website']['user_id']);

            // User time
            $user_time = the_crm_time_format($params['website']['user_id']);

            // Set wanted date format
            $date_format = str_replace(array('dd', 'mm', 'yyyy'), array('d', 'm', 'Y'), $user_date);

            // Set profile images
            $profile_images = array();

            // List the found messages
            foreach ( $the_messages AS $the_message ) {

                // Set message id
                $messages_ids[] = $the_message['message_id'];

                // Set created
                $the_message['created'] = (($the_message['created'] + 86400) < time())?date($date_format, $the_message['created']):the_crm_calculate_time($params['website']['user_id'], $the_message['created']);

                // Verify if member's ID exists
                if ( !empty($the_message['member_id']) ) {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['member_id']])?$profile_images[$the_message['member_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = the_crm_team_member_option('profile_image', $the_message['member_id']);

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['member_id']] = $the_image[0]['body'];

                            }

                        }

                    }
                    
                } else {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['user_id']])?$profile_images[$the_message['user_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = md_the_user_option($the_message['user_id'], 'profile_image');

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['user_id']] = $the_image[0]['body'];

                            }

                        }

                    }

                }

                // Author data
                $author = array(
                    'user_id' => $the_message['user_id'],
                    'username' => $the_message['user_username'],
                    'first_name' => $the_message['user_first_name'],
                    'last_name' => $the_message['user_last_name']
                );

                // Verify if the author is a team's member
                if ( !empty($the_message['member_id']) ) {

                    // Author data
                    $author = array(
                        'member_id' => $the_message['member_id'],
                        'member_username' => $the_message['member_username'],
                        'member_first_name' => $the_message['member_first_name'],
                        'member_last_name' => $the_message['member_last_name']
                    );                        

                }

                // Create author's data
                $author = array(
                    'member_id' => !empty($the_message['member_id'])?$author['member_id']:$author['user_id'],
                    'member_username' => !empty($the_message['member_id'])?$author['member_username']:$author['username'],
                    'first_name' => !empty($the_message['member_id'])?$author['member_first_name']:$author['first_name'],
                    'last_name' => !empty($the_message['member_id'])?$author['member_last_name']:$author['last_name'],
                    'team_member' => !empty($the_message['member_id'])?true:false
                );

                // Verify if author has profile image
                if ( !empty($the_message['profile_image']) ) {

                    // Set profile image
                    $author['profile_image'] = $the_message['profile_image'];

                } 

                // Set author
                $the_message['author'] = $author;

                // Set message
                $messages[] = array(
                    'message_id' => $the_message['message_id'],
                    'guest_id' => $the_message['guest_id'],
                    'bot' => $the_message['bot'],
                    'message_body' => $the_message['message_body'],
                    'created' => $the_message['created'],
                    'author' => $author
                );

            }

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'messages' => $messages,
                'total' => $the_total_messages[0]['total'],
                'page' => ($page + 1),
                'bot' => array(
                    'name' => the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_name'):$this->CI->lang->line('crm_chatbot_bot'),
                    'photo' => the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                )
            );

            // Get all attachments
            $the_attachments = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages_attachments',
                'crm_chatbot_websites_messages_attachments.message_id, medias.*',
                array(),
                array(
                    'crm_chatbot_websites_messages_attachments.message_id', $messages_ids
                ),
                array(),
                array(array(
                    'table' => 'medias',
                    'condition' => 'crm_chatbot_websites_messages_attachments.media_id=medias.media_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if attachments exists
            if ( $the_attachments ) {

                // Reduce the array
                $response['attachments'] = array_reduce($the_attachments, function($accumulator, $attachment) {

                    // Verify if message id already exists
                    if ( empty($accumulator[$attachment['message_id']]) ) {

                        // Set message id
                        $accumulator[$attachment['message_id']] = array();

                    }

                    // Set message
                    $accumulator[$attachment['message_id']][] = array(
                        'media_id' => $attachment['media_id'],
                        'name' => $attachment['name'],
                        'body' => $attachment['body'],
                    );

                    return $accumulator;

                }, []);

            }

            // Return success response
            return $response;

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );      
            
        }

    }

    /**
     * The public method crm_chatbot_get_unseen_messages gets the unseen messages
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_get_unseen_messages($params) {

        // Get messages if exists
        $the_messages = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages',
            'crm_chatbot_websites_messages.*, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
            users.user_id, users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
            array(
                'crm_chatbot_websites_threads.user_id' => $params['user_id'],
                'crm_chatbot_websites_threads.guest_id' => $params['guest_id'],
                'crm_chatbot_websites_threads.website_id' => $params['website_id'],
                'crm_chatbot_websites_messages.message_id >' => $params['last']
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_threads',
                'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams',
                'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'users',
                'condition' => "crm_chatbot_websites_messages.user_id=users.user_id",
                'join_from' => 'LEFT'
            )),
            array(
                'order_by' => array('crm_chatbot_websites_messages.message_id', 'DESC')
            )
        );

        // Verify if messages exists
        if ( $the_messages ) {

            // Messages ids
            $messages_ids = array();
            
            // Messages container
            $messages = array();

            // User date
            $user_date = the_crm_date_format($params['user_id']);

            // User time
            $user_time = the_crm_time_format($params['user_id']);

            // Set wanted date format
            $date_format = str_replace(array('dd', 'mm', 'yyyy'), array('d', 'm', 'Y'), $user_date);

            // Set profile images
            $profile_images = array();

            // List the found messages
            foreach ( $the_messages AS $the_message ) {

                // Set message id
                $messages_ids[] = $the_message['message_id'];

                // Set created
                $the_message['created'] = (($the_message['created'] + 86400) < time())?date($date_format, $the_message['created']):the_crm_calculate_time($params['user_id'], $the_message['created']);

                // Verify if member's ID exists
                if ( !empty($the_message['member_id']) ) {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['member_id']])?$profile_images[$the_message['member_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = the_crm_team_member_option('profile_image', $the_message['member_id']);

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['member_id']] = $the_image[0]['body'];

                            }

                        }

                    }
                    
                } else {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['user_id']])?$profile_images[$the_message['user_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = md_the_user_option($the_message['user_id'], 'profile_image');

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['user_id']] = $the_image[0]['body'];

                            }

                        }

                    }

                }

                // Author data
                $author = array(
                    'user_id' => $the_message['user_id'],
                    'username' => $the_message['user_username'],
                    'first_name' => $the_message['user_first_name'],
                    'last_name' => $the_message['user_last_name']
                );

                // Verify if the author is a team's member
                if ( !empty($the_message['member_id']) ) {

                    // Author data
                    $author = array(
                        'member_id' => $the_message['member_id'],
                        'member_username' => $the_message['member_username'],
                        'member_first_name' => $the_message['member_first_name'],
                        'member_last_name' => $the_message['member_last_name']
                    );                        

                }

                // Create author's data
                $author = array(
                    'member_id' => !empty($the_message['member_id'])?$author['member_id']:$author['user_id'],
                    'member_username' => !empty($the_message['member_id'])?$author['member_username']:$author['username'],
                    'first_name' => !empty($the_message['member_id'])?$author['member_first_name']:$author['first_name'],
                    'last_name' => !empty($the_message['member_id'])?$author['member_last_name']:$author['last_name'],
                    'team_member' => !empty($the_message['member_id'])?true:false
                );

                // Verify if author has profile image
                if ( !empty($the_message['profile_image']) ) {

                    // Set profile image
                    $author['profile_image'] = $the_message['profile_image'];

                } 

                // Set author
                $the_message['author'] = $author;

                // Set message
                $messages[] = array(
                    'message_id' => $the_message['message_id'],
                    'guest_id' => $the_message['guest_id'],
                    'bot' => $the_message['bot'],
                    'message_body' => $the_message['message_body'],
                    'created' => $the_message['created'],
                    'author' => $author
                );

            }

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'messages' => $messages,
                'bot' => array(
                    'name' => the_crm_chatbot_websites_meta($params['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($params['website_id'], 'bot_name'):$this->CI->lang->line('crm_chatbot_bot'),
                    'photo' => the_crm_chatbot_websites_meta($params['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($params['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                )
            );

            // Get all attachments
            $the_attachments = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages_attachments',
                'crm_chatbot_websites_messages_attachments.message_id, medias.*',
                array(),
                array(
                    'crm_chatbot_websites_messages_attachments.message_id', $messages_ids
                ),
                array(),
                array(array(
                    'table' => 'medias',
                    'condition' => 'crm_chatbot_websites_messages_attachments.media_id=medias.media_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if attachments exists
            if ( $the_attachments ) {

                // Reduce the array
                $response['attachments'] = array_reduce($the_attachments, function($accumulator, $attachment) {

                    // Verify if message id already exists
                    if ( empty($accumulator[$attachment['message_id']]) ) {

                        // Set message id
                        $accumulator[$attachment['message_id']] = array();

                    }

                    // Set message
                    $accumulator[$attachment['message_id']][] = array(
                        'media_id' => $attachment['media_id'],
                        'name' => $attachment['name'],
                        'body' => $attachment['body'],
                    );

                    return $accumulator;

                }, []);

            }

            // Return success response
            return $response;

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );      
            
        }

    }

    /**
     * The public method crm_chatbot_check_for_new_thread_messages gets the unseen messages
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_check_for_new_thread_messages($params) {

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
                'user_id' => $this->CI->user_id
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

        // Get team's member
        $member = the_crm_current_team_member();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if the website is allowed
            if ( !the_crm_team_roles_multioptions_list_item($this->CI->user_id,  $member['role_id'], 'crm_chatbot_allowed_websites', $the_thread[0]['website_id']) ) {

                // Prepare the false response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_thread_was_not_found')
                );                  
                
            }

        }

        // Verify if the reply_on_hold parameter exists
        if ( !empty($params['reply_on_hold']) ) {

            // Get the replies on hold
            $the_replies = md_the_cache('replies_on_hold_for_thread_' . $params['thread'])?md_the_cache('replies_on_hold_for_thread_' . $params['thread']):array();

            // Verify if replies on hold exists
            if ( $the_replies ) {

                // Delete cache
                md_delete_cache('replies_on_hold_for_thread_' . $params['thread']);

            }

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Update time
                $the_replies['m_' . $member['member_id']] = array(
                    'time' => time(),
                    'member_name' => $member['member_name']
                );

            } else {

                // Update time
                $the_replies[$this->CI->user_id] = array(
                    'time' => time(),
                    'member_name' => $member['member_name']
                );

            }

            // Save replies
            md_create_cache('replies_on_hold_for_thread_' . $params['thread'], $the_replies);

        }        

        // Require the Website Functions Inc file
        require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/website_functions.php';

        // Set last ID
        $last_id = !empty($params['last'])?$params['last']:0;

        // Get messages if exists
        $the_messages = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages',
            'crm_chatbot_websites_messages.*, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
            users.user_id, users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
            array(
                'crm_chatbot_websites_messages.message_id >' => $last_id,
                'crm_chatbot_websites_threads.thread_id' => $params['thread']
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_threads',
                'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams',
                'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'users',
                'condition' => "crm_chatbot_websites_messages.user_id=users.user_id",
                'join_from' => 'LEFT'
            )),
            array(
                'order_by' => array('crm_chatbot_websites_messages.message_id', 'DESC')
            )
        );

        // Verify if messages exists
        if ( $the_messages ) {

            // Messages ids
            $messages_ids = array();
            
            // Messages container
            $messages = array();

            // User date
            $user_date = the_crm_date_format($this->CI->user_id);

            // User time
            $user_time = the_crm_time_format($this->CI->user_id);

            // Set wanted date format
            $date_format = str_replace(array('dd', 'mm', 'yyyy'), array('d', 'm', 'Y'), $user_date);

            // Set profile images
            $profile_images = array();

            // List the found messages
            foreach ( $the_messages AS $the_message ) {

                // Set message id
                $messages_ids[] = $the_message['message_id'];

                // Set created
                $the_message['created'] = (($the_message['created'] + 86400) < time())?date($date_format, $the_message['created']):the_crm_calculate_time($this->CI->user_id, $the_message['created']);

                // Verify if member's ID exists
                if ( !empty($the_message['member_id']) ) {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['member_id']])?$profile_images[$the_message['member_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = the_crm_team_member_option('profile_image', $the_message['member_id']);

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['member_id']] = $the_image[0]['body'];

                            }

                        }

                    }
                    
                } else {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['user_id']])?$profile_images[$the_message['user_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = md_the_user_option($the_message['user_id'], 'profile_image');

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['user_id']] = $the_image[0]['body'];

                            }

                        }

                    }

                }

                // Author data
                $author = array(
                    'user_id' => $the_message['user_id'],
                    'username' => $the_message['user_username'],
                    'first_name' => $the_message['user_first_name'],
                    'last_name' => $the_message['user_last_name']
                );

                // Verify if the author is a team's member
                if ( !empty($the_message['member_id']) ) {

                    // Author data
                    $author = array(
                        'member_id' => $the_message['member_id'],
                        'member_username' => $the_message['member_username'],
                        'member_first_name' => $the_message['member_first_name'],
                        'member_last_name' => $the_message['member_last_name']
                    );                        

                }

                // Create author's data
                $author = array(
                    'member_id' => !empty($the_message['member_id'])?$author['member_id']:$author['user_id'],
                    'member_username' => !empty($the_message['member_id'])?$author['member_username']:$author['username'],
                    'first_name' => !empty($the_message['member_id'])?$author['member_first_name']:$author['first_name'],
                    'last_name' => !empty($the_message['member_id'])?$author['member_last_name']:$author['last_name'],
                    'team_member' => !empty($the_message['member_id'])?true:false
                );

                // Verify if author has profile image
                if ( !empty($the_message['profile_image']) ) {

                    // Set profile image
                    $author['profile_image'] = $the_message['profile_image'];

                } 

                // Set author
                $the_message['author'] = $author;

                // Set message
                $messages[] = array(
                    'message_id' => $the_message['message_id'],
                    'guest_id' => $the_message['guest_id'],
                    'bot' => $the_message['bot'],
                    'message_body' => $the_message['message_body'],
                    'created' => $the_message['created'],
                    'author' => $author
                );

            }

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'messages' => $messages,
                'bot' => array(
                    'name' => the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_name'):$this->CI->lang->line('crm_chatbot_bot'),
                    'photo' => the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($the_thread[0]['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                ),
                'words' => array(
                    'guest' => $this->CI->lang->line('crm_chatbot_guest')
                )
            );

            // Get all attachments
            $the_attachments = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages_attachments',
                'crm_chatbot_websites_messages_attachments.message_id, medias.*',
                array(),
                array(
                    'crm_chatbot_websites_messages_attachments.message_id', $messages_ids
                ),
                array(),
                array(array(
                    'table' => 'medias',
                    'condition' => 'crm_chatbot_websites_messages_attachments.media_id=medias.media_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if attachments exists
            if ( $the_attachments ) {

                // Reduce the array
                $response['attachments'] = array_reduce($the_attachments, function($accumulator, $attachment) {

                    // Verify if message id already exists
                    if ( empty($accumulator[$attachment['message_id']]) ) {

                        // Set message id
                        $accumulator[$attachment['message_id']] = array();

                    }

                    // Set message
                    $accumulator[$attachment['message_id']][] = array(
                        'media_id' => $attachment['media_id'],
                        'name' => $attachment['name'],
                        'body' => $attachment['body'],
                    );

                    return $accumulator;

                }, []);

            }

            // Return success response
            return $response;

        } else {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );      
            
        }

    }

    /**
     * The public method crm_chatbot_get_updates gets updates for guests
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_get_updates($params) {

        // Verify if the session exists
        if ( empty($params['guest']) ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
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

        // Verify if the quest exists
        if ( !$the_guest ) {

            // Return error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );             

        }

        // Get messages if exists
        $the_messages = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_messages',
            'crm_chatbot_websites_messages.*, teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name,
            users.user_id, users.username AS user_username, users.first_name AS user_first_name, users.last_name AS user_last_name',
            array(
                'crm_chatbot_websites_threads.user_id' => $params['website']['user_id'],
                'crm_chatbot_websites_threads.guest_id' => $the_guest[0]['guest_id'],
                'crm_chatbot_websites_threads.website_id' => $params['website']['website_id'],
                'crm_chatbot_websites_messages.message_id >' => $params['last']
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_websites_threads',
                'condition' => "crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams',
                'condition' => 'crm_chatbot_websites_messages.member_id=teams.member_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta first_name',
                'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'teams_meta last_name',
                'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'users',
                'condition' => "crm_chatbot_websites_messages.user_id=users.user_id",
                'join_from' => 'LEFT'
            )),
            array(
                'order_by' => array('crm_chatbot_websites_messages.message_id', 'DESC')
            )
        );

        // Verify if messages exists
        if ( $the_messages ) {

            // Delete the replies
            md_delete_cache('replies_on_hold_for_thread_' . $the_messages[0]['thread_id']);

            // Messages ids
            $messages_ids = array();
            
            // Messages container
            $messages = array();

            // User date
            $user_date = the_crm_date_format($params['website']['user_id']);

            // User time
            $user_time = the_crm_time_format($params['website']['user_id']);

            // Set wanted date format
            $date_format = str_replace(array('dd', 'mm', 'yyyy'), array('d', 'm', 'Y'), $user_date);

            // Set profile images
            $profile_images = array();

            // List the found messages
            foreach ( $the_messages AS $the_message ) {

                // Set message id
                $messages_ids[] = $the_message['message_id'];

                // Set created
                $the_message['created'] = (($the_message['created'] + 86400) < time())?date($date_format, $the_message['created']):the_crm_calculate_time($params['website']['user_id'], $the_message['created']);

                // Verify if member's ID exists
                if ( !empty($the_message['member_id']) ) {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['member_id']])?$profile_images[$the_message['member_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = the_crm_team_member_option('profile_image', $the_message['member_id']);

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['member_id']] = $the_image[0]['body'];

                            }

                        }

                    }
                    
                } else {

                    // Get profile image
                    $profile_image = isset($profile_images[$the_message['user_id']])?$profile_images[$the_message['user_id']]:'';

                    // Verify if profile image exists
                    if ( !empty($profile_image) ) {

                        // Set profile image
                        $the_message['profile_image'] = $profile_image;

                    } else {

                        // Get profile image
                        $profile_image = md_the_user_option($the_message['user_id'], 'profile_image');

                        // Verify if the user has a profile image
                        if ( $profile_image ) {

                            // Get user's image
                            $the_image = $this->CI->base_model->the_data_where('medias', 'body', array('media_id' => $profile_image));

                            // Verify if image exists
                            if ( $the_image ) {

                                // Set image
                                $the_message['profile_image'] = $the_image[0]['body'];
                                $profile_images[$the_message['user_id']] = $the_image[0]['body'];

                            }

                        }

                    }

                }

                // Author data
                $author = array(
                    'user_id' => $the_message['user_id'],
                    'username' => $the_message['user_username'],
                    'first_name' => $the_message['user_first_name'],
                    'last_name' => $the_message['user_last_name']
                );

                // Verify if the author is a team's member
                if ( !empty($the_message['member_id']) ) {

                    // Author data
                    $author = array(
                        'member_id' => $the_message['member_id'],
                        'member_username' => $the_message['member_username'],
                        'member_first_name' => $the_message['member_first_name'],
                        'member_last_name' => $the_message['member_last_name']
                    );                        

                }

                // Create author's data
                $author = array(
                    'member_id' => !empty($the_message['member_id'])?$author['member_id']:$author['user_id'],
                    'member_username' => !empty($the_message['member_id'])?$author['member_username']:$author['username'],
                    'first_name' => !empty($the_message['member_id'])?$author['member_first_name']:$author['first_name'],
                    'last_name' => !empty($the_message['member_id'])?$author['member_last_name']:$author['last_name'],
                    'team_member' => !empty($the_message['member_id'])?true:false
                );

                // Verify if author has profile image
                if ( !empty($the_message['profile_image']) ) {

                    // Set profile image
                    $author['profile_image'] = $the_message['profile_image'];

                } 

                // Set author
                $the_message['author'] = $author;

                // Set message
                $messages[] = array(
                    'message_id' => $the_message['message_id'],
                    'guest_id' => $the_message['guest_id'],
                    'bot' => $the_message['bot'],
                    'message_body' => $the_message['message_body'],
                    'created' => $the_message['created'],
                    'author' => $author
                );

            }

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'messages' => $messages,
                'bot' => array(
                    'name' => the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_name')?the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_name'):$this->CI->lang->line('crm_chatbot_bot'),
                    'photo' => the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_photo')?the_crm_chatbot_websites_meta($params['website']['website_id'], 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                )
            );

            // Get all attachments
            $the_attachments = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages_attachments',
                'crm_chatbot_websites_messages_attachments.message_id, medias.*',
                array(),
                array(
                    'crm_chatbot_websites_messages_attachments.message_id', $messages_ids
                ),
                array(),
                array(array(
                    'table' => 'medias',
                    'condition' => 'crm_chatbot_websites_messages_attachments.media_id=medias.media_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if attachments exists
            if ( $the_attachments ) {

                // Reduce the array
                $response['attachments'] = array_reduce($the_attachments, function($accumulator, $attachment) {

                    // Verify if message id already exists
                    if ( empty($accumulator[$attachment['message_id']]) ) {

                        // Set message id
                        $accumulator[$attachment['message_id']] = array();

                    }

                    // Set message
                    $accumulator[$attachment['message_id']][] = array(
                        'media_id' => $attachment['media_id'],
                        'name' => $attachment['name'],
                        'body' => $attachment['body'],
                    );

                    return $accumulator;

                }, []);

            }

            // Get the replies on hold
            $the_replies = md_the_cache('replies_on_hold_for_thread_' . $the_messages[0]['thread_id']);

            // Verify if replies exists
            if ( $the_replies ) {

                // Delete the replies
                md_delete_cache('replies_on_hold_for_thread_' . $the_messages[0]['thread_id']);

            }

            // Return success response
            return $response;

        } else {

            // Response
            $response = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_messages_found')
            );

            // Verify if the last message exists
            if ( !empty($params['last']) ) {

                // Get message if exists
                $the_message = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites_messages',
                '*',
                array(
                    'message_id' => $params['last']
                ));
    
                // Verify if the message was found
                if ( $the_message ) {

                    // Get the replies on hold
                    $the_replies = md_the_cache('replies_on_hold_for_thread_' . $the_message[0]['thread_id']);

                    // Verify if replies exists
                    if ( $the_replies ) {

                        // Add container
                        $add = array();

                        // Remove container
                        $remove = array();  

                        // Replies container
                        $replies = array();
                           
                        // List all replies
                        foreach ( $the_replies as $member_id => $params ) {

                            // Verify if time is less than 15 seconds
                            if ( $params['time'] > (time() - 15) ) {

                                // Add reply
                                $add[] = array(
                                    'member_id' => $member_id,
                                    'member_name' => $params['member_name']
                                );

                                // Save reply
                                $replies[$member_id] = $params;

                            } else {

                                // Remove reply
                                $remove[] = array(
                                    'member_id' => $member_id,
                                    'member_name' => $params['member_name']
                                );                            

                            }

                        }

                        // Verify if replies exists
                        if ( $replies ) {

                            // Update the replies
                            md_create_cache('replies_on_hold_for_thread_' . $the_message[0]['thread_id'], $replies);

                        } else {

                            // Delete the replies
                            md_delete_cache('replies_on_hold_for_thread_' . $the_message[0]['thread_id']);

                        }

                        // Set add and remove
                        $response['replies_on_hold'] = array(
                            'add' => $add,
                            'remove' => $remove
                        );

                    }

                }

            }

            // Return error response
            return $response;      
            
        }

    }

}

/* End of file read.php */