<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
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
    <!-- Navigation and Search Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Music Player</a>
            <form class="d-flex" action="/search" method="get">
                <input class="form-control me-2" type="search" name="search" placeholder="Search for a song">
                <button class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
    <h1>Music Player</h1>
            <!-- Flex container for buttons -->
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <!-- Button to open the "My Playlist" modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#existingPlaylistsModal">My Playlist</button>

                        <!-- Add Music Button -->
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
                </div>
            </div>
        </div>
    </div>

        <!-- Audio Player -->
        <audio id="audio" controls></audio>

        <!-- Add to Playlist Modal -->
        <div class="modal fade" id="addToPlaylistModal" tabindex="-1" aria-labelledby="addToPlaylistModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToPlaylistModalLabel">Select Playlist</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <!-- Form for selecting a playlist -->
                        <form id="selectPlaylistForm">
                            <input type="hidden" id="selectedSongId" name="selectedSongId">
                            <div class="mb-3">
                                <label for="playlistSelect" class="form-label">Select Playlist</label>
                                <select name="playlist" id="playlistSelect" class="form-select">
                                    <?php foreach ($playlists as $playlist): ?>
                                        <option value="<?= $playlist['id'] ?>">
                                            <?= $playlist['name'] ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    <!-- Table -->
    <table class="table">
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
            <?php foreach ($songs as $song): ?>
                <tr>
                    <td><?= $song['title'] ?></td>
                    <td><?= $song['artist'] ?></td>
                    <td><?= $song['file_path'] ?></td>
                    <td>
                        <!-- Check if the song is in any playlists -->
                        <?php if (!empty($song['playlists'])): ?>
                            <ul>
                                <?php foreach ($song['playlists'] as $playlist): ?>
                                    <li><?= $playlist['name'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            Not in any playlist
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (empty($song['playlists'])): ?>
                            <button class="btn btn-primary add-to-playlist-button" data-song-id="<?= $song['id'] ?>" data-toggle="modal" data-target="#addToPlaylistModal">Add to Playlist</button>
                        <?php else: ?>
                            <button class="btn btn-danger">Remove from Playlist</button>
                        <?php endif; ?>
                        <!-- Play button -->
                        <button class="play-button" data-song="<?= $song['file_path'] ?>" style="background-color: transparent; border: none; outline: none;">
                            <i class="fas fa-play" style="color: blue; font-size: 20px;"></i>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!--AddtoPlaylist-->
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
                    <form action="/addToPlaylist" method="post">
                        <!-- <p id="modalData"></p> -->
                        <input type="hidden" id="musicID" name="musicID">
                        <select name="playlistID" class="form-control">
                            <?php foreach ($playlists as $playlist): ?>
                                <option value="<?= $playlist['id'] ?>">
                                    <?= $playlist['name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input class="btn btn-primary mt-2 d-flex" type="submit" name="Add">
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Existing Playlists Modal -->
    <div class="modal fade" id="existingPlaylistsModal" tabindex="-1" aria-labelledby="existingPlaylistsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="existingPlaylistsModalLabel">Existing Playlists</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- List of existing playlists -->
                    <?php foreach ($playlists as $playlist): ?>
                        <br>
                        <a href="/showPlaylist/<?= $playlist['id'] ?>" class="existing-playlist-link" data-playlist-id="<?= $playlist['id'] ?>"><?= $playlist['name'] ?></a>
                        <br>
                    <?php endforeach; ?>
                    <!-- Create New Playlist Button -->
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

    <!--Add to Playlist-->
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
                    <form action="/addToPlaylist2" method="post">
                        <!-- <p id="modalData"></p> -->
                        <input type="hidden" id="musicID" name="musicID">
                        <select  name="playlist" class="form-control" >
                        <?php foreach ($playlists as $playlist): ?>
                            <?php if (isset($playlist['playlist_id'])): ?>
                                <option value="<?= $playlist['playlist_id'] ?>">
                                    <?= $playlist['PlaylistName'] ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </select>
                        <input class="btn btn-primary mt-2 d-flex" type="submit" name="Add">
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript (include only one version) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (include only one version) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery script -->
    <!-- JavaScript for setting the track_id when the "Add to playlist" button is clicked -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // Listen for clicks on the "Add to Playlist" button
            $('.add-to-playlist-button').click(function () {
                // Get the song ID from the data attribute
                var trackId = $(this).data('id');

                // Set the song ID in the hidden input field
                $('#musicID').val(trackId);

                // Open the modal
                $('#addToPlaylistModal').modal('show');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Add a click event listener to the "Add to Playlist" button
            $('.add-music-to-playlist-button').on('click', function() {
                // Retrieve the data-song-id and data-playlist-id attribute values
                var songId = $(this).data('song-id');
                var playlistId = $(this).data('playlist-id');

                // Show the modal
                $('#addToPlaylistModal').modal('show');

                // Store the songId and playlistId as data attributes on the modal's Add button
                $('#addToPlaylistModal #add-to-playlist-button').data('song-id', songId);
                $('#addToPlaylistModal #add-to-playlist-button').data('playlist-id', playlistId);
            });

            // Listen for clicks on the "Add" button inside the modal
            $('#add-to-playlist-button').click(function() {
                // Get the songId and playlistId from the button's data attributes
                var songId = $(this).data('song-id');
                var playlistId = $(this).data('playlist-id');

                // Perform an AJAX request to add the track to the playlist
                $.ajax({
                    url: '/addToPlaylist', // Replace with your actual URL
                    method: 'POST',
                    data: {
                        trackID: songId,
                        playlistID: playlistId
                    },
                    success: function(response) {
                        // Handle success, e.g., show a success message
                        alert('Track added to playlist successfully');
                    },
                    error: function(error) {
                        // Handle error, e.g., show an error message
                        alert('Error adding track to playlist');
                    }
                });

                // Close the modal
                $('#addToPlaylistModal').modal('hide');
            });
        });
    </script>



    <!-- Vanilla JavaScript script for "Add to Playlist" buttons -->
    <script>
        // Get all elements with the class "add-to-playlist-button"
        const addToPlaylistButtons = document.querySelectorAll('.add-to-playlist-button');

        // Add a click event listener to each button
        addToPlaylistButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the data-song-id attribute value from the button
                const songId = button.getAttribute('data-song-id');

                // Set the trackID input value in the modal's form
                document.getElementById('trackID').value = songId;
            });
        });
    </script>

    <!-- jQuery script for "Add to Playlist" buttons (alternate) -->
    <script>
        $(document).ready(function () {
            // Listen for clicks on "Add to Playlist" buttons
            $('.add-to-playlist').click(function () {
                // Get the track ID from the clicked button's data attribute
                var trackID = $(this).data('track-id');

                // Set the trackID input field's value
                $('#trackID').val(trackID);
            });
        });
    </script>

    <!-- Vanilla JavaScript script for removing from playlist -->
    <script>
        function prepareRemoveFromPlaylistModal(button) {
            const songId = button.getAttribute('data-song-id');
            const modal = document.getElementById('removeFromPlaylistModal');
            const trackIdInput = modal.querySelector('input[name="trackId"]');
            trackIdInput.value = songId;
        }
    </script>

    <!-- Vanilla JavaScript script for preparing "Add to Playlist" modal -->
    <script>
        function prepareAddToPlaylistModal(button) {
            // Extract song information from the button's data attributes
            const songId = button.getAttribute('data-song-id');
            const songFilePath = button.getAttribute('data-song');

            // Update the modal form fields with the song information
            const modal = document.getElementById('addToPlaylistModal');
            const playlistSelect = modal.querySelector('#playlistSelect');
            const songFilePathInput = modal.querySelector('#songFilePath');

            // Set the song ID as a data attribute on the modal for reference during submission
            modal.setAttribute('data-song-id', songId);

            // Set the song file path in the hidden input field
            songFilePathInput.value = songFilePath;

            // Reset the playlist select field to its default value
            playlistSelect.value = '';
        }
    </script>

    <!-- JavaScript for uploading music files -->
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

    <!-- jQuery script for modal -->
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

    <!-- JavaScript for controlling audio playback -->
    <script>
        const audio = document.getElementById('audio');
        const playButtons = document.querySelectorAll('.play-button');

        let currentTrack = 0;

        function playTrack(trackIndex) {
            if (trackIndex >= 0 && trackIndex < playButtons.length) {
                const button = playButtons[trackIndex];
                const trackSrc = button.getAttribute('data-song');
                audio.src = trackSrc;
                audio.play();
                currentTrack = trackIndex;
            }
        }

        function nextTrack() {
            currentTrack = (currentTrack + 1) % playButtons.length;
            playTrack(currentTrack);
        }

        function previousTrack() {
            currentTrack = (currentTrack - 1 + playButtons.length) % playButtons.length;
            playTrack(currentTrack);
        }

        playButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
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
