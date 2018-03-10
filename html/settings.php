<?php
$settings = get_option('wc_rma_settings');
if (class_exists('WC_RMA_API')) $WC_RMA_API = new WC_RMA_API();

?>
<div class="wrap">
    <h2><?php _e('Run My Accounts - Settings', 'wc-rma'); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('wc_rma_settings_group'); ?>
        <h2><?php _e('Connection settings', 'wc-rma'); ?></h2>
        <p><?php _e('The following settings affect the way how to connect to RMA server.', 'wc-rma'); ?></p>
        <table class="form-table">
            <tr valign="top" class="titledesc">
                <th scope="row" class="titledesc"><?php _e('Active', 'wc-rma'); ?></th>
                <td class="forminp forminp-text">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Active', 'wc-rma'); ?></span></legend>
                        <label for="rma-active">
                            <input name="wc_rma_settings[rma-active]" id="rma-active" type="checkbox" class="" value="1" <?php if(isset($settings['rma-active'])) echo ($settings['rma-active'] ? 'checked="checked"' : '' ); ?>><?php _e('Activate the plugin when you have set up all data.', 'wc-rma'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-client"><?php _e('Client', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-client]" id="rma-client" value="<?php echo ((isset($settings['rma-client'])) ? $settings['rma-client'] : '' ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-apikey"><?php _e('API key', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-apikey]" id="rma-apikey" value="<?php echo ((isset($settings['rma-apikey'])) ? $settings['rma-apikey'] : '' ); ?>" /></td>
            </tr>
            <tr valign="top" class="titledesc">
                <th scope="row" class="titledesc"><?php _e('Sandbox', 'wc-rma'); ?></th>
                <td class="forminp forminp-text">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Sandbox', 'wc-rma'); ?></span></legend>
                        <label for="rma-sandbox">
                            <input name="wc_rma_settings[rma-sandbox]" id="rma-sandbox" type="checkbox" class="" value="1" <?php if(isset($settings['rma-sandbox'])) echo ($settings['rma-sandbox'] ? 'checked="checked"' : '' ); ?>><?php _e('Activate sandbox instead connecting to live server.', 'wc-rma'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>
        <h2><?php _e('Invoice settings', 'wc-rma'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-payment-period"><?php _e('Payment Period in days', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-payment-period]" id="rma-payment-period" value="<?php echo ((isset($settings['rma-payment-period'])) ? $settings['rma-payment-period'] : '' ); ?>" />&nbsp;<?php _e('day(s)', 'wc-rma'); ?>
                    <p class="description"><?php _e('How many days do your customers have to pay an invoice? You can overwrite this value for a customer in the user profile.', 'wc-rma'); ?></p>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-invoice-prefix"><?php _e('Invoice Prefix', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-invoice-prefix]" id="rma-invoice-prefix" value="<?php echo ((isset($settings['rma-invoice-prefix'])) ? $settings['rma-invoice-prefix'] : '' ); ?>" />
                    <p class="description"><?php _e('Prefix plus order number will be the invoice number in Run My Account', 'wc-rma'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-digits"><?php _e('Number of digits', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-digits]" id="rma-digits" value="<?php echo ((isset($settings['rma-digits'])) ? $settings['rma-digits'] : '' ); ?>" />
                    <p class="description"><?php _e('How many digits should the invoice number have (including prefix)?', 'wc-rma'); ?></p>
                </td>

            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-invoice-description"><?php _e('Invoice Description', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input style="width: 35%" type="text" name="wc_rma_settings[rma-invoice-description]" id="rma-invoice-description" value="<?php echo ((isset($settings['rma-invoice-description'])) ? $settings['rma-invoice-description'] : '' ); ?>" />
                    <p class="description"><?php _e('Use possible variables: [orderdate]', 'wc-rma'); ?></p>
            </tr>
        </table>
	    <?php submit_button( __( 'Save Changes','wc-rma' ), 'primary', 'Update' ); ?>
        <?php wp_nonce_field( 'wc-rma-nonce-action', 'wc-rma-nonce' ); ?>
    </form>
</div>

<?php

//echo $WC_RMA_API->post_invoice(718);
