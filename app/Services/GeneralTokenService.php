<?php


namespace App\Services;


use Firebase\JWT\JWT;

class GeneralTokenService
{
    public function genToken(&$data, $exp, $key)
    {
        if ($exp) {
            $data['exp'] = time() + $exp;
        }
        $encoded     = JWT::encode($data, $key, 'HS256');
        return $encoded;
    }
}
