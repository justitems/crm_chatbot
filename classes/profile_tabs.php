<?php
/**
 * Profile Tabs Class
 *
 * This file loads the Profile_tabs class with methods to provide the profile's tabs
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
 * Profile_tabs class loads the properties used to collect the profile's tabs for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Profile_tabs {
    
    /**
     * Contains and array with saved tabs
     *
     * @since 0.0.8.3
     */
    public static $the_profile_tabs = array(); 

    /**
     * The public method set_tab adds the profile's tab in the queue
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
        if ( isset($args['tab_name']) && isset($args['tab_icon']) ) {

            // Set tab
            self::$the_profile_tabs[$tab_slug] = $args;

        }

    } 

    /**
     * The public method the_profile_tabs gets the profile's tabs
     * 
     * @since 0.0.8.3
     * 
     * @return array with tabs or boolean false
     */
    public function the_profile_tabs() {

        // Verify if tabs exists
        if ( self::$the_profile_tabs ) {

            return self::$the_profile_tabs;

        } else {

            return false;

        }

    }

}

/* End of file profile_tabs.php */