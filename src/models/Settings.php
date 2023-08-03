<?php

namespace denshamtechnology\youtube\models;

use craft\base\Model;

class Settings extends Model
{
    public $api_key = '';
    public $channel_id = '';

    public function rules(): array
    {
        return [
            [['api_key', 'channel_id'], 'required'],
        ];
    }
}