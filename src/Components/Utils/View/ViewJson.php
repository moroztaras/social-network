<?php

namespace App\Components\Utils\View;

use Symfony\Component\HttpFoundation\JsonResponse;

class ViewJson
{
    private static $view = [];
    private static $js = [];

    public static function add($key, $value)
    {
        static::$view[$key] = $value;
    }

    public static function response()
    {
        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        if (!empty(static::$js)) {
            static::$view['cmd_js'] = static::$js;
        }
        $response->setData(static::$view);

        return $response;
    }

    public static function setMessageWarning($message)
    {
        $messages = isset(self::$view['messages']) ? self::$view['messages'] : [];
        $messages['warning'][] = $message;
        self::add('messages', $messages);
    }

    public static function addJs($action, $opt)
    {
        self::$js[] = [
      $action => $opt,
    ];
    }
}
