<?php

namespace App\Controllers;
use App\Models\MusicModel;
use App\Models\PlaylistModel;
use App\Models\PlaylistTrackModel;

class Home extends BaseController
{
    public function index(): string
    {
        $musicModel = new MusicModel();
        $playlistModel = new PlaylistModel();
    
        // Replace these lines with the logic to get valid values for $playlistId and $trackId
        $playlistId = 1; // Replace with the appropriate value
        $trackId = 2; // Replace with the appropriate value
    
        // Retrieve songs and their associated playlist names
        $data['songs'] = $musicModel
            ->select('music_tracks.*, playlists.name AS playlist_name')
            ->join('playlist_tracks', 'playlist_tracks.track_id = music_tracks.id', 'left')
            ->join('playlists', 'playlists.id = playlist_tracks.playlist_id', 'left')
            ->findAll();
    
        // Retrieve all playlists
        $data['playlists'] = $playlistModel->findAll();
        
        // Pass the selected playlist ID and track ID to the view
        $data['selectedPlaylistId'] = $playlistId;
        $data['selectedTrackId'] = $trackId;
    
        return view('index', $data);
    }
}
