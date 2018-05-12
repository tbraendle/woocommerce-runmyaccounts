<?php
if (!defined('ABSPATH')) { exit; }

$settings = get_option('wc_rma_settings');
if (class_exists('WC_RMA_API')) $WC_RMA_API = new WC_RMA_API();

?>
<div class="wrap">
    <h2><?php _e('Run my Accounts - Settings', 'wc-rma'); ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields('wc_rma_settings_group'); ?>
        <h2><?php _e('Connection settings', 'wc-rma'); ?></h2>
        <table class="form-table">
            <tr valign="top" class="titledesc">
                <th scope="row" class="titledesc"><?php _e('Active', 'wc-rma'); ?></th>
                <td class="forminp forminp-text">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php _e('Active', 'wc-rma'); ?></span></legend>
                        <label for="rma-active">
                            <input name="wc_rma_settings[rma-active]" id="rma-active" type="checkbox" class="" value="1" <?php if(isset($settings['rma-active'])) echo ($settings['rma-active'] ? 'checked="checked"' : '' ); ?>><?php _e('Do not activate the plugin before you have set up all data.', 'wc-rma'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php _e('Operation Mode', 'wc-rma'); ?></th>
                <td class="forminp forminp-select">
                    <select name="wc_rma_settings[rma-mode]">
                        <option value="test"<?php if( isset( $settings['rma-mode'] ) ) echo ( 'test' == $settings['rma-mode'] ? ' selected="selected"' : '' ); ?>><?php _e('Test Mode', 'wc-rma'); ?></option>
                        <option value="live"<?php if( isset( $settings['rma-mode'] ) ) echo ( 'live' == $settings['rma-mode'] ? ' selected="selected"' : '' ); ?>><?php _e('Live Mode', 'wc-rma'); ?></option>
                    </select>&nbsp;
	                <?php
	                $options = $WC_RMA_API->get_customers();
	                if ( ! $options ) echo '<span style="color: red; font-weight: bold">' . __('No connection. Please check your settings.', 'wc-rma') . '</span>';
	                else echo '<span style="color: green">' . __('Connection successful.', 'wc-rma') . '</span>';
	                ?>
                    <p><?php _e('If the test mode is selected the test client is used and the test API key.', 'wc-rma'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-live-client"><?php _e('Live Client', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-live-client]" id="rma-live-client" value="<?php echo ((isset($settings['rma-live-client'])) ? $settings['rma-live-client'] : '' ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-live-apikey"><?php _e('Live API key', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-live-apikey]" id="rma-live-apikey" value="<?php echo ((isset($settings['rma-live-apikey'])) ? $settings['rma-live-apikey'] : '' ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-test-client"><?php _e('Test Client', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-test-client]" id="rma-test-client" value="<?php echo ((isset($settings['rma-test-client'])) ? $settings['rma-test-client'] : '' ); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-test-apikey"><?php _e('Test API key', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text"><input type="text" name="wc_rma_settings[rma-test-apikey]" id="rma-test-apikey" value="<?php echo ((isset($settings['rma-test-apikey'])) ? $settings['rma-test-apikey'] : '' ); ?>" /></td>
            </tr>
        </table>
        <h2><?php _e('Invoice settings', 'wc-rma'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-payment-period"><?php _e('Payment Period in days', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-payment-period]" id="rma-payment-period" value="<?php echo ((isset($settings['rma-payment-period'])) ? $settings['rma-payment-period'] : '' ); ?>" />&nbsp;<?php _e('day(s)', 'wc-rma'); ?>
                    <p class="description"><?php _e('Please set the general payment period. You can set a individual value, for a customer, in the user profile.', 'wc-rma'); ?></p>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-invoice-prefix"><?php _e('Invoice Prefix', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-invoice-prefix]" id="rma-invoice-prefix" value="<?php echo ((isset($settings['rma-invoice-prefix'])) ? $settings['rma-invoice-prefix'] : '' ); ?>" />
                    <p class="description"><?php _e('Prefix followed by order number will be the invoice number in Run my Accounts.', 'wc-rma'); ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-digits"><?php _e('Number of digits', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="number" name="wc_rma_settings[rma-digits]" id="rma-digits" value="<?php echo ((isset($settings['rma-digits'])) ? $settings['rma-digits'] : '' ); ?>" />
                    <p class="description"><?php _e('Set the maximum number of digits for the invoice number (including prefix).', 'wc-rma'); ?></p>
                </td>

            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><label for="rma-invoice-description"><?php _e('Invoice Description in RMA', 'wc-rma'); ?></label></th>
                <td class="forminp forminp-text">
                    <input type="text" name="wc_rma_settings[rma-invoice-description]" id="rma-invoice-description" value="<?php echo ( ( isset($settings['rma-invoice-description'] )) ? $settings['rma-invoice-description'] : '' ); ?>" />
                    <p class="description"><?php _e('Possible variable: [orderdate]', 'wc-rma'); ?></p>
            </tr>
        </table>
        <h2><?php _e('Plugin settings', 'wc-rma'); ?></h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="rma-loglevel"><?php _e('Log Level','wc-rma')?></label></th>
                <td>
                    <select name="wc_rma_settings[rma-loglevel]">
				        <?php  $loglevel = ( ( !isset($settings['rma-loglevel'] ) ? 'error' : $settings['rma-loglevel'] ) ); ?>
                        <option value="complete"<?php echo ( ( 'complete' == $loglevel ) ? ' selected' : ''); ?>><?php _e('complete','wc-rma')?></option>
                        <option value="error"<?php echo ( ( 'error' == $loglevel ) ? ' selected' : ''); ?>><?php _e('error','wc-rma')?></option>
                    </select>
                </td>
            </tr>
	        <?php /* ToDO: Activate this block?>
            <tr valign="top">
                <th scope="row"><label for="rma-logemail"><?php _e('Log Error Send Email','wc-rma')?></label></th>
                <td>
                    <select name="wc_rma_settings[rma-logemail]">
				        <?php  $logemail = ( ( !isset($settings['rma-logemail'] ) ? 'yes' : $settings['rma-logemail'] ) ); ?>
                        <option value="complete"<?php echo ( ( 'yey' == $logemail ) ? ' selected' : ''); ?>><?php _e('yes','wc-rma') ?></option>
                        <option value="error"<?php echo ( ( 'no' == $logemail ) ? ' selected' : ''); ?>><?php _e('no','wc-rma') ?></option>
                    </select>
                    <p class="description"><?php echo sprintf( __( 'Will be send to %s.', 'wc-rma' ), get_bloginfo('admin_email') ) ?></p>
                </td>
            </tr>
            <?php */ ?>
            <tr valign="top">
                <th scope="row"><label for="rma-delete-settings"><?php _e('Remove all plugin data when using the "Delete" link on the plugins screen','wc-rma')?></label></th>
                <td><select name="wc_rma_settings[rma-delete-settings]">
					    <?php  $deleteSettings = ( ( !isset($settings['rma-delete-settings'] ) ? 'no' : $settings['rma-delete-settings'] ) ); ?>
                        <option value="no"<?php echo ( ( 'no' == $deleteSettings ) ? ' selected' : ''); ?>><?php _e('no','wc-rma')?></option>
                        <option value="yes"<?php echo ( ( 'yes' == $deleteSettings ) ? ' selected' : ''); ?>><?php _e('yes','wc-rma')?></option>
                    </select></td>
            </tr>
        </table>

	    <?php submit_button( __( 'Save Changes','wc-rma' ), 'primary', 'Update' ); ?>
        <?php wp_nonce_field( 'wc-rma-nonce-action', 'wc-rma-nonce' ); ?>
    </form>
</div>

<?php

//echo $WC_RMA_API->post_invoice(718);
