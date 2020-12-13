<?php

include GFJEEB_PLUGIN_ROOT . 'includes/functions.php';

/**
 * Jeeb for Gravity Forms Uninstall
 *
 * @author 		jeeb
 */
if (!defined('WP_UNINSTALL_PLUGIN'))
    exit();

$prefix = 'gfjeeb_';

delete_option($prefix . 'ApiKey');
delete_option($prefix . 'BaseCurrency');
delete_option($prefix . 'PayableCoins');
delete_option($prefix . 'AllowTestnets');
delete_option($prefix . 'CallbackUrl');

$coins = array_keys(jeeb_available_coins_list());
foreach ($coins as $coin) {
    delete_option($prefix . ucfirst($coin));
}

delete_option($prefix . 'ExpirationTime');
delete_option($prefix . 'Language');
delete_option($prefix . 'WebhookDebugUrl');
