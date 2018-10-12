<?php

namespace ddme\crafthub\models;

use ddme\crafthub\CraftHub;

use Craft;
use craft\base\Model;

class Settings extends Model
{
    // Public Properties
    // =========================================================================

     public $accessToken = '';

    // Public Methods
    // =========================================================================

    public function rules()
    {
      return [
          [['accessToken'], 'string'],
          [['accessToken'], 'required']
      ];
    }
}
