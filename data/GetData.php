<?php
namespace rt\data;
require_once('Config.php');
use rt\Config;

/**
 * @var $db array
 * @var $wsdlUrl string
 */
class GetData
{
    private $mysqliClient=null;
    private $soapClient=null;
    private $db;
    private $wsdlUrl;

    /**
     * GetData constructor.
     */
    public function __construct()
    {
        $this->db = Config::$db;
        $this->wsdlUrl = Config::$wsdlUrl;
    }

    /**
     * @param array $array
     */
    public function setDbParams(array $array)
    {
        $this->db = $array;
    }

    /**
     * @return array
     */
    public function getDbParams()
    {
        return $this->db;
    }

    /**
     * @param $url
     */
    public function setSoapWSDLUrl($url)
    {
        $this->wsdlUrl = $url;
    }

    /**
     * @return string
     */
    public function getSoapWSDLURL()
    {
        return $this->wsdlUrl;
    }

    /**
     * @return \Mysqli|null
     */
    public function connectDB()
    {
        $this->mysqliClient = new \Mysqli($this->db['host'], $this->db['username'], $this->db['password'], $this->db['dbname']);
        return $this->mysqliClient;
    }

    /**
     * @return null|\SoapClient
     */
    public function connectSOAP()
    {
        $this->soapClient = new \SoapClient($this->wsdlUrl);
        return $this->soapClient;
    }

}
