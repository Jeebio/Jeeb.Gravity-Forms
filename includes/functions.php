<?php

/**
 * Get all available currencies in Jeeb gateway
 *
 * @since       3.4.0
 * @access      private
 * @return      array
 */
if (!function_exists('jeeb_available_currencies_list')) {
    function jeeb_available_currencies_list()
    {
        $currencies = [
            "IRT" => "IRT (Toman)",
            "IRR" => "IRR (Rial)",
            "BTC" => "BTC (Bitcoin)",
            "USD" => "USD (US Dollar)",
            "USDT" => "USDT (TetherUS)",
            "EUR" => "EUR (Euro)",
            "GBP" => "GBP (Pound)",
            "CAD" => "CAD (CA Dollar)",
            "AUD" => "AUD (AU Dollar)",
            "JPY" => "JPY (Yen)",
            "CNY" => "CNY (Yuan)",
            "AED" => "AED (Dirham)",
            "TRY" => "TRY (Lira)",
        ];

        return $currencies;
    }
}

/**
 * Get all available coins in Jeeb gateway
 *
 * @since       3.4.0
 * @access      private
 * @return      array
 */
if (!function_exists('jeeb_available_coins_list')) {
    function jeeb_available_coins_list()
    {
        $currencies = [
            "BTC" => "BTC (Bitcoin)",
            "ETH" => "ETH (Ethereum)",
            "DOGE" => "DOGE (Dogecoin)",
            "LTC" => "LTC (Litecoin)",
            "USDT" => "USDT (TetherUS)",
            "BNB" => "BNB (BNB)",
            "USDC" => "USDC (USD Coin)",
            "ZRX" => "ZRX (0x)",
            "LINK" => "LINK (ChainLink)",
            "PAX" => "PAX (Paxos Standard)",
            "DAI" => "DAI (Dai)",
            "TBTC" => "TBTC (Bitcoin Testnet)",
            "TETH" => "TETH (Ethereum Testnet)",
        ];

        return $currencies;
    }
}


/**
 * Get Jeeb options
 *
 * @since       3.4.0
 * @return      mixed
 */
if (!function_exists('get_jeeb_option')) {
    function get_jeeb_option($option)
    {
        $prefix = 'gfjeeb_';
        return get_option($prefix . ucfirst($option));
    }
}


/**
 * Push message to webhook.site endpoint
 *
 * @since       3.4.0
 * @access      private
 * @param       $message
 * @return      void
 */
if (!function_exists('notify_log')) {
    function notify_log($message)
    {
        $webhookDebugUrl = get_jeeb_option('webhookDebugUrl');

        if ($webhookDebugUrl) {
            $post = json_encode($message);
            $ch = curl_init($webhookDebugUrl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
            ));

            curl_exec($ch);
        }
    }
}
