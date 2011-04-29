<?php
/** 
 * EnvoiMoinsCher API parcel points class.
 * 
 * It can be used to load one or more parcel points (for pickup and dropoff). 
 * @author EnvoiMoinsCher <dev@envoimoinscher.com>
 * @version 1.0
 */

class Env_ParcelPoint extends Env_WebService {

  /** Protected array which indicates the possibles root elements in the server reply document.
   *  @access protected
   *  @var array
   */
  protected $types = array("pickup_point", "dropoff_point");

  /** Public array with parcel points. It takes pickup_point or dropoff_point as the keys.
   *  @access public
   *  @var array
   */
  public $points = array();


  /** Public boolean variable which specifies if the public $points variable will contain one 
   *  or more parcel points. 
   *  @access public
   *  @var array
   */
  public $constructList = false;
  
  /** Getter function to one parcel point. 
   *  @access public
   *  @param string $type Parcels' point type to load.
   *  @param string $code Parcels' point code composed by operator code and point id 
   *  (for exemple : SERN-206059 for Sernam parcel point in Lille).
   *  @return void
   */
  public function getParcelPoint($type = "", $code = "") {
    if(in_array($type, $this->types)) {
      $this->setOptions(array("action" => "/api/v1/$type/$code/informations",
	  )); 
      $this->doSimpleRequest($type);
    }
  }
  
  /** Function executes getParcelPoint() request and prepares the $points array.
   *  @access private
   *  @return void
   */
  private function doSimpleRequest($type) {
    $source = parent::doRequest();
    if($source !== false) {
      $domCl = new DOMDocument(); 
      $domCl->loadXML($source);
      $xpath = new DOMXPath($domCl);
      $pointDetail = array("code" => $xpath->evaluate("/$type/code")->item(0)->nodeValue,
        "name" =>  $xpath->evaluate("/$type/name")->item(0)->nodeValue,
        "address" =>  $xpath->evaluate("/$type/address")->item(0)->nodeValue,
        "city" =>  $xpath->evaluate("/$type/city")->item(0)->nodeValue,
        "zipcode" =>  $xpath->evaluate("/$type/zipcode")->item(0)->nodeValue,
        "country" =>  $xpath->evaluate("/$type/country")->item(0)->nodeValue,
        "phone" =>  $xpath->evaluate("/$type/phone")->item(0)->nodeValue,
        "description" => $xpath->evaluate("/$type/description")->item(0)->nodeValue
      );
      if($this->constructList) {
        $t = count($this->points[$type]);
        $this->points[$type][$t] = $pointDetail;
      }
      else {
        $this->points[$type] = $pointDetail;
      }
    }
  }


}
?>