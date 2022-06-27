<div class="col-xl-2 col-lg-3 col-md-4 theme-sidebar-1 crm-chatbot-menu">
    <div class="row">
        <div class="col-12">
            <h2>
                <a href="<?php echo site_url('user/app/crm_chatbot'); ?>" class="theme-border-black theme-color-black">
                    <?php echo $this->lang->line('crm_chatbot_main'); ?>
                </a>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4>
                <?php echo md_the_user_icon(array('icon' => 'inbox_archive_line')); ?>
                <?php echo $this->lang->line('crm_chatbot_lists'); ?>
            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <ul class="list-group crm-chatbot-lists-list">
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo !$this->input->get('p', TRUE)?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'forum')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>">
                            <?php echo $this->lang->line('crm_chatbot_threads'); ?>
                        </a>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'favorites')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'heart')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=favorites">
                            <?php echo $this->lang->line('crm_chatbot_favorites'); ?>
                        </a>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'important')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'bookmark_add')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=important">
                            <?php echo $this->lang->line('crm_chatbot_important'); ?>
                        </a>
                    </div>
                    <?php echo !empty($important)?'<span class="theme-sidebar-1-label crm-chatbot-important-threads-notification">' . $important . ' ' . strtolower($this->lang->line('crm_chatbot_threads')) . '</span>':''; ?>
                </li> 
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'numbers')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'settings_phone')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=numbers">
                            <?php echo $this->lang->line('crm_chatbot_numbers'); ?>
                        </a>
                    </div>
                </li> 
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'emails')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'alternate_email')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=emails">
                            <?php echo $this->lang->line('crm_chatbot_addresses'); ?>
                        </a>
                    </div>
                </li> 
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'guests')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'group_add')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=guests">
                            <?php echo $this->lang->line('crm_chatbot_guests'); ?>
                        </a>
                    </div>
                </li>                                                                            
            </ul>
        </div>
    </div>
    <?php

    // Verify if access is allowed
    if ( md_the_team_role_permission('crm_chatbot_configuration') ) {

    ?>    
    <div class="row">
        <div class="col-12">
            <h4>
                <?php echo md_the_user_icon(array('icon' => 'settings_line')); ?>
                <?php echo $this->lang->line('crm_chatbot_configuration'); ?>
            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <ul class="list-group crm-chatbot-configuration-list">
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'websites')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'window')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=websites">
                            <?php echo $this->lang->line('crm_chatbot_websites'); ?>
                        </a>
                    </div>
                </li> 
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'bots')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'node_tree')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=bots">
                            <?php echo $this->lang->line('crm_chatbot_bots'); ?>
                        </a>
                    </div>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'quick_replies')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'question_answer')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=quick_replies">
                            <?php echo $this->lang->line('crm_chatbot_quick_replies'); ?>
                        </a>
                    </div>
                </li>                                                                                       
            </ul>
        </div>
    </div>  
    <?php

    }

    // Verify if member exists
    if ( !$this->session->userdata( 'member' ) ) {

    ?>
    <div class="row">
        <div class="col-12">
            <h4>
                <?php echo md_the_user_icon(array('icon' => 'pie_chart_box_line')); ?>
                <?php echo $this->lang->line('crm_chatbot_reports'); ?>
            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <ul class="list-group crm-chatbot-reports-list">
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'overview')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'dashboard_line')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=overview">
                            <?php echo $this->lang->line('crm_chatbot_overview'); ?>
                        </a>
                    </div>
                </li> 
                <li class="list-group-item d-flex justify-content-between lh-condensed mb-3<?php echo ($this->input->get('p', TRUE) === 'activities')?' theme-sidebar-1-selected-item':''; ?>">
                    <div>
                        <?php echo md_the_user_icon(array('icon' => 'keyboard')); ?>
                        <a href="<?php echo site_url('user/app/crm_chatbot'); ?>?p=activities">
                            <?php echo $this->lang->line('crm_chatbot_activities'); ?>
                        </a>
                    </div>
                </li>                                                                          
            </ul>
        </div>
    </div> 
    <?php

    }

    ?>
</div>