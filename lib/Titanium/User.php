<?php

namespace Titanium;

class User extends Base
{
  const URL_CREATE = 'users/create.json';
  const URL_LOGIN = 'users/login.json';
  const URL_DELETE = 'users/delete.json';
  
  protected $id;
  protected $email;
  protected $username;
  protected $password;
  protected $first_name;
  protected $last_name;
  protected $role;

  public function __construct($username, $password)
  {
    $this->setUsername($username);
    $this->setPassword($password);
    
    parent::__construct();
  }
  
  public function getId()
  {
    return $this->id;
  }
  
  public function setId($id)
  {
    $this->id = $id;
  }
  
  public function getUsername()
  {
    return $this->username;
  }
  
  public function setUsername($username)
  {
    $this->username = $username;
  }
  
  public function getEmail()
  {
    return $this->email;
  }
  
  public function setEmail($email)
  {
    $this->email = $email;
  }
  
  public function getPassword()
  {
    return $this->password;
  }
  
  public function setPassword($password)
  {
    $this->password = $password;
  }
  
  public function getFirstName()
  {
    return $this->first_name;
  }
  
  public function setFirstName($first_name)
  {
    $this->first_name = $first_name;
  }
  
  public function getLastName()
  {
    return $this->last_name;
  }
  
  public function setLastName($last_name)
  {
    $this->last_name = $last_name;
  }
  
  public function getRole()
  {
    return $this->role;
  }
  
  public function setRole($role)
  {
    $this->role = $role;
  }
  
  public function login()
  {
    $url = $this->getFullUrl(self::URL_LOGIN);
    $parameters = $this->getDefaultParameters();
    
    if ($this->getEmail())
    {
      $parameters['login'] = $this->getEmail();
    }
    else
    {
      $parameters['login'] = $this->getUsername();
    }
    
    $parameters['password'] = $this->getPassword();
    
    $response = $this->post($url, $parameters);
    
    $is_logged_in = $response->meta->code == 200;
    
    if ($is_logged_in)
    {
      $user = $response->response->users[0];
      $this->setId($user->id);
    }

    return $is_logged_in;
  }

  public function create()
  {
    $url = $this->getFullUrl(self::URL_CREATE);
    $parameters = $this->getDefaultParameters();
    
    $this->addParameter($parameters, 'email');
    $this->addParameter($parameters, 'username');
    $this->addParameter($parameters, 'first_name');
    $this->addParameter($parameters, 'last_name');
    $this->addParameter($parameters, 'role');
    $this->addParameter($parameters, 'password');
    
    if (isset($parameters['password']))
    {
      $parameters['password_confirmation'] = $parameters['password'];  
    }
   
    $response = $this->post($url, $parameters);
    
    if ($response->meta->code == 200)
    {
      $user = $response->response->users[0];
      $this->setId($user->id);  
    }   
  }
  
  public function delete()
  {
    $url = $this->getFullUrl(self::URL_DELETE);
    
    $this->getBrowser()->delete($url);
    $response = json_decode($this->getBrowser()->getResponseText());
    
    return $response->meta->code == 200;
  }
  
  public function subscribe($device_token, $channel = null)
  {
    $notification = new PushNotification();
    
    return $notification->subscribe($device_token, $channel);
  }
  
  public function notify($payload, $channel = null)
  {
    $notification = new PushNotification();
    
    return $notification->notify($payload, $channel, array($this->getId()));
  }
  
  public function unsubscribe($device_token, $channel = null)
  {
    $notification = new PushNotification();
    
    return $notification->unsubscribe($device_token, $channel);
  }
  
  protected function addParameter(&$parameters, $field)
  {
    if ($this->$field)
    {
      $parameters[$field] = $this->$field;
    }
  }
}