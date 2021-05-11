<?php

/**
 * QvaPay Payment Card library
 */
class QvaPay
{

    // app_id / app?secret de QvaPay
    private $app_id = "xxxxxxxxxxxxxxxxxx";
    private $app_secret = "xxxxxxxxxxxxxxxxxx";

    /**
     * Constructor
     */
    public function __construct($app_id, $app_secret)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
    }

    /**
     * Pagar por una facetura en QvaPay
     * 
     * $amount = Cantidad de dinero a cobrar
     * $description = Descripción asociada a esta compra (se recomienda reflejar alguna URL para revisar estado de la compra)
     * $invoice_id = ID de la factura local para referencias
     */
    public function pay($amount, $description, $invoice_id) {

    }

    /**
     * Obtener el balance de su cuenta actualmente
     */
    public function balance() {

    }

    /**
     * Some easy implementation
     */
    private function encrypt($string, $app_id, $app_secret)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_iv = $app_id;
        $secret_key = $app_secret;

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        // Now encrypt
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);

        return $output;
    }

    /**
     * Curl call to QvaPay
     */
    private function curl_process($method, $data) {

    }
}
