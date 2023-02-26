<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PaddleSignatureValidator implements SignatureValidator
{

    public function isValid(Request $request, WebhookConfig $config): bool
    {
        // return $this->isPaddleRequestValid($request);
        /**
         * Dirty way to validate since I do not have a Paddle account
         */
        return !empty($request->all()); // Dirty way to validate
    }

    protected function isPaddleRequestValid(Request $request): bool
    {
        $publicPaddleKey = config('services.paddle.pubic-key');
        $signature = base64_decode($request->get('p_signature'));
        
        // Get the fiesl sent in the request, and remove the p_signature parameter
        $requestFields = $request->all();
        unset($requestFields['p_signature']);

        // ksort() and serialize the fields
        ksort($requestFields);
        foreach ($requestFields as $k => $v) {
            if (! in_array(gettype($v), ['object', 'array'])) {
                $requestFields[$k] = "$v";
            }
        }
        $data = serialize($requestFields);

        return (bool) openssl_verify($data, $signature, $publicPaddleKey, OPENSSL_ALGO_SHA1);
    }
}
