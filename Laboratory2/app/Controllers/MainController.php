<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MusicModel;
use App\Models\PlaylistModel;
use App\Models\PlaylistTrackModel;

class MainController extends BaseController
{
    private $playlists;
    private $playlistTracks;
    private $musicModel; // Add this
    private $playlistModel; // Add this

    function __construct(){
        $this->playlists = new PlaylistModel();
        $this->playlistTracks = new PlaylistTrackModel();
        $this->musicModel = new MusicModel(); // Initialize the MusicModel
        $this->playlistModel = new PlaylistModel(); // Initialize the PlaylistModel
    }

    public function addToPlaylist()
    {
        $playlistTrackModel = new PlaylistTrackModel(); 
        $trackId = $this->request->getPost('musicID');
        $playlistId = $this->request->getPost('playlistID');
    
        $data = [
            'track_id' => $trackId,
            'playlist_id' => $playlistId,
        ];
    
        $playlistTrackModel->save($data); 
    
        return redirect()->to('/');
    }
    
    

    public function index()
    {
        $data = [
            'songs' => $this->musicModel->findAll(),
            'playlists' => $this->playlistModel->findAll(),
        ];
    
        return view('index', $data);
    }    

    public function search()
    {
        // Get the search query from the request
        $searchQuery = $this->request->getGet('search');
    
        // Load the PlaylistModel
        $playlistModel = new PlaylistModel();
    
        // Check if the search query is not empty
        if (!empty($searchQuery)) {
            // Perform a database query to search for songs
            $musicModel = new MusicModel();
            $results = $musicModel->like('title', $searchQuery)->findAll();
    
            // Fetch all playlists using the PlaylistModel
            $playlists = $playlistModel->findAll();
    
            // Pass the search results and playlists to the view
            $data = [
                'songs' => $results,
                'playlists' => $playlists,
            ];
    
            return view('index', $data);
        } else {
            // Redirect to the home page if the search query is empty
            return redirect()->to('/');
        }
    }

    public function addplaylist()
    {
        $playlistsModel = new \App\Models\PlaylistModel();
        $data = [
            'name' => $this->request->getVar('playlistName')
        ];

        $playlistsModel->save($data); // Use $playlistsModel instead of $this->playlists
        
        return redirect()->to('/test');
    }

        
    public function upload()
    {
        // Check if the request contains a file
        if ($this->request->getFile('music_file')) {
            // Get the uploaded file
            $file = $this->request->getFile('music_file');
            
            // Check if the file is valid (you can add more validation)
            if ($file->isValid() && $file->getClientMimeType() === 'audio/mpeg') {
                // Move the file to the public/uploads directory
                $newName = $file->getClientName(); // Use a random name to avoid conflicts
                $file->move(ROOTPATH . 'public/uploads', $newName);
    
                // Save the file information to the database
                $musicModel = new MusicModel();
                $data = [
                    'title' => $file->getClientName(), // Use the original filename as the title
                    'artist' => 'Unknown', // You can set the artist name as needed
                    'album' => 'Unknown', // You can set the album name as needed
                    'genre' => 'Unknown', // You can set the genre as needed
                    'file_path' => 'uploads/' . $newName,// Store the generated filename in public/uploads
                ];
                $musicModel->insert($data);
    
                // Redirect to the "/test" page
                return redirect()->to('/test');
            } else {
                // File is not valid, handle the error (e.g., show an error message)
                return redirect()->back()->with('error', 'Invalid file format');
            }
        }
    
        // Handle the case when no file is uploaded
        return redirect()->back()->with('error', 'No file selected');
    }
    
    public function test()
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
