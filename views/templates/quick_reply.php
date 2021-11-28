<section class="crm-chatbot-page" data-quick-guide="crmchatbot" data-reply="<?php echo $reply_id; ?>">
    <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/quick_reply.php'); ?>
</section>

<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/categories.php'); ?>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        please_enter_at_least_keyword: "<?php echo $this->lang->line('crm_chatbot_please_enter_at_least_keyword'); ?>",
        please_enter_a_response: "<?php echo $this->lang->line('crm_chatbot_please_enter_a_response'); ?>",
        please_select_bot: "<?php echo $this->lang->line('crm_chatbot_please_select_bot'); ?>",
        text_response: "<?php echo $this->lang->line('crm_chatbot_text_response'); ?>",
        select_a_bot: "<?php echo $this->lang->line('crm_chatbot_select_a_bot'); ?>",
        icon_slideshow: '<?php echo md_the_user_icon(array('icon' => 'slideshow')); ?>',
        icon_delete: '<?php echo md_the_user_icon(array('icon' => 'delete')); ?>',
        icon_arrow_down: '<?php echo md_the_user_icon(array('icon' => 'arrow_down', 'class' => 'theme-dropdown-arrow-icon')); ?>'
    };

</script>