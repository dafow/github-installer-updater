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
    <option data-repo-zipball="<?= $tag['zipball_url'] ?>" data-repo-name="<?= $repo_owner . '/' . $repo_name ?>" data-repo-source="tag" data-repo-version="<?= $tag['name'] ?>">
      <?= !empty( $tag['name'] ) ? $tag['name'] : $tag['tag_name'] ?>
    </option>
  <?php endforeach; ?>
</select>

<input type="submit" name="submit" class="button button-primary install-plugin" value="Install as a plugin" />
