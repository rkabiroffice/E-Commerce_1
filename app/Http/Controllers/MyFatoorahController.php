<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use MyFatoorah\Library\API\MyFatoorahSupplier;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentEmbedded;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use MyFatoorah\Library\MyFatoorah;

class MyFatoorahController extends Controller {

    /**
     * MyFatoorah Config Array
     *
     * @var array
     */
    public $mfConfig = [];

    /**
     * Store Config Array
     *
     * @var array
     */
    private $params = [];

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Initiate MyFatoorah Configuration
     */
    public function __construct() {
        $this->params = require base_path('config/myfatoorah.php');

        $this->mfConfig = [
            'apiKey'    => $this->params['api_key'] ?? '',
            'isTest'    => $this->params['is_test'] ?? true,
            'vcCode'    => $this->params['vc_code'] ?? 'KWT',
            'loggerObj' => storage_path('logs/myfatoorah.log')
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to redirect the system to MyFatoorah invoice URL
     * Provide the index method with the order id and (payment method id or session id)
     *
     * @return RedirectResponse|JsonResponse
     */
    public function index() {
        try {
            //For example: pmid=0 for MyFatoorah invoice or pmid=1 for Knet in test mode
            $paymentId = request('pmid') ?: 0;
            $sessionId = request('sid') ?: null;

            $orderId  = request('oid') ?: 147;
            $curlData = $this->getPayLoadData($orderId);

            $mfObj   = new MyFatoorahPayment($this->mfConfig);
            $payment = $mfObj->getInvoiceURL($curlData, $paymentId, $orderId, $sessionId);

            return redirect($payment['invoiceURL']);
        } catch (Exception $ex) {
            $exMessage = $this->mfTransMsg($ex->getMessage());
            return response()->json(['IsSuccess' => false, 'Message' => $exMessage]);
        }
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Example on how to map order data to MyFatoorah
     * You can get the data using the order object in your system
     *
     * @return array
     */
    public function getPayLoadData($orderId) {
        return [];
    }

    /**
     * Example on how to map order data to MyFatoorah
     *
     * @param string $description
     *
     * @return string
     */
    public function mfTransMsg($description) {
        return $description;
    }

    public function process() {}

    public function callback() {}

    public function checkout() {}

    public function webhook() {}
}
