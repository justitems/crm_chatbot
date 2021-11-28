<?php
/**
 * Profile Preferences Options Class
 *
 * This file loads the Profile_preferences_options class with methods to provide the options for the profile preferences
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
 * Profile_preferences_options class loads the properties used to collect the profile preferences for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Profile_preferences_options {
    
    /**
     * Contains and array with saved options
     *
     * @since 0.0.8.3
     */
    public static $the_preferences_options = array(); 

    /**
     * The public method set_preferences_option adds the preferences's option in the queue
     * 
     * @param array $args contains the option's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_preferences_option($args) {

        // Verify if preferences has expected parameters
        if ( isset($args['label']) && isset($args['template_slug']) && isset($args['slug']) && isset($args['position']) ) {

            // Set preferences's option
            self::$the_preferences_options[$args['slug']] = $args;

            // Set the page to the option
            $args['page'] = 'profile';

            // Set the icon to the option
            $args['icon'] = md_the_user_icon(array('icon' => 'settings')); 

            // Add the option even to the options class
            (new CmsBaseUserAppsCollectionCrm_settingsClasses\Options)->set_option($args);

        }

    }

    /**
     * The public method the_preferences_options gets the preferences's options for the General Page
     * 
     * @since 0.0.8.3
     * 
     * @return array with options or boolean false
     */
    public function the_preferences_options() {

        // Verify if preferences exists
        if ( self::$the_preferences_options ) {

            // Sort the options
            usort(self::$the_preferences_options, $this->options_sorter('position'));

            return self::$the_preferences_options;

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

/* End of file profile_preferences_options.php */