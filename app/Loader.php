<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPay\NinjaForms;

use BeycanPress\CryptoPay\Integrator\Hook;
use BeycanPress\CryptoPay\Integrator\Helpers;
use BeycanPress\CryptoPay\Integrator\Session;

class Loader
{
    /**
     * Loader constructor.
     */
    public function __construct()
    {
        Helpers::registerIntegration('ninjaforms');

        add_action('init', function (): void {
            Helpers::createTransactionPage(
                esc_html__('Ninja Forms Transactions', 'cryptopay-gateway-for-ninjaforms'),
                'ninjaforms',
                9,
                [],
                ['orderId']
            );
        });

        Hook::addAction('payment_finished_ninjaforms', [$this, 'paymentFinished']);
        Hook::addFilter('edit_config_data_ninjaforms', [$this, 'disableReminderEmail']);
        Hook::addFilter('payment_redirect_urls_ninjaforms', [$this, 'paymentRedirectUrls']);

        add_filter('ninja_forms_register_fields', [$this, 'registerFields']);
        add_filter('ninja_forms_field_template_file_paths', [$this, 'addTemplatePath']);
        //add_filter('ninja_forms_register_payment_gateways', [$this, 'registerGateways']);
    }

    /**
     * @param object $data
     * @return object
     */
    public function disableReminderEmail(object $data): object
    {
        return $data->disableReminderEmail();
    }

    /**
     * @param object $data
     * @return void
     */
    public function paymentFinished(object $data): void
    {
        Session::set('ninjaforms_transaction_hash', $data->getHash());
    }

    /**
     * @param object $data
     * @return array<string>
     */
    public function paymentRedirectUrls(object $data): array
    {
        return [
            'success' => '#success',
            'failed' => '#failed'
        ];
    }

    /**
     * @param array<mixed> $fields
     * @return array<mixed>
     */
    public function registerFields(array $fields): array
    {
        $field = new Field();
        /** @disregard */
        $fields[$field->get_name()] = $field;

        return $fields;
    }

    /**
     * @param array<mixed> $paths
     * @return array<mixed>
     */
    public function addTemplatePath(array $paths): array
    {
        $paths[] = NINJA_FORMS_CRYPTOPAY_DIR . 'views/';

        return $paths;
    }

    /**
     * @param array<mixed> $gateways
     * @return array<mixed>
     */
    public function registerGateways(array $gateways): array
    {
        $gateway = new Gateway();
        /** @disregard */
        $gateways[$gateway->get_slug()] = $gateway;

        return $gateways;
    }
}
