<?php
/**
 * Triggers Helpers
 *
 * This file contains the class Triggers
 * with methods to manage the triggers
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

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Triggers as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers;

// Require the Triggers Functions Inc file
require_once CMS_BASE_USER_APPS_CRM_CHATBOT . 'inc/triggers_functions.php';

/*
 * Triggers class provides the methods to manage the triggers
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.5
*/
class Triggers {
    
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

        // Load language
        $this->CI->lang->load( 'crm_chatbot_triggers', $this->CI->config->item('language'), FALSE, TRUE, CMS_BASE_USER_APPS_CRM_CHATBOT );
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Triggers
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_create_trigger creates a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_create_trigger() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('trigger_name', 'Trigger Name', 'trim');
            $this->CI->form_validation->set_rules('event_slug', 'Event Slug', 'trim');
            $this->CI->form_validation->set_rules('trigger_fields', 'Trigger Fields', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $trigger_name = $this->CI->input->post('trigger_name', TRUE);
            $event_slug = $this->CI->input->post('event_slug', TRUE);
            $trigger_fields = $this->CI->input->post('trigger_fields', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'trigger_name' => $trigger_name,
                    'event_slug' => $event_slug,
                    'trigger_fields' => $trigger_fields
                );

                // Save the trigger
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Create)->crm_chatbot_create_trigger($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_created')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_update_trigger updates a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_update_trigger() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('trigger', 'Trigger ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('trigger_name', 'Trigger Name', 'trim');
            $this->CI->form_validation->set_rules('event_slug', 'Event Slug', 'trim');
            $this->CI->form_validation->set_rules('trigger_fields', 'Trigger Fields', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $trigger = $this->CI->input->post('trigger', TRUE);
            $trigger_name = $this->CI->input->post('trigger_name', TRUE);
            $event_slug = $this->CI->input->post('event_slug', TRUE);
            $trigger_fields = $this->CI->input->post('trigger_fields', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'trigger' => $trigger,
                    'trigger_name' => $trigger_name,
                    'event_slug' => $event_slug,
                    'trigger_fields' => $trigger_fields
                );

                // Save the trigger
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Update)->crm_chatbot_update_trigger($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_updated')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_triggers gets the triggers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_triggers() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('website', 'Website ID', 'trim|numeric|required');
            $this->CI->form_validation->set_rules('page', 'Page', 'trim');

            // Get data
            $website = $this->CI->input->post('website', TRUE);
            $page = $this->CI->input->post('page', TRUE)?($this->CI->input->post('page', TRUE) - 1):0;
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'website' => $website,
                    'page' => $page,
                    'limit' => 10
                );               

                // Get the triggers
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Read)->crm_chatbot_the_triggers($params);

                // Verify if triggers exists
                if ( !empty($data['triggers']) ) {

                    // Verify if the page exists
                    if ( isset($params['page']) ) {

                        // Set page
                        $data['page'] = ($params['page'] + 1);

                    }

                    // Display the success response
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_triggers_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_trigger_data gets the trigger's data
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_trigger_data() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('trigger', 'Trigger ID', 'trim|numeric|required');

            // Get data
            $trigger = $this->CI->input->post('trigger', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'trigger' => $trigger
                );               

                // Get the trigger's data
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Read)->crm_chatbot_the_trigger($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_triggers_events gets the triggers events
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_triggers_events() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('key', 'Key', 'trim');

            // Get data
            $key = $this->CI->input->post('key', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Events conainer
                $events = array();

                // Verify if events exists
                if ( glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') ) {

                    // List all events
                    foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') as $filename) {
                        
                        // Set class name
                        $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/', '.php'), '', $filename);

                        // Create an array
                        $array = array(
                            'CmsBase',
                            'User',
                            'Apps',
                            'Collection',
                            'Crm_chatbot',
                            'Events',
                            ucfirst($class_name)
                        );

                        // Implode the array above
                        $cl = implode('\\', $array);

                        // Get info
                        $info = (new $cl())->the_event_info();

                        // Verify if key exists
                        if ( $key ) {

                            // Verify if the event's name meets the key
                            if (strpos($info['event_name'], $key) === false) {
                                continue;
                            }

                        }

                        // Append the event's info
                        $events[] = $info;

                        // Verify if there are 10 events
                        if ( count($events) > 9 ) {
                            break;
                        }
                        
                    }

                }

                // Verify if events exists
                if ( $events ) {

                    // Prepare events
                    $data = array(
                        'success' => TRUE,
                        'events' => $events
                    );
                    
                    // Display the events
                    echo json_encode($data);
                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_events_were_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_event_fields gets the event's fields
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_get_event_fields() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('event', 'Event', 'trim');

            // Get data
            $event = $this->CI->input->post('event', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // List all events
                foreach (glob(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/*.php') as $filename) {
                                    
                    // Set class name
                    $class_name = str_replace(array(CMS_BASE_USER_APPS_CRM_CHATBOT . 'events/', '.php'), '', $filename);

                    // Create an array
                    $array = array(
                        'CmsBase',
                        'User',
                        'Apps',
                        'Collection',
                        'Crm_chatbot',
                        'Events',
                        ucfirst($class_name)
                    );

                    // Implode the array above
                    $cl = implode('\\', $array);

                    // Get info
                    $info = (new $cl())->the_event_info();

                    // Verify if is the required event
                    if ( $event === $info['event_slug'] ) {

                        // Get fields
                        $the_fields = (new $cl())->the_fields();

                        // Verify if the event has the expected fields
                        if ( isset($the_fields['condition']) && isset($the_fields['actions']) ) {

                            // Verfy if the condition and actions have fields
                            if ( isset($the_fields['condition']['fields']) ) {

                                // Prepare events
                                $data = array(
                                    'success' => TRUE,
                                    'event_fields' => $the_fields
                                );
                                
                                // Display the events
                                echo json_encode($data);
                                exit();

                            }

                        } else {

                            // Prepare the false response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_the_event_fields_wrong_parameters')
                            );

                            // Display the false response
                            echo json_encode($data);
                            exit();
                            
                        }

                    }
                    
                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_event_was_not_found')
        );

        // Display the false response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_delete_trigger deletes a trigger
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_delete_trigger() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('trigger', 'Trigger ID', 'trim|numeric|required');

            // Get data
            $trigger = $this->CI->input->post('trigger', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Set params
                $params = array(
                    'trigger' => $trigger
                );               

                // Delete a trigger
                $data = (new CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsTriggers\Delete)->crm_chatbot_delete_trigger($params);

                // Display the response
                echo json_encode($data);
                exit();

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_the_trigger_was_not_deleted')
        );

        // Display the false response
        echo json_encode($data);
        
    }

}

/* End of file triggers.php */