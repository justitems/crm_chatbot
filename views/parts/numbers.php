<div class="col-xl-10 col-lg-10 col-md-9 theme-tabs crm-chatbot-numbers">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs crm-tabs" id="crm-chatbot-list-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="crm-chatbot-list-tab" data-toggle="tab" href="#crm-chatbot-list" role="tab" aria-controls="crm-chatbot-list" aria-selected="true">
                        <?php echo md_the_user_icon(array('icon' => 'settings_phone')); ?>
                        <?php echo $this->lang->line('crm_chatbot_numbers'); ?>
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
                            <div class="card card-list">
                                <div class="card-header theme-search-box">
                                    <div class="row">
                                        <div class="col-md-9 col-12">
                                            <div class="theme-box-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <?php echo md_the_user_icon(array('icon' => 'search')); ?>
                                                        <input type="text" class="form-control crm-chatbot-search-for-numbers" id="crm-chatbot-search-for-numbers" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_numbers'); ?>" />
                                                        <?php echo md_the_user_icon(array('icon' => 'loader', 'class' => 'crm-search-icon-loader')); ?>
                                                        <a href="#" class="crm-cancel-search">
                                                            <?php echo md_the_user_icon(array('icon' => 'cancel')); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12 mb-0">
                                            <div class="btn-group theme-box-1 crm-chatbot-phone-numbers-group default-button-new" role="group" aria-label="Export Phone Numbers">
                                                <a href="#" class="btn btn-secondary theme-background-new theme-font-2 crm-chatbot-export-phone-numbers">
                                                    <?php echo md_the_user_icon(array('icon' => 'export')); ?>
                                                    <?php echo $this->lang->line('crm_chatbot_export'); ?>   
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                </div>
                                <div class="card-footer theme-box-1 theme-list-footer">
                                    <div class="row">
                                        <div class="col-md-5 col-12">
                                            <h6 class="theme-color-black"></h6>
                                        </div>
                                        <div class="col-md-7 col-12 text-right">
                                            <nav aria-label="numbers">
                                                <ul class="theme-pagination" data-type="numbers">
                                                </ul>
                                            </nav>
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