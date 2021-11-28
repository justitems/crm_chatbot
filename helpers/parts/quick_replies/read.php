<?php
/**
 * Quick_replies Read Helper
 *
 * This file read contains the methods
 * to read the quick_replies
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Quick_replies;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Quick_replies to make it lighter
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
    // Ajax's methods for the Quick_replies
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_quick_replies gets the quick replies list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_the_quick_replies($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_quick_replies_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_quick_replies.user_id' => $this->CI->user_id
        );

        // Set where in
        $where_in = array();

        // Set like
        $like = isset($params['key'])?array('keywords' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order' =>  array('crm_chatbot_quick_replies.reply_id', 'DESC'),
        );

        // Verify if start exists
        if ( isset($params['page']) ) {

            // Set the start
            $limit['start'] = $params['page'] * 10;

            // Set the limit
            $limit['limit'] = $params['limit'];

        }

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_quick_replies_' . $parameters_string) ) {

            // Set the cache
            $the_quick_replies = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_quick_replies_' . $parameters_string);

        } else {

            // Get the quick_replies
            $the_quick_replies = $this->CI->base_model->the_data_where(
                'crm_chatbot_quick_replies',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_quick_replies_' . $parameters_string, $the_quick_replies);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list', 'crm_chatbot_user_' . $this->CI->user_id . '_quick_replies_' . $parameters_string);

        }

        // Verify if the quick_replies exists
        if ( $the_quick_replies ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_quick_replies_' . $parameters_string) ) {

                // Get total quick_replies
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_quick_replies_' . $parameters_string);

            } else {

                // Get the total quick_replies
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_quick_replies',
                    'COUNT(crm_chatbot_quick_replies.reply_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_quick_replies_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_quick_replies_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_quick_replies_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'quick_replies' => $the_quick_replies,
                'total' => $the_total[0]['total']
            );

        }

        // Prepare the false response
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_quick_replies_were_found')
        );            

    }

    /**
     * The public method crm_chatbot_the_quick_reply gets the reply's data
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_the_quick_reply($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_quick_reply_was_found')
            );         

        }
        
        // Verify if the reply parameter exists
        if ( isset($params['reply']) ) {

            // Get the reply
            $the_reply = $this->CI->base_model->the_data_where(
                'crm_chatbot_quick_replies',
                'crm_chatbot_quick_replies.*, crm_chatbot_bots.bot_name',
                array(
                    'crm_chatbot_quick_replies.reply_id' => $params['reply'],
                    'crm_chatbot_quick_replies.user_id' => $this->CI->user_id
                ),
                array(),
                array(),
                array(array(
                    'table' => 'crm_chatbot_bots',
                    'condition' => 'crm_chatbot_quick_replies.response_bot=crm_chatbot_bots.bot_id',
                    'join_from' => 'LEFT'
                ))
            );

            // Verify if the reply exists
            if ( $the_reply ) {
                
                // Return the quick reply data
                return array(
                    'success' => TRUE,
                    'bot_name' => $the_reply[0]['bot_name']
                );                  

            }      

        }

        // Return array with error message
        return array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_quick_reply_was_found')
        );      

    }

    //-----------------------------------------------------
    // Quick Helpers for the Quick_replies
    //-----------------------------------------------------

    /**
     * The protected method generate_string generates a string for cache file
     * 
     * @param array $args contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return string
     */
    protected function generate_string($args) {

        // Create and return string
        return str_replace(' ', '_', implode('_', $args) );
        
    }

}

/* End of file read.php */