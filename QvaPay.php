<?php

/**
 * QvaPay Payment Card library
 */
class QvaPay
{

    // app_id / app?secret de QvaPay
    private $app_id = "";
    private $app_secret = "";
    private $base_url = "https://qvapay.com/api/v2/";

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
     * $remote_id = ID de la factura local para referencias del cliente
     * 
     */
    public function pay($number, $expire, $pin, $amount, $description, $remote_id)
    {
        // Payment data to array
        $payment_data = [$number, $expire, $pin];

        // Data to submit
        $data = [
            'payment_data' => $this->encrypt(implode(":", $payment_data)),
            'amount' => $amount,
            'description' => $description,
            'remote_id' => $remote_id
        ];

        $response = $this->curl_process("POST", "pay", $data);
        
        // Qvapay transaction uuid or "false" if not balance available
        return $response;
    }

    /**
     * Obtener el balance de su cuenta actualmente
     */
    public function balance()
    {
        $response = $this->curl_process("POST", "balance");
        return $response;
    }

    /**
     * Some easy implementation
     */
    private function encrypt($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_iv = $this->app_id;
        $secret_key = $this->app_secret;

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
    private function curl_process($method = "POST", $endpoint = "", $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url . $endpoint);

        // Merge auth data with submit parameters
        $data = http_build_query(array_merge($data, ['app_id' => $this->app_id, 'app_secret' => $this->app_secret]));

        if ($method == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;
    }
}
