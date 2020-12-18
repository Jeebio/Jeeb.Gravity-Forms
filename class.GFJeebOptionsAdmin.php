<?php


include GFJEEB_PLUGIN_ROOT . 'includes/functions.php';
/**
 * Options form input fields
 */
class GFJeebOptionsForm
{
    private $prefix = 'gfjeeb_';
    public $fields = [
        'apiKey',
        'baseCurrency',
        'payableCoins',
        'allowTestnets',
        'allowRefund',
        'callbackUrl',
        'expirationTime',
        'language',
        'webhookDebugUrl',
    ];

    public function __construct()
    {
        $this->defineFields();
    }


    /**
     * Define form fields in class object
     *
     * @since       3.4.0
     * @access      private
     */
    private function defineFields()
    {
        foreach ($this->fields as $field) {
            if ($field != 'payableCoins') {
                $this->{$field} = get_option($this->prefix . ucfirst($field), false);
            } else {
                $coins = array_keys(jeeb_available_coins_list());
                foreach ($coins as $coin) {
                    $this->payableCoins[$coin] = get_option($this->prefix . ucfirst($coin), false);
                }
            }
        }
    }

    /**
     * Save field values to DB
     * 
     * @since       3.4.0
     * @access      public
     */
    public function updateFields()
    {
        foreach ($this->fields as $field) {
            if ($field != 'payableCoins') {
                $fieldValue = self::getPostValue($field);
                update_option($this->prefix . ucfirst($field), $fieldValue);
                $this->{$field} = $fieldValue;
            } else {
                $coins = array_keys($this->payableCoins);
                foreach ($coins as $coin) {
                    $fieldValue = self::getPostValue($coin);
                    update_option($this->prefix . ucfirst($coin), $fieldValue);
                    $this->payableCoins[$coin] = $fieldValue;
                }
            }
        }
    }

    /**
     * Is this web request a form post?
     *
     * Checks to see whether the HTML input form was posted.
     *
     * @return boolean
     */
    public static function isFormPost()
    {
        return (bool)($_SERVER['REQUEST_METHOD'] == 'POST');
    }

    /**
     * Read a field from form post input.
     *
     * Guaranteed to return a string, trimmed of leading and trailing spaces, slashes stripped out.
     *
     * @return string
     * @param string $fieldname name of the field in the form post
     */
    public static function getPostValue($fieldname)
    {
        return isset($_POST[$fieldname]) ? stripslashes(trim($_POST[$fieldname])) : '';
    }

    public function setTempValues()
    {
        foreach ($_POST as $field => $value) {
            if (in_array($field, $this->fields)) {
                $this->{$field} = $value;
            }
        }
    }

    /**
     * Validate the form input, and return error messages.
     *
     * Return a string detailing error messages for validation errors discovered,
     * or an empty string if no errors found.
     * The string should be HTML-clean, ready for putting inside a paragraph tag.
     *
     * @return string
     */
    public function validate()
    {
        $errMsg = '';

        if (!$this->getPostValue('apiKey') || strlen($this->getPostValue('apiKey')) <= 0) {
            $errMsg .= "# Please enter your apiKey.<br/>\n";
        }

        if (!$this->getPostValue('baseCurrency')) {
            $errMsg .= "# Please select a base currency.<br/>\n";
        }

        if (false === isset($this->expirationTime) || $this->expirationTime < 15 || $this->expirationTime > 15)
            $this->expirationTime = 15;

        return $errMsg;
    }
}

/**
 * Options admin
 */
class GFJeebOptionsAdmin
{

    private $plugin;           // handle to the plugin object
    private $menuPage;         // slug for admin menu page
    private $scriptURL = '';
    private $frm;              // handle for the form validator

    /**
     * @param GFJeebPlugin $plugin handle to the plugin object
     * @param string $menuPage URL slug for this admin menu page
     */
    public function __construct($plugin, $menuPage, $scriptURL)
    {
        $this->plugin    = $plugin;
        $this->menuPage  = $menuPage;
        $this->scriptURL = $scriptURL;

        // wp_enqueue_script('jquery');
    }

    /**
     * process the admin request
     */
    public function process()
    {
        $this->frm = new GFJeebOptionsForm();

        if (false === isset($this->frm) || true === empty($this->frm)) {
            error_log('[ERROR] In GFJeebOptionsAdmin::process(): Could not create a new GFJeebOptionsForm object.');
            throw new \Exception('An error occurred in the Jeeb Payment plugin: Could not create a new GFJeebOptionsForm object.');
        }

        if ($this->frm->isFormPost()) {
            check_admin_referer('save', $this->menuPage . '_wpnonce');

            $errMsg = $this->frm->validate();

            if (true === empty($errMsg)) {
                $this->frm->updateFields();
                $this->plugin->showMessage(__('Options saved.'));
            } else {
                $this->frm->setTempValues();
                $this->plugin->showError($errMsg);
            }
        } else {

        }

        require GFJEEB_PLUGIN_ROOT . 'views/admin-settings.php';
    }
}
