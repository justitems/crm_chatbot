<div class="row">
    <div class="col-12 crm-chatbot-bot-builder">
        <div class="row">
            <div class="col-6 theme-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('user/app/crm_chatbot?p=bots'); ?>">
                                <?php echo $this->lang->line('crm_chatbot_bots'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item active crm-chatbot-bot-name" contenteditable="true" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_bot_name'); ?>" aria-current="page"></li>
                    </ol>
                </nav>
            </div>
            <div class="col-6 text-right">
                <div class="btn-group crm-chatbot-bot-actions-group" role="group" aria-label="Bot Actions">
                    <div class="dropdown dropdown-options theme-dropdown-1 theme-dropdown-icon-1" id="crm-chatbot-bot-preview-chat-dropdown">
                        <button class="btn btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo md_the_user_icon(array('icon' => 'slideshow')); ?>
                            <?php echo $this->lang->line('crm_chatbot_preview'); ?>
                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                        </button>
                        <div class="dropdown-menu crm-chatbot-preview-chat">
                            <p class="crm-chatbot-preview-chat-no-saved-changes"><?php echo $this->lang->line('crm_chatbot_preview_available_saved_bots'); ?></p>
                        </div>
                    </div>                    
                    <div class="dropdown dropdown-options theme-dropdown-1 theme-dropdown-icon-1" id="crm-chatbot-select-element-dropdown">
                        <button class="btn btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo md_the_user_icon(array('icon' => 'drag_indicator')); ?>
                            <?php echo $this->lang->line('crm_chatbot_elements'); ?>
                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                        </button>
                        <div class="dropdown-menu crm-chatbot-elements-directory">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mt-0">
                                        <?php echo $this->lang->line('crm_chatbot_display'); ?>
                                    </h4>
                                </div>
                            </div>                                
                            <div class="row">
                                <div class="col-12">  
                                    <ul class="crm-chatbot-elements-directory-list" data-type="display">                              
                                    </ul>                                    
                                </div>
                            </div>  
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <?php echo $this->lang->line('crm_chatbot_actions'); ?>
                                    </h4>
                                </div>
                            </div> 
                            <div class="row">
                                <div class="col-12">  
                                    <ul class="crm-chatbot-elements-directory-list" data-type="actions">                  
                                    </ul>                                    
                                </div>
                            </div>                                                                                          
                        </div>
                    </div>
                    <div class="dropdown theme-dropdown-1 theme-dropdown-icon-1" id="crm-chatbot-actions-dropdown">
                        <button class="btn btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo md_the_user_icon(array('icon' => 'flow_chart')); ?> 
                            <?php echo $this->lang->line('crm_chatbot_actions'); ?>
                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                        </button>
                        <div class="dropdown-menu crm-chatbot-actions-list">
                            <a href="#" class="dropdown-item crm-chatbot-action-save-bot theme-color-black">
                                <?php echo md_the_user_icon(array('icon' => 'import')); ?>
                                <?php echo $this->lang->line('crm_chatbot_save'); ?>
                            </a>
                            <a href="#crm-chatbot-bot-categories-modal" class="dropdown-item crm-clients-duplicate-role theme-color-black" data-toggle="modal" data-target="#crm-chatbot-bot-categories-modal">
                                <?php echo md_the_user_icon(array('icon' => 'drawer')); ?>
                                <?php echo $this->lang->line('crm_chatbot_categories'); ?>
                            </a>                                                               
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">   
                <div class="crm-chatbot-bot-rules-area">
                    <div class="crm-chatbot-bot-rules-area-container" id="crm-chatbot-bot-rules-area-container"></div>
                    <textarea class="d-none" id="flowchart_data"></textarea>
                </div>
            </div>
        </div>   
        <div class="row">
            <div class="col-12">   
                <div class="crm-chatbot-bot-manage">
                    <div class="btn-group crm-chatbot-bot-manage-group" role="group" aria-label="Bot Manage">
                        <button type="button" class="btn btn-secondary crm-chatbot-bot-zoom-btn">
                            <?php echo md_the_user_icon(array('icon' => 'center_focus_weak')); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>                  
    </div>
</div>