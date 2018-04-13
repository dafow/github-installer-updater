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
<div class="notice notice-error is-dismissible">
  <p>
    <?= get_transient('giu-errors') ?>
  </p>
  <p>
    <?php
      //var_dump(get_transient('giu-debug'));
    ?>
  </p>
</div>

<h1>Browse Github Plugins</h1>

<?php if( current_user_can('install_plugins') ): ?>
  <p class="giu-browse-help">
    Enter a repository name (e.g: gutenberg), or the owner's name followed by a forward slash and the repository's name (e.g: WordPress/gutenberg).<br />
    You can also directly enter a repository's URL (e.g: https://github.com/Wordpress/gutenberg).
  </p>
  <form action="<?= esc_url( admin_url( 'admin-post.php' ) ) ?>" method="POST">
    <input name="q" type="text" required />
    <input type="hidden" name="action" value="browse_plugins" />
    <?php wp_nonce_field('giu-browse-plugins', '_giunonce') ?>

    <input type="submit" name="submit" class="button button-primary" value="Browse Plugins" />
  </form>

  <div class="giu-browse-grid">
    <pre>
      <?= print_r(get_transient('giu-browse-repos')) ?>
    </pre>
  </div>

  <?php if ( isset( $_GET['q'] ) && !empty( $_GET['q'] ) ): ?>
    <div class="giu-browse-pagination">
      <form action="<?= esc_url( admin_url( 'admin-post.php' ) ) ?>" method="POST">
        <input name="q" type="hidden" value="<?= urldecode( $_GET['q'] ) ?>" />
        <input name="p" type="hidden" value="<?= isset( $_GET['p'] ) ? intval( $_GET['p'], 10 ) : 1 ?>" />
        <input type="hidden" name="action" value="browse_plugins" />
        <?php wp_nonce_field('giu-browse-plugins', '_giunonce') ?>

        <input type="submit" name="submit" class="button button-primary" value="Next Page" />
      </form>
    </div>
  <?php endif; ?>
<?php else: ?>
  <p>You are not authorized to perform this action.</p>
<?php endif; ?>
