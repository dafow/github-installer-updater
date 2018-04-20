<?php

/**
 * Provide the HTML fragment returned to show more information about a repository's releases
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<h3>Select a release to get the plugin's archive from:</h3>
<select>
  <?php foreach ( $releases as $release ): ?>
    <option data-repo-zipball="<?= $release['zipball_url'] ?>" data-repo-name="<?= $repo_owner . '/' . $repo_name ?>" data-repo-source="release" data-repo-version="<?= $release['id'] ?>">
      <?= !empty( $release['name'] ) ? $release['name'] : $release['tag_name'] ?>
    </option>
  <?php endforeach; ?>
</select>

<input type="submit" name="submit" class="button button-primary install-plugin" value="Install as a plugin" />
