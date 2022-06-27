<div class="col-xl-10 col-lg-10 col-md-9 theme-tabs crm-chatbot-threads">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="crm-chatbot-list-tabs" role="tablist">
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
                            <div class="card card-list">
                                <div class="card-header theme-search-box">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
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
                                        <div class="col-md-3 col-12">
                                            <div class="theme-box-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="dropdown w-100" id="crm-chatbot-websites-list-by-dropdown">
                                                            <a href="#" role="button" id="crm-chatbot-websites-list-by" class="btn btn-white dropdown-toggle w-100 d-flex justify-content-between align-items-center pl-3 pr-2 crm-chatbot-websites-list-btn" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                                <?php echo $this->lang->line('crm_chatbot_all_websites'); ?>
                                                                <?php echo md_the_user_icon(array('icon' => 'arrow_down')); ?>
                                                            </a>
                                                            <div class="dropdown-menu w-100" aria-labelledby="crm-chatbot-websites-list">
                                                                <input class="crm-chatbot-search-for-websites" type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_websites'); ?>" />
                                                                <div>
                                                                    <ul class="list-group crm-chatbot-websites-list">
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-12 mb-0">
                                            <div class="theme-box-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="dropdown w-100" id="crm-chatbot-status-list-by-dropdown">
                                                            <a href="#" role="button" id="crm-chatbot-status-list-by" class="btn btn-white dropdown-toggle w-100 d-flex justify-content-between align-items-center pl-3 pr-2 crm-chatbot-status-list-btn" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                                <?php echo $this->lang->line('crm_chatbot_all_threads'); ?>
                                                                <?php echo md_the_user_icon(array('icon' => 'arrow_down')); ?>
                                                            </a>
                                                            <div class="dropdown-menu w-100" aria-labelledby="crm-chatbot-status-list">
                                                                <div>
                                                                    <ul class="list-group crm-chatbot-status-list">
                                                                        <li class="list-group-item dropdown-item">
                                                                            <a href="#" class="dropdown-link" data-status="0">
                                                                                <?php echo $this->lang->line('crm_chatbot_inactive'); ?>
                                                                            </a>
                                                                        </li>                                                                        
                                                                        <li class="list-group-item dropdown-item">
                                                                            <a href="#" class="dropdown-link" data-status="1">
                                                                                <?php echo $this->lang->line('crm_chatbot_active'); ?>
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item dropdown-item">
                                                                            <a href="#" class="dropdown-link" data-status="2">
                                                                                <?php echo $this->lang->line('crm_chatbot_blocked'); ?>
                                                                            </a>
                                                                        </li>                                                                        
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>                                                        
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