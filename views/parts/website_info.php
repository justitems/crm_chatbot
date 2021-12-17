<div class="col-xl-10 col-lg-9 col-md-8 pt-3 crm-chatbot-website-info">
    <div class="row">
        <div class="col-md-6 theme-box-border-right">
            <div class="card theme-box-info">
                <div class="card-body theme-box-1">
                    <div class="list-group">
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 theme-color-black">
                                    <?php echo $this->lang->line('crm_chatbot_status'); ?>
                                </h5>
                            </div>
                            <div class="d-flex w-100 theme-font-2">
                                <?php echo $status?'<p class="theme-color-green">' . $this->lang->line('crm_chatbot_enabled') . '</p>':'<p class="theme-color-red">' . $this->lang->line('crm_chatbot_disabled') . '</p>'; ?>
                                
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 theme-color-black">
                                    <?php echo $this->lang->line('crm_chatbot_author'); ?>
                                </h5>
                            </div>
                            <div class="d-flex w-100 theme-font-2">
                                <p class="theme-color-blue">
                                    <?php echo $author['first_name']?$author['first_name'] . ' ' . $author['last_name']:$author['member_username'];?>
                                </p>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1 theme-color-black">
                                    <?php echo $this->lang->line('crm_chatbot_created'); ?>
                                </h5>
                            </div>
                            <div class="d-flex w-100 theme-font-2">
                                <p>
                                    <?php echo $created; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 theme-tabs crm-chatbot-threads">
            <div class="row">
                <div class="col-12">
                    <ul class="nav nav-tabs crm-tabs" id="crm-chatbot-list-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="crm-chatbot-list-tab" data-toggle="tab" href="#crm-chatbot-list" role="tab" aria-controls="crm-chatbot-list" aria-selected="true">
                                <?php echo md_the_user_icon(array('icon' => 'forum')); ?>
                                <?php echo $this->lang->line('crm_chatbot_threads'); ?>
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
                                    <div class="card card-list mb-0">
                                        <div class="card-header theme-search-box">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="theme-box-1">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <?php echo md_the_user_icon(array('icon' => 'search')); ?>
                                                                <input type="text" class="form-control crm-chatbot-search-for-threads" id="crm-chatbot-search-for-threads" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_threads'); ?>" />
                                                                <?php echo md_the_user_icon(array('icon' => 'loader', 'class' => 'crm-search-icon-loader')); ?>
                                                                <a href="#" class="crm-cancel-search">
                                                                    <?php echo md_the_user_icon(array('icon' => 'cancel')); ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body theme-covers">
                                        </div>
                                        <div class="card-footer theme-box-1 theme-list-footer">
                                            <div class="row">
                                                <div class="col-md-5 col-12">
                                                    <h6 class="theme-color-black"></h6>
                                                </div>
                                                <div class="col-md-7 col-12 text-right">
                                                    <nav aria-label="threads">
                                                        <ul class="theme-pagination" data-type="threads">
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
    </div>
</div>