<?php
/**
 * Options Templates Class
 *
 * This file loads the Options_templates Class with methods to save and provide the templates for the settings options
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
 * Options_templates class loads the methods to save and provide the templates in the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Options_templates {
    
    /**
     * Contains and array with saved templates
     *
     * @since 0.0.8.3
     */
    public static $the_templates = array(); 

    /**
     * The public method set_template adds a template in the queue
     * 
     * @param string $template_slug contains the tab's slug
     * @param array $args contains the template's parameters
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_template($template_slug, $args) {

        // Verify if tab has expected parameters
        if ( isset($args['validate_fields']) && isset($args['template_content_as_html']) ) {

            // Set template
            self::$the_templates[$template_slug] = $args;

        }

    } 

    /**
     * The public method the_template gets the template by the template's slug
     * 
     * @param string $template_slug contains the template's slug
     * 
     * @since 0.0.8.3
     * 
     * @return string with template or boolean false
     */
    public function the_template($template_slug) {

        // Verify if tabs exists
        if ( isset(self::$the_templates[$template_slug]) ) {

            return self::$the_templates[$template_slug];

        } else {

            return false;

        }

    }

}

/* End of file options_templates.php */