<?php

namespace Accordous\SerasaClient\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class SerasaService
{
    /**
     * @var \Illuminate\Http\Client\PendingRequest
     */
    private $http;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $b49c = 'B49C      %DOCUMENT%%TYPE%C     FI                       INIAN                                                    S                                                                                                                                                                                                                                                                                             P002BPCA                     BPRD                     BPHMHSPN                                                     P006NNNNN1099NNNNNNNN NSSNNS                                                                                       T999';

    /**
     * @var string
     */
    private $ip20 = 'IP20RPMES2        0%DOCUMENT%22N            032E                                                                                    HPJ4';


    /**
     * SerasaService constructor.
     */
    public function __construct()
    {
        $credentials = Config::get('serasa.user') . Config::get('serasa.password');

        $this->http = Http::withoutVerifying();
        $this->baseUrl = Config::get('serasa.host')
            .  Config::get('serasa.api')
            . '?p='
            . $credentials
            . str_repeat(' ', 8);
    }

    /**
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function client()
    {
        return $this->http;
    }

    /**
     * @param string $document
     * @param string $type
     * @return array
     * @throws \Exception
     */
    public function b49c(string $document, string $type): array
    {
        $path = str_replace('%DOCUMENT%', $this->b49cDocument($document), $this->b49c);
        $path = str_replace('%TYPE%', $type, $path);

        $response = $this->http->send('POST', $this->baseUrl . $path);

        return $this->b49cParser($response->body());
    }

    /**
     * @param string $document
     * @return array
     * @throws \Exception
     */
    public function ip20(string $document): array
    {
        $path = str_replace('%DOCUMENT%', $this->ip20Document($document), $this->ip20);

        $response = $this->http->send('POST', $this->baseUrl . $path);

        return $this->ip20Parser($response->body());
    }

    /**
     * @param string $string
     * @return array
     */
    private function b49cParser(string $string): array
    {
        $struct = [];

        $subString = substr($string, strripos($string, 'F900'));
        $struct['income'] = $this->parseIncome($subString);
        $struct['scoring'] = $this->parseScoring($subString);

        return $struct;
    }

    /**
     * @param string $string
     * @return array
     */
    private function ip20Parser(string $string): array
    {
        $struct = [];

        return $struct;
    }

    /**
     * @param string $document
     * @return array|string|string[]|null
     */
    private function sanitzeDocument(string $document)
    {
        return preg_replace('/[^0-9]/', '', $document);
    }

    /**
     * @param string $document
     * @return string
     */
    private function b49cDocument(string $document)
    {
        return str_pad($this->sanitzeDocument($document), 15, '0', STR_PAD_LEFT);
    }

    /**
     * @param string $document
     * @return false|string
     */
    private function ip20Document(string $document)
    {
        return substr($this->sanitzeDocument($document), 0, 8);
    }

    /**
     * @param string $string
     * @return string
     */
    private function parseIncome(string $string): string
    {
        $hasIncome = substr($string, 8, 1);
        if(! $hasIncome) {
            return 'não cálculo';
        }

        $number = floatval(substr($string, 9, 70))/100;

        return number_format($number, 2, ',', '.');
    }

    /**
     * @param string $string
     * @return array
     */
    private function parseScoring(string $string)
    {
        $b280Pos = strripos($string, 'B280');
        $type = substr($string, $b280Pos+4, 4);
        $score = substr($string, $b280Pos+8, 6);

        return [
            'type' => $type,
            'score ' => intval($score),
        ];
    }
}
