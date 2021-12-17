<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=2" />

        <!-- Title -->
        <title><?php echo $website['domain']; ?></title>

        <!-- Set website url -->
        <meta name="url" content="<?php echo site_url(); ?>">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css">

        <!-- CRM CHATBOT CHAT STYLE CSS -->
        <link rel="stylesheet" href="<?php echo $chat_css_url; ?>" media="all" />

        <!-- Remix Icons -->
        <link href="//cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

        <!-- Google Icons -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">

    </head>

    <body>
        <main role="main" class="main" data-csrf="<?php echo $this->security->get_csrf_token_name(); ?>" data-csrf-value="<?php echo $this->security->get_csrf_hash(); ?>">
            <section class="crm-chatbot-page" data-website="<?php echo $website_id; ?>">
                <div class="card card-chat">
                    <div class="card-header">
                        <div class="media<?php echo !empty($agent)?!empty($agent['agent_is_gone'])?' crm-chatbot-user-is-gone':'':''; ?>">
                            <?php echo !empty($agent)?'<img class="mr-3" src="' . $agent['agent_photo'] . '" alt="Human Agent" />':'<img class="mr-3" src="' . $bot['bot_photo'] . '" alt="Bot Agent" />'; ?>
                            <div class="media-body">
                                <h5>
                                    <?php echo !empty($agent)?$agent['agent_name']:$bot['bot_name']; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body theme-scrollbar-1<?php echo the_crm_chatbot_websites_meta($website_id, 'disable_chat_input')?' card-body-no-input':''; ?>">
                        <ul class="crm-chatbot-messages-list">
                        </ul>
                    </div>
                    <?php if ( !the_crm_chatbot_websites_meta($website_id, 'disable_chat_input') ) { ?>
                    <div class="card-footer text-muted">
                        <?php echo form_open('guest/crm_chatbot?get_chat=' . $website_id, array('class' => 'crm-chatbot-new-message')); ?>
                            <div class="input-group">
                                <input type="text" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_message'); ?>" class="form-control crm-chatbot-message-input" />
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
                                        <?php echo md_the_user_icon(array('icon' => 'send')); ?>
                                    </button>
                                </div>
                                <?php if ( the_crm_chatbot_websites_meta($website_id, 'enable_attachments') ) { ?>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-light crm-chatbot-message-attach-file">
                                        <?php echo md_the_user_icon(array('icon' => 'attachment')); ?>
                                    </button>
                                </div>
                                <?php } ?>                            
                            </div>
                        <?php echo form_close(); ?>
                    </div>
                    <?php } ?> 
                </div>
                <?php if ( the_crm_chatbot_websites_meta($website_id, 'gdrp') && empty($accept_gdrp) ) { ?>
                <div class="crm-chatbot-chat-gdrp">
                    <div class="row">
                        <div class="col-12">
                            <h6>
                                <?php echo the_crm_chatbot_websites_meta($website_id, 'gdrp_title')?the_crm_chatbot_websites_meta($website_id, 'gdrp_title'):$this->lang->line('crm_chatbot_gdrp_policy'); ?>
                            </h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <?php echo the_crm_chatbot_websites_meta($website_id, 'gdrp_content')?str_replace(array('<div', '</div'), array('<p', '</p'), the_crm_chatbot_websites_meta($website_id, 'gdrp_content')):''; ?>                                                      
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-light crm-chatbot-chat-gdrp-remove-btn">
                                <?php echo the_crm_chatbot_websites_meta($website_id, 'gdrp_accept_button')?the_crm_chatbot_websites_meta($website_id, 'gdrp_accept_button'):$this->lang->line('crm_chatbot_i_accept'); ?>
                            </button> 
                            <button type="button" class="btn btn-light crm-chatbot-chat-gdrp-hide-btn">
                                <?php echo the_crm_chatbot_websites_meta($website_id, 'gdrp_reject_button')?the_crm_chatbot_websites_meta($website_id, 'gdrp_reject_button'):$this->lang->line('crm_chatbot_not_now'); ?>
                            </button>                                                                                   
                        </div>
                    </div>                                    
                </div>
                <?php } ?> 
                <?php if ( (the_crm_chatbot_websites_meta($website_id, 'guest_name') || the_crm_chatbot_websites_meta($website_id, 'guest_email') || the_crm_chatbot_websites_meta($website_id, 'guest_phone')) && !$guest_data ) { ?>
                <div class="crm-chatbot-chat-guest-data">
                    <div class="row">
                        <div class="col-12">
                            <?php echo form_open('guest/crm_chatbot?get_chat=' . $website_id, array('class' => 'crm-chatbot-save-guest-data')); ?>
                                <?php if ( the_crm_chatbot_websites_meta($website_id, 'guest_name') ) { ?>
                                <div class="form-group">
                                    <label for="crm-chatbot-chat-your-name">
                                        <?php echo $this->lang->line('crm_chatbot_your_name'); ?>:
                                    </label>
                                    <input type="text" name="crm-chatbot-chat-your-name-<?php echo uniqid(); ?>" class="form-control crm-chatbot-chat-your-name-<?php echo uniqid(); ?>" id="crm-chatbot-chat-your-name" aria-describedby="name" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_your_name'); ?>" required />
                                </div>
                                <?php } ?>
                                <?php if ( the_crm_chatbot_websites_meta($website_id, 'guest_email') ) { ?>
                                <div class="form-group">
                                    <label for="crm-chatbot-chat-your-email">
                                        <?php echo $this->lang->line('crm_chatbot_email'); ?>:
                                    </label>
                                    <input type="email" name="crm-chatbot-chat-your-email-<?php echo uniqid(); ?>" class="form-control crm-chatbot-chat-your-email-<?php echo uniqid(); ?>" id="crm-chatbot-chat-your-email" aria-describedby="email" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_email'); ?>" required />
                                </div>  
                                <?php } ?>
                                <?php if ( the_crm_chatbot_websites_meta($website_id, 'guest_phone') ) { ?>
                                <div class="form-group">
                                    <label for="crm-chatbot-chat-your-phone">
                                        <?php echo $this->lang->line('crm_chatbot_phone'); ?>:
                                    </label>
                                    <input type="text" name="crm-chatbot-chat-your-phone-<?php echo uniqid(); ?>" class="form-control crm-chatbot-chat-your-phone-<?php echo uniqid(); ?>" id="crm-chatbot-chat-your-phone" aria-describedby="phone" placeholder="<?php echo $this->lang->line('crm_chatbot_enter_phone'); ?>" required />
                                </div> 
                                <?php } ?>
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $this->lang->line('crm_chatbot_start_chat'); ?>
                                </button>
                            <?php echo form_close(); ?>                                                                    
                        </div>
                    </div>                                    
                </div>
                <?php } ?>
                <?php if ( the_crm_chatbot_websites_meta($website_id, 'enable_attachments') ) { ?>
                    <div class="d-none">
                        <?php echo form_open('guest/crm_chatbot?upload_file=' . $website_id, array('class' => 'crm-chatbot-upload-file')); ?>
                            <input type="file" name="file[]" class="crm-upload-files-input" accept=".png,.jpeg,.gif,.mp4,.avi,.pdf,.doc,.docx,.xls,.xlsx">
                        <?php echo form_close(); ?>  
                    </div>
                <?php } ?>    
            </section>
        </main>

        <div class="theme-send-loading theme-background-green"></div>

        <!-- jQuery Link -->
        <script src="//code.jquery.com/jquery-3.5.1.js"></script>

        <!-- Main JS -->
        <script src="<?php echo base_url('assets/js/main.js?ver=' . MD_VER); ?>"></script>

        <!-- Popper Link -->
        <script src="//cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

        <!-- Bootstrap Link -->
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.1/js/bootstrap.min.js"></script>

        <!-- Chatbot Link -->
        <script src="<?php echo base_url('assets/base/user/apps/collection/crm-chatbot/js/chatbot.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION); ?>"></script>

        <!-- Bot Elements Link -->
        <script src="<?php echo base_url('assets/base/user/apps/collection/crm-chatbot/js/bot-elements.js?ver=' . CMS_BASE_USER_APPS_CRM_CHATBOT_VERSION); ?>"></script> 
        
        <!-- Theme JS -->
        <script src="<?php echo base_url('assets/base/user/themes/collection/crm/js/main.js?ver=' . MD_VER); ?>"></script>

        <script language="javascript">

        // CRM Chatbot words list
        let words = {
            me: "<?php echo $this->lang->line('crm_chatbot_me'); ?>",
            icon_attachment_line: '<?php echo md_the_user_icon(array('icon' => 'attachment_line')); ?>',
            icon_notification: '<?php echo md_the_user_icon(array('icon' => 'notification')); ?>'
        };

        </script>

    </body>
</html>