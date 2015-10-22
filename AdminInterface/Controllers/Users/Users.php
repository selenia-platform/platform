<?php
namespace Selenia\Plugins\AdminInterface\Controllers\Users;
use Selenia\Contracts\UserInterface;
use Selenia\Exceptions\HttpException;
use Selenia\Plugins\AdminInterface\Controllers\AdminController;

class Users extends AdminController
{
  public function interceptViewDataSet ($dataSourceName, array &$data)
  {
    $data = $this->dataItem->map ($data, function (UserInterface $user) {
      return [
        'id'               => $user->id (),
        'active'           => $user->active (),
        'realName'         => $user->realName (),
        'username'         => $user->username (),
        'registrationDate' => $user->registrationDate (),
        'lastLogin'        => $user->lastLogin (),
        'role'             => $user->role ()
      ];
    });
  }


}