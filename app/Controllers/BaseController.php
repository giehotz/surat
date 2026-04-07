<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @var array
     */
    protected $appSettings;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Load here all helpers you want to be available in your controllers that extend BaseController.
        // Caution: Do not put the this below the parent::initController() call below.
        $this->helpers = ['form', 'url', 'tanggal', 'honeypot'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');

        // Load Global Application Settings
        $pengaturanModel = new \App\Models\PengaturanModel();

        // Share settings globally with all views
        $this->appSettings = cache('app_settings');
        if (!$this->appSettings) {
            $this->appSettings = $pengaturanModel->getSettings();
            cache()->save('app_settings', $this->appSettings, 86400); // cache for 1 day
        }

        // Expose to all views via service
        $renderer = service('renderer');
        $renderer->setVar('appSettings', $this->appSettings);
    }
}
