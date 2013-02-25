<?php

namespace Titanium;

class PushNotification extends Base
{
  const URL_SUBSCRIBE = 'push_notification/subscribe.json';
  const URL_UNSUBSCRIBE = 'push_notification/unsubscribe.json';
  const URL_NOTIFY = 'push_notification/notify.json';
  
  const TYPE_ANDROID = 'android';
  const TYPE_IOS = 'ios';
  
  protected $channel;
  protected $type;
  
  public function getChannel()
  {
    return $this->channel;
  }
  
  public function setChannel($channel)
  {
    $this->channel = $channel;
  }
  
  public function getType()
  {
    return $this->type;
  }
  
  public function setType($type)
  {
    $this->type = $type;
  }
  
  public function subscribe($device_token, $channel = null, $type = null)
  {
    $url = $this->getFullUrl(self::URL_SUBSCRIBE);
    
    if (is_null($channel))
    {
      $channel = $this->getChannel();
    }
    
    if (is_null($type))
    {
      $type = $this->getType();
    }
    
    $parameters = array_merge($this->getDefaultParameters(), array(
      'channel'       => $channel,
      'device_token'  => $device_token,
      'type'          => $type 
    ));
   
    $response = $this->post($url, $parameters);

    return $response->meta->code == 200;
  }
  
  public function unsubscribe($device_token, $channel = null)
  {
    $url = $this->getFullUrl(self::URL_UNSUBSCRIBE);
    
    if (is_null($channel))
    {
      $channel = $this->getChannel();
    }
    
    $parameters = array_merge($this->getDefaultParameters(), array(
      'device_token'  => $device_token,
    ));
    
    if ($channel)
    {
      $parameters['channel'] = $channel;
    }
    
    $this->getBrowser()->delete($url, $parameters);
    $response = json_decode($this->getBrowser()->getResponseText());

    return $response->meta->code == 200;
  }
  
  public function notify($payload, $channel = null, $to_ids = null)
  {
    $url = $this->getFullUrl(self::URL_NOTIFY);
    
    if (is_null($channel))
    {
      $channel = $this->getChannel();
    }
    
    $parameters['channel'] = $channel;
    $parameters['payload'] = $payload;
    
    if (!is_null($to_ids))
    {
      $parameters['to_ids'] = implode(',', $to_ids);
    }
    
    $response = $this->post($url, $parameters);

    return $response->meta->code == 200;
  }
};