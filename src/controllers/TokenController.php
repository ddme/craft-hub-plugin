<?php

namespace ddme\crafthub\controllers;

use ddme\crafthub\CraftHub;

use Craft;
use craft\web\Controller;

class TokenController extends Controller
{
    // Public Methods
    // =========================================================================

    public function actionNewToken()
    {
        $this->requirePostRequest();

        CraftHub::$plugin->tokenService->generateToken();

        //Craft::$app->getSession()->setNotice(Craft::t('crafthub', 'Access token generated.'));

        Craft::$app->getUrlManager()->setRouteParams([
            'variables' => [
                'chNewToken' => true
            ]
        ]);

        return null;
    }
}
