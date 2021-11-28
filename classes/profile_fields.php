<?php
/**
 * Profile Fields Class
 *
 * This file loads the Profile_fields class with methods to create profile's fields
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
 * Profile_fields class loads the properties used to collect the profile's fields for the Settings app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Profile_fields {
    
    /**
     * Contains and array with saved fields
     *
     * @since 0.0.8.3
     */
    public static $the_profile_fields = array(); 

    /**
     * The public method set_profile_field adds fields in the queue
     * 
     * @param string $field_slug contains the field's slug
     * @param array $args contains the field's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_profile_field($field_slug, $args) {

        // Verify if the field has valid fields
        if ( $field_slug ) {

            self::$the_profile_fields[][$field_slug] = $args;
            
        }

    } 

    /**
     * The public method load_profile_fields loads all profile's fields
     * 
     * @since 0.0.8.3
     * 
     * @return array with fields or boolean false
     */
    public function load_profile_fields() {

        // Verify if fields exists
        if ( self::$the_profile_fields ) {

            return self::$the_profile_fields;

        } else {

            return false;

        }

    }

}

/* End of file profile_fields.php */