<?php
/**
 * Bot Read Helper
 *
 * This file read contains the methods
 * to read the bot's responses
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Bot;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Read class extends the class Bot to make it lighter
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
    // Ajax's methods for the Bot
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_get_bot_start shows the bot's start in the chat
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_get_bot_start($params) {

        // Verify if the bot's ID exists
        if ( empty($params['bot']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
            );   

        }

        // Get the element by connector
        $the_element = $this->the_element_by_connector($params['bot'], 'output_1');

        // Verify if element exists
        if ( $the_element ) {

            // Reduce the element's properties
            $element_properties = array_reduce($the_element, function($container, $element) {

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
                    'media_url' => $element['media_url'],
                    'bot_id' => $element['bot_id']
                );

                return $container;

            }, []);

            // Return array with element
            return array(
                'success' => TRUE,
                'element' => $element_properties
            );

        } else {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
            );  
            
        }

    }

    /**
     * The public method crm_chatbot_get_bot_message shows the bot's message in the chat
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_get_bot_message($params) {

        // Verify if the bot's ID exists
        if ( empty($params['bot']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
            );   

        }

        // Verify if the connector exists
        if ( empty($params['connector']) ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
            );   

        }

        // Get the element by connector
        $the_element = $this->the_element_by_connector($params['bot'], $params['connector']);

        // Verify if element exists
        if ( $the_element ) {

            // Reduce the element's properties
            $element_properties = array_reduce($the_element, function($container, $element) {

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
                    'media_url' => $element['media_url']
                );

                return $container;

            }, []);

            // Return array with element
            return array(
                'success' => TRUE,
                'element' => $element_properties
            );

        } else {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_response_not_available')
            );  
            
        }

    }

    //-----------------------------------------------------
    // Quick Helpers for the Bot
    //-----------------------------------------------------

    /**
     * The protected method the_element_by_connector gets an element by using the connector
     * 
     * @param integer $bot_id contains the bot's identifier
     * @param string $connector contains the contentor from
     * 
     * @since 0.0.8.4
     * 
     * @return array with the element or boolean false
     */
    protected function the_element_by_connector($bot_id, $connector) {

        // Get the element
        $the_element = $this->CI->base_model->the_data_where(
            'crm_chatbot_bots_elements_properties',
            'crm_chatbot_bots_elements_properties.*, crm_chatbot_bots_elements_links.bot_id, crm_chatbot_bots_elements.top, crm_chatbot_bots_elements.left, crm_chatbot_bots_elements.operator_id, crm_chatbot_bots_elements.operator_type, medias.body AS media_url',
            array(
                'crm_chatbot_bots_elements_links.bot_id' => $bot_id,
                'crm_chatbot_bots_elements_links.from_connector' => $connector
            ),
            array(),
            array(),
            array(array(
                'table' => 'crm_chatbot_bots_elements',
                'condition' => 'crm_chatbot_bots_elements_properties.element_id=crm_chatbot_bots_elements.element_id',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'crm_chatbot_bots_elements_links',
                'condition' => 'crm_chatbot_bots_elements.operator_id=crm_chatbot_bots_elements_links.to_operator',
                'join_from' => 'LEFT'
            ), array(
                'table' => 'crm_chatbot_bots_elements_properties property_media',
                'condition' => "crm_chatbot_bots_elements_properties.element_id=property_media.element_id AND property_media.type='medias'",
                'join_from' => 'LEFT'
            ), array(
                'table' => 'medias',
                'condition' => 'property_media.value=medias.media_id',
                'join_from' => 'LEFT'
            )),
            array(
                'group_by' => array('crm_chatbot_bots_elements_properties.property_id', 'property_media.value')
            )
        );

        // Verify if the element was found
        if ( $the_element ) {

            return $the_element;

        } else {

            return false;

        }
        
    }

}

/* End of file read.php */