<?php

namespace Sensy\Scrud\app\Http\Helpers;

class Model
{
    public static function call($req, $service, $function = 'index', $id = null, $asBuilder = false, $options = [], $isApi = false)
    {
        $caller = service_caller($function, $service, $isApi);
        if (!$req->data) $req->data = []; ## set default for data as empty

        if ($req->input('options')) $req->options = $req->input('options');
        else $req->options = $options;

        $service = new ServiceHandler($caller[0]);

        $req->asBuilder = $asBuilder;
        $handler = handler($caller);
        return $service->handle($req, $caller, $id, $isApi);
    }
}
