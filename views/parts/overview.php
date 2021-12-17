<div class="col-xl-10 col-lg-10 col-md-9 crm-chatbot-overview">
    <div class="row">
        <div class="col-12 theme-tabs">
            <ul class="nav nav-tabs" id="crm-chatbot-list-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="crm-chatbot-list-tab" data-toggle="tab" href="#crm-chatbot-list" role="tab" aria-controls="crm-chatbot-list" aria-selected="true">
                        <?php echo md_the_user_icon(array('icon' => 'dashboard_line')); ?>
                        <?php echo $this->lang->line('crm_chatbot_overview'); ?>
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
                            <div class="card card-overview">
                                <div class="card-header mb-3 border-0 theme-box-1">
                                    <div class="row">
                                        <div class="col-xl-9">
                                            <div class="d-flex pl-3 w-100">
                                                <div class="theme-time-picker" data-id="default-time-from" data-date-format="<?php echo the_crm_date_format($this->user_id); ?>" data-time="0" data-day="<?php echo date('d', strtotime('-30 days')); ?>" data-month="<?php echo date('m', strtotime('-30 days')); ?>" data-year="<?php echo date('Y', strtotime('-30 days')); ?>" data-first-day="1"></div>
                                                <div class="theme-time-picker-separator">
                                                    <?php echo md_the_user_icon(array('icon' => 'git_commit_line')); ?>
                                                </div>
                                                <div class="theme-time-picker" data-id="default-time-to" data-date-format="<?php echo the_crm_date_format($this->user_id); ?>" data-time="0" data-day="<?php echo date('d'); ?>" data-month="<?php echo date('m'); ?>" data-year="<?php echo date('Y'); ?>" data-first-day="1"></div>                                                     
                                            </div>
                                        </div>
                                        <div class="col-xl-3 pr-3 text-right">
                                            <div class="dropdown theme-dropdown-2 theme-dropdown-icon-1" id="crm-chatbot-websites-list-by-dropdown">
                                                <a href="#" role="button" id="crm-chatbot-websites-list-by" class="btn btn-secondary dropdown-toggle d-flex justify-content-between align-items-center pl-3 pr-2 crm-chatbot-websites-list-btn" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown">
                                                    <?php echo $this->lang->line('crm_chatbot_all_websites'); ?>
                                                    <?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="crm-chatbot-websites-list">
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8 theme-box-border-right">
                                            <div class="card card-guests-stats mb-3 theme-card-box-1 theme-box-1">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-link">
                                                                <?php echo md_the_user_icon(array('icon' => 'group_add')); ?>
                                                                <?php echo $this->lang->line('crm_chatbot_guests'); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div id="crm-chatbot-guests-chart"></div>       
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="card card-total-stats mb-3 theme-card-box-1 theme-box-1">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-link">
                                                                <?php echo md_the_user_icon(array('icon' => 'query_stats')); ?>
                                                                <?php echo $this->lang->line('crm_chatbot_stats'); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div id="crm-chatbot-total-chart"></div>       
                                                </div>
                                            </div>
                                        </div>                                                                               
                                    </div> 
                                    <div class="row mt-3">
                                        <div class="col-md-6 theme-box-border-right">
                                            <div class="card card-actions-stats mb-3 theme-card-box-1 theme-box-1">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-link">
                                                                <?php echo md_the_user_icon(array('icon' => 'queue_play_next')); ?>
                                                                <?php echo $this->lang->line('crm_chatbot_actions'); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ul class="theme-card-box-list">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <nav aria-label="actions">
                                                        <ul class="theme-pagination" data-type="actions">
                                                        </ul>
                                                    </nav>
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="col-md-6">
                                            <div class="card card-agents-stats mb-3 theme-card-box-1 theme-box-1">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-link">
                                                                <?php echo md_the_user_icon(array('icon' => 'support_agent')); ?>
                                                                <?php echo $this->lang->line('crm_chatbot_agents'); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <ul class="theme-card-box-list">
                                                                <li class="crm-card-box-no-items-found">
                                                                    <?php echo $this->lang->line('crm_chatbot_no_agents_found'); ?>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <nav aria-label="agents">
                                                        <ul class="theme-pagination" data-type="agents">
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