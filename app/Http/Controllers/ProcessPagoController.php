<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProcessPagoController extends Controller
{
    //

    public function processPago(Request $request)
    {
      try {
        // Agrega credenciales
        \MercadoPago\SDK::setAccessToken(env('PROD_ACCESS_TOKEN'));
        \MercadoPago\SDK::setIntegratorId(env('INTEGRATOR_ID'));

        // Crea un objeto de preferencia
        $preference = new \MercadoPago\Preference();

        $preference->back_urls = array(
            "success" => route('pago_exitoso'),
            "failure" => route('pago_fallo'),
            "pending" => route('pago_pendiente')
        );
        $preference->auto_return = "approved";
        $preference->external_reference = "info@uniting.com.ar";
        $preference->notification_url  = route('webhooks_mercadopago');

        //Exclusion de medio de pago
        $preference->payment_methods = array(
          "excluded_payment_methods" => array(
            array("id" => "amex")
          ),
          "excluded_payment_types" => array(
            array("id" => "atm")
          ),
          "installments" => 6
        );

        //Datos del pagador
        $payer = new \MercadoPago\Payer();
        $payer->name = "Lalo";
        $payer->surname = "Landa";
        $payer->email = "test_user_63274575@testuser.com";
        $payer->phone = array(
          "area_code" => "11",
          "number" => "22223333"
        );
        $payer->address = array(
          "street_name" => "False",
          "street_number" => 123,
          "zip_code" => "1111"
        );

        // Crea un ítem en la preferencia
        $item = new \MercadoPago\Item();
        $item->id = "1234";
        $item->title = $request->product_name;
        $item->description = "Dispositivo móvil de Tienda e-commerce";
        $item->picture_url = asset(substr($request->product_url,1));
        $item->quantity = 1;
        $item->currency_id = "ARS";
        $item->unit_price = (float) $request->product_price;
        $preference->items = array($item);
        $preference->save();

        return redirect($preference->init_point);
      } catch (Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
      }
    }

    public function pago_exitoso(Request $request)
    {
        $request = $request->all();
        return view('pago_exitoso', compact('request'));
    }

    public function webhooks_mercadopago(Request $request)
    {
        Log::debug("DEBUG: ".json_encode($request));
        return response('OK');
    }
}
