<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-export-quick-replies-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-export-quick-replies-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content theme-color-black">
            <div class="modal-header">
                <h5 class="modal-title theme-font-2">
                    <?php echo md_the_user_icon(array('icon' => 'export')); ?>
                    <?php echo $this->lang->line('crm_chatbot_export_quick_replies'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                           
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="theme-short-description mb-3">
                            <p>
                                <?php echo $this->lang->line('crm_chatbot_download_quick_replies_info'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="theme-modal-input-area m-0 text-center">
                            <a href="#" class="crm-chatbot-download-quick-replies">
                                <?php echo $this->lang->line('crm_chatbot_download_quick_replies'); ?>
                            </a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
</div>