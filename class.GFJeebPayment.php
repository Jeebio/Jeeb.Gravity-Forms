<?php


/**
 * Class for handling Jeeb payment
 *
 * @link https://jeeb.com/bitcoin-payment-gateway-api
 */
class GFJeebPayment
{
    public $uid;            // Displays unique id
    public $total;          // Displays Total
    public $buyer_email;    // Displays Customer's EmaiL

    const PLUGIN_NAME = 'gravityforms';
    const PLUGIN_VERSION = '3.2';
    const BASE_URL = "https://core.jeeb.io/api/";

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

    public function convertIrrToBtc($amount, $signature, $baseCur) {

        // return Jeeb::convert_irr_to_btc($url, $amount, $signature);
        $ch = curl_init(self::BASE_URL.'currency?'.$signature.'&value='.$amount.'&base='.$baseCur.'&target=btc');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'User-Agent:'.self::PLUGIN_NAME . '/' . self::PLUGIN_VERSION)
      );

      $result = curl_exec($ch);
      $data = json_decode( $result , true);
      error_log('Response =>'. var_export($data, TRUE));
      // Return the equivalent bitcoin value acquired from Jeeb server.
      return (float) $data["result"];

      }


      public function createInvoice($options = array(), $signature) {

          $post = json_encode($options);

          $ch = curl_init(self::BASE_URL.'payments/' . $signature . '/issue/');
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($post),
              'User-Agent:'.self::PLUGIN_NAME . '/' . self::PLUGIN_VERSION)
          );

          $result = curl_exec($ch);
          $data = json_decode( $result , true);
          error_log('data = '.$data['result']['token']);

          return $data['result']['token'];

      }

      public function redirectPayment($token) {
        error_log("Entered into auto submit-form");
        // Using Auto-submit form to redirect user with the token
        echo "<form id='form' method='post' action='".self::BASE_URL."payments/invoice'>".
                "<input type='hidden' autocomplete='off' name='token' value='".$token."'/>".
               "</form>".
               "<script type='text/javascript'>".
                    "document.getElementById('form').submit();".
               "</script>";
      }

    /**
     * Process a payment
     */
    public function processPayment()
    {
      global $wpdb;
            if (true === empty(get_option('jeebRedirectURL'))) {
                update_option('jeebRedirectURL', get_site_url());
            }

            // price
            $price = number_format($this->total, 2, '.', '');

            $signature    = get_option('jeebSignature');
            $callBack     = get_option('jeebRedirectURL');
            $notification = get_option('siteurl').'/?jeeb_callback=true';
            $baseCur      = get_option('jeebBase');
            $lang         = get_option('jeebLang')== "none" ? NULL : get_option("jeebLang") ;
            $target_cur   = "";
            $order_total  = $price;
            $params = array(
                            'Btc',
                            'Xrp',
                            'Xmr',
                            'Ltc',
                            'Bch',
                            'Eth',
                            'TestBtc',
                            'TestLtc'
                           );

            foreach ($params as $p) {
              get_option("jeeb".$p) != NULL ? $target_cur .= get_option("jeeb".$p) . "/" : get_option("jeeb".$p) ;
              error_log("target cur = ". get_option("jeeb".$p));
            }

            if($baseCur=='toman'){
              $baseCur='irr';
              $order_total *= 10;
            }

            error_log($this->uid." ".$baseUri." ".$signature." ".$callBack." ".$notification." ". $baseCur);
            error_log("target cur = ". $target_cur);

            $amount = $this->convertIrrToBtc($order_total, $signature, $baseCur);

            $params = array(
              'orderNo'          => $this->uid,
              'value'            => (float) $amount,
              'webhookUrl'       => $notification,
              'callbackUrl'      => $callBack,
              'expiration'       => get_option('jeebExpirationTime'),
              'allowReject'      => get_option('jeebAllowRefund')=="yes" ? true : false,
              "coins"            => $target_cur,
              "allowTestNet"     => get_option('jeebNetwork')=="Testnet" ? true : false,
              "language"         => $lang
            );

            $token = $this->createInvoice($params, $signature);

            $this->redirectPayment($token);

    }

}
