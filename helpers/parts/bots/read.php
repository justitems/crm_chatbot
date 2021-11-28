<?php
/**
 * Bots Read Helper
 *
 * This file read contains the methods
 * to read the bots
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bots;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Bots to make it lighter
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
    // Ajax's methods for the Bots
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_bots gets the bots list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_the_bots($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_bots_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_bots.user_id' => $this->CI->user_id
        );

        // Set where in
        $where_in = array();

        // Set like
        $like = isset($params['key'])?array('bot_name' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order' =>  array('crm_chatbot_bots.bot_id', 'DESC'),
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
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_bots_' . $parameters_string) ) {

            // Set the cache
            $the_bots = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_bots_' . $parameters_string);

        } else {

            // Get the bots bots
            $the_bots = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_bots_' . $parameters_string, $the_bots);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list', 'crm_chatbot_user_' . $this->CI->user_id . '_bots_' . $parameters_string);

        }

        // Verify if the bots exists
        if ( $the_bots ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_bots_' . $parameters_string) ) {

                // Get total bots
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_bots_' . $parameters_string);

            } else {

                // Get the total bots
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_bots',
                    'COUNT(crm_chatbot_bots.bot_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_bots_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_bots_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'bots' => $the_bots,
                'total' => $the_total[0]['total']
            );

        }

    }

    /**
     * The public method crm_chatbot_the_bot gets the bot's data
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_the_bot($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Return array with error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_data_was_not_found')
            );              

        }
        
        // Verify if the bot exists
        if ( isset($params['bot']) ) {

            // Get the bot
            $the_bot = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots',
                '*',
                array(
                    'bot_id' => $params['bot'],
                    'user_id' => $this->CI->user_id
                )
            );

            // Verify if the bot exists
            if ( $the_bot ) {

                // Bot container
                $bot = array();

                // Get all elements
                $the_bot_elements_properties = $this->CI->base_model->the_data_where(
                    'crm_chatbot_bots_elements_properties',
                    'crm_chatbot_bots_elements_properties.*, crm_chatbot_bots_elements.top, crm_chatbot_bots_elements.left, crm_chatbot_bots_elements.operator_id, crm_chatbot_bots_elements.operator_type',
                    array(
                        'crm_chatbot_bots_elements.bot_id' => $params['bot']
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'crm_chatbot_bots_elements',
                        'condition' => 'crm_chatbot_bots_elements_properties.element_id=crm_chatbot_bots_elements.element_id',
                        'join_from' => 'LEFT'
                    ))
                );
                
                // Verify if properties exists
                if ( $the_bot_elements_properties ) {

                    // Set the bot elements
                    $bot['elements'] = array_reduce($the_bot_elements_properties, function($container, $element) {

                        // Verify if element's ID missing
                        if ( !isset($container[$element['element_id']]) ) {

                            // Set element data
                            $container[$element['element_id']] = array(
                                'parameters' => array(
                                    'top' => $element['top'],
                                    'left' => $element['left'],
                                    'operator_id' => $element['operator_id'],
                                    'operator_type' => $element['operator_type']
                                ),
                                'properties' => array()
                            );

                        }

                        // Add element
                        $container[$element['element_id']]['properties'][] = array(
                            'input' => $element['input'],
                            'output' => $element['output'],
                            'type' => $element['type'],
                            'value' => $element['value'],
                        );

                        return $container;

                    }, []);

                    // Get all links
                    $the_bot_elements_links = $this->CI->base_model->the_data_where(
                        'crm_chatbot_bots_elements_links',
                        '*',
                        array(
                            'bot_id' => $params['bot']
                        )
                    );
                    
                    // Verify if the links exists
                    if ( $the_bot_elements_links ) {

                        // Set the links
                        $bot['links'] = $the_bot_elements_links;

                    }

                    // Return array with bot's data
                    return array(
                        'success' => TRUE,
                        'bot_data' => $bot
                    );

                }

            }

            // Return array with error message
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_data_was_not_found')
            );            

        }

    }

    //-----------------------------------------------------
    // Quick Helpers for the Bots
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