<?php

namespace ddme\crafthub\controllers;

use ddme\crafthub\CraftHub;

use Craft;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\web\Controller;

use yii\web\BadRequestHttpException;

class ReportController extends Controller
{
    // Protected Properties
    // =========================================================================

    public $info;

    public $config;

    public $plugins;

    public $updates;

    // Protected Properties
    // =========================================================================

    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        $this->enableCsrfValidation = false;

        $this->info    = Craft::$app->getInfo();
        $this->config  = Craft::$app->getConfig()->getGeneral();
        $this->plugins = Craft::$app->getPlugins()->getAllPluginInfo();
        $this->updates = Craft::$app->updates->getUpdates(true)->toArray();
    }

    public function actionIndex()
    {
        $this->requirePostRequest();
        $this->requireAccessToken();

        $edition             = Craft::$app->getEdition();
        $editionName         = Craft::$app->getEditionName();
        $licensedEdition     = Craft::$app->getLicensedEdition();
        $licensedEditionName = Craft::$app->getLicensedEditionName();
        $entries             = Entry::find()->count();
        $categories          = Category::find()->count();
        $users               = User::find()->count();
        $assets              = Asset::find()->count();
        $hasCraftUpdates     = $this->hasCraftUpdates();
        $hasPluginUpdates    = $this->hasPluginUpdates();
        $hasCriticalUpdates  = Craft::$app->updates->getIsCriticalUpdateAvailable(true);
        $availableUpdates    = Craft::$app->updates->getTotalAvailableUpdates(true);
        $deprecationCount    = Craft::$app->getDeprecator()->getTotalLogs();
        $deprecations        = $this->getDeprecations();

        return $this->asJson([
            'system' => [
                'name'          => $this->info->name,
                'version'       => $this->info->version,
                'schemaVersion' => $this->info->schemaVersion,
                'uid'           => $this->info->uid,
                'cpTrigger'     => $this->config->cpTrigger,
            ],
            'license' => [
                'edition'         => $editionName,
                'licensedEdition' => $licensedEditionName,
                'isTrial'         => ($licensedEdition !== null) and ($licensedEdition !== $edition),
            ],
            'stats' => [
                'entries'    => $entries,
                'categories' => $categories,
                'users'      => $users,
                'assets'     => $assets,
            ],
            'plugins'            => $this->plugins,
            'hasCraftUpdates'    => $hasCraftUpdates,
            'hasPluginUpdates'   => $hasPluginUpdates,
            'hasCriticalUpdates' => $hasCriticalUpdates,
            'availableUpdates'   => $availableUpdates,
            'updates'            => $this->updates,
            'deprecationCount'   => $deprecationCount,
            'deprecations'       => $deprecations,
        ]);
    }

    // Protected Methods
    // =========================================================================

    protected function requireAccessToken()
    {
        $token = Craft::$app->request->getParam('token');

        if ($token !== CraftHub::$plugin->settings->accessToken) {
            throw new BadRequestHttpException('Valid Access Token required');
        }
    }

    protected function hasCraftUpdates()
    {
        return count($this->updates['cms']['releases']) > 0;
    }

    protected function hasPluginUpdates()
    {
        $count = 0;

        foreach ($this->updates['plugins'] as $plug) {
            if (count($plug['releases']) > 0) {
                $count++;
            }
        }

        return $count > 0;
    }

    protected function getDeprecations()
    {
        $deprecations = Craft::$app->getDeprecator()->getLogs();

        if ($deprecations) {
            foreach ($deprecations as $dep) {
                unset($dep['traces']);
            }

            return $deprecations;
        }

        return null;
    }
}
