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
?>

<div class="ai1wm-container">
	<div class="ai1wm-row">
		<div class="ai1wm-left">
			<div class="ai1wm-holder">
				<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'OneDrive Settings', AI1WMOE_PLUGIN_NAME ); ?></h1>
				<br />
				<br />

				<div class="ai1wm-field">
					<?php if ( $token ) : ?>
						<p id="ai1wmoe-onedrive-details">
							<span class="spinner" style="visibility: visible;"></span>
							<?php _e( 'Retrieving OneDrive account details..', AI1WMOE_PLUGIN_NAME ); ?>
						</p>

						<div id="ai1wmoe-onedrive-progress">
							<div id="ai1wmoe-onedrive-progress-bar"></div>
						</div>

						<p id="ai1wmoe-onedrive-space"></p>

						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmoe_onedrive_revoke' ) ); ?>">
							<button type="submit" class="ai1wm-button-red" name="ai1wmoe_onedrive_logout" id="ai1wmoe-onedrive-logout">
								<i class="ai1wm-icon-exit"></i>
								<?php _e( 'Sign Out from your OneDrive account', AI1WMOE_PLUGIN_NAME ); ?>
							</button>
						</form>

					<?php else : ?>

						<form method="post" action="<?php echo esc_url( AI1WMOE_ONEDRIVE_URL ); ?>">
							<input type="hidden" name="ai1wmoe_client" id="ai1wmoe-client" value="<?php echo esc_url( network_admin_url( 'admin.php?page=ai1wmoe_settings' ) ); ?>" />
							<button type="submit" class="ai1wm-button-blue" name="ai1wmoe_onedrive_link" id="ai1wmoe-onedrive-link">
								<i class="ai1wm-icon-enter"></i>
								<?php _e( 'Link your OneDrive account', AI1WMOE_PLUGIN_NAME ); ?>
							</button>
						</form>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $token ) : ?>
				<div id="ai1wmoe-backups" class="ai1wm-holder">
					<h1><i class="ai1wm-icon-gear"></i> <?php _e( 'OneDrive Backups', AI1WMOE_PLUGIN_NAME ); ?></h1>
					<br />
					<br />

					<?php if ( Ai1wm_Message::has( 'settings' ) ) : ?>
						<div class="ai1wm-message ai1wm-success-message">
							<p><?php echo Ai1wm_Message::get( 'settings' ); ?></p>
						</div>
					<?php endif; ?>

					<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php?action=ai1wmoe_onedrive_settings' ) ); ?>">
						<article class="ai1wmoe-article">
							<h3><?php _e( 'Configure your backup plan', AI1WMOE_PLUGIN_NAME ); ?></h3>
							<ul id="ai1wmoe-onedrive-cron">
								<li>
									<label for="ai1wmoe-onedrive-cron-hourly">
										<input type="checkbox" name="ai1wmoe_onedrive_cron[]" id="ai1wmoe-onedrive-cron-hourly" value="hourly" <?php echo in_array( 'hourly', $onedrive_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every hour', AI1WMOE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmoe-onedrive-cron-daily">
										<input type="checkbox" name="ai1wmoe_onedrive_cron[]" id="ai1wmoe-onedrive-cron-daily" value="daily" <?php echo in_array( 'daily', $onedrive_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every day', AI1WMOE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmoe-onedrive-cron-weekly">
										<input type="checkbox" name="ai1wmoe_onedrive_cron[]" id="ai1wmoe-onedrive-cron-weekly" value="weekly" <?php echo in_array( 'weekly', $onedrive_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every week', AI1WMOE_PLUGIN_NAME ); ?>
									</label>
								</li>
								<li>
									<label for="ai1wmoe-onedrive-cron-monthly">
										<input type="checkbox" name="ai1wmoe_onedrive_cron[]" id="ai1wmoe-onedrive-cron-monthly" value="monthly" <?php echo in_array( 'monthly', $onedrive_backup_schedules ) ? 'checked' : null; ?> />
										<?php _e( 'Every month', AI1WMOE_PLUGIN_NAME ); ?>
									</label>
								</li>
							</ul>

							<p>
								<?php _e( 'Last backup date:', AI1WMOE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $last_backup_date; ?>
								</strong>
							</p>

							<p>
								<?php _e( 'Next backup date:', AI1WMOE_PLUGIN_NAME ); ?>
								<strong>
									<?php echo $next_backup_date; ?>
								</strong>
							</p>

							<p>
								<label for="ai1wmoe-onedrive-ssl">
									<input type="checkbox" name="ai1wmoe_onedrive_ssl" id="ai1wmoe-onedrive-ssl" value="1" <?php echo empty( $ssl ) ? 'checked' : null; ?> />
									<?php _e( 'Disable connecting to OneDrive via SSL (only if export is failing)', AI1WMOE_PLUGIN_NAME ); ?>
								</label>
							</p>
						</article>

						<article class="ai1wmoe-article">
							<h3><?php _e( 'Destination folder', AI1WMOE_PLUGIN_NAME ); ?></h3>
							<p id="ai1wmoe-onedrive-folder-details">
								<span class="spinner" style="visibility: visible;"></span>
								<?php _e( 'Retrieving OneDrive folder details..', AI1WMOE_PLUGIN_NAME ); ?>
							</p>
							<p>
								<input type="hidden" name="ai1wmoe_onedrive_folder_id" id="ai1wmoe-onedrive-folder-id" />
								<button type="button" class="ai1wm-button-gray" name="ai1wmoe_onedrive_change" id="ai1wmoe-onedrive-change">
									<i class="ai1wm-icon-folder"></i>
									<?php _e( 'Change', AI1WMOE_PLUGIN_NAME ); ?>
								</button>
							</p>
						</article>

						<article class="ai1wmoe-article">
							<h3><?php _e( 'Notification settings', AI1WMOE_PLUGIN_NAME ); ?></h3>
							<p>
								<label for="ai1wmoe-onedrive-notify-toggle">
									<input type="checkbox" id="ai1wmoe-onedrive-notify-toggle" name="ai1wmoe_onedrive_notify_toggle" <?php echo empty( $notify_ok_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email when a backup is complete', AI1WMOE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmoe-onedrive-notify-error-toggle">
									<input type="checkbox" id="ai1wmoe-onedrive-notify-error-toggle" name="ai1wmoe_onedrive_notify_error_toggle" <?php echo empty( $notify_error_toggle ) ? null : 'checked'; ?> />
									<?php _e( 'Send an email if a backup fails', AI1WMOE_PLUGIN_NAME ); ?>
								</label>
							</p>

							<p>
								<label for="ai1wmoe-onedrive-notify-email">
									<?php _e( 'Email address', AI1WMOE_PLUGIN_NAME ); ?>
									<br />
									<input class="ai1wmoe-email" style="width: 15rem;" type="email" id="ai1wmoe-onedrive-notify-email" name="ai1wmoe_onedrive_notify_email" value="<?php echo esc_attr( $notify_email ); ?>" />
								</label>
							</p>
						</article>

						<article class="ai1wmoe-article">
							<h3><?php _e( 'Retention settings', AI1WMOE_PLUGIN_NAME ); ?></h3>
							<p>
								<div class="ai1wm-field">
									<label for="ai1wmoe-onedrive-backups">
										<?php _e( 'Keep the most recent', AI1WMOE_PLUGIN_NAME ); ?>
										<input style="width: 3em;" type="number" min="0" name="ai1wmoe_onedrive_backups" id="ai1wmoe-onedrive-backups" value="<?php echo intval( $backups ); ?>" />
									</label>
									<?php _e( 'backups. <small>Default: <strong>0</strong> unlimited</small>', AI1WMOE_PLUGIN_NAME ); ?>
								</div>

								<div class="ai1wm-field">
									<label for="ai1wmoe-onedrive-total">
										<?php _e( 'Limit the total size of backups to', AI1WMOE_PLUGIN_NAME ); ?>
										<input style="width: 4em;" type="number" min="0" name="ai1wmoe_onedrive_total" id="ai1wmoe-onedrive-total" value="<?php echo intval( $total ); ?>" />
									</label>
									<select style="margin-top: -2px;" name="ai1wmoe_onedrive_total_unit" id="ai1wmoe-onedrive-total-unit">
										<option value="MB" <?php echo strpos( $total, 'MB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'MB', AI1WMOE_PLUGIN_NAME ); ?></option>
										<option value="GB" <?php echo strpos( $total, 'GB' ) !== false ? 'selected="selected"' : null; ?>><?php _e( 'GB', AI1WMOE_PLUGIN_NAME ); ?></option>
									</select>
									<?php _e( '<small>Default: <strong>0</strong> unlimited</small>', AI1WMOE_PLUGIN_NAME ); ?>
								</div>
							</p>
						</article>

						<article class="ai1wmoe-article">
							<h3><?php _e( 'Transfer settings', AI1WMOE_PLUGIN_NAME ); ?></h3>
							<div class="ai1wm-field">
								<label><?php _e( 'Slow Internet (Home)', AI1WMOE_PLUGIN_NAME ); ?></label>
								<input name="ai1wmoe_onedrive_file_chunk_size" min="5242880" max="20971520" step="5242880" type="range" value="<?php echo $file_chunk_size; ?>" id="ai1wmoe-onedrive-file-chunk-size" />
								<label><?php _e( 'Fast Internet (Internet Servers)', AI1WMOE_PLUGIN_NAME ); ?></label>
							</div>
						</article>

						<p>
							<button type="submit" class="ai1wm-button-blue" name="ai1wmoe_onedrive_update" id="ai1wmoe-onedrive-update">
								<i class="ai1wm-icon-database"></i>
								<?php _e( 'Update', AI1WMOE_PLUGIN_NAME ); ?>
							</button>
						</p>
					</form>
				</div>
			<?php endif; ?>

			<?php do_action( 'ai1wmoe_settings_left_end' ); ?>

		</div>
		<div class="ai1wm-right">
			<div class="ai1wm-sidebar">
				<div class="ai1wm-segment">
					<?php if ( ! AI1WM_DEBUG ) : ?>
						<?php include AI1WM_TEMPLATES_PATH . '/common/share-buttons.php'; ?>
					<?php endif; ?>

					<h2><?php _e( 'Leave Feedback', AI1WMOE_PLUGIN_NAME ); ?></h2>

					<?php include AI1WM_TEMPLATES_PATH . '/common/leave-feedback.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
