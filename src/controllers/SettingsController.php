<?php

namespace ddme\crafthub\controllers;

use ddme\crafthub\CraftHub;

use Craft;
use craft\web\Controller;

use yii\base\InvalidConfigException;

class SettingsController extends Controller
{
    // Public Properties
    // =========================================================================

    public $plugin;

    public $settings;

    public $overrides;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->plugin = CraftHub::$plugin;
        $this->settings = $this->plugin->getSettings();
        $this->overrides = Craft::$app->getConfig()->getConfigFromFile(strtolower($this->plugin->handle));

        if (!$this->settings->validate()) {
            throw new InvalidConfigException('Email Validator settings don’t validate.');
        }
    }

    public function actionToken(array $variables = [])
    {
        $variables = array_merge($variables, [
            'title'     => 'Craft Hub Access Token',
            'settings'  => $this->settings,
            'overrides' => $this->overrides,
        ]);

        return $this->renderTemplate('crafthub/_settings/token', $variables);
    }
}
