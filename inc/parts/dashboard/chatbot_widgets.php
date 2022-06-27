<?php
/**
 * Chatbot Widgets Inc Part
 *
 * PHP Version 7.3
 *
 * This files contains the widgets functions
 * to display the widgets content in the Dashboard
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

 // Define the constants
defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('the_crm_chatbot_widgets_dashboard_from_parts') ) {
    
    /**
     * The function the_crm_chatbot_widgets_dashboard_from_parts gets chatbot widget content
     * 
     * @return string with response
     */
    function the_crm_chatbot_widgets_dashboard_from_parts() {

        // Get CodeIgniter object instance
        $CI =& get_instance();

        // Set params
        $params = array(
            'user_id' => md_the_user_id()
        );

        // Set where in
        $where_in = array();

        // Verify if member exists
        if ( $CI->session->userdata( 'member' ) ) {

            // Get team's member
            $member = $CI->session->userdata('member')?the_crm_current_team_member():array();            

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Get the websites
                $the_websites_list = $CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => md_the_user_id()
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
                            continue;
                        }

                        // Add website to the list
                        $websites[] = $the_website['website_id'];

                    } 

                    // Set team's member to params
                    $params['team_member'] = 1;                    
                    
                    // Verify if $websites is not empty
                    if ( $websites ) {

                        // Set where in
                        $where_in = array('crm_chatbot_websites.website_id', $websites);

                    }

                }

            }

        }

        // Generate a string
        $parameters_string = str_replace(' ', '_', implode('_', $params) );

        // Threads container
        $threads = '';

        // Verify if is a team's member
        if ( (!empty($params['team_member']) && !empty($websites)) || empty($params['team_member']) ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_newest_threads_' . $parameters_string) ) {

                // Get the threads
                $the_threads = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_newest_threads_' . $parameters_string);

            } else {

                // Set join
                $join = array(
                    array(
                        'table' => 'crm_chatbot_websites',
                        'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                        'join_from' => 'LEFT'
                    )
                );

                // Get the threads
                $the_threads = $CI->base_model->the_data_where(
                    'crm_chatbot_websites_threads',
                    'crm_chatbot_websites_threads.*, crm_chatbot_websites.domain',
                    array(
                        'crm_chatbot_websites_threads.user_id' => md_the_user_id()
                    ),
                    $where_in,
                    array(),
                    array(
                        array(
                            'table' => 'crm_chatbot_websites',
                            'condition' => 'crm_chatbot_websites_threads.website_id=crm_chatbot_websites.website_id',
                            'join_from' => 'LEFT'
                        )
                    ),
                    array(
                        'order_by' => array('crm_chatbot_websites_threads.updated', 'DESC'),
                        'start' => 0,
                        'limit' => 5
                    )
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_newest_threads_' . $parameters_string, $the_threads);

                // Set saved cronology
                update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_threads_list', 'crm_chatbot_user_' . md_the_user_id() . '_newest_threads_' . $parameters_string);

            }

            // Verify if threads exists
            if ( $the_threads ) {

                // List the threads
                foreach ( $the_threads as $the_thread ) {

                    // Default status
                    $status = '<div class="d-inline-block theme-font-4 crm-chatbot-list-item-status crm-chatbot-list-item-status-active">'
                        . '<span class="badge theme-font-2">'
                            . $CI->lang->line('crm_chatbot_active')
                        . '</span>'
                    . '</div>';

                    // Verify which status is different
                    if ( (int)$the_thread['status'] === 2 ) {

                        // Change status
                        $status = '<div class="d-inline-block theme-font-4 crm-chatbot-list-item-status crm-chatbot-list-item-status-blocked">'
                            . '<span class="badge theme-font-2">'
                                . $CI->lang->line('crm_chatbot_blocked')
                            . '</span>'
                        . '</div>';    

                    } else if ( (int)$the_thread['updated'] < (time() - 3600) ) {

                        // Change status
                        $status = '<div class="d-inline-block theme-font-4 crm-chatbot-list-item-status crm-chatbot-list-item-status-inactive">'
                            . '<span class="badge theme-font-2">'
                                . $CI->lang->line('crm_chatbot_inactive')
                            . '</span>'
                        . '</div>';                

                    }

                    // Add thread to the container
                    $threads .= '<li class="list-group-item d-flex justify-content-between align-items-center">'
                        . '<div class="row">'
                            . '<div class="col-4">'
                                . '<div class="crm-chatbot-thread-name">'
                                    . '<h5 class="mt-0 mb-1">'
                                        . '<a href="' . site_url('user/app/crm_chatbot?thread=' . $the_thread['thread_id']) . '">'
                                            . '#' . $the_thread['thread_id']
                                        . '</a>'
                                        . '<span>'
                                        . '<i class="material-icons-outlined">'
                                            . md_the_user_icon(array('icon' => 'public'))
                                        . '</i>'
                                        . $the_thread['domain']
                                        . '</span>'
                                    . '</h5>'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-4 crm-chatbot-time text-center">'
                                . '<div>'
                                    . '<span>'
                                    . '</span>'
                                . '</div>'
                            . '</div>'
                            . '<div class="col-4 text-right">'
                                . $status
                            . '</div>'                        
                        . '</div>'
                    . '</li>';

                }

            }

        }

        // Verify if threads exists
        if ( !$threads ) {

            // Set the no found message
            $threads = '<li class="list-group-item crm-dashboard-no-data-found">'
                . $CI->lang->line('crm_chatbot_no_threads_found')
            . '</li>';

        }

        // Return widget
        return '<div class="crm-dashboard-widget-model-vertical-tabs">'
            . '<div class="row">'
                . '<div class="col-md-3">'
                    . '<div class="nav flex-column nav-pills" id="crm-chatbot-newest-threads-pills-tab" role="tablist" aria-orientation="vertical">'
                        . '<a class="nav-link active" id="crm-chatbot-newest-threads-tab" data-toggle="pill" href="#crm-chatbot-newest-threads" role="tab" aria-controls="crm-chatbot-newest-threads" aria-selected="true">'
                            . $CI->lang->line('crm_chatbot_newest_threads')
                        . '</a>'
                    . '</div>'
                . '</div>'
                . '<div class="col-md-9">'
                    . '<div class="tab-content" id="v-pills-tabContent">'
                        . '<div class="tab-pane fade show active" id="crm-chatbot-newest-threads" role="tabpanel" aria-labelledby="crm-chatbot-newest-threads-tab">'
                            . '<ul class="list-group crm-chatbot-threads-list">'
                                . $threads
                            . '</ul>'
                        . '</div>'
                    . '</div>'
                . '</div>'
            . '</div>'
        . '</div>';

    }

}

/* End of file chatbot_widgets.php */