<?php

namespace lsat\otp\helper;

use ParagonIE\ConstantTime\Base32;
use yii\base\Security;
use OTPHP\HOTP;
use OTPHP\TOTP;

class OtpHelper
{

    /**
     * @param int $length
     * @return string
     */
    public static function generateSecret($length = 20)
    {
        $security = new Security();
        $full = Base32::encode($security->generateRandomString($length));
        return substr($full, 0, $length);
    }

    /**
     * @param string $label
     * @param int $digits
     * @param string $digest
     * @param int $interval
     * @param string $issuer
     * @return TOTP
     */
    public static function getTotp($label = '', $digits = 6, $digest = 'sha1', $interval = 30, $issuer='')
    {
//        $totp = new TOTP($label, null, $interval, $digest, $digits);
        $totp = TOTP::create(null, $interval,  $digest, $digits);
        if(!empty($issuer)) {
            $totp->setIssuer($issuer);
        }
        if(!empty($label)) {
            $totp->setLabel($label);
        }

        return $totp;
    }

    /**
     * @param string $label
     * @param int $digits
     * @param string $digest
     * @param int $counter
     * @param string $issuer
     * @return HOTP
     */
    public static function getHotp($label = '', $digits = 6, $digest = 'sha1', $counter = 0, $issuer='')
    {
//        $hotp = new HOTP($label, null, $counter, $digest, $digits);
        $hotp = HOTP::create(null,$counter,  $digest, $digits);
        if(!empty($issuer)) {
            $hotp->setIssuer($issuer);
        }
        if(!empty($label)) {
            $hotp->setLabel($label);
        }

        return $hotp;
    }
}