<?php

/**
 * Provide the invididual plugin view inside the browsing view for the plugin
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<div class="giu-browse-plugin">
  <a href="<?= $repo['html_url'] ?>" target="_blank">
    <h2 class="name"><?= $repo['full_name'] ?></h2>
  </a>

  <div class="giu-browse-plugin-desc">
    <?= $repo['description'] ?>
  </div>

  <div class="giu-browse-plugin-meta">
    <div class="stars">Stars: <?= $repo['stargazers_count'] ?></div>
    <div class="watchers">Watchers: <?= $repo['watchers_count'] ?></div>
    <div class="language">Language: <?= $repo['language'] ?></div>
  </div>

  <input type="submit" class="button button-primary" value="Install Plugin" data-repo-url="#" />
</div>
