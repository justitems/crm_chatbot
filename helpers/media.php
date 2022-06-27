<?php
/**
 * Media Helpers
 *
 * This file contains the class Media
 * with methods to manage the chatbot's medias
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
use CmsBase\Classes\Media as CmsBaseClassesMedia;

/*
 * Media class provides the methods to manage the chatbot's medias
 * 
 * @author Scrisoft
 * @package CRM
 * @since 0.0.8.4
*/
class Media {
    
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
    // Ajax's methods for the Media
    //-----------------------------------------------------

    /**
     * The public method crm_chatbot_upload_bot_element_images uploads element's files
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_upload_bot_element_images() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Verify if the file was uploaded
            if ( empty($_FILES['files']) ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get user's plan
            $user_plan = md_the_user_option(md_the_user_id(), 'plan');

            // Verify if user's plan exists
            if ( !$user_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get plan's information
            $get_plan = $this->CI->base_model->the_data_where(
            'plans_meta',
            'meta_value AS storage',
            array(
                'plan_id' => $user_plan,
                'meta_name' => 'storage'
            ));

            // Verify if plan exists
            if ( !$get_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_plan_was_not_found')
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

            // Get user storage
            $user_storage = md_the_user_option(md_the_user_id(), 'user_storage');

            // Temporary storage
            $temp_storage = ($user_storage?$user_storage:0);

            // Files limit
            $files_limit = 10;

            // Errors catcher
            $errors = array();

            // Uplods catcher
            $attachments = array();

            // List all files
            for ( $t = 0; $t < count($_FILES['files']['tmp_name']); $t++ ) {

                // Verify if the uploaded file is an image
                if ( !in_array($_FILES['files']['type'][$t], array('image/jpeg', 'image/jpg', 'image/png', 'image/gif')) ) {

                    // Set cover
                    $cover = file_get_contents($_FILES['files']['tmp_name'][$t]);                    

                } else {

                    // Set default cover
                    $cover = file_get_contents(base_url('assets/img/no-image.png'));

                }

                // Set file's data
                $_FILES['file']['name'] = $_FILES['files']['name'][$t];
                $_FILES['file']['type'] = $_FILES['files']['type'][$t];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$t];
                $_FILES['file']['error'] = $_FILES['files']['error'][$t];
                $_FILES['file']['size'] = $_FILES['files']['size'][$t];

                // Upload media
                $response = (new CmsBaseClassesMedia\Upload)->upload(array(
                    'cover' => $cover,
                    'allowed_extensions' => array('image/png', 'image/jpg', 'image/jpeg', 'image/gif')
                ), true);

                // Verify if the file was uploaded
                if ( !empty($response['success']) ) {

                    // Get file type
                    $get_type = explode('/', $_FILES['file']['type']);

                    // Get the file url
                    $file_url = $this->CI->base_model->the_data_where(
                    'medias',
                    '*',
                    array(
                        'media_id' => $response['media_id']
                    ));

                    // Set uploaded file
                    $attachments[] = array(
                        'media_id' => $response['media_id'],
                        'file_name' => $_FILES['files']['name'][$t],
                        'file_size' => $this->calculate_file_size($_FILES['file']['size']),
                        'file_format' => $get_type[1],
                        'file_type' => $file_url?$file_url[0]['type']:'',
                        'file_url' => $file_url?$file_url[0]['body']:base_url('assets/img/no-image.png'),
                        'created' => time()
                    );

                } else {

                    // Set error
                    $errors[] = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_file') . ' ' . $_FILES['files']['name'][$t] . ' ' . $this->CI->lang->line('crm_chatbot_was_not_uploaded_successfully'),
                        'error' => $response['message']
                    );

                }

            }

            // Prepare response
            $message = array(
                'success' => TRUE,
                'attachments' => $attachments,
                'errors' => $errors
            );

            // Display the response
            echo json_encode($message);
            exit();

        }
        
        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_upload_bot_element_videos uploads element's files
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_upload_bot_element_videos() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Verify if the file was uploaded
            if ( empty($_FILES['files']) ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get user's plan
            $user_plan = md_the_user_option(md_the_user_id(), 'plan');

            // Verify if user's plan exists
            if ( !$user_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get plan's information
            $get_plan = $this->CI->base_model->the_data_where(
            'plans_meta',
            'meta_value AS storage',
            array(
                'plan_id' => $user_plan,
                'meta_name' => 'storage'
            ));

            // Verify if plan exists
            if ( !$get_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_plan_was_not_found')
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

            // Get user storage
            $user_storage = md_the_user_option(md_the_user_id(), 'user_storage');

            // Temporary storage
            $temp_storage = ($user_storage?$user_storage:0);

            // Files limit
            $files_limit = 10;

            // Errors catcher
            $errors = array();

            // Uplods catcher
            $attachments = array();

            // List all files
            for ( $t = 0; $t < count($_FILES['files']['tmp_name']); $t++ ) {

                // Verify if the uploaded file is an video
                if ( !in_array($_FILES['files']['type'][$t], array('video/mp4')) ) {

                    // Set cover
                    $cover = file_get_contents($_FILES['files']['tmp_name'][$t]);                    

                } else {

                    // Set default cover
                    $cover = file_get_contents(base_url('assets/img/no-image.png'));

                }

                // Set file's data
                $_FILES['file']['name'] = $_FILES['files']['name'][$t];
                $_FILES['file']['type'] = $_FILES['files']['type'][$t];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$t];
                $_FILES['file']['error'] = $_FILES['files']['error'][$t];
                $_FILES['file']['size'] = $_FILES['files']['size'][$t];

                // Upload media
                $response = (new CmsBaseClassesMedia\Upload)->upload(array(
                    'cover' => $cover,
                    'allowed_extensions' => array('video/mp4')
                ), true);

                // Verify if the file was uploaded
                if ( !empty($response['success']) ) {

                    // Get file type
                    $get_type = explode('/', $_FILES['file']['type']);

                    // Get the file url
                    $file_url = $this->CI->base_model->the_data_where(
                    'medias',
                    '*',
                    array(
                        'media_id' => $response['media_id']
                    ));

                    // Set uploaded file
                    $attachments[] = array(
                        'media_id' => $response['media_id'],
                        'file_name' => $_FILES['files']['name'][$t],
                        'file_size' => $this->calculate_file_size($_FILES['file']['size']),
                        'file_format' => $get_type[1],
                        'file_type' => $file_url?$file_url[0]['type']:'',
                        'file_url' => $file_url?$file_url[0]['body']:base_url('assets/img/no-image.png'),
                        'created' => time()
                    );

                } else {

                    // Set error
                    $errors[] = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_file') . ' ' . $_FILES['files']['name'][$t] . ' ' . $this->CI->lang->line('crm_chatbot_was_not_uploaded_successfully'),
                        'error' => $response['message']
                    );

                }

            }

            // Prepare response
            $message = array(
                'success' => TRUE,
                'attachments' => $attachments,
                'errors' => $errors
            );

            // Display the response
            echo json_encode($message);
            exit();

        }
        
        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_items_selected_medias gets items selected media files
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_items_selected_medias() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('media_ids', 'Media IDs', 'trim');

            // Get data
            $media_ids = $this->CI->input->post('media_ids', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get the medias
                $the_medias = $this->CI->base_model->the_data_where(
                    'medias',
                    '*',
                    array(
                        'user_id' => md_the_user_id()
                    ),
                    array(
                        'media_id', $media_ids
                    )

                );

                // Verify if media's files exists
                if ( $the_medias ) {

                    // Uplods catcher
                    $attachments = array();

                    // List the found media files
                    foreach ( $the_medias as $the_media ) {

                        // The extension
                        $the_extension = pathinfo($the_media['name'], PATHINFO_EXTENSION);

                        // Set uploaded file
                        $attachments[] = array(
                            'media_id' => $the_media['media_id'],
                            'file_name' => $the_media['name'],
                            'file_size' => $this->calculate_file_size($the_media['size']),
                            'file_format' => $the_extension,
                            'file_type' => $the_media['type'],
                            'file_url' => $the_media['body'],
                            'created' => time()
                        );

                    }

                    // Verify if attachments exists
                    if ( $attachments ) {

                        // Prepare response
                        $message = array(
                            'success' => TRUE,
                            'attachments' => $attachments
                        );

                        // Display the response
                        echo json_encode($message);
                        exit();

                    }

                }

            }

        }

        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_media_files_were_found')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_get_selected_medias gets selected media files
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_get_selected_medias() {

        // Check if data was submitted
        if ($this->CI->input->post()) {
            
            // Add form validation
            $this->CI->form_validation->set_rules('media_ids', 'Media IDs', 'trim');

            // Get data
            $media_ids = $this->CI->input->post('media_ids', TRUE);
            
            // Verify if the submitted data is correct
            if ( $this->CI->form_validation->run() !== false ) {

                // Get the medias
                $the_medias = $this->CI->base_model->the_data_where(
                    'medias',
                    '*',
                    array(
                        'user_id' => md_the_user_id()
                    ),
                    array(
                        'media_id', $media_ids
                    )

                );

                // Uplods catcher
                $attachments = array();

                // List the found media files
                foreach ( $the_medias as $the_media ) {

                    // The extension
                    $the_extension = pathinfo($the_media['name'], PATHINFO_EXTENSION);

                    // Set uploaded file
                    $attachments[] = array(
                        'media_id' => $the_media['media_id'],
                        'file_name' => $the_media['name'],
                        'file_size' => $this->calculate_file_size($the_media['size']),
                        'file_format' => $the_extension,
                        'file_type' => $the_media['type'],
                        'file_url' => $the_media['body'],
                        'created' => time()
                    );

                }

                // Prepare response
                $message = array(
                    'success' => TRUE,
                    'attachments' => $attachments
                );

                // Display the response
                echo json_encode($message);
                exit();

            }

        }

        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_no_media_files_were_found')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_change_icon changes an icon in the website's settings
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    public function crm_chatbot_change_icon() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Verify if the file was uploaded
            if ( empty($_FILES['files']) ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get user's plan
            $user_plan = md_the_user_option(md_the_user_id(), 'plan');

            // Verify if user's plan exists
            if ( !$user_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get plan's information
            $get_plan = $this->CI->base_model->the_data_where(
            'plans_meta',
            'meta_value AS storage',
            array(
                'plan_id' => $user_plan,
                'meta_name' => 'storage'
            ));

            // Verify if plan exists
            if ( !$get_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_plan_was_not_found')
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

            // Get user storage
            $user_storage = md_the_user_option(md_the_user_id(), 'user_storage');

            // Temporary storage
            $temp_storage = ($user_storage?$user_storage:0);

            // Verify if the uploaded file is an image
            if ( !in_array($_FILES['files']['type'][0], array('image/jpeg', 'image/jpg', 'image/png', 'image/gif')) ) {

                // Set cover
                $cover = file_get_contents($_FILES['files']['tmp_name'][0]);                    

            } else {

                // Set default cover
                $cover = file_get_contents(base_url('assets/img/no-image.png'));

            }

            // Set file's data
            $_FILES['file']['name'] = $_FILES['files']['name'][0];
            $_FILES['file']['type'] = $_FILES['files']['type'][0];
            $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][0];
            $_FILES['file']['error'] = $_FILES['files']['error'][0];
            $_FILES['file']['size'] = $_FILES['files']['size'][0];

            // Upload media
            $response = (new CmsBaseClassesMedia\Upload)->upload(array(
                'cover' => $cover,
                'allowed_extensions' => array('image/png', 'image/jpg', 'image/jpeg', 'image/gif')
            ), true);

            // Verify if the file was uploaded
            if ( !empty($response['success']) ) {

                // Get file type
                $get_type = explode('/', $_FILES['file']['type']);

                // Get the file url
                $file_url = $this->CI->base_model->the_data_where(
                'medias',
                '*',
                array(
                    'media_id' => $response['media_id']
                ));

                // Prepare response
                $message = array(
                    'success' => TRUE,
                    'message' => $this->CI->lang->line('crm_chatbot_the_image_was_uploaded'),
                    'media_id' => $response['media_id'],
                    'file_url' => $file_url?$file_url[0]['body']:base_url('assets/img/no-image.png')                    
                );

                // Display the response
                echo json_encode($message);

            } else {

                // Set error
                $errors[] = array(
                    'success' => FALSE,
                    'message' => !empty($response['message'])?$response['message']:$this->CI->lang->line('crm_chatbot_the_file') . ' ' . $_FILES['files']['name'][0] . ' ' . $this->CI->lang->line('crm_chatbot_was_not_uploaded_successfully')
                );

            }

            exit();

        }
        
        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    /**
     * The public method crm_chatbot_upload_message_files uploads files to a message
     * 
     * @since 0.0.8.5
     * 
     * @return void
     */
    public function crm_chatbot_upload_message_files() {

        // Check if data was submitted
        if ($this->CI->input->post()) {

            // Verify if the file was uploaded
            if ( empty($_FILES['files']) ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get user's plan
            $user_plan = md_the_user_option(md_the_user_id(), 'plan');

            // Verify if user's plan exists
            if ( !$user_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
                );

                // Display the error response
                echo json_encode($data);
                exit();                    

            }

            // Get plan's information
            $get_plan = $this->CI->base_model->the_data_where(
            'plans_meta',
            'meta_value AS storage',
            array(
                'plan_id' => $user_plan,
                'meta_name' => 'storage'
            ));

            // Verify if plan exists
            if ( !$get_plan ) {

                // Prepare the error response
                $data = array(
                    'success' => FALSE,
                    'message' => $this->CI->lang->line('crm_chatbot_plan_was_not_found')
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

            // Get user storage
            $user_storage = md_the_user_option(md_the_user_id(), 'user_storage');

            // Temporary storage
            $temp_storage = ($user_storage?$user_storage:0);

            // Files limit
            $files_limit = 10;

            // Errors catcher
            $errors = array();

            // Uplods catcher
            $attachments = array();

            // List all files
            for ( $t = 0; $t < count($_FILES['files']['tmp_name']); $t++ ) {

                // Verify if the uploaded file is an image
                if ( !in_array($_FILES['files']['type'][$t], array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'application/pdf', 'application/doc', 'application/ms-doc', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) ) {

                    // Set cover
                    $cover = file_get_contents($_FILES['files']['tmp_name'][$t]);                    

                } else {

                    // Set default cover
                    $cover = file_get_contents(base_url('assets/img/no-image.png'));

                }

                // Set file's data
                $_FILES['file']['name'] = $_FILES['files']['name'][$t];
                $_FILES['file']['type'] = $_FILES['files']['type'][$t];
                $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$t];
                $_FILES['file']['error'] = $_FILES['files']['error'][$t];
                $_FILES['file']['size'] = $_FILES['files']['size'][$t];

                // Upload media
                $response = (new CmsBaseClassesMedia\Upload)->upload(array(
                    'cover' => $cover,
                    'allowed_extensions' => array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/avi', 'application/pdf', 'application/doc', 'application/ms-doc', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/excel', 'application/vnd.ms-excel', 'application/x-excel', 'application/x-msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ), true);

                // Verify if the file was uploaded
                if ( !empty($response['success']) ) {

                    // Get file type
                    $get_type = explode('/', $_FILES['file']['type']);

                    // Get the file url
                    $file_url = $this->CI->base_model->the_data_where(
                    'medias',
                    '*',
                    array(
                        'media_id' => $response['media_id']
                    ));

                    // Set uploaded file
                    $attachments[] = array(
                        'media_id' => $response['media_id'],
                        'file_name' => $_FILES['files']['name'][$t],
                        'file_size' => $this->calculate_file_size($_FILES['file']['size']),
                        'file_format' => $get_type[1],
                        'file_type' => $file_url?$file_url[0]['type']:'',
                        'file_url' => $file_url?$file_url[0]['body']:base_url('assets/img/no-image.png'),
                        'created' => time()
                    );

                } else {

                    // Set error
                    $errors[] = array(
                        'success' => FALSE,
                        'message' => $this->CI->lang->line('crm_chatbot_the_file') . ' ' . $_FILES['files']['name'][$t] . ' ' . $this->CI->lang->line('crm_chatbot_was_not_uploaded_successfully'),
                        'error' => $response['message']
                    );

                }

            }

            // Prepare response
            $message = array(
                'success' => TRUE,
                'attachments' => $attachments,
                'errors' => $errors
            );

            // Display the response
            echo json_encode($message);
            exit();

        }
        
        // Prepare response
        $data = array(
            'success' => FALSE,
            'message' => $this->CI->lang->line('crm_chatbot_an_error_occurred')
        );

        // Prepare response
        echo json_encode($data);
        
    }

    //-----------------------------------------------------
    // Quick Helpers for the Media
    //-----------------------------------------------------

    /**
     * The protected method delete_files deletes all files by extension
     * 
     * @param string $path contains the dir path
     * @param string $ext the files extension
     * 
     * @since 0.0.8.4
     * 
     * @return void
     */
    protected function delete_files($path, $ext) {

        // Verify if files exists
        if ( glob($path . '/*' . $ext) ) {
        
            // List all files
            foreach (glob($path . '/*' . $ext) as $filename) {
                unlink($filename);
            }
        
        }

    }

    /**
     * The protected method calculate_file_size calculates the file's size
     * 
     * @param integer $file_size contains the file's size
     * 
     * @since 0.0.8.4
     * 
     * @return string with file's size
     */
    protected function calculate_file_size($file_size) {
        
        // Set logarithm
        $file_log = log($file_size, 1024);

        // Set labels
        $labels = array('', 'K', 'M', 'G', 'T');   
    
        return round(pow(1024, $file_log - floor($file_log)), 2) .' '. $labels[floor($file_log)];

    }

}

/* End of file media.php */