<?php
/**
 * Bots Update Helper
 *
 * This file update contains the methods
 * to update bots
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
 * Update class extends the class Bots to make it lighter
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Update {
    
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
     * The public method crm_chatbot_update_bot updates a bot
     * 
     * @param array $params contains the parameters
     * 
     * @since 0.0.8.4
     * 
     * @return array with response
     */
    public function crm_chatbot_update_bot($params) {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot_edit_bots') ) {

            // Prepare error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );            

        }

        // Verify if bot's ID exists
        if ( empty($params['bot']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_was_not_found')
            );

        }

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
        if ( !$the_bot ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_bot_was_not_found')
            );            

        }

        // Verify if operators exists
        if ( empty($params['operators']) ) {

            // Prepare the error response
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_bot_data')
            );

        }

        // List all operators
        foreach ( $params['operators'] as $operator ) {

            // Verify if top parameter exists
            if ( !isset($operator['top']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => str_replace('[parameter]', 'top', $this->CI->lang->line('crm_chatbot_elements_should_have_parameter'))
                );

            }

            // Verify if top parameter is numeric
            if ( !is_numeric($operator['top']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => str_replace('[parameter]', 'top', $this->CI->lang->line('crm_chatbot_elements_should_have_parameter'))
                );

            }  
            
            // Verify if left parameter exists
            if ( !isset($operator['left']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => str_replace('[parameter]', 'left', $this->CI->lang->line('crm_chatbot_elements_should_have_parameter'))
                );

            }

            // Verify if left parameter is numeric
            if ( !is_numeric($operator['left']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => str_replace('[parameter]', 'left', $this->CI->lang->line('crm_chatbot_elements_should_have_parameter'))
                );

            }
            
            // Verify if links exists
            if ( empty($operator['links']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_elements_should_be_linked')
                );

            }

            // List all links
            foreach ( $operator['links'] as $link ) {

                // Verify if the link has the expected parameters
                if ( empty($link['from_connector']) || empty($link['from_operator']) || !isset($link['from_sub_connector']) || empty($link['to_connector']) || empty($link['to_operator']) || !isset($link['to_sub_connector']) ) {

                    // Prepare the error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_one_link_wrong_parameters')
                    );                    

                }

            }

            // Verify if the operator has properties
            if ( empty($operator['properties']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_one_element_missing_properties')
                );
                
            }

            // Verify if operator_id exists
            if ( empty($operator['properties']['operator_id']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_one_element_missing_id')
                );
                
            }
            
            // Verify if operator_type exists
            if ( empty($operator['properties']['operator_type']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_one_element_missing_type')
                );
                
            }

            // Verify if the operator has properties
            if ( empty($operator['properties']['operator_properties']) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_one_element_missing_properties')
                );
                
            }

            // List the properties
            foreach ( $operator['properties']['operator_properties'] as $property ) {

                // Verify if the property has correct keys
                if ( (!isset($property['input']) && !isset($property['output'])) || empty($property['type']) || !isset($property['value']) ) {

                    // Prepare the error response
                    return array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_one_element_missing_properties')
                    );                    

                }

            }

        }

        // Verify if the bot has categories
        if ( $this->CI->base_model->the_data_where('crm_chatbot_bots_categories', '*', array('bot_id' => $params['bot']) ) ) {

            // Save the category
            if ( !$this->CI->base_model->delete('crm_chatbot_bots_categories', array('bot_id' => $params['bot']) ) ) {

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred_with_categories')
                );                
                
            }

        }

        // Try to delete the bot's records
        if ( !$this->delete_bot_records($params['bot']) ) {

            // Return the error
            return array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
            );

        }

        // Get team's member
        $member = the_crm_current_team_member();

        // List all operators
        foreach ( $params['operators'] as $operator ) {

            // Prepare the element
            $element_params = array(
                'bot_id' => $params['bot'],
                'top' => $operator['top'],
                'left' => $operator['left'],
                'operator_id' => $operator['properties']['operator_id'],
                'operator_type' => $operator['properties']['operator_type']
            );

            // Save the element
            $element_id = $this->CI->base_model->insert('crm_chatbot_bots_elements', $element_params);
            
            // Verify if the element was saved
            if ( $element_id ) {

                // List the properties
                foreach ( $operator['properties']['operator_properties'] as $property ) {

                    // Prepare the property
                    $property_params = array(
                        'element_id' => $element_id,
                        'type' => $property['type'],
                        'value' => $property['value']
                    );

                    // Verify if input exists
                    if ( !empty($property['input']) ) {

                        // Set input
                        $property_params['input'] = $property['input'];

                    }

                    // Verify if output exists
                    if ( !empty($property['output']) ) {

                        // Set output
                        $property_params['output'] = $property['output'];

                    }                    

                    // Save the property
                    $property_id = $this->CI->base_model->insert('crm_chatbot_bots_elements_properties', $property_params);
                    
                    // Verify if the property was saved
                    if ( !$property_id ) {

                        // Delete the bot
                        $this->delete_bot_records($params['bot']);

                        // Prepare the error response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_not_updated')
                        );       

                    }

                }                 

            } else {

                // Delete the bot
                $this->delete_bot_records($params['bot']);

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_not_updated')
                );  

            }

        }

        // List all links
        foreach ( $operator['links'] as $link ) {

            // Prepare the link
            $link_params = array(
                'bot_id' => $params['bot'],
                'from_connector' => $link['from_connector'],
                'from_operator' => $link['from_operator'],
                'from_sub_connector' => $link['from_sub_connector'],
                'to_connector' => $link['to_connector'],
                'to_operator' => $link['to_operator'],
                'to_sub_connector' => $link['to_sub_connector']
            );      

            // Save the link
            $link_id = $this->CI->base_model->insert('crm_chatbot_bots_elements_links', $link_params);
            
            // Verify if the link was saved
            if ( !$link_id ) {

                // Delete the bot
                $this->delete_bot_records($params['bot']);

                // Prepare the error response
                return array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_not_updated')
                );       

            }

        }

        // Delete the user's cache
        delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_bots_list');

        // Verify if member's name exists
        if ( isset($member['member_name']) ) {

            // Metas container
            $metas = array(
                array(
                    'meta_name' => 'activity_scope',
                    'meta_value' => $params['bot']
                ),
                array(
                    'meta_name' => 'title',
                    'meta_value' => $this->CI->lang->line('crm_chatbot_bot_update')
                ),                                  
                array(
                    'meta_name' => 'actions',
                    'meta_value' => $member['member_name'] . ' ' . $this->CI->lang->line('crm_chatbot_has_updated_the_bot') . ' ' . trim($the_bot[0]['bot_name']) . '.'
                )
                
            );

            // Verify if member exists
            if ( $this->CI->session->userdata( 'member' ) ) {

                // Set team's member
                $metas[] = array(
                    'meta_name' => 'team_member',
                    'meta_value' => $member['member_id']
                );

            }

            // Create the activity
            create_crm_activity(
                array(
                    'user_id' => $this->CI->user_id,
                    'activity_type' => 'crm_chatbot',
                    'for_id' => $params['bot'], 
                    'metas' => $metas
                )

            );

            // Delete the user's cache
            delete_crm_cache_cronology_for_user($this->CI->user_id, 'crm_chatbot_activities_list');

        }

        // Verify if categories exists
        if ( !empty($params['categories']) ) {

            // Categories errors counter
            $categories_count = 0;

            // List all categories
            foreach ( $params['categories'] as $category ) {

                // Verify if the category is numeric
                if ( !is_numeric($category) ) {
                    continue;
                }

                // Verify if the category exists
                if ( $this->CI->base_model->the_data_where('crm_chatbot_categories', '*', array('category_id' => $category, 'user_id' => $this->CI->user_id ) ) ) {

                    // Save the category
                    if ( !$this->CI->base_model->insert('crm_chatbot_bots_categories', array('bot_id' => $params['bot'], 'category_id' => $category) ) ) {
                        $categories_count++;
                    }

                }

            }

            // Verify if $categories_count is positive
            if ( $categories_count ) {

                // Prepare the success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_updated_without_some_categories')
                );                     

            } else {

                // Prepare the success response
                return array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_updated')
                ); 
                
            }

        } else {

            // Prepare the success response
            return array(
                'success' => TRUE,
                'message' => $this->CI->lang->line('crm_chatbot_the_bot_was_updated')
            ); 

        }

    }

    //-----------------------------------------------------
    // Quick Helpers for the Bots
    //-----------------------------------------------------

    /**
     * The protected method delete_bot_records deletes the bot's records
     * 
     * @param integer $bot_id contains the bot's identifier
     * 
     * @since 0.0.8.4
     * 
     * @return boolean true or false
     */
    protected function delete_bot_records($bot_id) {

        // Get bot's elements
        $the_elements = $this->CI->base_model->the_data_where(
            'crm_chatbot_bots_elements',
            '*',
            array(
                'bot_id' => $bot_id
            )
        );

        // Verify if the bot has elements
        if ( $the_elements ) {

            // List all bot's elements
            foreach ( $the_elements as $the_element ) {

                // Delete the bot's elements
                if ( !$this->CI->base_model->delete('crm_chatbot_bots_elements', array('element_id' => $the_element['element_id'])) ) {
                    return false;
                }

                // Delete the bot's elements property
                if ( !$this->CI->base_model->delete('crm_chatbot_bots_elements_properties', array('element_id' => $the_element['element_id'])) ) {
                    return false;
                }              

            }

            // Get bot's links
            $the_links = $this->CI->base_model->the_data_where(
                'crm_chatbot_bots_elements_links',
                '*',
                array(
                    'bot_id' => $bot_id
                )
            );

            // Verify if links exists
            if ( $the_links ) {

                // Delete the bot's links
                if ( !$this->CI->base_model->delete('crm_chatbot_bots_elements_links', array('bot_id' => $bot_id) ) ) {
                    return false;
                }

            }

        }

        return true;
        
    }

}

/* End of file update.php */