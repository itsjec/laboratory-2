<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f5f5f5;
            padding: 20px;
        }

        h1 {
            color: #333;
        }

        #player-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        audio {
            width: 100%;
        }

        #playlist {
            list-style: none;
            padding: 0;
        }

        #playlist li {
            cursor: pointer;
            padding: 10px;
            background-color: #eee;
            margin: 5px 0;
            transition: background-color 0.2s ease-in-out;
        }

        #playlist li:hover {
            background-color: #ddd;
        }

        #playlist li.active {
            background-color: #007bff;
            color: #fff;
        }

        #song-table-container {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%; /* Make the table take up 100% of its container's width */
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #song-table {
            list-style: none;
            padding: 0;
        }

        #song-table li {
            cursor: pointer;
        }

    </style>
</head>
<body>


    <form action="/" method="get">
        <input type="search" name="search" placeholder="Search for a song" id="searchInput">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <select id="searchResults" style="display: none;">
        <!-- Search results will be dynamically added here -->
    </select>



    <h1>Music Player</h1>
    <div class="btn-group" role="group" style=" justify-content: center; gap: 10px;">
   <!-- Button to open the "My Playlist" modal -->
   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#existingPlaylistsModal">My Playlist</button>

    <!-- Existing Playlists Modal -->
    <div class="modal fade" id="existingPlaylistsModal" tabindex="-1" aria-labelledby="existingPlaylistsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existingPlaylistsModalLabel">Existing Playlists</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php foreach($playlists as $playlist): ?>
                        <br>
                        <a href="playlist/<?= $playlist['id'] ?>"><?= $playlist['name'] ?></a>
                        <br>
                    <?php endforeach; ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlaylistModal">Create New Playlist</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Create New Playlist Modal -->
    <div class="modal fade" id="createPlaylistModal" tabindex="-1" aria-labelledby="createPlaylistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlaylistModalLabel">Create New Playlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for creating a new playlist -->
                    <form action="/addplaylist" method="post">
                        <div class="mb-3">
                            <label for="newPlaylistName" class="form-label">Playlist Name</label>
                            <input type="text" class="form-control" id="newPlaylistName" name="playlistName" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

        <!-- Add music upload form -->
        <form method="post" action="/music/upload" enctype="multipart/form-data" id="upload-form">
            <!-- Use a regular input for file selection -->
            <input type="file" id="music_file" name="music_file" style="display: none;">
            <!-- Display the selected file name -->
            <span id="selected-file" style="display: none;">No Music Found!</span>
            <!-- Add a hidden input to submit the form when the file is selected -->
            <input type="submit" value="Upload" style="display: none;">
        </form>

        <!-- Add the "Choose File" button -->
        <label for="music_file" class="btn btn-primary">
            <span id="choose-file-label">Add Music</span>
            <input type="file" id="music_file" name="music_file" style="display: none;">
        </label>

        <!-- Display the selected file name -->
        <span id="selected-file" style="display: none;">No Music Found!</span>

        <!-- Add the "Upload" button with initial display set to none -->
        <button id="upload-button" class="btn btn-primary" style="display: none;">Upload</button>
    </div>

    <audio id="audio" controls autoplay></audio>
    <!-- Add a list to display uploaded music -->
    <div id="song-table-container">
        <h1>All Songs</h1>
            <table border="1">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>File Path</th>
                        <th>Playlist</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($songs) && is_array($songs)) : ?>
                    <?php foreach ($songs as $song): ?>
                        <tr>
                            <td><?= $song['title'] ?></td>
                            <td><?= $song['artist'] ?></td>
                            <td><?= $song['file_path'] ?></td>
                            <td><?= $song['playlist_id'] ? getPlaylistName($song['playlistId']) : 'No Playlist' ?></td>
                            <td>
                                <td style="display: flex; justify-content: center; gap: 10px;">
                                    <!-- Add to Playlist button with an icon -->
                                    <button class="add-to-playlist-button" data-song-id="<?= $song['id'] ?>" data-song="<?= $song['file_path'] ?>" data-bs-toggle="modal" data-bs-target="#addToPlaylistModal" style="background-color: transparent; border: none; outline: none;">
                                        <i class="fas fa-plus" style="color: green; font-size: 20px;"></i>
                                    </button>

                                    <!-- Play button with an icon -->
                                    <button class="play-button" data-song="<?= $song['file_path'] ?>" style="background-color: transparent; border: none; outline: none;">
                                        <i class="fas fa-play" style="color: blue; font-size: 20px;"></i>
                                    </button>
                                </td>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="4">No songs available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>

    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Select from playlist</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="/add-to-playlist/<?= $playlistId ?>/<?= $trackId ?>" method="post">
                        <!-- <p id="modalData"></p> -->
                        <input type="hidden" id="musicID" name="musicID">
                        <select name="playlist" class="form-control">
                            <option value="playlist">playlist</option>
                        </select>
                        <input type="submit" name="add">
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add to Playlist Modal -->
    <div class="modal fade" id="addToPlaylistModal" tabindex="-1" aria-labelledby="addToPlaylistModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addToPlaylistModalLabel">Add to Playlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/add-to-playlist" method="post" id="addToPlaylistForm">
                        <input type="hidden" id="songFilePath" name="songFilePath">
                        <div class="mb-3">
                            <label for="playlistSelect" class="form-label">Select Playlist</label>
                            <select class="form-select" id="playlistSelect" name="playlistId" required>
                                <option value="" disabled selected>Select a Playlist</option>
                                <?php foreach ($playlists as $playlist): ?>
                                    <option value="<?= $playlist['id'] ?>"><?= $playlist['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <input type="hidden" id="trackId" name="trackId" value=""> <!-- Hidden input for trackId -->
                        <button type="submit" class="btn btn-primary">Add to Playlist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Set the selected playlist ID before showing the modal
        $(".add-to-playlist-button").click(function () {
            const selectedPlaylistId = $(this).data("playlist-id"); // Assuming you have a data attribute for playlist ID
            $("#playlistSelect").val(selectedPlaylistId); // Set the selected playlist in the dropdown
            $("#addToPlaylistForm").find("#trackId").val($(this).data("song-id")); // Set the track ID
            $("#addToPlaylistModal").modal("show");
        });
            let currentTrack = 0;

            function playTrack(trackIndex) {
                if (trackIndex >= 0 && trackIndex < playlistItems.length) {
                    const track = playlistItems[trackIndex];
                    const trackSrc = track.getAttribute('data-src');
                    audio.src = trackSrc;
                    audio.play();
                    currentTrack = trackIndex;

                    // Set the trackId in the modal form
                    document.getElementById('trackId').value = track.getAttribute('data-song-id');
                }
            }

            // Rest of your code for playTrack and event listeners...

            // When the "Add to Playlist" button is clicked, show the modal
            $(".add-to-playlist-button").click(function () {
                $("#addToPlaylistModal").modal("show");
            });
        });
    </script>
    <script>
        const musicFileInput = document.getElementById('music_file');
        const selectedFileLabel = document.getElementById('selected-file');
        const uploadForm = document.getElementById('upload-form');
        const uploadButton = document.getElementById('upload-button');
        const chooseFileLabel = document.getElementById('choose-file-label');
        const playlist = document.getElementById('playlist'); // Reference to the playlist container

        // When the user selects a file, display its name and show the "Upload" button
        musicFileInput.addEventListener('change', () => {
            const fileName = musicFileInput.value.split('\\').pop();
            selectedFileLabel.textContent = fileName;

            // Hide the "Choose File" label and show the "Upload" button and selected file label
            chooseFileLabel.style.display = 'none';
            uploadButton.style.display = 'block';
            selectedFileLabel.style.display = 'block';
        });

        // Add click event listener to the "Upload" button
        uploadButton.addEventListener('click', () => {
            // Trigger the form submission when the "Upload" button is clicked
            uploadForm.submit();
        });

        // After the form submission, redirect to the same page
        uploadForm.addEventListener('submit', async (e) => {
            // Prevent the default form submission
            e.preventDefault();

            try {
                const formData = new FormData(uploadForm); // Get the form data
                const response = await fetch('/music/upload', {
                    method: 'POST',
                    body: formData, // Send the form data to the server
                });

                if (response.ok) {
                    // Redirect to the same page
                    window.location.reload();
                } else {
                    // Handle errors if the server response is not okay
                    console.error('Error uploading the file.');
                }
            } catch (error) {
                console.error('An error occurred:', error);
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            // Get references to the button and modal
            const modal = $("#myModal");
            const modalData = $("#modalData");
            const musicID = $("#musicID");
            // Function to open the modal with the specified data
            function openModalWithData(dataId) {
                // Set the data inside the modal content
                modalData.text("Data ID: " + dataId);
                musicID.val(dataId);
                // Display the modal
                modal.css("display", "block");
            }

            // Add click event listeners to all open modal buttons

            // When the user clicks the close button or outside the modal, close it
            modal.click(function (event) {
                if (event.target === modal[0] || $(event.target).hasClass("close")) {
                    modal.css("display", "none");
                }
            });
        });
    </script>
    <script>
        const audio = document.getElementById('audio');
        const playlist = document.getElementById('playlist');
        const playlistItems = playlist.querySelectorAll('button');

        let currentTrack = 0;

        function playTrack(trackIndex) {
            if (trackIndex >= 0 && trackIndex < playlistItems.length) {
                const track = playlistItems[trackIndex];
                const trackSrc = track.getAttribute('data-src');
                audio.src = trackSrc;
                audio.play();
                currentTrack = trackIndex;
            }
        }

        function nextTrack() {
            currentTrack = (currentTrack + 1) % playlistItems.length;
            playTrack(currentTrack);
        }

        function previousTrack() {
            currentTrack = (currentTrack - 1 + playlistItems.length) % playlistItems.length;
            playTrack(currentTrack);
        }

        playlistItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                playTrack(index);
            });
        });

        audio.addEventListener('ended', () => {
            nextTrack();
        });

        playTrack(currentTrack);
    </script>
</body>
</html>
