<?php

namespace ddme\crafthub\services;

use ddme\crafthub\CraftHub;

use Craft;
use craft\base\Component;

class TokenService extends Component
{
    // Public Properties
    // =========================================================================

    public $plugin;

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->plugin = CraftHub::getInstance();
    }

    public function generateToken()
    {
        $token = date('dmY').'_'.md5(uniqid());

        Craft::$app->plugins->savePluginSettings($this->plugin, [
            'accessToken' => $token,
        ]);
    }
}
