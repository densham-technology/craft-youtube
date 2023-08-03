<?php

namespace denshamtechnology\youtube\services;

use craft\base\Component;
use denshamtechnology\youtube\YouTube;
use Google\Client;
use Google\Service\YouTube as YouTubeService;

class Videos extends Component
{
    /**
     * @var YouTubeService
     */
    protected $youtube;

    /**
     * @var string
     */
    protected $channel_id;

    public function init()
    {
        $client = new Client();
        $client->setApplicationName('CraftCMS');
        $client->setDeveloperKey(YouTube::$plugin->getSettings()->api_key);

        $this->youtube = new YouTubeService($client);

        $this->channel_id = YouTube::$plugin->getSettings()->channel_id;
    }

    public function playlists(): YouTubeService\PlaylistListResponse
    {
        return $this->youtube->playlists->listPlaylists('snippet', ['channelId' => $this->channel_id]);
    }

    public function playlistItems($playlist_id, $page_token = null): YouTubeService\PlaylistItemListResponse
    {
        $params = ['playlistId' => $playlist_id, 'maxResults' => 9];

        if ($page_token) {
            $params['pageToken'] = $page_token;
        }

        return $this->youtube->playlistItems->listPlaylistItems('snippet', $params);
    }
}