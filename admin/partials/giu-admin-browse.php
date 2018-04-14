<?php

/**
 * Provide the browsing view for the plugin
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<?php
  $errors_flash = get_transient( 'giu-errors' );
  if ( $errors_flash !== false ): ?>
<div class="notice notice-error is-dismissible">
  <p>
    <?= get_transient( 'giu-errors' ) ?>
  </p>
</div>
<?php endif; ?>

<h1>Browse Github Plugins</h1>

<?php if( current_user_can( 'install_plugins' ) ): ?>
  <p class="giu-browse-help">
    <ul>
      <li>Enter keywords (e.g: gutenberg).</li>
      <li>Enter the owner's name followed by a forward slash and the repository's name (e.g: WordPress/gutenberg).</li>
      <li>Enter a repository's URL (e.g: https://github.com/Wordpress/gutenberg).</li>
    </ul>
  </p>
  <form action="<?= esc_url( admin_url( 'admin-post.php' ) ) ?>" method="POST">
    <input name="q" type="text" required />
    <input type="hidden" name="action" value="browse_plugins" />
    <?php wp_nonce_field( 'giu-browse-plugins', '_giunonce' ) ?>

    <input type="submit" name="submit" class="button button-primary" value="Browse Plugins" />
  </form>

  <div class="giu-browse-grid">
    <?php
      $repos = get_transient( 'giu-browse-repos' );
      if ( $repos !== false ):
    ?>
      <div class="giu-browse-plugins-grid">
        <?php if ( isset( $repos['total_count'] ) ): ?>
          <?php foreach ( $repos['items'] as $repo ): ?>
            <?php include plugin_dir_path( __FILE__ ) . 'giu-browse-plugin.php'; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <?php
            $repo = $repos;
            include plugin_dir_path( __FILE__ ) . 'giu-browse-plugin.php';
          ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if ( isset( $_GET['q'] ) && !empty( $_GET['q'] ) && $repos['items'] > 0 ): ?>
    <div class="giu-browse-pagination">
      <form action="<?= esc_url( admin_url( 'admin-post.php' ) ) ?>" method="POST">
        <input name="q" type="hidden" value="<?= urldecode( $_GET['q'] ) ?>" />
        <input name="p" type="hidden" value="<?= isset( $_GET['p'] ) ? intval( $_GET['p'], 10 ) : 1 ?>" />
        <input type="hidden" name="action" value="browse_plugins" />
        <?php wp_nonce_field( 'giu-browse-plugins', '_giunonce' ) ?>

        <input type="submit" name="submit" class="button button-primary" value="Next Page" />
      </form>
    </div>
  <?php endif; ?>
<?php else: ?>
  <p>You are not authorized to perform this action.</p>
<?php endif; ?>
