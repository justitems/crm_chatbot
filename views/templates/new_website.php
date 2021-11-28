<section class="crm-chatbot-page" data-quick-guide="crmchatbot">
    <?php md_get_the_file(CMS_BASE_USER_APPS_CRM_CHATBOT . 'views/parts/new_website.php'); ?>
</section>

<?php if ( md_the_option('app_crm_chatbot_quick_guide') ) { ?>
    <?php md_get_the_file(CMS_BASE_USER . 'default/php/quick_guide.php'); ?>
<?php } ?>