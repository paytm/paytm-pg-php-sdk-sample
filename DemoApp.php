<?php
    /**
     * Copyright (C) 2019 Paytm.
     */

    require_once('vendor/autoload.php');
    require_once('SampleData.php');


    /** Enable error reporting */
    error_reporting(E_ALL);
    ini_set('display_errors', 'on');

    /** Creating SDK level constant */
    define('PROJECT', realpath((__DIR__) . '/vendor/paytm/paytm-pg'));

    use paytmpg\merchant\models\PaymentDetail\PaymentDetailBuilder;
    use paytmpg\merchant\models\PaymentStatusDetail\PaymentStatusDetailBuilder;
    use paytmpg\merchant\models\RefundDetail\RefundDetailBuilder;
    use paytmpg\merchant\models\RefundStatusDetail\RefundStatusDetailBuilder;
    use paytmpg\pg\constants\LibraryConstants;
    use paytmpg\pg\constants\MerchantProperties;
    use paytmpg\pg\process\Payment;
    use paytmpg\pg\process\Refund;
    use paytmpg\pg\utils\LoggingUtil;
    use Psr\Log\LogLevel;

    /**
     * This class has example of how to initialize and make api calls to hit paytm servers.
     * Merchant can change this as per his requirements and make api calls
     *
     * Class DemoApp
     * @package Paytm\merchant\app
     */
    class DemoApp
    {
        /**
         * 1. Merchants who only want to use PG for accepting payments
         *
         * This method creates a PaymentDetail object having all the required parameters and
         * calls SDK's createTxnToken method to get the InitiateTransactionResponse object
         * having token which will be used in future transactions such as getting payment options
         *
         * @throws Exception
         */
        public static function createTxnTokenwithRequiredParams()
        {
            try {
                // Channel through which call initiated [enum (APP, WEB, WAP, SYSTEM)]
                $channelId = SampleData::getEChannelId();

                // Unique order for each order request
                $orderId = SampleData::getOrderId();

                // Transaction amount and the currency value
                $txnAmount = SampleData::getMoney();

                // cid : <Mandatory> user unique identification with respect to merchant
                $userInfo = SampleData::getUserInfo();

                /* paymentDetail object will have all the information required to make initiate transaction call */
                $paymentDetailBuilder = new PaymentDetailBuilder($channelId, $orderId, $txnAmount, $userInfo);
                $paymentDetail = $paymentDetailBuilder->build();

                /*
                * Making call to SDK method which will return a InitiateTransactionResponse object that will contain
                * a token which can be used for validation purpose for future transactions
                */
                $response = Payment::createTxnToken($paymentDetail);

                echo PHP_EOL."Response from SDK on calling createTxnTokenwithRequiredParams".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in createTxnTokenwithRequiredParams".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }


        /**
         * 2. Merchants who want to use PG with Wallet and configure paymentmodes for accepting payments with paytmSSOTokenS
         *
         * This method create a PaymentDetail object with required parameters, payment modes and PaytmSSOToken and
         * calls SDK's createTxnToken method to get the InitiateTransactionResponse object having
         * token which will be used in future transactions such as getting payment options
         *
         * Merchant can only use payment modes for this transaction which he will
         * specify in this call if these payment modes are applicable on the merchant
         *
         * @throws Exception
         */
        public static function createTxnTokenwithPaytmSSotokenAndPaymentMode()
        {
            try {
                // Channel through which call initiated [enum (APP, WEB, WAP, SYSTEM)]
                $channelId = SampleData::getEChannelId();

                // Unique order for each order request
                $orderId = SampleData::getOrderId();

                // Transaction amount and the currency value
                $txnAmount = SampleData::getMoney();

                // cid : <Mandatory> user unique identification with respect to merchant
                $userInfo = SampleData::getUserInfo();

                // Paytm Token for a user
                $paytmSsoToken = SampleData::getPaytmSsoToken();

                /*
                 * list of the payment modes which needs to be enabled.
                 * If the value provided then only listed payment modes are available for transaction
                 */
                $enablePaymentMode = SampleData::getEnablePaymentModes();

                /*
                 * list of the payment modes which need to be disabled.
                 * If the value provided then all the listed payment modes are unavailable for transaction
                 */
                $disablePaymentMode = SampleData::getDisablePaymentModes();

                /* paymentDetail object will have all the information required to make initiate transaction call */
                $paymentDetailBuilder = new PaymentDetailBuilder($channelId, $orderId, $txnAmount, $userInfo);
                $paymentDetail = $paymentDetailBuilder->setPaytmSsoToken($paytmSsoToken)->setEnablePaymentMode($enablePaymentMode)
                    ->setDisablePaymentMode($disablePaymentMode)->build();

                /*
                * Making call to SDK method which will return a InitiateTransactionResponse object that will contain
                * a token which can be used for validation purpose for future transactions
                */
                $response = Payment::createTxnToken($paymentDetail);

                echo PHP_EOL."Response from SDK on calling createTxnTokenwithPaytmSSotokenAndPaymentMode".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in createTxnTokenwithPaytmSSotokenAndPaymentMode".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }

        /**
         * 3. Merchants who want to use PG with Wallet, configure paymentmodes, send order details
         *
         * This method creates a PaymentDetail object having all the required parameters and
         * calls SDK's createTxnToken method to get the InitiateTransactionResponse object
         * having token which will be used in future transactions such as getting payment options
         *
         * @throws Exception
         */
        public static function createTxnTokenwithAllParams()
        {
            try {
                // Channel through which call initiated [enum (APP, WEB, WAP, SYSTEM)]
                $channelId = SampleData::getEChannelId();

                // Unique order for each order request
                $orderId = SampleData::getOrderId();

                // Transaction amount and the currency value
                $txnAmount = SampleData::getMoney();

                // cid : <Mandatory> user unique identification with respect to merchant
                $userInfo = SampleData::getUserInfo();

                // Paytm Token for a user
                $paytmSsoToken = SampleData::getPaytmSsoToken();

                /*
                 * list of the payment modes which needs to be enabled.
                 * If the value provided then only listed payment modes are available for transaction
                 */
                $enablePaymentMode = SampleData::getEnablePaymentModes();

                /*
                 * list of the payment modes which need to be disabled.
                 * If the value provided then all the listed payment modes are unavailable for transaction
                 */
                $disablePaymentMode = SampleData::getDisablePaymentModes();

                // This contain the Goods info for an order
                $goods = SampleData::getGoodsInfo();

                // This contain the shipping info for an order
                $shippingInfo = SampleData::getShippingInfo();

                // promode that user is using for the payment
                $promoCode = SampleData::getPromocode();

                // This contain the set of parameters for some additional information
                $extendInfo = SampleData::getExtendInfo();

                $emiOption = SampleData::getEmiOption();

                $cardTokenRequired = SampleData::getCardTokenRequired();

                /* paymentDetail object will have all the information required to make initiate transaction call */
                $paymentDetailBuilder = new PaymentDetailBuilder($channelId, $orderId, $txnAmount, $userInfo);
                $paymentDetail = $paymentDetailBuilder->setPaytmSsoToken($paytmSsoToken)->setEnablePaymentMode($enablePaymentMode)
                    ->setDisablePaymentMode($disablePaymentMode)->setGoods($goods)->setShippingInfo($shippingInfo)
                    ->setPromoCode($promoCode)->setExtendInfo($extendInfo)->setEmiOption($emiOption)
                    ->setCardTokenRequired($cardTokenRequired)->build();

                /*
                * Making call to SDK method which will return a InitiateTransactionResponse object that will contain
                * a token which can be used for validation purpose for future transactions
                */
                $response = Payment::createTxnToken($paymentDetail);

                echo PHP_EOL."Response from SDK on calling createTxnTokenwithAllParams".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in createTxnTokenwithAllParams".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }


        /**
         * 4. Merchants who want to get TransactionStatus
         *
         * This method is used to get Payment status after payment is completed. It requires OrderId ID as a mandatory parameter.
         * This will return the status for the specific OrderId ID.
         *
         * @throws Exception
         */
        public static function getPaymentStatus()
        {
            try {
                /** Unique order for each order request */
                $orderId = "xxxxxxxx";
                $readTimeout = 80000;

                /* PaymentStatusDetail object will have all the information required to make getPaymentStatus call */
                $paymentStatusDetailBuilder = new PaymentStatusDetailBuilder($orderId);
                $paymentStatusDetail = $paymentStatusDetailBuilder->setReadTimeout($readTimeout)->build();

                /**
                 * Making call to SDK method which will return a NativeMerchantStatusResponse object that will contain
                 * the transaction status response corresponding to passed order Id
                 */
                $response = Payment::getPaymentStatus($paymentStatusDetail);

                echo PHP_EOL."Response from SDK on calling getPaymentStatus".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in getPaymentStatus".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }

        /**
         * 5. Merchants who want to do refund
         *
         * This method requires Transaction ID, Transaction Type and Refund Amount as mandatory parameters.
         * This will initiate the refund for the specific Transaction ID and returns AsyncRefundResponse object.
         *
         * @throws Exception
         */
        public static function initiateRefund()
        {
            try {
                /** Unique order for each order request */
                $orderId = "xxxxxxxx";

                /** REF ID returned in Paytm\pg\process\Refund call */
                $refId = "xxxxxxxx";

                /** Transaction ID returned in Paytm\pg\process\Refund Api */
                $txnId = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

                /** Paytm\pg\process\Refund Amount to be refunded (should not be greater than the Amount paid in the Transaction) */
                $refundAmount = "1";
                $readTimeout = 80000;

                /** Subwallet amount used in Paytm\pg\process\Refund Api */
                $subWalletAmount = SampleData::getSubWalletAmount();

                /** Extra params map used in Paytm\pg\process\Refund Api */
                $extraParamsMap = SampleData::getExtraParamsMap();

                /** Paytm\pg\process\Refund object will have all the information required to make refund call */
                $refund = new RefundDetailBuilder($orderId, $refId, $txnId, $refundAmount);
                $refundDetail = $refund->setReadTimeout($readTimeout)
                    ->setSubwalletAmount($subWalletAmount)
                    ->setExtraParamsMap($extraParamsMap)
                    ->build();

                /**
                 * Making call to SDK method which will return a AsyncRefundResponse object that will contain
                 * response corresponding to the transaction Id
                 */
                $response = Refund::initiateRefund($refundDetail);

                echo PHP_EOL."Response from SDK on calling initiateRefund".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in initiateRefund".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }

        /**
         * 6. Merchants who want to get Refund Status
         *
         * This method is used get Refund Status after payment. It requires OrderId ID and refId as mandatory parameters.
         * This will return the NativeRefundStatusResponse object having status for the specific refund order.
         *
         * @throws Exception
         */
        public static function getRefundStatus()
        {
            try {
                /** Unique order for each order request */
                $orderId = "xxxx";
                /** Unique ref id for each refund request */
                $refId = "xxxxxxxx";

                $readTimeout = 8000;

                /**
                 * Paytm\merchant\models\RefundStatusDetail object will have all the information required to make
                 * getRefundStatus call
                 */
                $refundStatusDetailBuilder = new RefundStatusDetailBuilder($orderId, $refId);
                $refundStatusDetail = $refundStatusDetailBuilder->setReadTimeout($readTimeout)->build();

                /**
                 * Making call to SDK method which will return the
                 * Paytm\merchant\models\SDKResponse(Paytm\pg\request\NativeRefundStatusRequest) that holds Paytm\pg\process\Refund Status of any
                 * previous Paytm\pg\process\Refund.
                 */
                $response = Refund::getRefundStatus($refundStatusDetail);

                echo PHP_EOL."Response from SDK on calling getRefundStatus".PHP_EOL;
                print_r($response->getResponseObject());

            } catch (Exception $e) {
                echo "Exception caught in getRefundStatus".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }

        /**
         * This method is used to initialize parameters required to make call to any of the Api's
         *
         * @throws Exception
         */
        public static function setInitialParameters()
        {
            try {
                $env = LibraryConstants::STAGING_ENVIRONMENT;
                // Following mid and key is for create txn API
                $mid = "xxxxxxxxxxxxxxxxxxxx";
                $key = "xxxxxxxxxxxxxxxx";

                $website = "WEBSTAGING";

                /** Initialize mandatory Parameters */
                MerchantProperties::initialize($env, $mid, $key, $website);

                /** Setting timeout for connection i.e. Connection Timeout */
                MerchantProperties::setConnectionTimeout(5000);

            } catch (Exception $e) {
                echo "Exception caught in setInitialParameters".PHP_EOL;
                print_r($e->getMessage());
                LoggingUtil::addLog(LogLevel::INFO, __CLASS__, print_r($e->getMessage(), true));
            }
        }

    }

    DemoApp::setInitialParameters();

    // Example using only mandatory fields
    DemoApp::createTxnTokenwithRequiredParams();

    // Example using mandatory and enabling and disabling payment modes fields
    DemoApp::createTxnTokenwithPaytmSSotokenAndPaymentMode();

    // Example using all fields
    DemoApp::createTxnTokenwithAllParams();

    // Example of get payment status
    DemoApp::getPaymentStatus();

    // Example of refund
    DemoApp::initiateRefund();

    // Example of get refund status
    DemoApp::getRefundStatus();
