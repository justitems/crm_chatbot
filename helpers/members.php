<?php
/**
 * Members Helpers
 *
 * This file contains the class Members
 * with methods to manage the members
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Members class provides the methods to manage the members
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Members {
    
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
    // Ajax's methods for the Members
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_team_members gets the team's members
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_team_members() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set like
                $like = !empty($key)?array('teams_meta.meta_value' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($key)))):array();

                // Gets the team's members
                $the_team_members = $this->CI->base_model->the_data_where(
                    'teams_meta',
                    'teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name',
                    array(
                        'teams.user_id' => md_the_user_id(),
                        'teams.status >' => 0
                    ),
                    array(),
                    $like,
                    array(array(
                        'table' => 'teams',
                        'condition' => 'teams_meta.member_id=teams.member_id',
                        'join_from' => 'LEFT'
                    ), array(
                        'table' => 'teams_meta first_name',
                        'condition' => "teams.member_id=first_name.member_id AND first_name.meta_name='first_name'",
                        'join_from' => 'LEFT'
                    ), array(
                        'table' => 'teams_meta last_name',
                        'condition' => "teams.member_id=last_name.member_id AND last_name.meta_name='last_name'",
                        'join_from' => 'LEFT'
                    )),
                    array(
                        'group_by' => 'teams.member_id',
                        'start' => 0,
                        'limit' => 10
                    )
                );

                // Verify if members exists
                if ( $the_team_members ) {

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'members' => $the_team_members
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }
        
        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_members_found')
        );

        // Display the error message
        echo json_encode($data);    
        
    }

    /**
     * The public method crm_chatbot_get_website_members gets the team members for a website
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_website_members() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread', 'trim');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get the thread
                $the_thread = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    '*',
                    array(
                        'thread_id' => $thread,
                        'user_id' => md_the_user_id()
                    )
                );

                // Verify if the thread exists
                if ( !$the_thread ) {

                    // Prepare the error message
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_members_found')
                    );

                    // Display the error message
                    echo json_encode($data); 
                    exit();
                    
                }

                // Set where parameters
                $where = array(
                    'teams.user_id' => md_the_user_id(),
                    'teams.status' => 0,
                    'teams_roles_multioptions_list.option_value' => $the_thread[0]['website_id']
                );

                // Get parameters string
                $parameters_string = $this->generate_string(array(
                    'thread' => $thread,
                    'page' => $page
                ));

                // Verify if the cache exists for this query
                if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_thread_team_members_' . $parameters_string) ) {

                    // Set the cache
                    $the_team_members = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_thread_team_members_' . $parameters_string);

                } else {    

                    // Gets the team's members
                    $the_team_members = $this->CI->base_model->the_data_where(
                        'teams_meta',
                        'teams.member_id, teams.member_username, first_name.meta_value AS member_first_name, last_name.meta_value AS member_last_name, medias.body AS profile_image',
                        $where,
                        array(),
                        array(),
                        array(array(
                            'table' => 'teams',
                            'condition' => 'teams_meta.member_id=teams.member_id',
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
                            'table' => 'teams_roles_multioptions_list',
                            'condition' => "teams.role_id=teams_roles_multioptions_list.role_id AND teams_roles_multioptions_list.option_slug='crm_chatbot_allowed_websites'",
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'teams_meta member_profile_image',
                            'condition' => "teams.member_id=member_profile_image.member_id AND member_profile_image.meta_name='profile_image'",
                            'join_from' => 'LEFT'
                        ), array(
                            'table' => 'medias',
                            'condition' => 'member_profile_image.meta_value=medias.media_id',
                            'join_from' => 'LEFT'
                        )),
                        array(
                            'group_by' => 'teams.member_id',
                            'start' => ($page * 10),
                            'limit' => 10
                        )
                    );

                    // Save cache
                    md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_thread_team_members_' . $parameters_string, $the_team_members);

                    // Set saved cronology
                    update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_threads_teams_members', 'crm_chatbot_user_' . md_the_user_id() . '_thread_team_members_' . $parameters_string);

                }

                // Verify if members exists
                if ( $the_team_members ) {

                    // Verify if the cache exists for this query
                    if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_thread_team_members_' . $parameters_string) ) {

                        // Set cache
                        $the_total = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_thread_team_members_' . $parameters_string);

                    } else {

                        // Get the total number of members
                        $the_total = $this->CI->base_model->the_data_where(
                            'teams_meta',
                            'COUNT(teams.member_id) AS total',
                            $where,
                            array(),
                            array(),
                            array(array(
                                'table' => 'teams',
                                'condition' => 'teams_meta.member_id=teams.member_id',
                                'join_from' => 'LEFT'
                            ), array(
                                'table' => 'teams_roles_multioptions_list',
                                'condition' => "teams.role_id=teams_roles_multioptions_list.role_id AND teams_roles_multioptions_list.option_slug='crm_chatbot_allowed_websites'",
                                'join_from' => 'LEFT'
                            )),
                            array(
                                'group_by' => 'teams.member_id'
                            )
                        );

                        // Save cache
                        md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_thread_team_members_' . $parameters_string, $the_total);

                        // Set saved cronology
                        update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_threads_teams_members', 'crm_chatbot_user_' . md_the_user_id() . '_load_total_thread_team_members_' . $parameters_string);

                    }

                    // Prepare success response
                    $data = array(
                        'success' => TRUE,
                        'members' => $the_team_members,
                        'total' => $the_total[0]['total'],
                        'page' => ($page + 1)
                    );

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }
        
        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_members_found')
        );

        // Display the error message
        echo json_encode($data);    
        
    }

    /**
     * The public method crm_chatbot_send_member_invite sends team's member invite to join a thread
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_send_member_invite() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('thread', 'Thread', 'trim');
            $this->CI->form_validation->set_rules('member', 'Member', 'trim');

            // Get data
            $thread = $this->CI->input->post('thread', TRUE);
            $member = $this->CI->input->post('member', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get team's member
                $the_member = the_crm_current_team_member();

                // Verify if member's name exists
                if ( isset($the_member['member_name']) ) {

                    // Send an invitation
                    $response = send_crm_team_message(array(
                        'subject' => $this->CI->lang->line('crm_chatbot_chatbot_thread_invite'),
                        'message' => $the_member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_invited_you_to_join') . ' <a href="' . site_url('user/app/crm_chatbot?thread=' . $thread) . '">#' . $thread . '</a>',
                        'receivers' => array($member)
                    ));

                    // Verify if the invitation was not sent
                    if ( !empty($response['success']) ) {

                        // Prepare the success message
                        $data = array(
                            'success' => TRUE,
                            'message' => $this->CI->lang->line('crm_chatbot_invitation_was_sent')
                        );

                        // Display the success message
                        echo json_encode($data);
                        exit();

                    }

                }

            }

        }
        
        // Prepare the error message
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_invitation_was_not_sent')
        );

        // Display the error message
        echo json_encode($data);    
        
    }

    //-----------------------------------------------------
    // Quick Helpers for the Members
    //-----------------------------------------------------

    /**
     * The protected method generate_string generates a string for cache file
     * 
     * @param array $args contains the parameters
     * 
     * @since 0.0.8.5
     * 
     * @return string
     */
    protected function generate_string($args) {

        // Create and return string
        return str_replace(' ', '_', implode('_', $args) );
        
    }

}

/* End of file members.php */