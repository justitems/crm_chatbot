<!-- Modal -->
<div class="modal fade theme-modal" tabindex="-1" role="dialog" id="crm-chatbot-operator-modal" aria-labelledby="crm-chatbot-operator-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content theme-color-black">
            <div class="crm-chatbot-operator-form">
                <div class="modal-header">
                    <h5 class="modal-title theme-font-2">
                        <?php echo md_the_user_icon(array('icon' => 'space_dashboard')); ?>   
                        <?php echo $this->lang->line('crm_chatbot_edit_element'); ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                           
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-12 p-0 text-right">
                            <button type="button" class="btn btn-secondary theme-background-green" data-dismiss="modal">
                                <?php echo md_the_user_icon(array('icon' => 'close_circle')); ?>                        
                                <?php echo $this->lang->line('crm_chatbot_close'); ?>
                            </button>
                            <button type="button" class="btn btn-primary theme-background-green crm-chatbot-operator-update-data">
                                <?php echo md_the_user_icon(array('icon' => 'upload')); ?>                 
                                <?php echo $this->lang->line('crm_chatbot_save'); ?>
                            </button>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>