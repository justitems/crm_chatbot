<div class="col-xl-7 col-lg-6 col-md-5 crm-chatbot-thread-chat">
    <div class="row">
        <div class="col-12 crm-chat">
            <div class="card card-message-content">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h1 class="crm-message-subject theme-color-black theme-font-1">
                                #<?php echo $this->input->get('thread', TRUE); ?>
                            </h1>
                        </div>
                        <div class="col-4 text-right">
                            
                        </div>
                    </div>
                </div>
                <div class="card-body crm-messages-model-1">
                    <div class="row">
                        <div class="col-12">
                            <div class="crm-chatbot-messages-list"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <ul class="list-group crm-message-replies"></ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer crm-message-composer-model-1">
                    <div class="row">
                        <div class="col-12">
                            <?php echo form_open('user/app/crm_chatbot', array('class' => 'crm-chatbot-new-message d-flex justify-content-between align-items-center', 'autocomplete' => 'off')) ?>
                                <textarea class="crm-message-reply" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_your_message'); ?>"></textarea>
                                <button type="submit" class="btn btn-link crm-message-reply-send-btn theme-background-blue">
                                    <?php echo md_the_user_icon(array('icon' => 'send')); ?>
                                </button>
                                <?php echo form_close(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button
                                type="button"
                                class="btn btn-link crm-message-reply-attach-btn theme-color-grey"
                                data-toggle="collapse"
                                data-target="#crm-message-reply-attachments"
                                aria-expanded="true"
                                aria-controls="crm-message-reply-attachments">
                                <?php echo md_the_user_icon(array('icon' => 'attachment')); ?>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="collapse" id="crm-message-reply-attachments">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="crm-drag-and-drop-files" data-upload-form-url="<?php echo site_url('user/app-ajax/crm_chatbot'); ?>" data-upload-form-action="crm_chatbot_upload_message_files" data-supported-extensions=".png,.jpeg,.gif,.mp4,.avi,.pdf,.doc,.docx,.xls,.xlsx"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="collapse" id="crm-reply-attached-files-collapse">
                                            <div class="crm-reply-attached-files">
                                                <ul class="list-group"></ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="collapse" id="crm-reply-error-upload-files-collapse">
                                            <div class="crm-reply-error-upload-files">
                                                <ul class="list-group"></ul>
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