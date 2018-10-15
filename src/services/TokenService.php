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
        // Generate MD5 hash using combination of system uid & uniqid()
        $uid = Craft::$app->getSystemUid();
        $rand = uniqid();
        $token = md5($uid.$rand);

        Craft::$app->plugins->savePluginSettings($this->plugin, [
            'accessToken' => $token,
        ]);
    }
}
