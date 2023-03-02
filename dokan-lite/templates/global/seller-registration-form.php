<?php

/**
 * Dokan Seller registration form
 *
 * @since 2.4
 */
?>

<div class="show_if_hospital"   style="<?php echo esc_attr($role_style); ?>">

    <div class="split-row form-row-wide">
        <p class="form-row form-group">
            <label for="hospital-name">
                <?php esc_html_e('Hospital name', 'dokan-lite'); ?> <span class="required">*</span>
            </label>
            <input type="text" class="input-text form-control" name="hospital-name" id="hospital-name" disabled value="<?php if (!empty($postdata['hname'])) {
                                                                                                            echo esc_attr($postdata['hname']);
                                                                                                        } ?>" required="required" />
        </p>
    </div>

    <p class="form-row form-group form-row-wide">
        <label for="person-incharge">
            <?php esc_html_e('Person in charge', 'dokan-lite'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="person-incharge" id="person-incharge" disabled value="<?php if (!empty($postdata['personincharge'])) {
                                                                                                                    echo esc_attr($postdata['personincharge']);
                                                                                                                } ?>" required="required" />
    </p>

    <p class="form-row form-group form-row-wide">
        <label for="hospital-phone">
            <?php esc_html_e('Phone', 'dokan-lite'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="hospital-phone" id="hospital-phone" disabled value="<?php if (!empty($postdata['hospital-phone'])) {
                                                                                                echo esc_attr($postdata['hospital-phone']);
                                                                                            } ?>" required="required" />
    </p>


    <div class="mb-3">
        <label for="logo">
            <?php esc_html_e('Logo', 'dokan-lite'); ?> <span class="required">*</span>
        </label>
        
        <input type="file" class="form-control" name="logo" id="logo" disabled value="<?php if (!empty($postdata['logo'])) {
                                                                                                echo esc_attr($postdata['logo']);
                                                                                            } ?>" required="required" />
    </div>



    <p class="form-row form-group form-row-wide">
        <label for="hospital-address">
            <?php esc_html_e('Address', 'dokan-lite'); ?><span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="hospital-address" id="hospital-address" disabled value="<?php if (!empty($postdata['hospital-address'])) {
                                                                                                    echo esc_attr($postdata['hospital-address']);
                                                                                                } ?>" required="required" />
    </p>

    <?php
    $show_terms_condition = dokan_get_option('enable_tc_on_reg', 'dokan_general');
    $terms_condition_url = dokan_get_terms_condition_url();

    if ('on' === $show_terms_condition && $terms_condition_url) { ?>
        <p class="form-row form-group form-row-wide">
            <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required" disabled>
            <label style="display: inline" for="tc_agree">
                <?php echo wp_kses_post(sprintf(__('I have read and agree to the <a target="_blank" href="%s">Terms &amp; Conditions</a>.', 'dokan-lite'), esc_url($terms_condition_url))); ?>
            </label>
        </p>
    <?php }

    do_action('dokan_seller_registration_field_after');
    ?>
</div>

<div class="show_if_seller"  style="<?php echo esc_attr($role_style); ?>">

    <div class="split-row form-row-wide">
        <p class="form-row form-group">
            <label for="company-name">
                <?php esc_html_e('Company Name', 'dokan-lite'); ?> <span class="required">*</span>
            </label>
            <input type="text" class="input-text form-control" name="company-name" id="company-name" disabled value="<?php if (!empty($postdata['company-name'])) {
                                                                                                        echo esc_attr($postdata['company-name']);
                                                                                                    } ?>" required="required" />
        </p>
    </div>

    <p class="form-row form-group form-row-wide">
        <label for="contact-person">
            <?php esc_html_e('Contact Person', 'dokan-lite'); ?> <span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="contact-person" id="contact-person" disabled value="<?php if (!empty($postdata['contact-person'])) {
                                                                                                        echo esc_attr($postdata['contact-person']);
                                                                                                    } ?>" required="required" />
    </p>


    <p class="form-row form-group form-row-wide">
        <label for="seller-phone">
            <?php esc_html_e('Phone Number', 'dokan-lite'); ?><span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="seller-phone" id="seller-phone" disabled value="<?php if (!empty($postdata['seller-phone'])) {
                                                                                                    echo esc_attr($postdata['seller-phone']);
                                                                                                } ?>" required="required" />
    </p>


    <p class="form-row form-group form-row-wide">
        <label for="seller-address">
            <?php esc_html_e('Address', 'dokan-lite'); ?><span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="seller-address" id="seller-address" disabled value="<?php if (!empty($postdata['seller-address'])) {
                                                                                                    echo esc_attr($postdata['seller-address']);
                                                                                                } ?>" required="required" />
    </p>

    <?php
    $show_terms_condition = dokan_get_option('enable_tc_on_reg', 'dokan_general');
    $terms_condition_url = dokan_get_terms_condition_url();

    if ('on' === $show_terms_condition && $terms_condition_url) { ?>
        <p class="form-row form-group form-row-wide">
            <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required" disabled>
            <label style="display: inline" for="tc_agree">
                <?php echo wp_kses_post(sprintf(__('I have read and agree to the <a target="_blank" href="%s">Terms &amp; Conditions</a>.', 'dokan-lite'), esc_url($terms_condition_url))); ?>
            </label>
        </p>
    <?php }

    do_action('dokan_seller_registration_field_after');
    ?>
</div>


<div class="show_if_customer" >

    <div class="split-row form-row-wide">
        <p class="form-row form-group">
            <label for="customer-name">
                <?php esc_html_e('Name', 'dokan-lite'); ?> <span class="required">*</span>
            </label>
            <input type="text" class="input-text form-control" name="customer-name" id="customer-name" value="<?php if (!empty($postdata['customer-name'])) {
                                                                                                        echo esc_attr($postdata['customer-name']);
                                                                                                    } ?>" required="required" />
        </p>
    </div>


    <p class="form-row form-group form-row-wide">
        <label for="customer-phone">
            <?php esc_html_e('Phone Number', 'dokan-lite'); ?><span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="customer-phone" id="customer-phone" value="<?php if (!empty($postdata['customer-phone'])) {
                                                                                                    echo esc_attr($postdata['customer-phone']);
                                                                                                } ?>" required="required" />
    </p>


    <p class="form-row form-group form-row-wide">
        <label for="customer-address">
            <?php esc_html_e('Address', 'dokan-lite'); ?><span class="required">*</span>
        </label>
        <input type="text" class="input-text form-control" name="customer-address" id="customer-address" value="<?php if (!empty($postdata['customer-address'])) {
                                                                                                    echo esc_attr($postdata['customer-address']);
                                                                                                } ?>" required="required" />
    </p>

    <?php
    $show_terms_condition = dokan_get_option('enable_tc_on_reg', 'dokan_general');
    $terms_condition_url = dokan_get_terms_condition_url();

    if ('on' === $show_terms_condition && $terms_condition_url) { ?>
        <p class="form-row form-group form-row-wide">
            <input class="tc_check_box" type="checkbox" id="tc_agree" name="tc_agree" required="required">
            <label style="display: inline" for="tc_agree">
                <?php echo wp_kses_post(sprintf(__('I have read and agree to the <a target="_blank" href="%s">Terms &amp; Conditions</a>.', 'dokan-lite'), esc_url($terms_condition_url))); ?>
            </label>
        </p>
    <?php }

    do_action('dokan_seller_registration_field_after');
    ?>
</div>


<?php do_action('dokan_reg_form_field'); ?>

<p class="form-row form-group user-role vendor-customer-registration">
    <label class="radio">
        <input type="radio" name="role" value="hospital" <?php checked($role, 'hospital'); ?>>
        <?php esc_html_e('Hospital', 'dokan-lite'); ?>
    </label>
    <br />
    <label class="radio">
        <input type="radio" name="role" value="seller" <?php checked($role, 'seller'); ?>>
        <?php esc_html_e('Supplier', 'dokan-lite'); ?>
    </label>
    <br />
    <label class="radio">
        <input type="radio" name="role" value="customer" <?php checked($role, 'customer'); ?>>
        <?php esc_html_e('Customer', 'dokan-lite'); ?>
    </label>

    <?php do_action('dokan_registration_form_role', $role); ?>

</p>