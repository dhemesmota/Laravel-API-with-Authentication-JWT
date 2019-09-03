<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Dados de exemplos que não necessita de autenticação via token
     */
    public function open()
    {
        $data = "Esses dados estão abertos e podem ser acessados ​​sem que o cliente seja autenticado";
        return response()->json(compact('data'), 200);
    }

    /**
     * Dado de exemplo que necessita de autenticação via token
     */
    public function closed()
    {
        $data = "Somente usuários autorizados podem ver isso";
        return response()->json(compact('data'), 200);
    }
}
