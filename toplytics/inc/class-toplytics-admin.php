<?php
/*  Copyright 2014 PressLabs SRL <ping@presslabs.com>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


class Toplytics_Admin {
	private $toplytics;

	public function __construct() {
		global $toplytics;
		$this->toplytics = $toplytics;

		if ( current_user_can( 'manage_options' ) ) {
			if ( $this->toplytics->get_token() ) {
				new Toplytics_Submenu_Settings();
			} else {
				new Toplytics_Submenu_Configure();
				add_action( 'admin_init', array( $this, 'admin_notices' ) );
			}
		}
	}

	public function admin_notices() {
		add_action(
			'admin_notices',
			create_function(
				'',
				"echo '<div class=\"error\"><p>"
				. sprintf(
					__( 'Toplytics needs configuration information on its <a href="%s">Settings</a> page.', 'toplytics' ),
					$this->toplytics->return_settings_link()
				)
				. "</p></div>';"
			)
		);
	}
}

if ( is_admin() ) {
	function toplytics_admin_page() {
		new Toplytics_Admin();
	}
	add_action( 'init', 'toplytics_admin_page' );
}
