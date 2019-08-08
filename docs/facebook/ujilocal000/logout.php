<?php
# Include configuration file
include '../../../i-tatarajah.php';# untuk capaian localhost

# Remove access token from session
unset($_SESSION['facebook_access_token']);

# Remove user data from session
unset($_SESSION['userData']);

# Redirect to the homepage
header("Location:index.php");
