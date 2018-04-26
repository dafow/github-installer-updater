(function($) {
	'use strict';

	$(document).ready(function() {
		//Show modal with installation options when button is clicked
		$('.giu-install-plugin').click(function(e) {
			e.preventDefault();
			let repoName = e.currentTarget.getAttribute('data-repo-name');
			if (repoName === null || repoName === '') { return; }

			//Open fancybox modal
			//http://fancyapps.com/fancybox/3/docs/#api
			$.fancybox.open({
				src:	'#giu-install-plugin-modal',
				type:	'inline',
				opts:	{
					afterShow:	function(instance, current) {
						$('#giu-install-plugin-modal .install-choice .button').attr('data-repo-name', repoName);
					},
					afterClose:	function(instance, current) {
						//Reset modal state
						document.querySelector("#giu-install-plugin-modal .install-info").innerHTML = "";
						document.querySelector("#giu-install-plugin-modal .install-result").innerHTML = "";
					}
				}
			});
		});

		//Get repository's installation options (releases, tags, etc...) via AJAX
		$(document).on('click', '#giu-install-plugin-modal .install-choices', function(e) {
			e.preventDefault();
			let repoName = e.currentTarget.getAttribute('data-repo-name');
			let installChoice = document.querySelector('.install-choice input[name="install-choice"]:checked');
			if (!repoName || !installChoice) { return; }
			
			let installInfoDiv = document.querySelector("#giu-install-plugin-modal .install-info");
			showLoading(installInfoDiv);

			//Get Repository download option info
			//AJAX nonce is available through wp_localize_script

			//If download from master is selected, go directly to installation step
			if (installChoice.value === 'master-last-commit') {
				let repoData = {
					action:					'install_plugin',
					repoName:				repoName,
					installChoice:	installChoice.value,
					_guiAjaxNonce:	giu_ajaxnonce
				};
				$.post(ajaxurl, repoData, function(response) {
					//Populate view with results
					installInfoDiv.innerHTML = '<h3>'+response.message+'</h3>';
				});
			}
			else {
				//Get installation options
				let repoData = {
					action:					'get_repo_install_info',
					repoName:				repoName,
					installChoice:	installChoice.value,
					_guiAjaxNonce:	giu_ajaxnonce
				};
				$.post(ajaxurl, repoData, function(response) {
					//Populate view with results
					installInfoDiv.innerHTML = response;
				});
			}
		});

		//Send AJAX action with repository's information to install as a plugin
		$(document).on('click', '#giu-install-plugin-modal .install-plugin', function(e) {
			e.preventDefault();
			let optionsDropdown = document.querySelector('#giu-install-plugin-modal .install-info select');
			let selectedOption = optionsDropdown.options[optionsDropdown.selectedIndex];
			let repoName = selectedOption.getAttribute('data-repo-name');
			let repoZipball = selectedOption.getAttribute('data-repo-zipball');
			let repoSource = selectedOption.getAttribute('data-repo-source');
			let repoVersion = selectedOption.getAttribute('data-repo-version');
			if (!repoZipball || !repoName || !repoSource || !repoVersion) { return; }

			let installResultDiv = document.querySelector("#giu-install-plugin-modal .install-result");
			showLoading(installResultDiv);

			//Get Repository download option info
			let repoData = {
				action:					'install_plugin',
				repoName:				repoName,
				repoZipball:		repoZipball,
				repoSource:			repoSource,
				repoVersion:		repoVersion,
				_guiAjaxNonce:	giu_ajaxnonce
			};
			$.post(ajaxurl, repoData, function(response) {
				//Populate view with results
				installResultDiv.innerHTML = '<h3>'+response.message+'</h3>';
			});
		});
	});

	function showLoading(el) {
		el.innerHTML = '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
	}
})(jQuery);
