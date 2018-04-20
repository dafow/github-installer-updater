<?php

/**
 * Provide the HTML fragment returned to show more information about a repository's tags
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<h3>Select a tag name to get the plugin's archive from:</h3>
<select>
  <?php foreach ( $tags as $tag ): ?>
    <option value="<?= $release['zipball_url'] ?>" data-repo-name="<?= $repo_owner . '/' . $repo_name ?>" data-repo-source="release" data-repo-ver="<?= $release['id'] ?>">
      <?= !empty( $release['name'] ) ? $release['name'] : $release['tag_name'] ?>
    </option>
  <?php endforeach; ?>
</select>

<input type="submit" name="submit" class="button button-primary" value="Next Page" />
