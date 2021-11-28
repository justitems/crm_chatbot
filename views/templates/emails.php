<section class="crm-chatbot-page" data-quick-guide="crmchatbot">
    <div class="row theme-row-equal">
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/sidebar.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/emails.php'); ?>
    </div>
</section>

<?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/modals/confirmation.php'); ?>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        delete: "<?php echo $this->lang->line('crm_chatbot_delete'); ?>",
        icon_more: '<?php echo md_the_user_icon(array('icon' => 'more')); ?>',
        icon_mail_check: '<?php echo md_the_user_icon(array('icon' => 'mail_check', 'class' => 'theme-color-black')); ?>',
        icon_delete: '<?php echo md_the_user_icon(array('icon' => 'delete', 'class' => 'ml-0 theme-color-black')); ?>',
        icon_arrow_down: '<?php echo md_the_user_icon(array('icon' => 'arrow_down')); ?>',
        icon_heart: '<?php echo md_the_user_icon(array('icon' => 'heart')); ?>',
        icon_heart_red: '<?php echo md_the_user_icon(array('icon' => 'heart', 'class' => 'theme-color-red')); ?>'
    };

</script>