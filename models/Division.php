<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 19.12.2018
 * Time: 19:49
 */

namespace app\models;



class Division
{
    /**
     * @var \SoapClient
     */
    private $soapClient;

    public function __construct(array $config = [])
    {
        $this->soapClient = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
    }

    public function getAllDivisionsFromSoap()
    {
        return json_decode($this->soapClient->getDivision()->return);
    }

    public function getAutocolumns()
    {
        $resultArray = [];
        $divisions = $this->getAllDivisionsFromSoap();
        foreach ($divisions as $division) {
            if ($division->Priznak === 'K') {
                $resultArray[] = $division;
            }
        }
        return $resultArray;
    }

    public function getSpots()
    {
        $resultArray = [];
        $divisions = $this->getAllDivisionsFromSoap();
        foreach ($divisions as $division) {
            if ($division->Priznak === 'A') {
                $resultArray[] = $division;
            }
        }
        return $resultArray;
    }

    public function getOthers()
    {
        $resultArray = [];
        $divisions = $this->getAllDivisionsFromSoap();
        foreach ($divisions as $division) {
            if ($division->Priznak === 'P') {
                $resultArray[] = $division;
            }
        }
        return $resultArray;
    }
}