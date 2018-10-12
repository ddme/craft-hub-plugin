<?php

namespace ddme\crafthub\controllers;

use ddme\crafthub\CraftHub;

use Craft;
use craft\web\Controller;

use yii\web\BadRequestHttpException;

class ValidateController extends Controller
{
    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->enableCsrfValidation = false;
    }

    public function actionIndex()
    {
        if (!$this->requireAccessToken()) {
            Craft::$app->response->statusCode = 400;

            return $this->asJson([
                'success' => false,
                'reason'  => 'Valid access token required',
            ]);
        }

        return $this->asJson([
            'success' => true,
        ]);
    }

    // Protected Methods
    // =========================================================================

    protected function requireAccessToken()
    {
        $token = Craft::$app->request->getParam('token');

        if ($token == CraftHub::$plugin->settings->accessToken) {
            return true;
        }

        return false;
    }
}
