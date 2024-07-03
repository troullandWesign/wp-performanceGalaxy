<?php 

namespace WS_2020\inc\core;

abstract class AbstractController
{
    /**
     * Verify if ReCaptcha token exist in the $_POST object, then check validity
     */
    protected function verifyCaptcha()
    {
        if (!isset($_POST['g-recaptcha-response'])) wp_die();

        $request = curl_init('https://www.google.com/recaptcha/api/siteverify');

        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, [
            'secret'   => '',
            'response' => $_POST['g-recaptcha-response'],
        ]);

        $response = curl_exec($request);
        curl_close($request);

        if (!json_decode($response)->success) {
            wp_send_json(['status' => 'error', 'message' => __('Le captcha est invalide', 'ws-starter')]);
            wp_die();
        };
    }

    /**
     * @param string $method - Method name used for nonce token generation
     * @param string $token - Nonce token
     */
    protected function verifyRequestNonce(string $method, ?string $token = null)
    {
        if (is_null($token)) {
            if (!isset($_POST['_wpnonce'])) wp_die();
            $token = $_POST['_wpnonce'];
        }

        if (wp_verify_nonce($token, $method) !== 1) {
            wp_send_json(['status' => 'error', 'message' => __('Jeton de validation non valide', 'ws-starter')]);
            wp_die();
        };
    }

    /**
     * @param string $slug - Custom post type slug
     * @param array $callback - Callback method
     */
    protected function register_post_type(string $slug, array $callback)
    {
        register_post_type($slug, call_user_func($callback));
    }


    /**
     * @param string $slug - New taxonomy slug
     * @param array $post_types - Array of post types slug where taxonomy must be applied
     * @param array $callback - Callback method
     */
    protected function register_taxonomy(string $slug, array $post_types, array $callback)
    {
        register_taxonomy($slug, $post_types, call_user_func($callback));
    }


    /**
     * @param array $args - Custom field arguments
     */
    protected function register_field(array $args)
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group($args);
        }
    }


    /**
     * @param string $class - Class name where $method should be call
     * @param string $method - Action Method name
     */
    protected function handle_request(object $class, string $method)
    {
        add_action("wp_ajax_{$method}", [$class, $method]);
        add_action("wp_ajax_nopriv_{$method}", [$class, $method]);
    }
}