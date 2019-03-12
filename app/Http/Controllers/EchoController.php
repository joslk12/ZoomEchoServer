<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EchoController extends Controller
{
    /** 
     * This method evaluate the type of http request
     * and return the type and parameters send to the endpoint 
    */
    public function echoRequest(Request $request){
        switch($request->method()){
            case 'GET':
                return $this->generateResponse($request->method(), 
                    [
                        'message' => 'The GET method does not require a json parameters, they usually are query string',
                        'data' => $request->getContent()
                    ]);
                break;
            case 'POST':
                return $this->checkParameters($request);
                break;
            case 'PUT':
                return $this->checkParameters($request);
                break;
            case 'DELETE':
                return $this->generateResponse($request->method(), 
                    [
                        'message' => 'The DELETE method does not require a json parameters, usually as a only parameter the id from data to delete',
                        'data' => $request->getContent()
                    ]);
                break;
            case 'PATCH':
                return $this->checkParameters($request);
                break;
            default:
                return response()->json('Method not allowed', 405);
                break;           
        }
    }

    /**
     * Generate the response for data endpoint
     * @param string $method 
     *     Http method of the request
     * @param object $data
     *     Params sendt to the request
     * @return json 
     *     Response to the request composed by
     *     method: String containing the method of request
     *     data: Object containing paramethers received in the request
     */
    private function generateResponse($method, $data){
        $resp = [
            'method' => $method,
            'data' => $data
        ];
        return response()->json($resp);
    }

    /** 
     * Check if the request parameters is a valid Json object
     * @param object $req
     *     Request received
     * @return Json Response
     *     Response for the request
    */
    private function checkParameters($req){
        if($req->isJson()){
            return $this->generateResponse($req->method(), json_decode($req->getContent()));
        }
        return response()->json(['message' => 'Parameters must be Json format'], 400);
    }
}
