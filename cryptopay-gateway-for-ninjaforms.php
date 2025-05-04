<?php

declare(strict_types=1);

defined('ABSPATH') || exit;

// @phpcs:disable PSR1.Files.SideEffects
// @phpcs:disable PSR12.Files.FileHeader
// @phpcs:disable Generic.Files.InlineHTML
// @phpcs:disable Generic.Files.LineLength

/**
 * Plugin Name: CryptoPay Gateway for Ninja Forms
 * Version:     1.0.2
 * Plugin URI:  https://beycanpress.com/cryptopay/
 * Description: Adds Cryptocurrency payment gateway (CryptoPay) for Ninja Forms.
 * Author:      BeycanPress LLC
 * Author URI:  https://beycanpress.com
 * License:     GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: cryptopay-gateway-for-ninjaforms
 * Tags: Bitcoin, Ethereum, Crypto, Payment, Ninja Forms
 * Requires at least: 5.0
 * Tested up to: 6.7
 * Requires PHP: 8.1
*/

// Autoload
require_once __DIR__ . '/vendor/autoload.php';

define('NINJA_FORMS_CRYPTOPAY_FILE', __FILE__);
define('NINJA_FORMS_CRYPTOPAY_VERSION', '1.0.2');
define('NINJA_FORMS_CRYPTOPAY_KEY', basename(__DIR__));
define('NINJA_FORMS_CRYPTOPAY_URL', plugin_dir_url(__FILE__));
define('NINJA_FORMS_CRYPTOPAY_DIR', plugin_dir_path(__FILE__));
define('NINJA_FORMS_CRYPTOPAY_SLUG', plugin_basename(__FILE__));

use BeycanPress\CryptoPay\Integrator\Helpers;

/**
 * @return void
 */
function nfCryptoPayRegisterModels(): void
{
    Helpers::registerModel(BeycanPress\CryptoPay\NinjaForms\Models\TransactionsPro::class);
    Helpers::registerLiteModel(BeycanPress\CryptoPay\NinjaForms\Models\TransactionsLite::class);
}

nfCryptoPayRegisterModels();

add_action('plugins_loaded', function (): void {
    nfCryptoPayRegisterModels();

    if (!class_exists('Ninja_Forms')) {
        Helpers::requirePluginMessage('Ninja Forms', admin_url('plugin-install.php?s=Ninja%2520Forms&tab=search&type=term'));
    } elseif (Helpers::bothExists()) {
        new BeycanPress\CryptoPay\NinjaForms\Loader();
    } else {
        Helpers::requireCryptoPayMessage('Ninja Forms');
    }
});
