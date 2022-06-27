<div class="col-xl-10 col-lg-10 col-md-9 pb-3 crm-chatbot-activities">
    <div class="row">
        <div class="col-12 theme-tabs">
            <ul class="nav nav-tabs crm-tabs" id="crm-chatbot-list-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="crm-chatbot-list-tab" data-toggle="tab" href="#crm-chatbot-list" role="tab" aria-controls="crm-chatbot-list" aria-selected="true">
                        <?php echo md_the_user_icon(array('icon' => 'keyboard')); ?>
                        <?php echo $this->lang->line('crm_chatbot_activities'); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>   
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="crm-chatbot-list-tabs-data">
                <div class="tab-pane fade show active" id="crm-chatbot-list" role="tabpanel" aria-labelledby="crm-chatbot-list-tab">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-activities-list">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-primary theme-background-blue crm-activities-load-more-recent-records">
                                                <?php echo $this->lang->line('crm_chatbot_load_more_recent_records'); ?>
                                                <?php echo md_the_user_icon(array('icon' => 'bi_arrow_up')); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group default-activities-list"></ul>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="button" class="btn btn-primary theme-background-blue crm-activities-load-more-old-records">
                                                <?php echo $this->lang->line('crm_chatbot_load_more_old_records'); ?>
                                                <?php echo md_the_user_icon(array('icon' => 'bi_arrow_down')); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>