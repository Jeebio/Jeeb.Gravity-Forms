<?php

require GFJEEB_PLUGIN_ROOT . 'includes/functions.php';

?>
<div class="wrap">
	<h3>Jeeb Payment Gateway</h3>

	<form action="<?php echo $this->scriptURL; ?>" method="post" id="jeeb-settings-form">
		<table class="form-table">
			<tr>
				<th>API Key</th>
				<td id='jeeb_api_token'>
					<label><input type="text" name="apiKey" value="<?php echo $this->frm->apiKey; ?>" style="width: 100%;" /></label>
					<p><small>The API key provided by <a href="https://jeeb.io" target="_blank">Jeeb</a> for your merchant.</small></p>
				</td>
			</tr>

			<tr valign="top">
				<th>Base Currency</th>
				<td>
					<select name="baseCurrency" class="jeebbaseCurrency">
						<option value="-1" disabled selected>Choose a currency</option>
						<?php
						$currencies = jeeb_available_currencies_list();
						foreach ($currencies as $curreny => $title) {
							echo '<option value="' . $curreny . '" ' . ($this->frm->baseCurrency == $curreny ? 'selected' : '') . '>' . $title . '</option>';
						}
						?>
					</select>
					<p><small>The base currency of your website.</small></p>
				</td>
			</tr>

			<tr valign="top">
				<th>Payable Currencies</th>
				<td>
					<?php
					$coins = jeeb_available_coins_list();

					foreach ($coins as $coin => $title) {
						$checked = $this->frm->payableCoins[$coin] == $coin ? 'checked' : '';
						echo '<input type="checkbox" name="' . $coin . '" value="' . $coin . '" ' . $checked . ' />' . $title . '<br/>';
					}
					?>
					<p><small>The currencies which users can use for payments.</small></p>
				</td>
			</tr>

			<!-- AllowRefund -->
			<tr valign="top">
				<th>Allow TestNets</th>
				<td>
					<fieldset>
						<label for="allowTestnets">
							<input type="checkbox" name="allowTestnets" <?php echo $this->frm->allowTestnets == 'yes' ? 'checked' : ''; ?> value="yes" />
							<small>Allows testnets such as TEST-BTC to get processed.</small>
						</label>
					</fieldset>
				</td>
			</tr>

			<!-- AllowRefund -->
			<tr valign="top">
				<th>Allow Refund</th>
				<td>
					<fieldset>
						<label>
							<input type="checkbox" name="allowRefund" <?php echo $this->frm->allowRefund == 'yes' ? 'checked' : ''; ?> value="yes" />
							<small>Allows payments to be refunded.</small>
						</label>
						</fieldset>
				</td>
			</tr>

			<tr>
				<th>Expiration Time</th>
				<td id='jeeb_expiration_time'>
					<label><input type="text" name="expirationTime" value="<?php echo $this->frm->expirationTime; ?>" /></label>
					<p><small>Expands default payments expiration time. It should be between 15 to 2880 (mins).</small></p>
				</td>
			</tr>

			<!-- Language -->
			<?php
			$auto = $en = $fa = "";
			$this->frm->language == "auto" ? $auto = "selected" : $auto = "";
			$this->frm->language == "en" ? $en = "selected" : $en = "";
			$this->frm->language == "fa" ? $fa = "selected" : $fa = "";
			?>
			<tr valign="top">
				<th>Language</th>
				<td>
					<select name="language" class="jeebLang">
						<option value="auto" <?php echo $auto; ?>>Auto-Select</option>
						<option value="en" <?php echo $en; ?>>English</option>
						<option value="fa" <?php echo $fa; ?>>Persian</option>
					</select>
					<p><small>The language of the payment area.</small></p>
				</td>
			</tr>

			<tr valign="top">
				<th>Redirect URL</th>
				<td>
					<label><input type="text" name="callbackUrl" value="<?php echo $this->frm->callbackUrl; ?>" style="width: 100%;" /></label>
					<p><small>Enter the URL to which you want the users to return after payments.</small></p><br><br>
				</td>
			</tr>
		</table>


		<div class="hr-divider"></div>

		<h3>Debugging</h3>
		<table class="form-table">
		
		<tr valign="top">
				<th>Webhook.site URL</th>
				<td>
					<label><input type="text" name="webhookDebugUrl" value="<?php echo $this->frm->webhookDebugUrl; ?>" style="width: 100%;" /></label>
					<p><small>With <a href="https://webhook.site">Webhook.site</a>, you instantly get a unique, random URL that you can use to test and debug Webhooks and HTTP requests</small></p><br><br>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
			<?php wp_nonce_field('save', $this->menuPage . '_wpnonce', false); ?>
		</p>
	</form>

</div>