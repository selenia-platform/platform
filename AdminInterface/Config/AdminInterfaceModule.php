<?php
namespace Selenia\Plugins\AdminInterface\Config;

use Psr\Http\Message\ServerRequestInterface;
use Selenia\Application;
use Selenia\Core\Assembly\Services\ModuleServices;
use Selenia\Interfaces\ModuleInterface;
use Selenia\Plugins\AdminInterface\Config;
use Selenia\Plugins\AdminInterface\Controllers\Users\User;
use Selenia\Plugins\AdminInterface\Controllers\Users\Users;
use Selenia\Plugins\AdminInterface\Models\User as UserModel;

class AdminInterfaceModule implements ModuleInterface
{
  /** @var Application */
  private $app;

  //TODO: replace "dummy URI" below
  function routes ()
  {
    $module    = 'selenia-plugins/admin-interface';
    $settings  = self::settings ();
    $userModel = $this->app->userModel ?: UserModel::ref ();

    return [

      PageRoute ([
        'title'         => '$ADMIN_ADMIN_USERS',
        'URI'           => 'users',
        'module'        => $module,
        'model'         => $userModel,
        'view'          => "users/users.html",
        'controller'    => Users::ref (),
        'autoloadModel' => true,
        'isIndex'       => true,
        'format'        => 'grid',
        'links'         => [
          'mainForm' => 'users/{{r.id}}',
        ],
        'routes'        => [
          PageRoute ([
            'URI'        => 'users/{id}',
            'view'       => "users/user.html",
            'controller' => User::ref (),
            'format'     => 'form',
          ]),

        ],
      ])->activeFor ($settings->users ()),

      // This is hidden from the main menu.

      PageRoute ([
        'onMenu'     => "dummy VURI" == $settings->prefix() . '/user',
        'title'      => '$LOGIN_PROFILE',
        'URI'        => 'user',
        'module'     => $module,
        'view'       => "users/user.html",
        'controller' => User::ref (),
        'config'     => [
          'self' => true // Editing the logged-in user.
        ],
      ])->activeFor ($settings->profile ()),

    ];

  }

  /**
   * @return AdminInterfaceSettings
   */
  static function settings ()
  {
    global $application;
    return get ($application->config, 'selenia-plugins/admin-interface');
  }

  function boot ()
  {
  }

  function configure (ModuleServices $module, Application $app)
  {
    $this->app = $app;
    $module
      ->publishPublicDirAs ('modules/selenia-plugins/admin-interface')
      ->provideTranslations ()
      ->provideTemplates ()
      ->provideViews ()
      ->registerPresets ([Config\AdminPresets::ref])
      ->setDefaultConfig ([
        'main'                            => [
          'userModel'   => UserModel::ref (),
          'loginView'   => 'login.html',
          'translation' => true,
        ],
        'selenia-plugins/admin-interface' => new AdminInterfaceSettings,
      ])
      ->onPostConfig (function () use ($module) {
        $module->registerRoutes ([
          RouteGroup ([
            'title'  => '$ADMIN_MENU_TITLE',
            'prefix' => self::settings ()->prefix (),
            'defaultURI' => self::settings ()->adminHomeUrl(),
            'routes' => $this->routes (),
          ])->activeFor (self::settings ()->menu ()),
        ]);
      });
  }

}
