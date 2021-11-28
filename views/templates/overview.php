<section class="crm-chatbot-page" data-quick-guide="crmchatbot">
    <div class="row theme-row-equal">
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/sidebar.php'); ?>
        <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/overview.php'); ?>
    </div>
</section>

<?php md_get_the_file(CMS_BASE_USER . 'default/php/modals/time_picker.php'); ?>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>

<script language="javascript">

    // CRM Chatbot words list
    let words = {
        icon_reply: '<?php echo md_the_user_icon(array('icon' => 'reply')); ?>',
        icon_arrow_down: '<?php echo md_the_user_icon(array('icon' => 'arrow_down')); ?>',
        icon_keyboard_tab: '<?php echo md_the_user_icon(array('icon' => 'keyboard_tab')); ?>'
    };

</script>