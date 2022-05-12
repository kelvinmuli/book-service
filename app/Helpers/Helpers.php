<?php


namespace App\Helpers;

class Helpers
{
    public static function jsonResponse($errorState, $code, $data, $message, $errors = [])
    {
        $data ? $respData['data']  = $data : '';
        $errorState ? $respData['error'] = $errorState : $respData['success']=true;
        count($errors) > 0 ? $respData['errors'] = $errors : '';
        $message ? $respData['message'] = $message : '';
        return response()->json($respData, $code);
    }
}
