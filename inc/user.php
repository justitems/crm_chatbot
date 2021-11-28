<?php
/**
 * User Inc
 *
 * This file contains the hooks required
 * to create the settings's pages
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

defined('BASEPATH') OR exit('No direct script access allowed');

// Set settings's page
set_crm_settings_page(
    'general',
    array(
        'page_name' => $this->lang->line('crm_settings_general'),
        'page_icon' => md_the_user_icon(array('icon' => 'sound_module')),
        'page_content' => 'get_crm_settings_general_page',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/user/apps/collection/crm-settings/styles/css/general.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all'),
            array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/user/apps/collection/crm-settings/js/general.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION)),
            array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION))
        )
    )
);

if ( !function_exists('get_crm_settings_general_page') ) {
    
    /**
     * The function get_crm_settings_general_page shows the CRM Settings general Page
     * 
     * @return void
     */
    function get_crm_settings_general_page() {

        // Require the General Inc Part
        require_once CMS_BASE_USER_APPS_CRM_SETTINGS . 'inc/parts/general.php';   
        
        // Load page
        get_crm_settings_general_page_from_parts();
        
    }

}

// Set settings's page
set_crm_settings_page(
    'profile',
    array(
        'page_name' => $this->lang->line('crm_settings_my_profile'),
        'page_icon' => md_the_user_icon(array('icon' => 'settings')),
        'page_content' => 'get_crm_settings_profile_page',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/user/apps/collection/crm-settings/styles/css/profile.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all'),
            array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION))
        )
    )
);

if ( !function_exists('get_crm_settings_profile_page') ) {
    
    /**
     * The function get_crm_settings_profile_page shows the CRM Settings profile Page
     * 
     * @return void
     */
    function get_crm_settings_profile_page() {
        
        // Require the Profile Inc Part
        require_once CMS_BASE_USER_APPS_CRM_SETTINGS . 'inc/parts/profile.php';   
        
        // Load page
        get_crm_settings_profile_page_from_parts();
        
    }

}

// Set settings's page
set_crm_settings_page(
    'plan',
    array(
        'page_name' => $this->lang->line('crm_settings_my_plan'),
        'page_icon' => '<i class="ri-bar-chart-box-line"></i>',
        'page_content' => 'get_crm_settings_plan_page',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/user/apps/collection/crm-settings/styles/css/plan.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all'),
            array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/user/apps/collection/crm-settings/js/plan.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION)),
            array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION))
        )
    )
);

if ( !function_exists('get_crm_settings_plan_page') ) {
    
    /**
     * The function get_crm_settings_plan_page shows the CRM Settings plan Page
     * 
     * @return void
     */
    function get_crm_settings_plan_page() {
        

        
    }

}

// Set settings's page
set_crm_settings_page(
    'notifications',
    array(
        'page_name' => $this->lang->line('crm_settings_notifications'),
        'page_icon' => md_the_user_icon(array('icon' => 'notification')),
        'page_content' => 'get_crm_settings_notifications_page',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/user/apps/collection/crm-settings/styles/css/notifications.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all'),
            array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/user/apps/collection/crm-settings/js/notifications.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION)),
            array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION))
        )
    )
);

if ( !function_exists('get_crm_settings_notifications_page') ) {
    
    /**
     * The function get_crm_settings_notifications_page shows the CRM Settings notifications Page
     * 
     * @return void
     */
    function get_crm_settings_notifications_page() {
        

        
    }

}

// Set settings's page
set_crm_settings_page(
    'apps',
    array(
        'page_name' => $this->lang->line('crm_settings_my_apps'),
        'page_icon' => '<i class="ri-device-line"></i>',
        'page_content' => 'get_crm_settings_apps_page',
        'css_urls' => array(
            array('stylesheet', base_url('assets/base/user/apps/collection/crm-settings/styles/css/apps.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all'),
            array('stylesheet', base_url('assets/base/user/themes/collection/crm/styles/css/upload-box.css?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION), 'text/css', 'all')
        ),
        'js_urls' => array(
            array(base_url('assets/base/user/apps/collection/crm-settings/js/apps.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION)),
            array(base_url('assets/base/user/default/js/upload-box.js?ver=' . CMS_BASE_USER_APPS_CRM_SETTINGS_VERSION))
        )
    )
);

if ( !function_exists('get_crm_settings_apps_page') ) {
    
    /**
     * The function get_crm_settings_apps_page shows the CRM Settings apps Page
     * 
     * @return void
     */
    function get_crm_settings_apps_page() {
        

        
    }

}

set_crm_settings_options_template(
    'category',
    array(
        'validate_fields' => 'validate_crm_settings_options_category_template',
        'template_content_as_html' => 'the_crm_settings_options_template_content_as_html'
    )
);

if ( !function_exists('validate_crm_settings_options_category_template') ) {
    
    /**
     * The function validate_crm_settings_options_category_template the option for the category template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return boolean true or false
     */
    function validate_crm_settings_options_category_template($args) {
        
        // Verify if the label exists
        if ( empty($args['slug']) || empty($args['label']) ) {

            return false;

        } else {

            return true;
            
        }

    }

}

if ( !function_exists('the_crm_settings_options_template_content_as_html') ) {
    
    /**
     * The function the_crm_settings_options_template_content_as_html generates the option's template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return string
     */
    function the_crm_settings_options_template_content_as_html($args) {

        // Set and return html
        return '<li>'
            . '<div class="row">'
                . '<div class="col-12">'
                    . '<h2 class="theme-color-grey">' . $args['label'] . '</h2>'
                . '</div>'
            . '</div>'
        . '</li>';

    }

}

set_crm_settings_options_template(
    'checkbox_input',
    array(
        'validate_fields' => 'validate_crm_settings_options_checkbox_input_template',
        'template_content_as_html' => 'the_crm_settings_options_checkbox_input_template_content_as_html',
        'save_option' => 'save_crm_settings_options_checkbox_input_template',
    )
);

if ( !function_exists('validate_crm_settings_options_checkbox_input_template') ) {
    
    /**
     * The function validate_crm_settings_options_checkbox_input_template the option for the checkbox input template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return boolean true or false
     */
    function validate_crm_settings_options_checkbox_input_template($args) {

        // Verify if the label exists
        if ( empty($args['slug']) || empty($args['label']) || empty($args['label_description']) ) {

            return false;

        } else {

            return true;
            
        }

    }

}

if ( !function_exists('the_crm_settings_options_checkbox_input_template_content_as_html') ) {
    
    /**
     * The function the_crm_settings_options_checkbox_input_template_content_as_html generates the option's template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return string
     */
    function the_crm_settings_options_checkbox_input_template_content_as_html($args) {

        // Require the Chackbox Input Inc Part
        require_once CMS_BASE_USER_APPS_CRM_SETTINGS . 'inc/parts/checkbox_input.php';   
        
        // Return content
        return the_crm_settings_options_checkbox_input_template_content_as_html_from_parts($args);

    }

}

if ( !function_exists('save_crm_settings_options_checkbox_input_template') ) {
    
    /**
     * The function save_crm_settings_options_checkbox_input_template saves an option
     * 
     * @param array $args contains the option's parameters
     * 
     * @return boolean true or false
     */
    function save_crm_settings_options_checkbox_input_template($args) {

        // Assign the CodeIgniter super-object
        $CI =& get_instance();

        // Verify if the option has valid parameters
        if ( isset($args['value']) ) {

            // Try to save the option
            if ( md_update_user_option($CI->user_id, $args['slug'], $args['value']) ) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

}

set_crm_settings_options_template(
    'select_list',
    array(
        'validate_fields' => 'validate_crm_settings_options_select_list_template',
        'template_content_as_html' => 'the_crm_settings_options_select_list_template_content_as_html',
        'save_option' => 'save_crm_settings_options_select_list_template',
    )
);

if ( !function_exists('validate_crm_settings_options_select_list_template') ) {
    
    /**
     * The function validate_crm_settings_options_select_list_template the option for the checkbox input template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return boolean true or false
     */
    function validate_crm_settings_options_select_list_template($args) {

        // Verify if the label exists
        if ( empty($args['slug']) || empty($args['label']) || empty($args['label_description']) || empty($args['btn_text']) || empty($args['btn_id']) || empty($args['items']) ) {

            return false;

        } else {

            return true;
            
        }

    }

}

if ( !function_exists('the_crm_settings_options_select_list_template_content_as_html') ) {
    
    /**
     * The function the_crm_settings_options_select_list_template_content_as_html generates the option's template
     * 
     * @param array $args contains the option's parameters
     * 
     * @return string
     */
    function the_crm_settings_options_select_list_template_content_as_html($args) {

        // Require the Select List Inc Part
        require_once CMS_BASE_USER_APPS_CRM_SETTINGS . 'inc/parts/select_list.php';   
        
        // Return content
        return the_crm_settings_options_select_list_template_content_as_html_from_parts($args);

    }

}

if ( !function_exists('save_crm_settings_options_select_list_template') ) {
    
    /**
     * The function save_crm_settings_options_select_list_template saves an option
     * 
     * @param array $args contains the option's parameters
     * 
     * @return boolean true or false
     */
    function save_crm_settings_options_select_list_template($args) {

        // Assign the CodeIgniter super-object
        $CI =& get_instance();

        // Verify if the option has valid parameters
        if ( isset($args['value']) ) {

            // Try to save the option
            if ( md_update_user_option($CI->user_id, $args['slug'], $args['value']) ) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }

    }

}

// Set profile's tab
set_crm_settings_profile_tab(
    'crm_settings_profile_information_tab',
    array(
        'tab_name' => $this->lang->line('crm_settings_information'),
        'tab_icon' => md_the_user_icon(array('icon' => 'user_search'))
    )
);

// Set profile's tab
set_crm_settings_profile_tab(
    'crm_settings_profile_preferences_tab',
    array(
        'tab_name' => $this->lang->line('crm_settings_preferences'),
        'tab_icon' => md_the_user_icon(array('icon' => 'sound_module'))
    )
);

// Set profile's tab
set_crm_settings_profile_tab(
    'crm_settings_profile_preferences_tab',
    array(
        'tab_name' => $this->lang->line('crm_settings_preferences'),
        'tab_icon' => md_the_user_icon(array('icon' => 'sound_module'))
    )
);

// Languages container
$languages_list = array();

// Get all languages
$languages = glob(APPPATH . 'language' . '/*', GLOB_ONLYDIR);

// List all languages
foreach ($languages as $language) {

    // Get language dir name
    $lang_dir = str_replace(APPPATH . 'language' . '/', '', $language);

    // Set laguage
    $languages_list[] = array(
        'id' => $lang_dir,
        'text' => ucfirst($lang_dir)
    );

}

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_language',
        'label' => $this->lang->line('crm_settings_language'),
        'label_description' => $this->lang->line('crm_settings_language_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_language')?ucfirst(md_the_user_option($this->user_id, 'user_language')):ucfirst($this->config->item('language')),
        'btn_id' => md_the_user_option($this->user_id, 'user_language')?md_the_user_option($this->user_id, 'user_language'):$this->config->item('language'),
        'items' => $languages_list,
        'position' => 1
    )
);

// Time's zones container
$time_zones_list = array(
    array(
        'id' => '-11',
        'text' => $this->lang->line('crm_settings_gmt_11_00_midway')      
    ),
    array(
        'id' => '-10',
        'text' => $this->lang->line('crm_settings_gmt_10_00_honolulu')      
    ),  
    array(
        'id' => '-9',
        'text' => $this->lang->line('crm_settings_gmt_09_00_juneau')      
    ), 
    array(
        'id' => '-8',
        'text' => $this->lang->line('crm_settings_gmt_08_00_los_angeles')      
    ),
    array(
        'id' => '-7',
        'text' => $this->lang->line('crm_settings_gmt_07_00_phoenix')      
    ),     
    array(
        'id' => '-6',
        'text' => $this->lang->line('crm_settings_gmt_06_00_chicago')      
    ), 
    array(
        'id' => '-5',
        'text' => $this->lang->line('crm_settings_gmt_05_00_new_york')      
    ),  
    array(
        'id' => '-4',
        'text' => $this->lang->line('crm_settings_gmt_04_00_manaus')      
    ),     
    array(
        'id' => '-3',
        'text' => $this->lang->line('crm_settings_gmt_03_00_buenos_aires')      
    ), 
    array(
        'id' => '-2',
        'text' => $this->lang->line('crm_settings_gmt_02_00_sao_paulo')      
    ),
    array(
        'id' => '-1',
        'text' => $this->lang->line('crm_settings_gmt_01_00_cape_verde')      
    ),       
    array(
        'id' => '0',
        'text' => $this->lang->line('crm_settings_gmt_00_00_london')      
    ),  
    array(
        'id' => '1',
        'text' => $this->lang->line('crm_settings_gmt_01_00_amsterdam')      
    ),  
    array(
        'id' => '2',
        'text' => $this->lang->line('crm_settings_gmt_02_00_cairo')      
    ),  
    array(
        'id' => '3',
        'text' => $this->lang->line('crm_settings_gmt_03_00_istanbul')      
    ),   
    array(
        'id' => '3.30',
        'text' => $this->lang->line('crm_settings_gmt_03_30_tehran')      
    ),  
    array(
        'id' => '4',
        'text' => $this->lang->line('crm_settings_gmt_04_00_baku')      
    ),  
    array(
        'id' => '4.30',
        'text' => $this->lang->line('crm_settings_gmt_04_30_kabul')      
    ),  
    array(
        'id' => '5',
        'text' => $this->lang->line('crm_settings_gmt_05_00_karachi')      
    ),  
    array(
        'id' => '5.30',
        'text' => $this->lang->line('crm_settings_gmt_05_30_colombo')      
    ),  
    array(
        'id' => '5.45',
        'text' => $this->lang->line('crm_settings_gmt_05_45_katmandu')      
    ),  
    array(
        'id' => '6',
        'text' => $this->lang->line('crm_settings_gmt_06_00_dhaka')      
    ), 
    array(
        'id' => '6.30',
        'text' => $this->lang->line('crm_settings_gmt_06_30_rangoon')      
    ),  
    array(
        'id' => '7',
        'text' => $this->lang->line('crm_settings_gmt_07_00_bangkok')      
    ), 
    array(
        'id' => '8',
        'text' => $this->lang->line('crm_settings_gmt_08_00_hong_kong')      
    ),  
    array(
        'id' => '9',
        'text' => $this->lang->line('crm_settings_gmt_09_00_tokyo')      
    ),   
    array(
        'id' => '9.30',
        'text' => $this->lang->line('crm_settings_gmt_09_30_darwin')      
    ),
    array(
        'id' => '10',
        'text' => $this->lang->line('crm_settings_gmt_10_00_vladivostok')      
    ),
    array(
        'id' => '10.30',
        'text' => $this->lang->line('crm_settings_gmt_10_30_adelaide')      
    ), 
    array(
        'id' => '11',
        'text' => $this->lang->line('crm_settings_gmt_11_00_magadan')      
    ),
    array(
        'id' => '13',
        'text' => $this->lang->line('crm_settings_gmt_13_00_auckland')      
    ),
    array(
        'id' => '14',
        'text' => $this->lang->line('crm_settings_gmt_14_00_kiritimati')      
    )
);

// Format time's zones
$time_zones = array_column($time_zones_list, 'text', 'id');

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_time_zone',
        'label' => $this->lang->line('crm_settings_time_zone'),
        'label_description' => $this->lang->line('crm_settings_time_zone_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_time_zone')?$time_zones[md_the_user_option($this->user_id, 'user_time_zone')]:$time_zones[(date('Z') / 3600)],
        'btn_id' => md_the_user_option($this->user_id, 'user_time_zone')?md_the_user_option($this->user_id, 'user_time_zone'):(date('Z') / 3600),
        'items' => $time_zones_list,
        'position' => 2
    )
);

// Date format container
$date_format_list = array(
    array(
        'id' => 'dd/mm/yyyy',
        'text' => 'DD / MM / YYYY'    
    ),
    array(
        'id' => 'mm/dd/yyyy',
        'text' => 'MM / DD / YYYY'     
    ),
    array(
        'id' => 'yyyy-mm-dd',
        'text' => 'YYYY - MM- DD '     
    )   
);

// Format date formats
$date_formats = array_column($date_format_list, 'text', 'id');

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_date_format',
        'label' => $this->lang->line('crm_settings_date_format'),
        'label_description' => $this->lang->line('crm_settings_date_format_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_date_format')?$date_formats[md_the_user_option($this->user_id, 'user_date_format')]:'DD / MM / YYYY',
        'btn_id' => md_the_user_option($this->user_id, 'user_date_format')?md_the_user_option($this->user_id, 'user_date_format'):'dd/mm/yyyy',
        'items' => $date_format_list,
        'position' => 3
    )
);

// Time format container
$time_format_list = array(
    array(
        'id' => 'hh:ii',
        'text' => 'HH : II'    
    ),
    array(
        'id' => 'hh:ii:ss',
        'text' => 'HH : II : SS'    
    )  
);

// Format time formats
$time_formats = array_column($time_format_list, 'text', 'id');

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_time_format',
        'label' => $this->lang->line('crm_settings_time_format'),
        'label_description' => $this->lang->line('crm_settings_time_format_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_time_format')?$time_formats[md_the_user_option($this->user_id, 'user_time_format')]:'HH : II',
        'btn_id' => md_the_user_option($this->user_id, 'user_time_format')?md_the_user_option($this->user_id, 'user_time_format'):'hh:ii',
        'items' => $time_format_list,
        'position' => 4
    )
);

// Hours format container
$hours_format_list = array(
    array(
        'id' => '12',
        'text' => $this->lang->line('crm_settings_12_hour_clock')    
    ),
    array(
        'id' => '24',
        'text' => $this->lang->line('crm_settings_24_hour_clock')
    )  
);

// Format hours formats
$hours_formats = array_column($hours_format_list, 'text', 'id');

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_hours_format',
        'label' => $this->lang->line('crm_settings_hours_format'),
        'label_description' => $this->lang->line('crm_settings_hours_format_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_hours_format')?$hours_formats[md_the_user_option($this->user_id, 'user_hours_format')]:$this->lang->line('crm_settings_24_hour_clock'),
        'btn_id' => md_the_user_option($this->user_id, 'user_hours_format')?md_the_user_option($this->user_id, 'user_hours_format'):'24',
        'items' => $hours_format_list,
        'position' => 5
    )
);

// Days of the week container
$days_of_the_week = array(
    array(
        'id' => '1',
        'text' => $this->lang->line('crm_settings_monday')
    ),
    array(
        'id' => '7',
        'text' => $this->lang->line('crm_settings_sunday')    
    )
);

// Format days of the week
$days = array_column($days_of_the_week, 'text', 'id');

// Set profile's preferences option
set_crm_settings_profile_preferences_option (
    array(
        'template_slug' => 'select_list',
        'slug' => 'user_first_day',
        'label' => $this->lang->line('crm_settings_first_day_week'),
        'label_description' => $this->lang->line('crm_settings_first_day_week_description'),
        'btn_text' => md_the_user_option($this->user_id, 'user_first_day')?$days[md_the_user_option($this->user_id, 'user_first_day')]:$this->lang->line('crm_settings_monday'),
        'btn_id' => md_the_user_option($this->user_id, 'user_first_day')?md_the_user_option($this->user_id, 'user_first_day'):'1',
        'items' => $days_of_the_week,
        'position' => 6
    )
);

/* End of file user.php */