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
            if ($division->Priznak === 'U') {
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

    /**
     * @param $objArray
     * @param string $x_pos
     * @param string $y_pos
     * @return array|null
     */
    public static function getBoundsAsArray($objArray, $x_pos='x_pos', $y_pos='y_pos')
    {
        if ($objArray === null) {
            return null;
        }
        if (count($objArray) === 1) {
            return [
                'x' => $objArray[0]->x_pos,
                'y' => $objArray[0]->y_pos
            ];
        }
        $xMin = 1000;
        $xMax = 0;
        $yMin = 1000;
        $yMax = 0;
        foreach ($objArray as $item) {
            if ($item->$x_pos < $xMin)
                $xMin = $item->$x_pos;
            if ($item->$x_pos > $xMax)
                $xMax = $item->$x_pos;
            if ($item->$y_pos < $yMin)
                $yMin = $item->$y_pos;
            if ($item->$y_pos > $yMax)
                $yMax = $item->$y_pos;
        }
        return [
            'x_min' => $xMin,
            'x_max' => $xMax,
            'y_min' => $yMin,
            'y_max' => $yMax
        ];
    }
}