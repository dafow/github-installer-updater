<?php

/**
 * Provide the modal view displayed when installing a plugin
 * @link       https://github.com/BBackerry/github-installer-updater
 * @since      1.0.0
 *
 * @package    GithubInstallerUpdater
 * @subpackage GithubInstallerUpdater/admin/partials
 */
?>

<div id="giu-install-plugin-modal">
  <div class="modal-content-container">

    <div class="install-choice">
      <h3>Select a plugin installation method:</h3>
      <div>
        <input type="radio" id="master-last-commit" name="install-choice" value="master-last-commit">
        <label for="master-last-commit">Clone repository from last commit on the master branch</label>
      </div>

      <div>
        <input type="radio" id="release" name="install-choice" value="release">
        <label for="release">Get the plugin files from a published release</label>
      </div>

      <div>
        <input type="radio" id="tag" name="install-choice" value="tag">
        <label for="tag">Get the plugin files from a release by tag name</label>
      </div>

      <input id="giu-install-plugin-choice" type="submit" class="button button-primary" value="View Available Installation Options" data-repo-name="" />
    </div>

    <div class="install-info">

    </div>

    <div class="install-confirmation">

    </div>

  </div>
</div>
