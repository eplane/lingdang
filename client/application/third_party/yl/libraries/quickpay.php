<?php

const QUICKPAY_PAY_SEVICE = 1;
const QUICKPAY_NOTIFY_SEVICE = 2;

class quickpay
{
    static $GATE_WAY = "https://unionpaysecure.com/api/Pay.action";

    static $SIGN_TYPE = "MD5";
    // 商户密钥
    static $SECURITY_KEY = "SJERG7W34T578W34RWC7834TR972T4R2CGR";

    static $FILE_CHARSET = "UTF-8";

    static $ARR_SP_CONF = array(
        'version' => '1.0.0',
        'charset' => 'UTF-8',
        'merId' => '308221080110001',   //商户ID
        'merAbbr' => 'XXXX有限公司',
        'acqCode' => '',
        'merCode' => '',
    );

    static $ARR_PAY_PARAMETER = array(
        "transType",
        "origQid",
        "commodityUrl",
        "commodityName",
        "commodityUnitPrice",
        "commodityQuantity",
        "commodityDiscount",
        "transferFee",
        "orderNumber",
        "orderAmount",
        "orderCurrency",
        "orderTime",
        "customerIp",
        "customerName",
        "defaultPayType",
        "defaultBankNumber",
        "transTimeout",
        "frontEndUrl",
        "backEndUrl",
        "merReserved",
    );

    static $ARR_NOTIFY_PARAMETER = array(
        "charset",
        "cupReserved",
        "exchangeRate",
        "exchangeDate",
        "merAbbr",
        "merId",
        "orderAmount",
        "orderCurrency",
        "orderNumber",
        "qid",
        "respCode",
        "respMsg",
        "respTime",
        "settleAmount",
        "settleCurrency",
        "settleDate",
        "transType",
        "version",
        "traceNumber",
        "traceTime",
    );

    private $gateway;
    private $arr_parameter;
    private $signature;
    private $notifySignature;
    private $notifySignMethod;

    private $default;

    function __construct($p)
    {
        $arr_parameter = $p['config'];
        $service = $p['service'];

        $CI =& get_instance();
        $CI->load->add_package_path(APPPATH.'third_party/yl/');
        $CI->load->config('yl');

        $this->default = $CI->config->item('yl');

        $arr_parameter = array_merge($arr_parameter, $this->default);

        $check_arr_parameter = array();

        if ($service == QUICKPAY_PAY_SEVICE)
        {
            $check_arr_parameter = quickpay::$ARR_PAY_PARAMETER;

            foreach (quickpay::$ARR_SP_CONF as $conf_item_key => $conf_item_value)
            {
                $this->arr_parameter[$conf_item_key] = $conf_item_value;
            }
        }
        else if ($service == QUICKPAY_NOTIFY_SEVICE)
        {
            $check_arr_parameter = quickpay::$ARR_NOTIFY_PARAMETER;

            if (!isset($arr_parameter['signature']) || !isset($arr_parameter['signMethod']))
            {
                die("notify but without signature or signMethod");
            }

            $this->notifySignature = $arr_parameter['signature'];
            $this->notifySignMethod = $arr_parameter['signMethod'];

            unset($arr_parameter['signature']);
            unset($arr_parameter['signMethod']);
        }
        else
        {
            die("unsuported service : " . $service);
        }

        foreach ($check_arr_parameter as $key)
        {
            if (!isset($arr_parameter[$key]))
            {
                die("parameter [" . $key . "] is not set in input parameters");
            }

            $this->arr_parameter[$key] = $arr_parameter[$key];
        }

        $this->sign_type = quickpay::$SIGN_TYPE;
        $this->gateway = quickpay::$GATE_WAY;

        $sort_array = $this->arr_parameter;

        ksort($sort_array);
        reset($sort_array);

        $sign_str = "";

        while (list ($key, $val) = each($sort_array))
        {
            $sign_str .= $key . "=" . $this->charset_encode($val, $this->arr_parameter['charset'], quickpay::$FILE_CHARSET) . "&";
        }

        if (isset($arr_parameter['bank']))
        {
            $this->arr_parameter['bank'] = $arr_parameter['bank'];
        }

        $this->signature = md5($sign_str . md5(quickpay::$SECURITY_KEY));
    }

    function verify_notify()
    {
        if ($this->notifySignMethod != 'MD5')
        {
            die("unsuported signMethod: " . $this->notifySignMethod);
        }
        if ($this->signature != $this->notifySignature)
        {
            return false;
        }

        return true;
    }

    function pay()
    {
        $html = '<form id="pay_form" name="pay_form" action="' . $this->gateway . '" method="post" target="_blank">';

        foreach ($this->arr_parameter as $key => $value)
        {
            $html .= '<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $this->charset_encode($value, $this->arr_parameter['charset'], quickpay::$FILE_CHARSET) . '" />';
        }

        $html .= '<input type="hidden" name="signature" id="signature" value="' . $this->signature . '">
            <input type="hidden" name="signMethod" id="signMethod" value="' . quickpay::$SIGN_TYPE . '" />
            <input type="submit" >
            </form>';

        return $html;
    }

    function charset_encode($input, $output_charset, $input_charset = "UTF-8")
    {
        if (!isset($output_charset))
        {
            $output_charset = $this->arr_parameter['charset'];
        }

        if ($input_charset == $output_charset || $input == null)
        {
            return $input;
        }

        if (function_exists("mb_convert_encoding"))
        {
            return mb_convert_encoding($input, $output_charset, $input_charset);
        }

        if (function_exists("iconv"))
        {
            return iconv($input_charset, $output_charset, $input);
        }

        die("sorry, you have no libs support for charset convert.");
    }


    /** 获得客户端IP
     * @return string
     */
    function getIp()
    {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR'];
        else $ip = "unknown";
        return ($ip);
    }
}