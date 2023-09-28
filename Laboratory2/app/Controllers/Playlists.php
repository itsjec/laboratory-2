<?php

// app/Controllers/PlaylistController.php

namespace App\Controllers;

use App\Models\PlaylistModel;
use CodeIgniter\API\ResponseTrait;

class PlaylistController extends BaseController
{
    use ResponseTrait;

    public function create()
    {
        $playlistName = $this->request->getPost('name');

        // Insert the new playlist into the database
        $playlistModel = new PlaylistModel();
        $data = [
            'name' => $playlistName,
        ];

        if ($playlistModel->insert($data)) {
            return $this->respond(['success' => true]);
        } else {
            return $this->respond(['success' => false]);
        }
    }
}
