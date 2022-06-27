<?php
/**
 * Websites Read Helper
 *
 * This file read contains the methods
 * to read the websites
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
 * Read class extends the class Websites to make it lighter
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
    // Ajax's methods for the Websites
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_websites gets the websites list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_the_websites($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_websites_were_found')
            );         

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites.user_id' => md_the_user_id()
        );

        // Set where in
        $where_in = array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
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
                    
                    // Verify if $websites is not empty
                    if ( $websites ) {

                        // Set team's member to params
                        $params['team_member'] = 1;

                        // Set where in
                        $where_in = array('crm_chatbot_websites.website_id', $websites);

                    } else {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_websites_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = isset($params['key'])?array('domain' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order_by' =>  array('crm_chatbot_websites.website_id', 'DESC'),
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
        if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_websites_' . $parameters_string) ) {

            // Set the cache
            $the_websites = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_websites_' . $parameters_string);

        } else {

            // Get the websites websites
            $the_websites = $this->CI->base_model->the_data_where(
                'crm_chatbot_websites',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_websites_' . $parameters_string, $the_websites);

            // Set saved cronology
            update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list', 'crm_chatbot_user_' . md_the_user_id() . '_websites_' . $parameters_string);

        }

        // Verify if the websites exists
        if ( $the_websites ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_websites_' . $parameters_string) ) {

                // Get total websites
                $the_total = md_the_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_websites_' . $parameters_string);

            } else {

                // Get the total websites
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    'COUNT(crm_chatbot_websites.website_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . md_the_user_id() . '_load_total_websites_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_websites_list', 'crm_chatbot_user_' . md_the_user_id() . '_load_total_websites_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'websites' => $the_websites,
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
                'crm_chatbot_websites',
                '*',
                array(
                    'website_id' => $params['bot'],
                    'user_id' => md_the_user_id()
                )
            );

            // Verify if the bot exists
            if ( $the_bot ) {

                // Bot container
                $bot = array();

                // Get all elements
                $the_bot_elements_properties = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_elements_properties',
                    'crm_chatbot_websites_elements_properties.*, crm_chatbot_websites_elements.top, crm_chatbot_websites_elements.left, crm_chatbot_websites_elements.operator_id, crm_chatbot_websites_elements.operator_type',
                    array(
                        'crm_chatbot_websites_elements.website_id' => $params['bot']
                    ),
                    array(),
                    array(),
                    array(array(
                        'table' => 'crm_chatbot_websites_elements',
                        'condition' => 'crm_chatbot_websites_elements_properties.element_id=crm_chatbot_websites_elements.element_id',
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
                        'crm_chatbot_websites_elements_links',
                        '*',
                        array(
                            'website_id' => $params['bot']
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
    // Quick Helpers for the Websites
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