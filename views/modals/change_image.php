<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-change-image-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-change-image-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <?php echo md_the_user_icon(array('icon' => 'image')); ?> 
                    <?php echo $this->lang->line('crm_chatbot_image'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                            
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="crm-chatbot-selected-image"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-primary theme-background-blue" data-toggle="collapse" data-target="#crm-chatbot-upload-image" aria-expanded="false" aria-controls="crm-chatbot-upload-image">
                            <?php echo md_the_user_icon(array('icon' => 'image_add')); ?>  
                            <?php echo $this->lang->line('crm_chatbot_change_image'); ?>
                        </button>                               
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="collapse" id="crm-chatbot-upload-image">
                            <div class="crm-drag-and-drop-files" data-upload-form-url="<?php echo site_url('user/app-ajax/crm_chatbot'); ?>" data-upload-form-action="crm_chatbot_change_icon" data-supported-extensions=".png,.jpeg,.gif">
                                <div data-for="form">
                                    <input type="text" name="member" value="<?php echo $this->input->get('member', TRUE); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>