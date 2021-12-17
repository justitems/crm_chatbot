<div class="col-xl-3 col-lg-4 col-md-4 crm-chatbot-thread-info">
    <div class="row">
        <div class="col-12 theme-tabs">
            <ul class="nav nav-tabs" id="crm-chatbot-thread-info-nav" role="tablist">
                <li class="nav-item">
                    <a href="#crm-chatbot-thread-info-guest" role="tab" id="crm-chatbot-thread-info-guest-tab" class="nav-link active" aria-controls="crm-chatbot-thread-info-guest" aria-selected="true" data-toggle="tab">
                        <?php echo md_the_user_icon(array('icon' => 'user_line')); ?>
                        <?php echo $this->lang->line('crm_chatbot_guest'); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#crm-chatbot-thread-info-actions" role="tab" id="crm-chatbot-thread-info-actions-tab" class="nav-link" aria-controls="crm-chatbot-thread-info-actions" aria-selected="false" data-toggle="tab">
                        <?php echo md_the_user_icon(array('icon' => 'tools')); ?>
                        <?php echo $this->lang->line('crm_chatbot_actions'); ?>
                    </a>
                </li>                
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="crm-chatbot-thread-info-tabs">
                <div class="tab-pane fade show active pt-3" id="crm-chatbot-thread-info-guest" role="tabpanel" aria-labelledby="crm-chatbot-thread-info-guest-tab">
                    <div class="card mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'information')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_general'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_website'); ?>
                                            </p>
                                            <span>
                                                <a href="https://<?php echo $thread['domain']; ?>" target="_blank">
                                                    <?php echo $thread['domain']; ?>
                                                </a>
                                            </span>
                                        </li>                                        
                                        <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_timezone') ) { ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_guest_time'); ?>
                                            </p>
                                            <span>
                                                <?php

                                                // Get date
                                                $date = new DateTime("now", new DateTimeZone(the_crm_chatbot_websites_guests_meta($guest_id, 'guest_timezone')) );

                                                // Display time
                                                echo the_crm_calculate_time($this->user_id, $date->getTimestamp());
                                                
                                                ?>
                                            </span>
                                        </li>
                                        <?php } ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_guest_ip'); ?>
                                            </p>
                                            <span>
                                                <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_ip'); ?>
                                            </span>
                                        </li> 
                                        <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_city_name') ) { ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_location'); ?>
                                            </p>
                                            <span>
                                                <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_city_name'); ?> / <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_country_name'); ?>
                                            </span>
                                        </li>    
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_name') || the_crm_chatbot_websites_guests_meta($guest_id, 'guest_email') || the_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone') ) { ?>
                    <div class="card mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'user_shared_line')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_sent_data'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">
                                        <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_name') ) { ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_full_name'); ?>
                                            </p>
                                            <span>
                                                <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_name'); ?>
                                            </span>
                                        </li>
                                        <?php } ?>
                                        <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_email') ) { ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_email'); ?>
                                            </p>
                                            <span>
                                                <a href="mailto:<?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_email'); ?>" target="_blank">
                                                    <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_email'); ?>
                                                </a>
                                            </span>
                                        </li>  
                                        <?php } ?>
                                        <?php if ( the_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone') ) { ?>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_phone'); ?>
                                            </p>
                                            <span>
                                                <a href="tel:<?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone'); ?>" target="_blank">
                                                    <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_phone'); ?>
                                                </a>
                                            </span>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if ( md_the_option('app_crm_chatbot_google_map_enabled') && md_the_option('app_crm_chatbot_google_map_api_key') && the_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude') && the_crm_chatbot_websites_guests_meta($guest_id, 'guest_longitude') ) { ?>
                    <div class="card mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'map')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_location_on_map'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div id="crm-chatbot-thread-info-guest-map"></div>
                                    <script
                                    src="https://maps.googleapis.com/maps/api/js?key=<?php echo md_the_option('app_crm_chatbot_google_map_api_key'); ?>&callback=initMap&libraries=&v=weekly&channel=2"
                                    async
                                    ></script>
                                    <script>
                                    let map;

                                    function initMap() {
                                        map = new google.maps.Map(document.getElementById("crm-chatbot-thread-info-guest-map"), {
                                            center: { lat: <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_latitude'); ?>, lng: <?php echo the_crm_chatbot_websites_guests_meta($guest_id, 'guest_longitude'); ?> },
                                            zoom: 8
                                        });
                                    }
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="card card-seen-pages mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'window')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_seen_pages'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">                                                                                                                                                                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <nav aria-label="urls">
                                <ul class="theme-pagination" data-type="urls">
                                </ul>
                            </nav>
                        </div>
                    </div>                                        
                </div>
                <div class="tab-pane fade pt-3" id="crm-chatbot-thread-info-actions" role="tabpanel" aria-labelledby="crm-chatbot-thread-info-actions-tab">
                    <div class="card card-quick-replies mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'question_answer')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_quick_replies'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_bot_pause'); ?>
                                            </p>
                                            <div class="theme-checkbox-input-2">
                                                <input name="crm-chatbot-bot-pause" type="checkbox" id="crm-chatbot-bot-pause" class="crm-chatbot-bot-pause"<?php echo !empty($thread['auto'])?' checked':''; ?> />
                                                <label for="crm-chatbot-bot-pause"></label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="card card-preferences mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'folder_special')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_preferences'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_favorite'); ?>
                                            </p>
                                            <div class="theme-checkbox-input-2">
                                                <input name="crm-chatbot-favorite" type="checkbox" id="crm-chatbot-favorite" class="crm-chatbot-favorite"<?php echo !empty($thread['favorite'])?' checked':''; ?> />
                                                <label for="crm-chatbot-favorite"></label>
                                            </div>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_important'); ?>
                                            </p>
                                            <div class="theme-checkbox-input-2">
                                                <input name="crm-chatbot-important" type="checkbox" id="crm-chatbot-important" class="crm-chatbot-important"<?php echo !empty($thread['important'])?' checked':''; ?> />
                                                <label for="crm-chatbot-important"></label>
                                            </div>
                                        </li>                                        
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="card card-team-members mb-3 theme-box-1 theme-card-box-1 theme-card-list-with-buttons">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'group_line')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_team_members'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="theme-short-description">
                                        <p>
                                            <?php echo $this->lang->line('crm_chatbot_team_members_description'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>                              
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list"></ul>
                                </div>
                            </div>                            
                        </div>
                        <div class="card-footer text-right" style="">
                            <nav aria-label="members">
                                <ul class="theme-pagination" data-type="members">
                                </ul>
                            </nav>
                        </div>
                    </div>                   
                    <div class="card card-moderate mb-3 theme-box-1 theme-card-box-1">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-link">
                                        <?php echo md_the_user_icon(array('icon' => 'settings')); ?>
                                        <?php echo $this->lang->line('crm_chatbot_moderate'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <ul class="theme-card-box-list">
                                        <li class="d-flex justify-content-between align-items-center">
                                            <p>
                                                <?php echo $this->lang->line('crm_chatbot_block_thread'); ?>
                                            </p>
                                            <div class="theme-checkbox-input-2">
                                                <input name="crm-chatbot-block-thread" type="checkbox" id="crm-chatbot-block-thread" class="crm-chatbot-block-thread"<?php echo ($thread['status'] > 1)?' checked':''; ?> />
                                                <label for="crm-chatbot-block-thread"></label>
                                            </div>
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