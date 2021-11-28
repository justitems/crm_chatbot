<!-- Modal -->
<div class="modal fade theme-modal" id="crm-chatbot-bot-categories-modal" tabindex="-1" role="dialog" aria-labelledby="crm-chatbot-bot-categories-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbot-new-category')) ?>
                <div class="modal-header">
                    <h5 class="modal-title theme-color-black">
                        <?php echo md_the_user_icon(array('icon' => 'drawer')); ?>
                        <?php echo $this->lang->line('crm_chatbot_categories'); ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <?php echo md_the_user_icon(array('icon' => 'bi_x')); ?>                         
                    </button>
                </div>
                <div class="modal-body">
                    <div class="theme-modal-quick-list">
                        <div class="row">
                            <div class="col-12">
                                <div class="theme-modal-input-area">
                                    <div class="row">
                                        <div class="<?php echo md_the_team_role_permission('crm_chatbot_create_categories')?'col-7 col-xs-9':'col-12'; ?>">
                                            <input type="text" class="theme-text-input-1 crm-chatbot-search-for-categories" placeholder="<?php echo $this->lang->line('crm_chatbot_search_for_categories'); ?>" />
                                        </div>
                                        <?php if ( md_the_team_role_permission('crm_chatbot_create_categories') ) { ?>
                                        <div class="col-5 col-xs-3">
                                            <button
                                                type="button"
                                                class="btn btn-secondary theme-background-new crm-chatbot-new-category-btn"
                                                data-toggle="collapse"
                                                data-target="#crm-chatbot-new-category-collapse"
                                                aria-expanded="false"
                                                aria-controls="crm-chatbot-new-category-collapse"
                                                >
                                                <?php echo md_the_user_icon(array('icon' => 'drawer')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_new_category'); ?>
                                            </button>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="theme-modal-quick-list-collapse collapse" id="crm-chatbot-new-category-collapse">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <input type="text" class="theme-text-input-1 crm-chatbot-category-name" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_category_name'); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-success theme-background-green theme-font-2 crm-chatbot-save-category">
                                                                <?php echo md_the_user_icon(array('icon' => 'import')); ?>
                                                                <?php echo $this->lang->line('crm_chatbot_save_category'); ?>
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
                        <div class="row">
                            <div class="col-12">
                                <ul class="crm-chatbot-bot-categories"></ul>
                            </div>
                        </div>  
                    </div>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>