<div class="col-xl-10 col-lg-10 col-md-9 theme-tabs crm-chatbot-quick-replies">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="crm-chatbot-list-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="crm-chatbot-list-tab" data-toggle="tab" href="#crm-chatbot-list" role="tab" aria-controls="crm-chatbot-list" aria-selected="true">
                        <?php echo md_the_user_icon(array('icon' => 'question_answer')); ?>
                        <?php echo $this->lang->line('crm_chatbot_quick_replies'); ?>
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
                                                        <input type="text" class="form-control crm-chatbot-search-for-quick-replies" id="crm-chatbot-search-for-quick-replies" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_quick_replies'); ?>" />
                                                        <?php echo md_the_user_icon(array('icon' => 'loader')); ?>
                                                        <a href="#" class="crm-cancel-search">
                                                            <?php echo md_the_user_icon(array('icon' => 'cancel')); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <div class="btn-group crm-chatbot-add-quick-reply default-button-new theme-box-1" role="group" aria-label="Button group with nested dropdown">
                                                <a href="<?php echo site_url('user/app/crm_chatbot?p=quick_replies&new=1'); ?>" class="btn btn-secondary btn-crm-chatbot-new-quick-reply theme-background-new theme-font-2">
                                                    <?php echo md_the_user_icon(array('icon' => 'add_comment')); ?>
                                                    <?php echo $this->lang->line('crm_chatbot_new_reply'); ?>
                                                </a>
                                                <div class="btn-group" role="group">
                                                    <button type="button" id="" class="btn btn-secondary dropdown-toggle theme-dropdown-icon-1 theme-background-new" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                        <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'float-none theme-dropdown-arrow-icon')); ?>
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="crm-chatbot-import-quick-replies" style="">
                                                        <a href="#" class="dropdown-item theme-color-black" data-toggle="modal" data-target="#crm-chatbot-import-quick-replies-modal">
                                                            <?php echo md_the_user_icon(array('icon' => 'import', 'class' => 'theme-color-black')); ?>
                                                            <?php echo $this->lang->line('crm_chatbot_import'); ?>
                                                        </a>
                                                        <a href="#" class="dropdown-item theme-color-black" data-toggle="modal" data-target="#crm-chatbot-export-quick-replies-modal">
                                                            <?php echo md_the_user_icon(array('icon' => 'export', 'class' => 'theme-color-black')); ?>
                                                            <?php echo $this->lang->line('crm_chatbot_export'); ?>
                                                        </a>
                                                    </div>
                                                </div>
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
                                            <nav aria-label="quick-replies">
                                                <ul class="theme-pagination" data-type="quick-replies">
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