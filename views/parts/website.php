<div class="row">
    <div class="col-12 crm-chatbot-website-builder">
        <div class="row">
            <div class="col-12 theme-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('user/app/crm_chatbot?p=websites'); ?>">
                                <?php echo $this->lang->line('crm_chatbot_websites'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <span>
                                <?php echo $website['domain']; ?>
                            </span>
                        </li>
                        <li class="breadcrumb-item crm-breadcrumb-actions" aria-current="page">
                            <div class="theme-checkbox-input-2">
                                <input name="crm-chatbot-website-enable" type="checkbox" id="crm-chatbot-website-enable" class="crm-chatbot-website-enable"<?php echo $status?' checked':''; ?>>
                                <label for="crm-chatbot-website-enable"></label>
                            </div>
                            <span class="crm-chatbot-website-status-active">
                                <?php echo $status?$this->lang->line('crm_chatbot_on'):$this->lang->line('crm_chatbot_off'); ?>
                            </span>
                        </li>                     
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbot-update-website')) ?>
                    <div class="row">
                        <div class="col-md-6 theme-box-border-right">
                            <div class="card theme-box-1 theme-card-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'insert_link')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_website'); ?>
                                            </button>                                            
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="dropdown theme-dropdown-1">
                                                <button class="btn btn-secondary dropdown-toggle theme-color-black" id="crm-chatbot-website-edit-website" aria-expanded="false" aria-haspopup="true" type="button" data-toggle="dropdown">
                                                    <?php echo md_the_user_icon(array('icon' => 'more')); ?>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="crm-chatbot-website-edit-website">
                                                    <a href="#" class="dropdown-item crm-chatbot-website-categories-manage theme-color-black" data-toggle="modal" data-target="#crm-chatbot-website-edit-website-modal">
                                                        <?php echo md_the_user_icon(array('icon' => 'edit_box')); ?>
                                                        <?php echo $this->lang->line('crm_chatbot_edit'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_website_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="theme-box-2 mt-3 mb-0 p-2">
                                                <a href="https://<?php echo $website['domain']; ?>" target="_blank">
                                                    <?php echo $website['domain']; ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card theme-box-1 theme-card-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'code')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_embed'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_embed_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>                                    
                                    <div class="row">
                                        <div class="col-12 theme-form crm-chatbot-line-height-0">
                                            <textarea class="mb-0 crm-chatbot-website-embed-code"><script src="<?php echo base_url('assets/base/user/apps/collection/crm-chatbot/js/chat.js'); ?>?ver=<?php echo CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION; ?>" data-crm-website="<?php echo $website_id; ?>"></script></textarea>
                                            <button type="button" class="btn btn-default crm-chatbot-copy-website-embed-code">
                                                <?php echo md_the_user_icon(array('icon' => 'file_copy')); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="mb-0 card theme-box-1 theme-card-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'settings_2')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_settings'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_website_settings_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <?php

                                            // Options
                                            $options = array(
                                                array(
                                                    'id' => 'category_general',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_general')
                                                ),
                                                array(
                                                    'id' => 'chat_styles',
                                                    'type'=> 'dropdown',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_chat_style'),
                                                        'description' => $this->lang->line('crm_chatbot_chat_style_description'),
                                                        'text' => the_crm_chatbot_website_chat_style_name($website_id),
                                                        'id' => the_crm_chatbot_websites_meta($website_id, 'chat_style')?the_crm_chatbot_websites_meta($website_id, 'chat_style'):'default_style'                                                        
                                                    ),
                                                    'button_id' => 'chat_styles',
                                                    'menu' => array(
                                                        'input' => $this->lang->line('crm_chatbot_search_for_styles')
                                                    )
                                                ),
                                                array(
                                                    'id' => 'chat_mode',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_chat_mode'),
                                                        'description' => $this->lang->line('crm_chatbot_chat_mode_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'chat_mode')?1:0
                                                    ),
                                                    'checkbox_id' => 'chat_mode'
                                                ),                                                
                                                array(
                                                    'id' => 'show_chat',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_show_chat'),
                                                        'description' => $this->lang->line('crm_chatbot_show_chat_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'show_chat')?1:0
                                                    ),
                                                    'checkbox_id' => 'show_chat'
                                                ),
                                                array(
                                                    'id' => 'show_welcome',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_show_welcome'),
                                                        'description' => $this->lang->line('crm_chatbot_show_welcome_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'show_welcome')?1:0
                                                    ),
                                                    'checkbox_id' => 'show_welcome'
                                                ),
                                                array(
                                                    'id' => 'category_button',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_button')
                                                ), 
                                                array(
                                                    'id' => 'remove_shadow_button',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_shadow_button'),
                                                        'description' => $this->lang->line('crm_chatbot_shadow_button_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'remove_shadow_button')?1:0
                                                    ),
                                                    'checkbox_id' => 'remove_shadow_button'
                                                ),                                                
                                                array(
                                                    'id' => 'background_color_button',
                                                    'type'=> 'color',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_background_color_button'),
                                                        'description' => $this->lang->line('crm_chatbot_background_color_button_description'),
                                                        'color' => the_crm_chatbot_websites_meta($website_id, 'background_color_button')?the_crm_chatbot_websites_meta($website_id, 'background_color_button'):'rgb(44, 115, 210)'
                                                    ),
                                                    'color_id' => 'background_color_button'
                                                ),
                                                array(
                                                    'id' => 'icon_button',
                                                    'type'=> 'image',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_icon_button'),
                                                        'description' => $this->lang->line('crm_chatbot_icon_button_description'),
                                                        'media_id' => the_crm_chatbot_websites_meta($website_id, 'icon_button', TRUE)?the_crm_chatbot_websites_meta($website_id, 'icon_button', TRUE):0,
                                                        'media_url' => the_crm_chatbot_website_chat_button_icon($website_id)
                                                    ),
                                                    'image_id' => 'icon_button'
                                                ),                                                
                                                array(
                                                    'id' => 'category_welcome_message',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_welcome_message')
                                                ), 
                                                array(
                                                    'id' => 'welcome_message_title',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_welcome_message_title'),
                                                        'description' => $this->lang->line('crm_chatbot_welcome_message_title_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_welcome_message_title'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'welcome_message_title')?the_crm_chatbot_websites_meta($website_id, 'welcome_message_title'):''
                                                    ),
                                                    'textarea_id' => 'welcome_message_title'
                                                ), 
                                                array(
                                                    'id' => 'welcome_message_content',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_welcome_message_content'),
                                                        'description' => $this->lang->line('crm_chatbot_welcome_message_content_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_welcome_message_content'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'welcome_message_content')?the_crm_chatbot_websites_meta($website_id, 'welcome_message_content'):''
                                                    ),
                                                    'textarea_id' => 'welcome_message_content'
                                                ),
                                                array(
                                                    'id' => 'welcome_message_bot',
                                                    'type'=> 'dropdown',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_welcome_message_bot'),
                                                        'description' => $this->lang->line('crm_chatbot_welcome_message_bot_description'),
                                                        'text' => the_crm_chatbot_website_chat_welcome_message_bot($website_id),
                                                        'id' => the_crm_chatbot_websites_meta($website_id, 'welcome_message_bot')?the_crm_chatbot_websites_meta($website_id, 'welcome_message_bot'):0
                                                    ),
                                                    'button_id' => 'welcome_message_bot',
                                                    'menu' => array(
                                                        'input' => $this->lang->line('crm_chatbot_search_for_bots')
                                                    )
                                                ),
                                                array(
                                                    'id' => 'category_chat',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_chat')
                                                ),
                                                array(
                                                    'id' => 'disable_chat_input',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_disable_chat_input'),
                                                        'description' => $this->lang->line('crm_chatbot_disable_chat_input_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'disable_chat_input')?1:0
                                                    ),
                                                    'checkbox_id' => 'disable_chat_input'
                                                ),                                              
                                                array(
                                                    'id' => 'category_agent',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_agent_information')
                                                ),
                                                array(
                                                    'id' => 'user_agent',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_user_agent'),
                                                        'description' => $this->lang->line('crm_chatbot_user_agent_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'user_agent')?1:0
                                                    ),
                                                    'checkbox_id' => 'user_agent'
                                                ),
                                                array(
                                                    'id' => 'member_agent',
                                                    'type'=> 'dropdown',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_select_member'),
                                                        'description' => $this->lang->line('crm_chatbot_select_member_description'),
                                                        'text' => the_crm_chatbot_website_chat_member_agent($website_id),
                                                        'id' => the_crm_chatbot_websites_meta($website_id, 'member_agent')?the_crm_chatbot_websites_meta($website_id, 'member_agent'):0
                                                    ),
                                                    'button_id' => 'member_agent',
                                                    'menu' => array(
                                                        'input' => $this->lang->line('crm_chatbot_search_for_team_members')
                                                    )
                                                ),                                            
                                                array(
                                                    'id' => 'category_bot',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_bot_information')
                                                ), 
                                                array(
                                                    'id' => 'bot_name',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_bot_name'),
                                                        'description' => $this->lang->line('crm_chatbot_bot_name_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_bot_name'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'bot_name')?the_crm_chatbot_websites_meta($website_id, 'bot_name'):''
                                                    ),
                                                    'textarea_id' => 'bot_name'
                                                ),
                                                array(
                                                    'id' => 'bot_photo',
                                                    'type'=> 'image',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_bot_photo'),
                                                        'description' => $this->lang->line('crm_chatbot_bot_photo_description'),
                                                        'media_id' => the_crm_chatbot_websites_meta($website_id, 'bot_photo', TRUE)?the_crm_chatbot_websites_meta($website_id, 'bot_photo', TRUE):0,
                                                        'media_url' => the_crm_chatbot_websites_meta($website_id, 'bot_photo')?the_crm_chatbot_websites_meta($website_id, 'bot_photo'):base_url('assets/base/user/apps/collection/crm-chatbot/img/bot-photo.png')
                                                    ),
                                                    'image_id' => 'bot_photo'
                                                ),
                                                array(
                                                    'id' => 'category_guest',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_guest_information')
                                                ),
                                                array(
                                                    'id' => 'guest_name',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_enable_guest_name'),
                                                        'description' => $this->lang->line('crm_chatbot_enable_guest_name_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'guest_name')?1:0
                                                    ),
                                                    'checkbox_id' => 'guest_name'
                                                ),  
                                                array(
                                                    'id' => 'guest_email',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_enable_guest_email'),
                                                        'description' => $this->lang->line('crm_chatbot_enable_guest_email_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'guest_email')?1:0
                                                    ),
                                                    'checkbox_id' => 'guest_email'
                                                ),
                                                array(
                                                    'id' => 'guest_phone',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_enable_guest_phone'),
                                                        'description' => $this->lang->line('crm_chatbot_enable_guest_phone_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'guest_phone')?1:0
                                                    ),
                                                    'checkbox_id' => 'guest_phone'
                                                ),                                                                                                                                              
                                                array(
                                                    'id' => 'category_gdrp',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_gdrp')
                                                ),
                                                array(
                                                    'id' => 'gdrp',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_enable_gdrp'),
                                                        'description' => $this->lang->line('crm_chatbot_enable_gdrp_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'gdrp')?1:0
                                                    ),
                                                    'checkbox_id' => 'gdrp'
                                                ),
                                                array(
                                                    'id' => 'gdrp_title',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_gdrp_title'),
                                                        'description' => $this->lang->line('crm_chatbot_gdrp_title_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_gdrp_title'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'gdrp_title')?the_crm_chatbot_websites_meta($website_id, 'gdrp_title'):$this->lang->line('crm_chatbot_gdrp_policy')
                                                    ),
                                                    'textarea_id' => 'gdrp_title'
                                                ),
                                                array(
                                                    'id' => 'gdrp_content',
                                                    'type'=> 'text_editor',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_gdrp_content'),
                                                        'description' => $this->lang->line('crm_chatbot_gdrp_content_description'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'gdrp_content')?the_crm_chatbot_websites_meta($website_id, 'gdrp_content'):''
                                                    ),
                                                    'text_editor_id' => 'gdrp_content'
                                                ),
                                                array(
                                                    'id' => 'gdrp_accept_button',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_gdrp_accept_button'),
                                                        'description' => $this->lang->line('crm_chatbot_gdrp_accept_button_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_gdrp_accept_button'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'gdrp_accept_button')?the_crm_chatbot_websites_meta($website_id, 'gdrp_accept_button'):$this->lang->line('crm_chatbot_i_accept')
                                                    ),
                                                    'textarea_id' => 'gdrp_accept_button'
                                                ),
                                                array(
                                                    'id' => 'gdrp_reject_button',
                                                    'type'=> 'textarea',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_gdrp_reject_button'),
                                                        'description' => $this->lang->line('crm_chatbot_gdrp_reject_button_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_gdrp_reject_button'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'gdrp_reject_button')?the_crm_chatbot_websites_meta($website_id, 'gdrp_reject_button'):$this->lang->line('crm_chatbot_not_now')
                                                    ),
                                                    'textarea_id' => 'gdrp_reject_button'
                                                ),                                                
                                            );

                                            // Verify if the guests attachments are enabled
                                            if ( md_the_plan_feature('app_crm_chatbot_guests_attachments') ) {

                                                // Set category
                                                $options[] = array(
                                                    'id' => 'category_attachments',
                                                    'type'=> 'category',
                                                    'title' => $this->lang->line('crm_chatbot_attachments')
                                                );

                                                // Set guests attachment
                                                $options[] = array(
                                                    'id' => 'enable_attachments',
                                                    'type'=> 'checkbox',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_enable_attachments'),
                                                        'description' => $this->lang->line('crm_chatbot_enable_attachments_description'),
                                                        'checked' => the_crm_chatbot_websites_meta($website_id, 'enable_attachments')?1:0
                                                    ),
                                                    'checkbox_id' => 'enable_attachments'
                                                );

                                                // Set guests attachment
                                                $options[] = array(
                                                    'id' => 'thread_attachments',
                                                    'type'=> 'number',
                                                    'info' => array(
                                                        'title' => $this->lang->line('crm_chatbot_thread_attachments'),
                                                        'description' => $this->lang->line('crm_chatbot_thread_attachments_description'),
                                                        'placeholder' => $this->lang->line('crm_chatbot_enter_thread_attachments'),
                                                        'value' => the_crm_chatbot_websites_meta($website_id, 'thread_attachments')?the_crm_chatbot_websites_meta($website_id, 'thread_attachments'):1
                                                    ),
                                                    'number_id' => 'thread_attachments'
                                                );

                                            }

                                            ?>
                                            <?php get_crm_chatbot_website_settings($options); ?>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>     
                        </div>
                        <div class="col-md-6">
                            <div class="card card-triggers theme-box-1 theme-card-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'offline_bolt')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_triggers'); ?>
                                            </button> 
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="dropdown theme-dropdown-1">
                                                <button class="btn btn-secondary dropdown-toggle theme-color-black" id="crm-chatbot-website-edit-website" aria-expanded="false" aria-haspopup="true" type="button" data-toggle="dropdown">
                                                    <?php echo md_the_user_icon(array('icon' => 'more')); ?>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="crm-chatbot-website-edit-website">
                                                    <a href="#" class="dropdown-item crm-chatbot-website-new-trigger theme-color-black" data-toggle="modal" data-target="#crm-chatbot-website-new-trigger-modal">
                                                        <?php echo md_the_user_icon(array('icon' => 'highlight')); ?> 
                                                        <?php echo $this->lang->line('crm_chatbot_new_trigger'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_website_triggers_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="theme-card-box-list">                                                                                                                                                                                        
                                            </ul>
                                        </div>
                                    </div>                                   
                                </div>
                                <div class="card-footer text-right">
                                    <nav aria-label="triggers">
                                        <ul class="theme-pagination" data-type="triggers">
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="card theme-box-1 theme-card-box-1 theme-card-list-with-tags">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'drawer')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_categories'); ?>
                                            </button>
                                        </div>
                                        <div class="col-6 text-right">
                                            <div class="dropdown theme-dropdown-1">
                                                <button class="btn btn-secondary dropdown-toggle theme-color-black" id="crm-chatbot-website-categories-actions" aria-expanded="false" aria-haspopup="true" type="button" data-toggle="dropdown">
                                                    <?php echo md_the_user_icon(array('icon' => 'more')); ?>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="crm-chatbot-website-categories-actions">
                                                    <a href="#" class="dropdown-item crm-chatbot-website-categories-manage theme-color-black" data-toggle="modal" data-target="#crm-chatbot-website-categories-modal">
                                                        <?php echo md_the_user_icon(array('icon' => 'sound_module')); ?>
                                                        <?php echo $this->lang->line('crm_chatbot_manage'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_website_categories_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="crm-chatbot-website-categories"></ul>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="card theme-box-1 theme-card-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'flow_chart')); ?> 
                                                <?php echo $this->lang->line('crm_chatbot_actions'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="crm-breadcrumb-actions">
                                                <button type="submit" class="btn btn-primary theme-background-green theme-button-1">
                                                    <?php echo md_the_user_icon(array('icon' => 'import')); ?>           
                                                    <?php echo $this->lang->line('crm_chatbot_save_changes'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>                
    </div>
</div>