<!-- Modal -->
<div class="modal fade crm-confirmation-modal" id="crm-chatbot-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-confirmation-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content theme-color-black">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <h4 class="theme-font-2">
                            <?php echo $this->lang->line('crm_chatbot_are_you_sure'); ?>
                        </h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-12 p-0 text-right">
                        <button type="button" class="btn btn-secondary theme-background-green" data-dismiss="modal">
                            <?php echo md_the_user_icon(array('icon' => 'bi_x_circle')); ?>  
                            <?php echo $this->lang->line('crm_chatbot_no'); ?>
                        </button>
                        <button type="button" class="btn btn-primary theme-background-red crm-confirm-action">
                            <?php echo md_the_user_icon(array('icon' => 'bi_check_circle')); ?>
                            <?php echo $this->lang->line('crm_chatbot_yes'); ?>
                        </button>                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>