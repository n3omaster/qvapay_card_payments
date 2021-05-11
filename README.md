# qvapay_card_payments
Librería para pagos con la tarjeta de débito QVAPAY en merchants asociados.

## Proceso de implemnetación de pagos con las tarjetas de débito
1. Crear una cuenta en https://qvapay.com
2. Crear una APP de desarrollo en https://qvapay.com/apps
3. Introducir Nombre, URL, Callback, Redirección ante pago completado, Redirección ante pago cancelado, Descripción del servicio, Logo.
4. Esperar aprobación de aun admin para habilitar el proceso de pagos mediante tarjetas débito QvaPay.
5. En su flujo de venta, aceptar pago con tarjeta de débito QvaPay, requerir: `número de tarjeta`, `vencimiento formato MM/YY`, `PIN`

Ejemplo funcional:

    <?php 
    include "QvaPay.php";

    // Crear el objeto QvaPay (app_id, app_secret)
    $qp = new QvaPay('XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');

    // Consultar balance en su cuenta QvaPay
    var_dump($qp->balance());

    // Crear una compra de $1.56
    $amount = 1.56;
    $description = "Perritos calientes";
    $invoice_id = 'MERCHANT_432312';

    // Manipular los datos del cliente
    $number = "2304589600005601";
    $expire = "04/28";
    $pin = "1234";

    // Pagar y obtener como respuesta dos posibles valores: el UUID de la transacción o `false`
    // (no posee fondos suficientes dicha tarjeta o está deshabilitada)
    $response = $qp->pay($number, $expire, $pin, $amount, $description, $invoice_id);

    // Respuesta, si es un UUID considerar el pago como completado, si es false no se procede.
    var_dump($response);

    // Chequear el balance nuevamente
    var_dump($qp->balance());