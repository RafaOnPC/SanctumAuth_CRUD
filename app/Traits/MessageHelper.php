<?php

namespace App\Traits;

trait MessageHelper{
    function jsend_fail($data = null, $code = 400, $message = null)
    {
        return response()->json([
            'status' => 'fail',
            'data' => $data,
            'code' => $code,
            'message' => $message
        ]);
    }
}

