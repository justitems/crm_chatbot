<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-activity-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-activity-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content theme-color-black">
            <div class="modal-header">
                <h5 class="modal-title theme-font-2">
                    <?php echo md_the_user_icon(array('icon' => 'bi_window')); ?>
                    <?php echo $this->lang->line('crm_chatbot_activity_details'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                           
                </button>
            </div>
            <div class="modal-activity-actions">              
                <div class="row">
                    <div class="col-12">
                        <div class="list-group">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>