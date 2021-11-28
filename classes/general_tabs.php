<?php
/**
 * General Tabs Class
 *
 * This file loads the General_tabs class with methods to provide the settings general's tabs
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
 * General_tabs class loads the properties used to collect the general's tabs for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class General_tabs {
    
    /**
     * Contains and array with saved tabs
     *
     * @since 0.0.8.3
     */
    public static $the_general_tabs = array(); 

    /**
     * The public method set_tab adds the general's tab in the queue
     * 
     * @param string $tab_slug contains the tab's slug
     * @param array $args contains the tab's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_tab($tab_slug, $args) {

        // Verify if tab has expected parameters
        if ( isset($args['name']) && isset($args['icon']) && isset($args['position']) ) {

            // Set the tab's slug
            $args['slug'] = $tab_slug;

            // Set tab
            self::$the_general_tabs[] = $args;

        }

    } 

    /**
     * The public method the_general_tabs gets the general's tabs
     * 
     * @since 0.0.8.3
     * 
     * @return array with tabs or boolean false
     */
    public function the_general_tabs() {

        // Verify if tabs exists
        if ( self::$the_general_tabs ) {

            // Sort the options
            usort(self::$the_general_tabs, $this->options_sorter('position'));

            return self::$the_general_tabs;

        } else {

            return false;

        }

    }

    /**
     * The protected method options_sorter sorts the options
     * 
     * @param string $position contains the position's value
     * 
     * @since 0.0.8.3
     * 
     * @return array with options
     */
    protected function options_sorter($position) {

        return function ($a, $b) use ($position) {

            return strnatcmp($a[$position], $b[$position]);

        };

    }

}

/* End of file general_tabs.php */