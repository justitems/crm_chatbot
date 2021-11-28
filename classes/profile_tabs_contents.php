<?php
/**
 * Client Tabs Contents Class
 *
 * This file loads the Profile_tabs_contents class with methods to provide the profile's tabs contents
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
 * Profile_tabs_contents class loads the properties used to collect the profile's tabs contents for the CRM Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Profile_tabs_contents {
    
    /**
     * Contains and array with saved tabs
     *
     * @since 0.0.8.3
     */
    public static $the_profile_tabs_contents = array(); 

    /**
     * The public method set_tab adds the profile's tab content in the queue
     * 
     * @param string $tab_slug contains the tab's slug
     * @param array $args contains the tab's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_tab_content($tab_slug, $args) {

        // Verify if tab has expected parameters
        if ( isset($args['tab_content'])  && isset($args['css_urls']) && isset($args['js_urls']) ) {

            // Set tab
            self::$the_profile_tabs_contents[$tab_slug] = $args;

        }

    } 

    /**
     * The public method the_profile_tabs_contents gets the profile's tabs contents
     * 
     * @since 0.0.8.3
     * 
     * @return string or boolean false
     */
    public function the_profile_tabs_contents() {

        // Verify if tabs contents exists
        if ( self::$the_profile_tabs_contents ) {

            return self::$the_profile_tabs_contents;

        } else {

            return false;

        }

    }    

    /**
     * The public method the_profile_tab_content gets the profile's tab content
     * 
     * @param string $tab_slug contains the tab's slug
     * 
     * @since 0.0.8.3
     * 
     * @return string or boolean false
     */
    public function the_profile_tab_content($tab_slug) {

        // Verify if tabs exists
        if ( isset(self::$the_profile_tabs_contents[$tab_slug]) ) {

            return self::$the_profile_tabs_contents[$tab_slug]['tab_content'];

        } else {

            return false;

        }

    }

}

/* End of file profile_tabs_contents.php */