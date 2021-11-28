<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-website-edit-website-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-website-edit-website-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content theme-color-black">
            <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbots-edit-website-url')) ?>
                <div class="modal-header">
                    <h5 class="modal-title theme-font-2">
                        <?php echo md_the_user_icon(array('icon' => 'insert_link')); ?>
                        <?php echo $this->lang->line('crm_chatbot_edit_website_url'); ?>
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
                                        <input type="text" value="<?php echo $website['url']; ?>" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_website_url'); ?>" class="form-control theme-text-input-1 crm-chatbot-website-url" aria-describedby="crm-chatbot-website-url" required />
                                    </div>
                                </div>
                            </div>
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
                                <?php echo $this->lang->line('crm_chatbot_save_changes'); ?>
                            </button>                                
                        </div>
                    </div>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>