<div class="row">
    <div class="col-12 crm-chatbot-quick-reply-builder">
        <div class="row">
            <div class="col-12 theme-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('user/app/crm_chatbot?p=quick_replies'); ?>">
                                <?php echo $this->lang->line('crm_chatbot_quick_replies'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo $this->lang->line('crm_chatbot_new_quick_reply'); ?>
                        </li>                        
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php echo form_open('user/app/crm_chatbot', array('class' => 'mb-3 crm-chatbot-new-quick-reply')) ?>
                    <div class="row">
                        <div class="col-md-6 theme-box-border-right">
                            <div class="card theme-card-box-1 theme-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'keyboard_alt')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_keywords'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_keywords_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-12 mt-3 theme-form">
                                            <textarea placeholder="<?php echo $this->lang->line('crm_chatbot_enter_keywords_divided_by_white_spaces'); ?>" class="w-100 crm-chatbot-quick-reply-keywords"></textarea>
                                        </div>
                                    </div>                                                   
                                </div>
                            </div>
                            <div class="card theme-card-box-1 theme-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'percent_line')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_accuracy'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">  
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_accuracy_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="crm-chatbot-quick-reply-accuracy-dropdown" class="dropdown theme-box-2 theme-dropdown-5 theme-dropdown-icon-1">
                                                <button class="btn btn-link" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" data-accuracy="0">
                                                    0 %
                                                    <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                                                </button>
                                                <div class="dropdown-menu crm-chatbot-quick-reply-accuracy-list">
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="0">
                                                        0 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="10">
                                                        10 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="20">
                                                        20 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="30">
                                                        30 %
                                                    </a>
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="40">
                                                        40 %
                                                    </a>
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="50">
                                                        50 %
                                                    </a>
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="60">
                                                        60 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="70">
                                                        70 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="80">
                                                        80 %
                                                    </a>
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="90">
                                                        90 %
                                                    </a> 
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-accuracy" data-accuracy="100">
                                                        100 %
                                                    </a>                                                                                                                                                                                                                                                                                               
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                                      
                                </div>
                            </div>                
                            <div class="mb-0 card theme-card-box-1 theme-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'reply')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_response'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="theme-short-description">
                                                <p>
                                                    <?php echo $this->lang->line('crm_chatbot_response_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="crm-chatbot-quick-reply-response-type-dropdown" class="dropdown theme-box-2 theme-dropdown-5 theme-dropdown-icon-1">
                                                <button class="btn btn-link" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" data-type="0">
                                                    <?php echo $this->lang->line('crm_chatbot_text_response'); ?>
                                                    <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                                                </button>
                                                <div class="dropdown-menu crm-chatbot-quick-reply-response-types-list">
                                                    <a href="#crm-chatbot-quick-reply-response-text" class="dropdown-item crm-chatbot-quick-reply-response-type" data-type="0">
                                                        <?php echo $this->lang->line('crm_chatbot_text_response'); ?>
                                                    </a> 
                                                    <a href="#crm-chatbot-quick-reply-response-bot" class="dropdown-item crm-chatbot-quick-reply-response-type" data-type="1">
                                                        <?php echo $this->lang->line('crm_chatbot_bot_response'); ?>
                                                    </a>                                                                                                                                                                                                                                                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane active" id="crm-chatbot-quick-reply-response-text" aria-labelledby="crm-chatbot-quick-reply-response-text-tab">
                                                    <div class="theme-modal-input-area mt-3 mb-0 p-0">
                                                        <div class="form-group crm-chatbot-text-editor-element-preview m-0">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card p-0 pt-3 theme-minimal-text-editor-box theme-box-2">
                                                                        <div class="card-header">
                                                                            <div class="row">
                                                                                <div class="col-12 theme-minimal-text-editor-toolbar">
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="justifyLeft">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_align_left')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="justifyCenter">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_align_center')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="justifyRight">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_align_right')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="justifyFull">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_align_justify')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="bold">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_bold')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="italic">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_italic')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="underline">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_underline')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-link-add">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_link')); ?>
                                                                                    </button>
                                                                                    <button type="button" class="btn btn-light btn-option" data-type="unlink">
                                                                                        <?php echo md_the_user_icon(array('icon' => 'fa_unlink')); ?>
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="card-body">
                                                                            <div class="theme-minimal-text-editor" contenteditable="true"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane" id="crm-chatbot-quick-reply-response-bot" aria-labelledby="crm-chatbot-quick-reply-response-bot-tab">
                                                    <div id="crm-chatbot-quick-reply-response-bots-dropdown" class="dropdown theme-box-2 theme-dropdown-5 theme-dropdown-icon-1">
                                                        <button class="btn btn-link d-flex justify-content-between align-items-center" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" data-bot="0">
                                                            <div class="media">
                                                                <span class="mr-3 crm-chatbot-bot-icon">
                                                                    <?php echo md_the_user_icon(array('icon' => 'smart_toy', 'class' => 'theme-color-black')); ?>
                                                                </span>
                                                                <div class="media-body">
                                                                    <?php echo $this->lang->line('crm_chatbot_select_a_bot'); ?>
                                                                </div>
                                                            </div>
                                                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                                                        </button>
                                                        <div class="dropdown-menu crm-chatbot-quick-reply-response-bots-menu">
                                                            <input class="crm-chatbot-quick-reply-response-bots-search" type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_bots'); ?>">
                                                            <div>
                                                                <ul class="list-group"></ul>
                                                            </div>                                                                                                                                                                                                                                                                     
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                               
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-6">
                            <div class="card theme-card-box-1 theme-box-1">
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
                                                <button class="btn btn-secondary dropdown-toggle theme-color-black" id="crm-chatbot-quick-reply-categories-actions" aria-expanded="false" aria-haspopup="true" type="button" data-toggle="dropdown">
                                                    <?php echo md_the_user_icon(array('icon' => 'more')); ?>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="crm-chatbot-quick-reply-categories-actions">
                                                    <a href="#" class="dropdown-item crm-chatbot-quick-reply-categories-manage theme-color-black" data-toggle="modal" data-target="#crm-chatbot-quick-reply-categories-modal">
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
                                                    <?php echo $this->lang->line('crm_chatbot_categories_description'); ?>
                                                </p>                                    
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row">
                                        <div class="col-12">
                                            <ul class="crm-chatbot-quick-reply-categories"></ul>
                                        </div>
                                    </div>   
                                </div>
                            </div>
                            <div class="card theme-card-box-1 theme-box-1">
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
                                            <div class="crm-chatbot-quick-reply-actions">
                                                <button type="submit" class="btn btn-primary theme-button-1 theme-background-green">
                                                    <?php echo md_the_user_icon(array('icon' => 'import')); ?>            
                                                    <?php echo $this->lang->line('crm_chatbot_save_reply'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>                
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>                
    </div>
</div>