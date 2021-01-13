<?php


class Responce 
{
    private static $statusNames = [
        '100' => 'Continue',
        '101' => 'Switching Protocols',
        '102' => 'Processing',
        '103' => 'Early Hints',

        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '207' => 'Multi-Status',
        '208' => 'Already Reported',
        '226' => 'IM Used',

        '404' => 'Not Found',
    ];


    public function __construct() 
    {
        header('Content-type: json/application');
    }


    public function Send(int $statusCode, string $message) 
    {
        header('HTTP/1.0 '.$statusCode.' '.$this->statusNames[strval($statusCode)]);

		echo json_encode([
			'error' => ($statusCode > 300) ? 1 : 0,
			'message' => $message
		]);
    }
}