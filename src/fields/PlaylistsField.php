<?php

namespace denshamtechnology\youtube\fields;

use Craft;
use craft\fields\Dropdown;
use denshamtechnology\youtube\YouTube;
use Google\Service\YouTube\Playlist;
use yii\base\InvalidConfigException;

class PlaylistsField extends Dropdown
{
    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('app', 'Playlists');
    }

    /**
     * @throws InvalidConfigException
     */
    protected function options(): array
    {
        return array_map(function (Playlist $playlist) {
            return [
                'label' => $playlist->getSnippet()->title,
                'value' => $playlist->id,
                'default' => '',
            ];
        }, YouTube::getInstance()->getVideos()->playlists()->getItems());
    }
}