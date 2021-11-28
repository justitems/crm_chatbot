<div class="row">
    <div class="col-12 crm-chatbot-website-builder">
        <div class="row">
            <div class="col-12 theme-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="<?php echo site_url('user/app/crm_chatbot?p=websites'); ?>">
                                <?php echo $this->lang->line('crm_chatbot_websites'); ?>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo $this->lang->line('crm_chatbot_new_website'); ?>
                        </li>                        
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbot-new-website')) ?>
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <div class="card theme-card-box-1 mt-3 theme-box-1">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-link">
                                                <?php echo md_the_user_icon(array('icon' => 'add_link')); ?>
                                                <?php echo $this->lang->line('crm_chatbot_new_website'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <input type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_website_url'); ?>" class="theme-text-input-1 crm-chatbot-website-url">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12"> 
                                            <div class="crm-chatbot-website-actions">
                                                <button type="submit" class="btn btn-primary theme-button-1 theme-background-green">
                                                    <?php echo md_the_user_icon(array('icon' => 'import')); ?>         
                                                    <?php echo $this->lang->line('crm_chatbot_save_website'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>                
                        </div>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>                
    </div>
</div>