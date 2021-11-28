<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-import-quick-replies-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-import-quick-replies-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content theme-color-black">
            <div class="modal-header">
                <h5 class="modal-title theme-font-2">
                    <?php echo md_the_user_icon(array('icon' => 'import')); ?>
                    <?php echo $this->lang->line('crm_chatbot_import_quick_replies'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                            
                </button>
            </div>
            <div class="modal-body">
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
                    <div class="col-12 mb-3">
                        <ul class="crm-chatbot-quick-reply-categories"></ul>
                    </div>
                </div>  
                <div class="row">
                    <div class="col-12">
                        <div class="theme-short-description mb-2">
                            <p>
                                <?php echo $this->lang->line('crm_chatbot_get_csv_file_quick_replies'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="crm-drag-and-drop-files" data-upload-form-url="<?php echo site_url('user/app-ajax/crm_chatbot?p=quick_replies'); ?>" data-upload-form-action="crm_chatbot_import_quick_replies_from_csv" data-supported-extensions=".csv">'
                            <div data-for="form">
                                <input type="text" name="categories" value="test">
                            </div>
                        </div>
                    </div>
                </div>              
            </div>
            <div class="modal-report collapse" id="crm-chatbot-imported-quick-replies-report">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <h3>
                            <?php echo md_the_user_icon(array('icon' => 'bar_chart_horizontal')); ?>
                            <?php echo $this->lang->line('crm_chatbot_quick_replies_import_report'); ?>
                        </h3>
                        <button type="button" class="crm-chatbot-hide-report" aria-label="Hide report">
                            <?php echo md_the_user_icon(array('icon' => 'arrow_up')); ?>                          
                        </button>
                    </div>
                </div>               
                <div class="row">
                    <div class="col-12">
                        <div class="list-group"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>