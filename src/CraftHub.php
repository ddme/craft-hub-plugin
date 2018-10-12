<?php

namespace ddme\crafthub;

use ddme\crafthub\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\helpers\UrlHelper;
use craft\services\Plugins;
use craft\web\UrlManager;

use Yii\base\Event;

class CraftHub extends Plugin
{
    // Static Properties
    // =========================================================================

    public static $plugin;

    // Public Properties
    // =========================================================================

    public $schemaVersion = '0.0.1';

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // After installation
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                    // Generate token
                    $this->tokenService->generateToken();

                    // Redirect to settings
                    Craft::$app->getResponse()->redirect(UrlHelper::cpUrl('settings/plugins/crafthub'))->send();
                }
            }
        );

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['settings/crafthub/token'] = 'crafthub/settings/token';
            }
        );

        // Register components
        $this->setComponents([
            'tokenService' => \ddme\crafthub\services\TokenService::class,
        ]);
    }

    // Protected Methods
    // =========================================================================

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml(): string
    {

        return Craft::$app->view->renderTemplate('crafthub/settings');
    }
}
