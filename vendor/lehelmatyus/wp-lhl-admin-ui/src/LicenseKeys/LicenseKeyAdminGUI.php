<?php

namespace WpLHLAdminUi\LicenseKeys;

use WpLHLAdminUi\Forms\AdminForm;
use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;
use WpLHLAdminUi\Utility\LHLLinkUtility;
use WpLHLAdminUi\Models\LHLLinkModel;

class LicenseKeyAdminGUI {

    private LicenseKeyHandler $license_key_handler;
    private bool $license_key_valid;
    private LHLLinkModel $link_to_license;

    private string $namespace;
    private string $obtain_text = "Don't have a license key yet? Get one here:";
    private string $settings_fields = '';
    private string $do_settings_sections = '';
    private $uniqKey = "";

    public function __construct(
        bool $license_key_valid,
        LHLLinkModel $link_to_license,
        string $obtain_text,
        string $namespace,
        string $settings_fields = '',
        string $do_settings_sections = ''
    ) {

        $this->license_key_valid = $license_key_valid;
        $this->link_to_license = $link_to_license;
        $this->settings_fields = $settings_fields;
        $this->do_settings_sections = $do_settings_sections;
        $this->namespace = $namespace;
        $this->obtain_text = $obtain_text;
    }

    /** --------------------------------------------------------------------
     * General options - License
     * ---------------------------------------------------------------------
     */

    public function __license_card_display() {

        $active_class = '';
        if (!empty($this->license_key_valid)) {
            $active_class .= " lbrty-license-box--activated";
        } else {
            $active_class .= " open";
        }

?>
        <div class="lbrty-license-box <?php echo $active_class; ?>">

            <h2 class="lbrty-license-box__title"><?php _e("License: ", $this->namespace) ?>

                <?php
                echo '<span class="activated_label-active" style="color: #008000; font-style: italic;">Active</span>';
                echo '<span class="activated_label-inactive" style="color: #800000; font-style: italic;">Inactive</span>';
                ?>

                <div class="lbrty-license-box__toggle_holder">
                    <?php if (!empty($this->license_key_valid)) { ?>
                        <a class="lbrty-view-key-button lbrty-view-key-button--open" onclick="lbrtyLicenseBoxToggle(event,this)" style="font-size:14px;" href="#"><?php _e("View License", $this->namespace) ?></a>
                        <a class="lbrty-view-key-button lbrty-view-key-button--close" onclick="lbrtyLicenseBoxToggle(event, this)" style="font-size:14px;" href="#"><?php _e("Close", $this->namespace) ?></a>
                    <?php } ?>

                </div>

            </h2>

            <div class="llbrty-license-box__body">
                <div class="llbrty-license-box__body_inner">
                    <div class="lbrty-card-activate lbrty-card-sub-item">
                        <?php
                        $this->lbrty_public_key_renders();
                        ?>
                    </div>

                    <div class="lbrty-card-sub-sub-item lbrty-card-create-account">
                        <div class="lhl__pt_20">
                            <span><?php echo $this->obtain_text; ?></span>
                        </div>
                        <div class="lhl__pt_10">
                            <?php
                            LHLLinkUtility::render_link_model($this->link_to_license);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    <?php
    }

    public function lbrty_public_key_renders() {
        $readonly = '';
        $license_key = "";
        if ($this->license_key_valid) {
            $readonly = "readonly";
            $license_key = "************************************";
        }
    ?>
        <div>
            <input type='text' class="regular-text" name='lbrty_settings_general_options[lbrty_license_key]' value='<?php echo $license_key; ?>' <?php echo $readonly; ?> placeholder="Enter your lciense key">

            <?php if (!$this->license_key_valid) : ?>
                <button id="lbrty-license-box-activate-key" class="lbrty_script_button button" href="#" onclick="lbrtyLicenseBoxActivateKey(event)">Activate Key</button>
                <div class="lbrty-license-box__working_msg">
                    <span class="lbrty_button_loader">
                        <img class="load_spinner" src="/wp-includes/images/spinner.gif" />
                    </span>
                    <span class="lbrty-license-box-activate-key__msg lbrty-license-box__working_msg_txt" data-waitmsg="Activating Script running..."></span>
                </div>
            <?php else : ?>
                <button id="lbrty-license-box-deactivate-key" class="lbrty_script_button button" href="#" onclick="lbrtyLicenseBoxDeactivateKey(event)">Deactivate Key</button>
                <div class="lbrty-license-box__working_msg">
                    <span class="lbrty_button_loader">
                        <img class="load_spinner" src="/wp-includes/images/spinner.gif" />
                    </span>
                    <span class="lbrty-license-box-deactivate-key__msg lbrty-license-box__working_msg_txt" data-waitmsg="Deactivating Script running..."></span>
                </div>
            <?php endif; ?>

        </div>
<?php
    }

    public static function wp_enqueue_license_js() {
        $uniqKey = "";
        wp_enqueue_script('wp-lhl-admin-ui-styles' . esc_attr($uniqKey),  plugin_dir_url(dirname(__FILE__)) . '../js/LicenseKeyAdminGUI.js');
    }
}



/**
 * Example Usage
 */
/**
 * $link_to_license = new LHLLinkModel(
 *    __('Get a License Key.', 'tgen-template-generator'),
 *    'https://lehelmatyus.com/tgen-template-generator-for-tnew?utm_source=plugin&utm_medium=license&utm_campaign=tgen-template-generator-for-tnew',
 * );

 * $licenes_gui = new LicenseKeyAdminGUI(
 *    $this->license_key_valid,
 *    $link_to_license,
 *    __("Don't have a license? Obtain one now to unlock all features and receive full support.", 'tgen-template-generator'),
 *    'tgen-template-generator'
 * );
 * $licenes_gui->__license_card_display();
 * 
 * 
 * 
 */
