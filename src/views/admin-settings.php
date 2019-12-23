<div class="wrap">
<h3>Jeeb Payment Gateway</h3>
<p style="text-align: left;">
	This Plugin requires you to set up a Jeeb account.
</p>
<ul>
	<li>Navigate to the Jeeb <a href="https://jeeb.io">signup page.</a></li>
</ul>
<br/>
<form action="<?php echo $this->scriptURL; ?>" method="post" id="jeeb-settings-form">
	<table class="form-table">
		<tr>
			<th>Signature</th>
			<td id='jeeb_api_token'>
				<label><input type="text" name="jeebSignature" value="<?php echo $this->frm->jeebSignature; ?>" /></label>
			<p><small>The signature provided by Jeeb for your merchant.</small></p>
			</td>
		</tr>
		<?php
$bbtc = $beth = $bltc = $bbch = $birr = $busd = $beur = $bgbp = $bcad = $baud = $baed = $btry = $bcny = $bjpy = $btoman = "";

$this->frm->jeebBase == "btc" ? $bbtc = "selected" : $bbtc = "";
$this->frm->jeebBase == "eth" ? $beth = "selected" : $beth = "";
$this->frm->jeebBase == "ltc" ? $bltc = "selected" : $bltc = "";
$this->frm->jeebBase == "bch" ? $bbch = "selected" : $bbch = "";
$this->frm->jeebBase == "irr" ? $birr = "selected" : $birr = "";
$this->frm->jeebBase == "usd" ? $busd = "selected" : $busd = "";
$this->frm->jeebBase == "gbp" ? $bgbp = "selected" : $bgbp = "";
$this->frm->jeebBase == "cad" ? $bcad = "selected" : $bcad = "";
$this->frm->jeebBase == "aud" ? $baud = "selected" : $baud = "";
$this->frm->jeebBase == "aed" ? $baed = "selected" : $baed = "";
$this->frm->jeebBase == "try" ? $btry = "selected" : $btry = "";
$this->frm->jeebBase == "cny" ? $bcny = "selected" : $bcny = "";
$this->frm->jeebBase == "jpy" ? $bjpy = "selected" : $bjpy = "";
$this->frm->jeebBase == "toman" ? $btoman = "selected" : $btoman = "";
?>
		<tr valign="top">
      		<th>Base Currency</th>
			<td>
				<select name="jeebBase" class="jeebBase">
					<option value="irr" <?php echo $birr; ?>>IRR (Iranian Rials)</option>
					<option value="toman" <?php echo $btoman ?>>IRT (Iranian Tomans)</option>
					<option value="btc" <?php echo $bbtc ?>> BTC (Bitcoin)</option>
					<option value="usd" <?php echo $busd ?>> USD (US Dollar)</option>
					<option value="eur" <?php echo $beur ?>> EUR (Euro)</option>
					<option value="gbp" <?php echo $bgbp ?>> GBP (British Pound)</option>
					<option value="cad" <?php echo $bcad ?>> CAD (Canadian Dollar)</option>
					<option value="aud" <?php echo $baud ?>> AUD (Australian Dollar)</option>
					<option value="aed" <?php echo $baed ?>> AED (Dirham)</option>
					<option value="try" <?php echo $btry ?>> TRY (Turkish Lira)</option>
					<option value="cny" <?php echo $bcny ?>> CNY (Chinese Yuan)</option>
					<option value="jpy" <?php echo $bjpy ?>> JPY (Japanese Yen)</option>
				</select>
				<p><small>The base currency of your website.</small></p>
			</td>
		</tr>

		<?php
$btc = $ltc = $eth = $bch = $test_btc = "";
$this->frm->jeebBtc == "btc" ? $btc = "checked" : $btc = "";
$this->frm->jeebLtc == "ltc" ? $ltc = "checked" : $ltc = "";
$this->frm->jeebEth == "eth" ? $eth = "checked" : $eth = "";
$this->frm->jeebBch == "bch" ? $bch = "checked" : $bch = "";

$this->frm->jeebTestBtc == "test-btc" ? $test_btc = "checked" : $test_btc = "";
$this->frm->jeebTestLtc == "test-ltc" ? $test_ltc = "checked" : $test_ltc = "";
?>

		<tr valign="top">
      <th>Payable Currencies</th>
			<td>
				<input type="checkbox" name="jeebBtc" value="btc" <?php echo $btc; ?>/>BTC<br>
				<input type="checkbox" name="jeebLtc" value="ltc" <?php echo $ltc; ?>/>LTC<br>
				<input type="checkbox" name="jeebEth" value="eth" <?php echo $eth; ?>/>ETH<br>
				<input type="checkbox" name="jeebBch" value="bch" <?php echo $bch; ?>/>BCH<br>
				<input type="checkbox" name="jeebTestBtc" value="test-btc" <?php echo $test_btc; ?>/>TEST-BTC<br>
				<input type="checkbox" name="jeebTestLtc" value="test-ltc" <?php echo $test_ltc; ?>/>TEST-LTC<br>
				<p><small>The currencies which users can use for payments.</small></p>
			</td>
		</tr>
		<?php
$live = $test = "";
$this->frm->jeebNetwork == "Livenet" ? $live = "selected" : $live = "";
$this->frm->jeebNetwork == "Testnet" ? $test = "selected" : $test = "";
?>
		<tr valign="top">
      <th>Allow TestNets</th>
			<td>
				<select name="jeebNetwork" class="jeebNetwork">
					<option value="Livenet" <?php echo $live; ?>>Livenet</option>
					<option value="Testnet" <?php echo $test; ?>>Testnet</option>
				</select>
				<p><small>Allows testnets such as TBTC to get processed.</small></p>
			</td>
		</tr>
		<?php
$auto = $en = $fa = "";
$this->frm->jeebLang == "none" ? $auto = "selected" : $auto = "";
$this->frm->jeebLang == "en" ? $en = "selected" : $en = "";
$this->frm->jeebLang == "fa" ? $fa = "selected" : $fa = "";
?>
		<?php
$yes = $no = "";
$this->frm->jeebAllowRefund == "yes" ? $yes = "selected" : $yes = "";
$this->frm->jeebAllowRefund == "no" ? $no = "selected" : $no = "";
?>
		<tr valign="top">
      <th>Allow Refund</th>
			<td>
				<select name="jeebAllowRefund" class="jeebAllowRefund">
					<option value="yes" <?php echo $yes; ?>>Allow</option>
					<option value="no" <?php echo $no; ?>>Disable</option>
				</select>
				<p><small>Allows payments to be refunded.</small></p>
			</td>
		</tr>
		<tr>
			<th>Expiration Time</th>
			<td id='jeeb_expiration_time'>
				<label><input type="text" name="jeebExpirationTime" value="<?php echo $this->frm->jeebExpirationTime; ?>" /></label>
				<p><small>Expands default payments expiration time. It should be between 15 to 2880 (mins).</small></p>
			</td>
		</tr>
		<tr valign="top">
      <th>Language</th>
			<td>
				<select name="jeebLang" class="jeebLang">
					<option value="none" <?php echo $auto; ?>>Auto-Select</option>
					<option value="en" <?php echo $en; ?>>English</option>
					<option value="fa" <?php echo $fa; ?>>Persian</option>
				</select>
				<p><small>Tthe language of the payment area.</small></p>
			</td>
		</tr>

		<tr valign="top">
      <th>Redirect URL</th>
			<td>
				<label><input type="text" name="jeebRedirectURL" value="<?php echo $this->frm->jeebRedirectURL; ?>" /></label>
				<p><small>Enter the URL to which you want the users to return after payments.</small></p><br><br>
			</td>
		</tr>

	</table>
	<p class="submit">
	<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
	<?php wp_nonce_field('save', $this->menuPage . '_wpnonce', false);?>
	</p>
</form>

</div>
