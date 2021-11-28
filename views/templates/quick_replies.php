<section class="crm-chatbot-page" data-quick-guide="crmchatbot">
    <div class="row theme-row-equal">
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/sidebar.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/quick_replies.php'); ?>
    </div>
</section>

<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/import_replies.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/export_replies.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/confirmation.php'); ?>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        delete: "<?php echo $this->lang->line('crm_chatbot_delete'); ?>",
        keywords_divided_by_white_space: "<?php echo $this->lang->line('crm_chatbot_keywords_divided_by_white_space'); ?>",
        text_response_bot_id: "<?php echo $this->lang->line('crm_chatbot_text_response_bot_id'); ?>",
        accuracy_percentage_as_number: "<?php echo $this->lang->line('crm_chatbot_accuracy_percentage_as_number'); ?>",
        icon_more: '<?php echo md_the_user_icon(array('icon' => 'more')); ?>',
        icon_delete: '<?php echo md_the_user_icon(array('icon' => 'delete', 'class' => 'ml-0 theme-color-black')); ?>',
        icon_reply_all: '<?php echo md_the_user_icon(array('icon' => 'reply_all', 'class' => 'theme-color-black')); ?>',
        icon_information: '<?php echo md_the_user_icon(array('icon' => 'information', 'class' => 'theme-color-grey')); ?>',
        icon_repeat_grey: '<?php echo md_the_user_icon(array('icon' => 'repeat', 'class' => 'theme-color-grey')); ?>',
        icon_repeat_green: '<?php echo md_the_user_icon(array('icon' => 'repeat', 'class' => 'theme-color-green')); ?>',
        icon_slideshow: '<?php echo md_the_user_icon(array('icon' => 'slideshow')); ?>'
    };

</script>