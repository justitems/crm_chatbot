<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-website-new-trigger-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-website-new-trigger-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbots-new-trigger-form')) ?>
                <div class="modal-header">
                    <h5 class="modal-title theme-font-2">
                        <?php echo md_the_user_icon(array('icon' => 'highlight')); ?> 
                        <?php echo $this->lang->line('crm_chatbot_new_trigger'); ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                            
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="theme-modal-input-area">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_trigger_name'); ?>" class="form-control theme-text-input-1 crm-chatbot-trigger-name" aria-describedby="crm-chatbot-trigger-name" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="theme-modal-input-area">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dropdown theme-dropdown-3 theme-dropdown-icon-1" id="crm-chatbot-dropdown-new-triggers-events-dropdown">
                                            <a href="#" role="button" class="btn btn-secondary dropdown-toggle crm-chatbot-selected-event-btn" id="crm-chatbot-selected-event-btn" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                <?php echo md_the_user_icon(array('icon' => 'airplay')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_choose_event'); ?>
                                                <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                                            </a>
                                            <div class="dropdown-menu crm-chatbot-events-list" aria-labelledby="select-an-event">
                                                <input class="crm-chatbot-search-for-events" type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_events'); ?>" />
                                                <div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="crm-chatbot-dropdown-triggers-event-configuration"></div>
                        </div>
                    </div>                                     
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-12 p-0 text-right">
                            <button type="button" class="btn btn-secondary theme-background-green" data-dismiss="modal">
                                <?php echo md_the_user_icon(array('icon' => 'close_circle')); ?>                           
                                <?php echo $this->lang->line('crm_chatbot_close'); ?>
                            </button>
                            <button type="submit" class="btn btn-primary theme-background-green">
                                <?php echo md_the_user_icon(array('icon' => 'upload')); ?>                           
                                <?php echo $this->lang->line('crm_chatbot_save_trigger'); ?>
                            </button>                                
                        </div>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>