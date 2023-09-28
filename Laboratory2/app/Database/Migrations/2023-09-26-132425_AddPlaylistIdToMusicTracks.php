<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPlaylistIdToMusicTracks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('music_tracks', [
            'playlist_id' => ['type' => 'INT', 'constraint' => 11, 'null' => true],
        ]);
    }
    
    public function down()
    {
        $this->forge->dropColumn('music_tracks', 'playlist_id');
    }    
}
