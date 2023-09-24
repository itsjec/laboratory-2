<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MusicModel;
use App\Models\PlaylistModel;

class MainController extends BaseController
{

    public function addToPlaylist()
    {
        // Get the selected playlist ID and track ID from the form submission
        $playlistId = $this->request->getPost('playlistId');
        $trackId = $this->request->getPost('trackId');
        
        // Create instances of your models
        $playlistModel = new PlaylistModel();
        $trackModel = new MusicModel(); // Adjust the model name based on your setup
        
        // Find the playlist and track
        $playlist = $playlistModel->find($playlistId);
        $track = $trackModel->find($trackId);
        
        if (!$playlist || !$track) {
            // Handle the case where the playlist or track doesn't exist
            return redirect()->to('/test')->with('error', 'Playlist or track not found.');
        }
        
        // Set the 'playlist_id' of the track to the selected playlist
        $track->playlist_id = $playlistId;
        
        // Save the updated track to the "music_tracks" table
        if ($track->save()) {
            // Successful save
            return redirect()->to('/test')->with('success', 'Track added to the playlist successfully.');
        } else {
            // Handle save failure
            return redirect()->to('/test')->with('error', 'Failed to add the track to the playlist.');
        }
    }
    

    
    public function search()
    {
        // Get the search query from the POST data
        $searchQuery = $this->request->getPost('search');
    
        // Perform the search (adjust this based on your database setup)
        $musicModel = new \App\Models\MusicModel();
        $results = $musicModel->like('title', $searchQuery)->findAll();
    
        // Return the search results as JSON
        return $this->response->setJSON($results);
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
                    'file_path' => 'uploads/' . $newName, // Store the generated filename in public/uploads
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
        
    
        $data['songs'] = $musicModel->findAll(); // Fetch all songs
        $data['playlists'] = $playlistModel->findAll();
        $data['playlistId'] = $playlistId;
        $data['trackId'] = $trackId;
    
        return view('index', $data);
    }    
    
}
