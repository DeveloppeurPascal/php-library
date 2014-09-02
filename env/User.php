<?php
/** 
 * EnvoiMoinsCher API user class.
 * 
 * Actually can be used only to configure e-mail send by EnvoiMoinsCher.com to user, shipper and receiver. 
 * @package Env
 * @author EnvoiMoinsCher <dev@envoimoinscher.com>
 * @version 1.0
 */

class Env_User extends Env_WebService {

  /**
   * Array with user configuration informations. Actually we put only email informations.
   * @access public
   * @var array
   */
  public $user_configuration = array('emails' => array());
  
  /**
   * String with user partnership code. 
   * @access public
   * @var string
   */
  public $partnership = "";

  /**
   * Gets information about partnership for logged user.
   * @access public
   * @return Void
   */
  public function getPartnership() {
    $this->setOptions(array('action' => '/api/v1/partnership'));
    $this->setPartnership();
  }

  /**
   * Gets information about e-mail configuration for logged user.
   * @access public
   * @return Void
   */
  public function getEmailConfiguration() {
    $this->setOptions(array('action' => '/api/v1/emails_configuration'));
    $this->setEmailConfiguration();
  }

  /**
   * Posts new informations about e-mail configuration for logged user.
   * Accepted keys are : label, notification, bill. If you want to remove the e-mail sending
   * for one of these keys, you must put into it an empty string like "".
   * @access public
   * @param Array $params Params with new e-mail configuration
   * @return Void
   */
  public function postEmailConfiguration($params) {
    $this->setOptions(array('action' => '/api/v1/emails_configuration'));
    $this->param = $params;
    $this->setPost();
    $this->setEmailConfiguration();
  }

  /**
   * Parses API response and puts the values into e-mail configuration array.
   * @access private
   * @return Void
   */
  private function setEmailConfiguration() {
    $source = parent::doRequest();
    if($source !== false) {
      parent::parseResponse($source);
      foreach($this->xpath->evaluate('/user/mails')->item(0)->childNodes as $config_line) {
        if(!($config_line instanceof DOMText)) {
          $this->user_configuration['emails'][$config_line->nodeName] = $config_line->nodeValue;
        }
      }
    }
  }

  /**
   * Parses API response and puts the values into partnership attribute.
   * @access private
   * @return Void
   */
  private function setPartnership() {
    $source = parent::doRequest();
    if($source !== false)
	{
      parent::parseResponse($source);
	  $node = $this->xpath->evaluate('/user/partnership');
	  if ($node)
	  {
		$this->partnership = $node->item(0)->nodeValue;
	  }
    }
  }

}

?>