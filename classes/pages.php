<?php
/**
 * Pages Class
 *
 * This file loads the Pages class with methods to create pages
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_settings\Classes;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Pages class loads the properties used to collect the pages for the Team app
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.3
 */
class Pages {
    
    /**
     * Contains and array with saved pages
     *
     * @since 0.0.8.3
     */
    public static $the_pages = array(); 

    /**
     * The public method set_page adds page
     * 
     * @param string $page_slug contains the page's slug
     * @param array $args contains the page's arguments
     * 
     * @since 0.0.8.3
     * 
     * @return void
     */
    public function set_page($page_slug, $args) {

        // Verify if the page has valid fields
        if ( $page_slug ) {

            self::$the_pages[][$page_slug] = $args;
            
        }

    } 

    /**
     * The public method load_pages loads all apps pages
     * 
     * @since 0.0.8.3
     * 
     * @return array with pages or boolean false
     */
    public function load_pages() {

        // Verify if pages exists
        if ( self::$the_pages ) {

            return self::$the_pages;

        } else {

            return false;

        }

    }

}

/* End of file pages.php */