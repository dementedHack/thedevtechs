<?php
/**
 * Copyright (C) 2014-2019 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Kangaroos cannot jump here' );
}

class Ai1wmoe_Export_Retention {

	public static function execute( $params, Ai1wmoe_OneDrive_Client $onedrive = null ) {

		// Set OneDrive client
		if ( is_null( $onedrive ) ) {
			$onedrive = new Ai1wmoe_OneDrive_Client(
				get_option( 'ai1wmoe_onedrive_token', false ),
				get_option( 'ai1wmoe_onedrive_ssl', true )
			);
		}

		// Get folder items
		$items = $onedrive->list_folder( $params['folder_id'] );

		$backups = array();
		foreach ( $items as $item ) {
			if ( $item['type'] === 'file' && pathinfo( $item['name'], PATHINFO_EXTENSION ) === 'wpress' ) {
				$backups[] = $item;
			}
		}

		// No backups, no need to apply backup retention
		if ( count( $backups ) === 0 ) {
			return $params;
		}

		usort( $backups, 'Ai1wmoe_Export_Retention::sort_by_date_asc' );

		// Number of backups
		if ( ( $backups_limit = get_option( 'ai1wmoe_onedrive_backups', 0 ) ) ) {
			if ( ( $backups_to_remove = count( $backups ) - intval( $backups_limit ) ) > 0 ) {
				for ( $i = 0; $i < $backups_to_remove; $i++ ) {
					$onedrive->delete( $backups[ $i ]['id'] );
				}
			}
		}

		// Sort backups by date desc
		$backups = array_reverse( $backups );

		// Get the size of the latest backup before we remove it
		$size_of_backups = $backups[0]['bytes'];

		// Remove the latest backup, the user should have at least one backup
		array_shift( $backups );

		// Size of backups
		if ( ( $retention_size = ai1wm_parse_size( get_option( 'ai1wmoe_onedrive_total', 0 ) ) ) > 0 ) {
			foreach ( $backups as $backup ) {
				$size_of_backups += $backup['bytes'];

				// Remove file if retention size is exceeded
				if ( $size_of_backups > $retention_size ) {
					$onedrive->delete( $backup['id'] );
				}
			}
		}

		return $params;
	}

	public static function sort_by_date_asc( $first_backup, $second_backup ) {
		return intval( $first_backup['date'] ) - intval( $second_backup['date'] );
	}
}
