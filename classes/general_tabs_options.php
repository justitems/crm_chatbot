<?php
/**
 * General Tabs Options Class
 *
 * This file loads the General_tabs_options class with methods to provide the settings general's tabs options
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

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_settings\Classes as CmsBaseUserAppsCollectionCrm_settingsClasses;

/*
 * General_tabs_options class loads the properties used to collect the general's tabs options for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class General_tabs_options {
    
    /**
     * Contains and array with saved options
     *
     * @since 0.0.8.3
     */
    public static $the_tabs_options = array(); 

    /**
     * The public method set_tab_option adds the tab's option in the queue
     * 
     * @param string $tab_slug contains the tab's slug
     * @param array $args contains the option's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_tab_option($tab_slug, $args) {

        // Verify if tab has expected parameters
        if ( isset($args['label']) && isset($args['template_slug']) && isset($args['slug']) && isset($args['position']) ) {

            // Verify if the tab already exists
            if ( !isset(self::$the_tabs_options[$tab_slug]) ) {
                self::$the_tabs_options[$tab_slug] = array();
            }

            // Set tab's option
            self::$the_tabs_options[$tab_slug][] = $args;

            // Set the page to the option
            $args['page'] = 'general'; 

            // Set the icon to the option
            $args['icon'] = md_the_user_icon(array('icon' => 'sound_module')); 

            // Add the option even to the options class
            (new CmsBaseUserAppsCollectionCrm_settingsClasses\Options)->set_option($args);

        }

    }

    /**
     * The public method the_tab_options gets the tab's options for the General Page
     * 
     * @param string $tab_slug contains the tab's slug
     * 
     * @since 0.0.8.3
     * 
     * @return array with options or boolean false
     */
    public function the_tab_options($tab_slug) {

        // Verify if tabs exists
        if ( isset(self::$the_tabs_options[$tab_slug]) ) {

            // Sort the options
            usort(self::$the_tabs_options[$tab_slug], $this->options_sorter('position'));

            return self::$the_tabs_options[$tab_slug];

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

/* End of file general_tabs_options.php */