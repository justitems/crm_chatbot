<section class="crm-chatbot-page" data-quick-guide="crmchatbot" data-website="<?php echo $website_id; ?>">
    <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/website.php'); ?>
</section>

<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/website_categories.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/change_image.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/edit_website.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/new_trigger.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/trigger.php'); ?>
<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/confirmation.php'); ?>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        code_was_copied_successfully: "<?php echo $this->lang->line('crm_chatbot_code_was_copied_successfully'); ?>",
        please_enter_numeric_value: "<?php echo $this->lang->line('crm_chatbot_please_enter_numeric_value'); ?>",
        condition: "<?php echo $this->lang->line('crm_chatbot_condition'); ?>",
        actions: "<?php echo $this->lang->line('crm_chatbot_actions'); ?>",
        text_response: "<?php echo $this->lang->line('crm_chatbot_text_response'); ?>",
        bot_response: "<?php echo $this->lang->line('crm_chatbot_bot_response'); ?>",
        select_a_bot: "<?php echo $this->lang->line('crm_chatbot_select_a_bot'); ?>",
        search_for_bots: "<?php echo $this->lang->line('crm_chatbot_search_for_bots'); ?>",
        edit: "<?php echo $this->lang->line('crm_chatbot_edit'); ?>",
        delete: "<?php echo $this->lang->line('crm_chatbot_delete'); ?>",
        choose_event: "<?php echo $this->lang->line('crm_chatbot_choose_event'); ?>",
        icon_arrow_down: '<?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>',
        icon_arrow_down_custom: '<?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'mr-0 ml-3 theme-dropdown-arrow-icon theme-color-black')); ?>',
        icon_asterisk: '<?php echo md_the_user_icon(array('icon' => 'asterisk', 'class' => 'theme-color-red')); ?>',
        icon_fa_align_left: '<?php echo md_the_user_icon(array('icon' => 'fa_align_left')); ?>',
        icon_fa_align_center: '<?php echo md_the_user_icon(array('icon' => 'fa_align_center')); ?>',
        icon_fa_align_right: '<?php echo md_the_user_icon(array('icon' => 'fa_align_right')); ?>',
        icon_fa_align_justify: '<?php echo md_the_user_icon(array('icon' => 'fa_align_justify')); ?>',
        icon_fa_bold: '<?php echo md_the_user_icon(array('icon' => 'fa_bold')); ?>',
        icon_fa_italic: '<?php echo md_the_user_icon(array('icon' => 'fa_italic')); ?>',
        icon_fa_underline: '<?php echo md_the_user_icon(array('icon' => 'fa_underline')); ?>',
        icon_fa_link: '<?php echo md_the_user_icon(array('icon' => 'fa_link')); ?>',
        icon_fa_unlink: '<?php echo md_the_user_icon(array('icon' => 'fa_unlink')); ?>',
        icon_file_cloud: '<?php echo md_the_user_icon(array('icon' => 'file_cloud')); ?>',
        icon_smart_toy: '<?php echo md_the_user_icon(array('icon' => 'smart_toy')); ?>',
        icon_airplay: '<?php echo md_the_user_icon(array('icon' => 'airplay')); ?>',
        icon_device_hub: '<?php echo md_the_user_icon(array('icon' => 'device_hub')); ?>',
        icon_monitor_heart: '<?php echo md_the_user_icon(array('icon' => 'monitor_heart')); ?>',
        icon_delete: '<?php echo md_the_user_icon(array('icon' => 'delete')); ?>',
        icon_delete_black: '<?php echo md_the_user_icon(array('icon' => 'delete', 'class' => 'ml-0 theme-color-black')); ?>',
        icon_slideshow: '<?php echo md_the_user_icon(array('icon' => 'slideshow')); ?>',
        icon_more: '<?php echo md_the_user_icon(array('icon' => 'more')); ?>',
        icon_insights: '<?php echo md_the_user_icon(array('icon' => 'insights')); ?>',
        icon_edit_box: '<?php echo md_the_user_icon(array('icon' => 'edit_box')); ?>'
    };

</script>