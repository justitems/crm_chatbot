<section class="crm-chatbot-page" data-quick-guide="crmchatbot" data-thread="<?php echo $this->input->get('thread', TRUE); ?>">
    <div class="row theme-row-equal">
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/sidebar.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/thread.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/info.php'); ?>
    </div>
</section>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        invite: "<?php echo $this->lang->line('crm_chatbot_invite'); ?>",
        icon_attachment_line: '<?php echo md_the_user_icon(array('icon' => 'attachment_line')); ?>',
        icon_file_cloud: '<?php echo md_the_user_icon(array('icon' => 'file_cloud')); ?>',
        icon_delete: '<?php echo md_the_user_icon(array('icon' => 'delete')); ?>',
        icon_forbid: '<?php echo md_the_user_icon(array('icon' => 'forbid')); ?>',
    };

</script>