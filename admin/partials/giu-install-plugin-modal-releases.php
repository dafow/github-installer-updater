<?php

/**
 * Provide the HTML fragment returned to show more information about a selected download option
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<h3>Select a release to get the plugin's file from:</h3>
<select>
  <?php foreach ( $releases as $release ): ?>
    <?php
      $zip_url = strpos ( $release['zipball_url'], 'zipball/' );
      $zip_url = substr( $release['zipball_url'], $zip_url );
    ?>

    <option value="<?= $zip_url ?>"><?= !empty( $release['name'] ) ? $release['name'] : $release['tag_name'] ?></option>
  <?php endforeach; ?>
</select>
