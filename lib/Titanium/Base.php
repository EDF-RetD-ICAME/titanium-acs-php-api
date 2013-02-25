<?php

namespace Titanium;

class Base
{
  protected static $key;
  protected $browser;
  protected $base_url;

  public function __construct()
  {
    $this->setBaseUrl('https://api.cloud.appcelerator.com/v1');
  }

  public function &getBrowser()
  {
    if (is_null($this->browser))
    {
      $default_headers = array();
      $adapter_options = $this->getCookiesOptions();

      $this->browser = new \sfWebBrowser($default_headers, 'sfCurlAdapter', $adapter_options);
    }

    return $this->browser;
  }

  public function setBrowser(sfWebBrowser $browser)
  {
    $this->browser = $browser;
  }

  public static function getKey()
  {
    return self::$key;
  }

  public static function setKey($key)
  {
    self::$key = $key;
  }

  public function getBaseUrl()
  {
    return $this->base_url;
  }

  public function setBaseUrl($base_url)
  {
    $this->base_url = $base_url;
  }

  public function getFullUrl($url)
  {
    return $this->getBaseUrl().'/'.$url.'?key='.self::getKey();
  }

  public function getCookiesOptions()
  {
    return array(
      'followlocation' => true,
      'cookies' => true,
      'cookies_file' => self::getCookieFile(),
      'cookies_dir' => self::getCookiePath()
    );
  }

  /**
   *
   *
   * @author Maxime PICAUD <maxime.picaud@agence-shape.fr>
   * @since Aug 3, 2011
   */
  public static function getCookiePath()
  {
    return __DIR__.'/../../data';
  }

  /**
   *
   * @param sfGuardUser $guard_user
   *
   * @author Maxime PICAUD <maxime.picaud@agence-shape.fr>
   * @since Aug 3, 2011
   */
  public static function getCookieFile()
  {
    $cookie_name = 'cookie';

    return self::getCookiePath().'/'.$cookie_name;
  }

  public function getDefaultParameters()
  {
    return array();
  }

  public function get($url, $parameters = array())
  {
    $this->getBrowser()->get($url, $parameters);

    return json_decode($this->getBrowser()->getResponseText());
  }

  public function post($url, $parameters = array())
  {
    $this->getBrowser()->post($url, $parameters);

    return json_decode($this->getBrowser()->getResponseText());
  }
}