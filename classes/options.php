<?php
/**
 * Options Class
 *
 * This file loads the Options class with methods to provide the all registered options
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the field namespace
namespace CmsBase\User\Apps\Collection\Crm_settings\Classes;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Options class loads the properties used to collect all registered options for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Options {
    
    /**
     * Contains and array with saved options
     *
     * @since 0.0.8.3
     */
    public static $the_options = array(); 

    /**
     * The public method set_option adds the option in the queue
     * 
     * @param array $args contains the option's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_option($args) {

        // Verify if the option has expected parameters
        if ( isset($args['template_slug']) && isset($args['slug']) && isset($args['position']) && isset($args['label']) ) {

            // Set option
            self::$the_options[$args['slug']] = $args;

        }

    }

    /**
     * The public method the_options gets the options
     * 
     * @since 0.0.8.3
     * 
     * @return array with options or boolean false
     */
    public function the_options() {

        // Verify if the options exists
        if ( self::$the_options ) {

            return self::$the_options;

        } else {

            return false;

        }

    }

}

/* End of file options.php */