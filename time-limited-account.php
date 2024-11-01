<?php
/*
Plugin Name: Time Limited Account
Description: With this plugin you can time limite the users accounts registered on your website for a specific amount of time.
Version: 1.0
Author: Ayman Al Zarrad
Author URI: https://profiles.wordpress.org/aymanalzarrad/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

require plugin_dir_path( __FILE__ ) . 'options.php';
require plugin_dir_path( __FILE__ ) . 'admin.php';
require plugin_dir_path( __FILE__ ) . 'login.php';
require plugin_dir_path( __FILE__ ) . 'loggedin.php';
?>