<?php
/**
 * Categories Read Helper
 *
 * This file read contains the methods
 * to read the categories
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Categories;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Categories to make it lighter
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
    // Ajax's methods for the Categories
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_the_categories gets the categories list by page
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_the_categories($params) {
        
        // Set where
        $where = array(
            'crm_chatbot_categories.user_id' => $this->CI->user_id
        );

        // Set where in
        $where_in = array();

        // Set like
        $like = isset($params['key'])?array('category_name' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Set join
        $join = array();

        // Set limit
        $limit = array(
            'order' =>  array('crm_chatbot_categories.category_id', 'DESC'),
        );

        // Verify if start exists
        if ( isset($params['page']) ) {

            // Set the start
            $limit['start'] = (($params['page'] > 0)?($params['page'] - 1):0) * 10;

            // Set the limit
            $limit['limit'] = $params['limit'];

        }

        // Get parameters string
        $parameters_string = $this->generate_string($params);

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_' . $parameters_string) ) {

            // Set the cache
            $the_categories = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_' . $parameters_string);

        } else {

            // Get the categories categories
            $the_categories = $this->CI->base_model->the_data_where(
                'crm_chatbot_categories',
                '*',
                $where,
                $where_in,
                $like,
                $join,
                $limit
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_' . $parameters_string, $the_categories);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_categories_list', 'crm_chatbot_user_' . $this->CI->user_id . '_categories_' . $parameters_string);

        }

        // Verify if the categories exists
        if ( $the_categories ) {

            // Verify if the cache exists for this query
            if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_categories_' . $parameters_string) ) {

                // Get total categories
                $the_total = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_categories_' . $parameters_string);

            } else {

                // Get the total categories
                $the_total = $this->CI->base_model->the_data_where(
                    'crm_chatbot_categories',
                    'COUNT(crm_chatbot_categories.category_id) AS total',
                    $where,
                    $where_in,
                    $like,
                    $join
                );

                // Save cache
                md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_load_total_categories_' . $parameters_string, $the_total);

                // Set saved cronology
                update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_categories_list', 'crm_chatbot_user_' . $this->CI->user_id . '_load_total_categories_' . $parameters_string);

            }

            // Prepare success response
            return array(
                'success' => TRUE,
                'categories' => $the_categories,
                'total' => $the_total[0]['total'],

            );

        } else {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_categories_were_found')
            );            

        }

    }

    /**
     * The public method crm_chatbot_the_all_categories gets the all categories list
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_the_all_categories($params) {
        
        // Set where
        $where = array(
            'crm_chatbot_categories.user_id' => $this->CI->user_id
        );

        // Set like
        $like = isset($params['key'])?array('category_name' => trim(str_replace('!_', '_', $this->CI->db->escape_like_str($params['key'])))):array();

        // Set key
        $key = isset($params['key'])?'_show_by_' . $this->generate_string(array('key' => $params['key'])):'';

        // Verify if the cache exists for this query
        if ( md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_all' . $key) ) {

            // Set the cache
            $the_categories = md_the_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_all' . $key);

        } else {

            // Get the categories categories
            $the_categories = $this->CI->base_model->the_data_where(
                'crm_chatbot_categories',
                '*',
                $where,
                array(),
                $like
            );

            // Save cache
            md_create_cache('crm_chatbot_user_' . $this->CI->user_id . '_categories_all' . $key, $the_categories);

            // Set saved cronology
            update_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_categories_list', 'crm_chatbot_user_' . $this->CI->user_id . '_categories_all' . $key);

        }

        // Verify if the categories exists
        if ( $the_categories ) {

            // Prepare the response
            $response = array(
                'success' => TRUE,
                'categories' => $the_categories
            );

            // Verify if the bot's parameter exists
            if ( !empty($params['bot']) ) {

                // Get the bot's categories
                $the_bot_categories = $this->CI->base_model->the_data_where(
                    'crm_chatbot_bots_categories',
                    'category_id',
                    array(
                        'bot_id' => $params['bot']
                    )
                );
                
                // Verify if the bot contains categories
                if ( $the_bot_categories ) {

                    // Set categories
                    $response['bot_categories'] = array_column($the_bot_categories, 'category_id');

                }

            }

            // Verify if the reply's parameter exists
            if ( !empty($params['reply']) ) {

                // Get the reply's categories
                $the_reply_categories = $this->CI->base_model->the_data_where(
                    'crm_chatbot_quick_replies_categories',
                    'category_id',
                    array(
                        'reply_id' => $params['reply']
                    )
                );
                
                // Verify if the reply contains categories
                if ( $the_reply_categories ) {

                    // Set categories
                    $response['reply_categories'] = array_column($the_reply_categories, 'category_id');

                }

            }  
            
            // Verify if the website's parameter exists
            if ( !empty($params['website']) ) {

                // Get the website's categories
                $the_website_categories = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites_categories',
                    'category_id',
                    array(
                        'website_id' => $params['website']
                    )
                );
                
                // Verify if the website contains categories
                if ( $the_website_categories ) {

                    // Set categories
                    $response['website_categories'] = array_column($the_website_categories, 'category_id');

                }

            } 

            // Prepare success response
            return $response;

        } else {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_categories_were_found')
            );            

        }

    }

    //-----------------------------------------------------
    // Quick Helpers for the Categories
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