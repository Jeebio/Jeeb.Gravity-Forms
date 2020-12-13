<?php

require GFJEEB_PLUGIN_ROOT . 'includes/functions.php';

/**
 * Class for handling Jeeb payment
 */
class GFJeebPayment
{
  public $uid;            // Displays unique id
  public $total;          // Displays Total
  public $buyer_email;    // Displays Customer's EmaiL

  const PLUGIN_NAME = 'gravityforms';
  const PLUGIN_VERSION = '3.4';
  const BASE_URL = "https://core.jeeb.io/api/v3/";

  /**
   * Writes $contents to system error logger.
   *
   * @param mixed $contents
   * @throws Exception $e
   */
  public function error_log($contents)
  {
    if (false === isset($contents) || true === empty($contents)) {
      return;
    }

    if (true === is_array($contents)) {
      $contents = var_export($contents, true);
    } else if (true === is_object($contents)) {
      $contents = json_encode($contents);
    }

    error_log($contents);
  }

  public function createInvoice($options = array(), $api_key)
  {

    // die($api_key);

    $post = json_encode($options);

    $ch = curl_init(self::BASE_URL . 'payments/issue/');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'X-API-Key: ' . $api_key,
      'Content-Length: ' . strlen($post),
      'User-Agent:' . self::PLUGIN_NAME . '/' . self::PLUGIN_VERSION
    ));

    $result = curl_exec($ch);
    $data = json_decode($result, true);
    error_log('data = ' . $data['result']['token']);

    return $data['result']['token'];
  }

  public function redirectPayment($token)
  {
    error_log("Entered into auto submit-form");
    // Using Auto-submit form to redirect user with the token
    echo "<form id='form' method='post' action='" . self::BASE_URL . "payments/invoice'>" .
      "<input type='hidden' autocomplete='off' name='token' value='" . $token . "'/>" .
      "</form>" .
      "<script type='text/javascript'>" .
      "document.getElementById('form').submit();" .
      "</script>";
  }

  /**
   * Process a payment
   */
  public function processPayment()
  {

    // price
    $amount = number_format($this->total, 2, '.', '');

    $api_key      = get_jeeb_option('apiKey');
    $callbackUrl  = get_jeeb_option('callbackUrl') ? get_jeeb_option('callbackUrl') : home_url();

    $hash_key = md5($api_key . $this->uid);

    $webhookUrl = home_url('/?jeeb_callback=gfjeeb&hash_key=' . $hash_key);
    $baseCurrency = get_jeeb_option('baseCurrency');
    $language     = get_jeeb_option('language') == "auto" ? null : get_jeeb_option("language");

    // Prepare payable coins string delimited by slash
    $payableCoins = [];
    $coins = array_keys(jeeb_available_coins_list());
    foreach ($coins as $coin) {
      $is_seleted = get_jeeb_option($coin);
      if ($is_seleted) {
        $payableCoins[] = $coin;
      }
    }
    $payableCoins = implode('/', $payableCoins);

    error_log($this->uid . " " . BASE_URL . " " . $api_key . " " . $callbackUrl . " " . $webhookUrl . " " . $baseCurrency);
    error_log("target cur = " . $payableCoins);

    $params = array(
      'orderNo'          => $this->uid,
      'baseAmount'       => (float) $amount,
      "payableCoins"     => $payableCoins,
      "baseCurrencyId"   => $baseCurrency,
      'webhookUrl'       => $webhookUrl,
      'callbackUrl'      => $callbackUrl,
      'allowReject'      => get_jeeb_option('AllowRefund') == "yes" ? true : false,
      "allowTestNets"    => get_jeeb_option('AllowTestnets') == "yes" ? true : false,
      'expiration'       => get_jeeb_option('ExpirationTime'),
      "language"         => $language
    );

    $token = $this->createInvoice($params, $api_key);

    $this->redirectPayment($token);
  }
}
