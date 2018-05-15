<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<div class="wrap">
<?php
  $errors_flash = get_transient( 'giu-errors' );
  if ( $errors_flash !== false ): ?>
<div class="notice notice-error is-dismissible">
  <p>
    <?= get_transient( 'giu-errors' ) ?>
  </p>
</div>
<?php endif; ?>

<h1>Global Settings</h1>

<?php if( current_user_can( 'install_plugins' ) ): ?>
  <form action="<?= esc_url( admin_url( 'options.php' ) ) ?>" method="POST">
    <?php settings_fields('giu-settings'); ?>
    <?php do_settings_sections('giu'); ?>
    <?php submit_button(); ?>
  </form>
<?php else: ?>
  <p>You are not authorized to perform this action.</p>
<?php endif; ?>
</div>
