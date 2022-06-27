<?php
/**
 * Csv Helpers
 *
 * This file contains the class Csv
 * with methods to manage the quick replies
 *
 * @author Scrisoft
 * @package CRM
 * @author   Scrisoft <asksyn@gmail.com>
 * @license  https://github.com/scrisoft/crm_chatbot/blob/main/license CRM Chatbot License
 * @link     https://www.midrub.com/
 */

// Define the page namespace
namespace CmsBase\User\Apps\Collection\Crm_chatbot\Helpers;

// Constants
defined('BASEPATH') OR exit('No direct script access allowed');

// Define the namespaces to use
use CmsBase\User\Apps\Collection\Crm_chatbot\Helpers\Parts\Csv as CmsBaseUserAppsCollectionCrm_chatbotHelpersPartsCsv;

/*
 * Csv class provides the methods to manage the csv
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Csv {
    
    /**
     * Class variables
     *
     * @since 0.0.8.4
     */
    protected $CI;

    /**
     * Initialise the Class
     *
     * @since 0.0.8.4
     */
    public function __construct() {
        
        // Get CodeIgniter object instance
        $this->CI =& get_instance();
        
    }

    //-----------------------------------------------------
    // Ajax's methods for the Csv
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_import_quick_replies_from_csv imports quick replies from CSV
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_import_quick_replies_from_csv() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );

            // Display the false response
            echo json_encode($data);
            exit();    

        }

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Add form validation
            $this->CI->form_validation->set_rules('categories', 'Categories', 'trim');

            // Get data
            $categories = $this->CI->input->post('categories', TRUE);

            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Verify if the team's member has permissions
                if ( !md_the_team_role_permission('crm_chatbot_create_quick_replies') ) {

                    // Prepare the false response
                    $data = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
                    );

                    // Display the false response
                    echo json_encode($data);
                    exit();           

                }

                // Categories container
                $categories_list = array();

                // Verify if categories exists
                if ( $categories ) {

                    // Decode the categories
                    $decoded_categories = explode(',', $this->CI->security->xss_clean(base64_decode($categories)));

                    // Verify if decoded categories exists
                    if ( !empty($decoded_categories) ) {

                        // List all decoded categories
                        foreach ( $decoded_categories as $category ) {

                            // Verify if the category exists
                            if ( $this->CI->base_model->the_data_where('crm_chatbot_categories', '*', array('category_id' => $category, 'user_id' => md_the_user_id() ) ) ) {

                                // Set category
                                $categories_list[] = $category;

                            }

                        }

                    }

                }

                // Verify if files exists
                if ( !empty($_FILES['files']) ) {
                    
                    // Verify if the file has expected type
                    if ( ($_FILES['files']['type'][0] !== 'application/octet-stream') && ($_FILES['files']['type'][0] !== 'text/plain') && ($_FILES['files']['type'][0] !== 'text/csv') && ($_FILES['files']['type'][0] !== 'application/vnd.ms-excel') && ($_FILES['files']['type'][0] !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') ) {

                        // Prepare error response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_uploaded_file_wrong_format')
                        );

                        // Display the error response
                        echo json_encode($data);
                        exit();                       

                    }

                    // Get upload limit
                    $upload_limit = md_the_option('upload_limit');
                    
                    // Verify for upload limit
                    if ( !$upload_limit ) {

                        // Set default limit
                        $upload_limit = 6291456;

                    } else {

                        // Set wanted limit
                        $upload_limit = $upload_limit * 1048576;

                    }
                    
                    // Verify if the file size has allowed size
                    if ( $_FILES['files']['size'][0] > $upload_limit ) {
                        
                        // Prepare response
                        $data = array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_uploaded_file_too_large')
                        );

                        // Prepare response
                        echo json_encode($data);
                        exit();
                        
                    }

                    // Set folder
                    $config['upload_path'] = 'assets/base/user/apps/collection/crm-chatbot/temp';

                    // Set allowed types
                    $config['allowed_types'] = '*';

                    // Set maximum size
                    $config['max_size'] = '1';

                    // Set file's data
                    $_FILES['file']['name'] = $_FILES['files']['name'][0];
                    $_FILES['file']['type'] = $_FILES['files']['type'][0];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
                    $_FILES['file']['error'] = $_FILES['files']['error'][0];
                    $_FILES['file']['size'] = $_FILES['files']['size'][0];

                    // Generate a new file name
                    $file_name = uniqid() . '-' . time();

                    // Set file
                    $config['file_name'] = $file_name;

                    // Load library
                    $this->CI->load->library('upload', $config);
 
                    // Upload file
                    if ( $this->CI->upload->do_upload('file') ) {

                        // Verify if the CSV file was upoaded
                        if ( file_exists($config['upload_path'] . '/' . $file_name . '.csv') ) {

                            // Decode the csv file
                            $handle = fopen($config['upload_path'] . '/' . $file_name . '.csv', 'r');

                            // Replies conrainer
                            $replies = array();

                            while ( ($data = fgetcsv($handle, 1000, ",") ) !== FALSE) {

                                // Count columns
                                $num = count($data);

                                // Verify if num is 3
                                if ( $num !== 3 ) {
                                    continue;
                                }

                                // Verify if keywords and response exists
                                if ( empty($this->CI->security->xss_clean($data[0])) || empty($this->CI->security->xss_clean($data[1])) ) {
                                    continue;
                                }

                                // Create reply
                                $reply = array(
                                    'user_id' => md_the_user_id(),
                                    'keywords' => trim($this->CI->security->xss_clean($data[0])),
                                    'status' => 1,
                                    'created' => time()
                                );

                                // Verify if the response is a number
                                if ( !is_numeric(trim($this->CI->security->xss_clean($data[1]))) ) {

                                    // Set response's type
                                    $reply['response_type'] = 0;

                                    // Set response
                                    $reply['response_text'] = trim($this->CI->security->xss_clean($data[1]));

                                } else {

                                    // Get the bot
                                    $the_bot = $this->CI->base_model->the_data_where(
                                        'crm_chatbot_bots',
                                        '*',
                                        array(
                                            'bot_id' => trim($this->CI->security->xss_clean($data[1])),
                                            'user_id' => md_the_user_id()
                                        )
                                    );

                                    // Verify if the bot's exists
                                    if ( $the_bot ) {
                                        
                                        // Set response's type
                                        $reply['response_type'] = 1;

                                        // Set response
                                        $reply['response_bot'] = trim($this->CI->security->xss_clean($data[1]));                                        
                                        
                                    } else {

                                        // Set response's type
                                        $reply['response_type'] = 0;

                                        // Set response
                                        $reply['response_text'] = trim($this->CI->security->xss_clean($data[1]));

                                    }

                                }

                                // Verify if accuracy has correct data
                                if ( in_array(trim($this->CI->security->xss_clean($data[2])), array('0', '10', '20', '30', '40', '50', '60', '70', '80', '90', '100')) ) {

                                    // Set accuracy
                                    $reply['accuracy'] = trim($this->CI->security->xss_clean($data[2]));   

                                } else {

                                    // Set accuracy
                                    $reply['accuracy'] = 100;   

                                }

                                // Add reply to the container
                                $replies[] = $reply;
                                
                            }
                            
                            // Close
                            fclose($handle);
                            
                            // Delete the csv
                            unlink($config['upload_path'] . '/' . $file_name . '.csv');
                            
                            // Replies counter
                            $replies_count = 0;

                            // Categories errors counter
                            $categories_count = 0;

                            // Verify if replies exists
                            if ( $replies ) {

                                // List all replies
                                foreach ( $replies as $reply ) {

                                    // Save the quick reply
                                    $reply_id = $this->CI->base_model->insert('crm_chatbot_quick_replies', $reply);

                                    // Verify if the quick reply was created
                                    if ( $reply_id ) {

                                        // First count
                                        $replies_count++;

                                        // Verify if categories exists
                                        if ( !empty($categories_list) ) {

                                            // List all categories
                                            foreach ( $categories_list as $category ) {

                                                // Save the category
                                                if ( !$this->CI->base_model->insert('crm_chatbot_quick_replies_categories', array('reply_id' => $reply_id, 'category_id' => $category) ) ) {
                                                    $categories_count++;
                                                }

                                            }

                                        }

                                    }

                                }

                            }

                            // Verify if quick replies were imported
                            if ( $replies_count ) {

                                // Delete the user's cache
                                delete_crm_cache_cronology_for_user(md_the_user_id(), 'crm_chatbot_quick_replies_list');

                            }

                            // Verify if categories errors exists
                            if ( $categories_count ) {

                                // Prepare success response
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $replies_count . ' ' . $this->CI->lang->line('crm_chatbot_quick_replies_were_imported') . ' ' . $this->CI->lang->line('crm_chatbot_some_categories_were_not_added')
                                );

                                // Display the success response
                                echo json_encode($data);                                

                            } else {

                                // Prepare success response
                                $data = array(
                                    'success' => TRUE,
                                    'message' => $replies_count . ' ' . $this->CI->lang->line('crm_chatbot_quick_replies_were_imported')
                                );

                                // Display the success response
                                echo json_encode($data);

                            }                          


                        } else {

                            // Prepare error response
                            $data = array(
                                'success' => FALSE,
                                'message' => $this->CI->lang->line('crm_chatbot_file_was_not_uploaded_successfully')
                            );

                            // Display the error response
                            echo json_encode($data);

                        }

                    } else {                        

                        // Prepare error response
                        $data = array(
                            'success' => FALSE,
                            'message' => strip_tags($this->CI->upload->display_errors())
                        );

                        // Display the error response
                        echo json_encode($data); 

                    }

                    exit();

                }

            }

        }

        // Prepare the false response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Display the false response
        echo json_encode($data);

    }

    /**
     * The public method crm_chatbot_download_quick_replies exports quick replies in a CSV
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_download_quick_replies() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );

            // Display the false response
            echo json_encode($data);
            exit();    

        }

        // Get the quick_replies
        $the_quick_replies = $this->CI->base_model->the_data_where(
            'crm_chatbot_quick_replies',
            '*',
            array(
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the quick replies exists
        if ( $the_quick_replies ) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'quick_replies' => $the_quick_replies
            );

            // Display the success response
            echo json_encode($data);      

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_quick_replies_were_found')
            );

            // Display the false response
            echo json_encode($data);            

        }

    }

    /**
     * The public method crm_chatbot_download_phone_numbers exports the phone numbers
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_phone_numbers() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );

            // Display the false response
            echo json_encode($data);
            exit();    

        }

        // Get the numbers numbers
        $the_phone_numbers = $this->CI->base_model->the_data_where(
            'crm_chatbot_numbers',
            '*',
            array(
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the phone numbers exists
        if ( $the_phone_numbers ) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'numbers' => $the_phone_numbers
            );

            // Display the success response
            echo json_encode($data);      

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_numbers_were_found')
            );

            // Display the false response
            echo json_encode($data);            

        }

    }

    /**
     * The public method crm_chatbot_download_email_addresses exports the email addresses
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_email_addresses() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );

            // Display the false response
            echo json_encode($data);
            exit();    

        }

        // Get the emails
        $the_email_addresses = $this->CI->base_model->the_data_where(
            'crm_chatbot_emails',
            '*',
            array(
                'user_id' => md_the_user_id()
            )
        );

        // Verify if the email addresses exists
        if ( $the_email_addresses ) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'emails' => $the_email_addresses
            );

            // Display the success response
            echo json_encode($data);      

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_emails_were_found')
            );

            // Display the false response
            echo json_encode($data);            

        }

    }

    /**
     * The public method crm_chatbot_download_guests exports the guests
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_download_guests() {

        // Verify if the team's member has permissions
        if ( !md_the_team_role_permission('crm_chatbot') ) {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_permissions_for_this_action')
            );

            // Display the false response
            echo json_encode($data);
            exit();    

        }

        // Get team's member
        $member = $this->CI->session->userdata('member')?the_crm_current_team_member():array();

        // Set where
        $where = array(
            'crm_chatbot_websites_guests.user_id' => md_the_user_id()
        );      

        // Set where in
        $where_in = array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Verify if member's role exists
            if ( isset($member['role_id']) ) {             

                // Get the websites
                $the_websites_list = $this->CI->base_model->the_data_where(
                    'crm_chatbot_websites',
                    '*',
                    array(
                        'user_id' => md_the_user_id()
                    )

                );

                // Verify if websites exists
                if ( $the_websites_list ) {

                    // Set websites container
                    $websites = array();     
                    
                    // List the websites
                    foreach ( $the_websites_list as $the_website ) {

                        // Verify if the website is allowed
                        if ( !the_crm_team_roles_multioptions_list_item(md_the_user_id(),  $member['role_id'], 'crm_chatbot_allowed_websites', $the_website['website_id']) ) {
                            continue;
                        }

                        // Add website to the list
                        $websites[] = $the_website['website_id'];

                    } 
                    
                    // Verify if $websites is not empty
                    if ( $websites ) {

                        // Set team's member to params
                        $params['team_member'] = 1;

                        // Set where in
                        $where_in = array('crm_chatbot_websites_threads.website_id', $websites);

                    } else {

                        // Prepare the false response
                        return array(
                            'success' => FALSE,
                            'message' => $this->CI->lang->line('crm_chatbot_no_guests_were_found')
                        );  

                    }

                }

            }

        }

        // Set like
        $like = array();

        // Verify if member exists
        if ( $this->CI->session->userdata( 'member' ) ) {

            // Set join
            $join = array(
                array(
                    'table' => 'crm_chatbot_websites_guests_meta',
                    'condition' => "crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_guests_meta.guest_id AND crm_chatbot_websites_guests_meta.meta_name='guest_name'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta email',
                    'condition' => "crm_chatbot_websites_guests.guest_id=email.guest_id AND email.meta_name='guest_email'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta phone',
                    'condition' => "crm_chatbot_websites_guests.guest_id=phone.guest_id AND phone.meta_name='guest_phone'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_messages',
                    'condition' => 'crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_messages.guest_id',
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_threads',
                    'condition' => 'crm_chatbot_websites_messages.thread_id=crm_chatbot_websites_threads.thread_id',
                    'join_from' => 'LEFT'
                )
            );

        } else {

            // Set join
            $join = array(
                array(
                    'table' => 'crm_chatbot_websites_guests_meta',
                    'condition' => "crm_chatbot_websites_guests.guest_id=crm_chatbot_websites_guests_meta.guest_id AND crm_chatbot_websites_guests_meta.meta_name='guest_name'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta email',
                    'condition' => "crm_chatbot_websites_guests.guest_id=email.guest_id AND email.meta_name='guest_email'",
                    'join_from' => 'LEFT'
                ), array(
                    'table' => 'crm_chatbot_websites_guests_meta phone',
                    'condition' => "crm_chatbot_websites_guests.guest_id=phone.guest_id AND phone.meta_name='guest_phone'",
                    'join_from' => 'LEFT'
                )
            );
            
        }

        // Set limit
        $limit = array(
            'order_by' =>  array('crm_chatbot_websites_guests.created', 'DESC'),
        );

        // Get the guests guests
        $the_guests = $this->CI->base_model->the_data_where(
            'crm_chatbot_websites_guests',
            'crm_chatbot_websites_guests.*, crm_chatbot_websites_guests_meta.meta_value AS full_name, email.meta_value AS guest_email, phone.meta_value AS guest_phone',
            $where,
            $where_in,
            $like,
            $join,
            $limit
        );

        // Verify if the guests exists
        if ( $the_guests ) {

            // Prepare the success response
            $data = array(
                'success' => TRUE,
                'guests' => $the_guests
            );

            // Display the success response
            echo json_encode($data);      

        } else {

            // Prepare the false response
            $data = array(
                'success' => FALSE,
                'message' => $this->CI->lang->line('crm_chatbot_no_guests_were_found')
            );

            // Display the false response
            echo json_encode($data);            

        }

    }

}

/* End of file csv.php */