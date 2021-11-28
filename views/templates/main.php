<section class="crm-chatbot-page" data-quick-guide="crmchatbot">
    <div class="row theme-row-equal">
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/sidebar.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/threads.php'); ?>
    </div>
</section>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        status: "<?php echo $this->lang->line('crm_chatbot_status'); ?>",
        active: "<?php echo $this->lang->line('crm_chatbot_active'); ?>",
        inactive: "<?php echo $this->lang->line('crm_chatbot_inactive'); ?>",
        blocked: "<?php echo $this->lang->line('crm_chatbot_blocked'); ?>",
        icon_arrow_down: '<?php echo md_the_user_icon(array('icon' => 'arrow_down')); ?>',
        icon_heart: '<?php echo md_the_user_icon(array('icon' => 'heart')); ?>',
        icon_heart_red: '<?php echo md_the_user_icon(array('icon' => 'heart', 'class' => 'theme-color-red')); ?>',
        icon_repeat_grey: '<?php echo md_the_user_icon(array('icon' => 'repeat', 'class' => 'theme-color-grey')); ?>',
        icon_repeat_green: '<?php echo md_the_user_icon(array('icon' => 'repeat', 'class' => 'theme-color-green')); ?>',
        icon_send_plane: '<?php echo md_the_user_icon(array('icon' => 'send_plane')); ?>'
    };

</script>