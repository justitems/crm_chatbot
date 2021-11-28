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
                        <li class="breadcrumb-item active crm-chatbot-bot-name" contenteditable="true" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_bot_name'); ?>" aria-current="page"><?php echo $bot_name; ?></li>
                        <li class="breadcrumb-item crm-chatbot-bot-actions" aria-current="page">
                            <div class="theme-checkbox-input-2">
                                <input id="crm-chatbot-bot-enable" name="crm-chatbot-bot-enable" class="crm-chatbot-bot-enable" type="checkbox"<?php echo $status?' checked':''; ?>>
                                <label for="crm-chatbot-bot-enable"></label>
                            </div>
                            <span class="crm-chatbot-bot-status-active">
                                <?php echo $status?$this->lang->line('crm_chatbot_on'):$this->lang->line('crm_chatbot_off'); ?>
                            </span>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-6 text-right">
                <div class="btn-group crm-chatbot-bot-actions-group" role="group" aria-label="Bot Actions">
                    <div class="dropdown dropdown-options theme-dropdown-1" id="crm-chatbot-bot-preview-chat-dropdown">
                        <button class="btn btn-link theme-dropdown-icon-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo md_the_user_icon(array('icon' => 'slideshow')); ?>
                            <?php echo $this->lang->line('crm_chatbot_preview'); ?>
                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                        </button>
                        <div class="dropdown-menu crm-chatbot-preview-chat" x-placement="bottom-start">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="media">
                                        <span class="mr-3 crm-chatbot-bot-icon">
                                            <?php echo md_the_user_icon(array('icon' => 'smart_toy', 'class' => 'theme-color-black')); ?>
                                        </span>
                                        <div class="media-body">
                                            <h5>
                                                <?php echo $this->lang->line('crm_chatbot_bot'); ?>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body theme-scrollbar-1">
                                    <ul class="crm-chatbot-messages-list">
                                        <li>
                                            <div class="media">
                                                <span class="mr-1 crm-chatbot-bot-icon">
                                                    <?php echo md_the_user_icon(array('icon' => 'smart_toy', 'class' => 'theme-color-black')); ?>
                                                </span>
                                                <div class="media-body">                                                                                                                                            
                                                </div>
                                            </div>                                            
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="dropdown dropdown-options theme-dropdown-1 theme-dropdown-icon-1" id="crm-chatbot-select-element-dropdown">
                        <button class="btn btn-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo md_the_user_icon(array('icon' => 'drag_indicator')); ?>
                            <?php echo $this->lang->line('crm_chatbot_elements'); ?>
                            <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                        </button>
                        <div class="dropdown-menu crm-chatbot-elements-directory" x-placement="bottom-start">
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
                        <div class="dropdown-menu crm-chatbot-actions-list" x-placement="bottom-start">
                            <a href="#" class="dropdown-item crm-chatbot-action-update-bot theme-color-black">
                                <?php echo md_the_user_icon(array('icon' => 'import')); ?>
                                <?php echo $this->lang->line('crm_chatbot_save'); ?>
                            </a>
                            <a href="#crm-chatbot-bot-categories-modal" class="dropdown-item crm-clients-duplicate-role theme-color-black" data-toggle="modal" data-target="#crm-chatbot-bot-categories-modal">
                                <?php echo md_the_user_icon(array('icon' => 'drawer')); ?>
                                <?php echo $this->lang->line('crm_chatbot_categories'); ?>
                            </a>
                            <a href="#" class="dropdown-item crm-clients-delete-role theme-color-black">
                                <?php echo md_the_user_icon(array('icon' => 'delete')); ?>
                                <?php echo $this->lang->line('crm_chatbot_delete'); ?>
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
                </div>
            </div>
        </div>        
        <?php
        
        // Get the bot
        $bot = the_crm_chatbot_bot(array('bot' => $bot_id));

        // Verify if the bot's data exists
        if ( !empty($bot['bot_data']) ) {

            ?>
            <script>
                let bot_data = JSON.parse(JSON.stringify(<?php echo json_encode($bot['bot_data']); ?>));
            </script>
            <?php

        }

        ?>
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