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
						document.querySelector("#giu-install-plugin-modal .install-info").innerHTML = "";
						document.querySelector("#giu-install-plugin-modal .install-confirmation").innerHTML = "";
					}
				}
			});
		});

		$(document).on('click', '#giu-install-plugin-choice', function(e) {
			e.preventDefault();
			let repoName = e.currentTarget.getAttribute('data-repo-name');
			let installChoice = document.querySelector('.install-choice input[name="install-choice"]:checked');
			if (repoName === null || repoName === '' || installChoice === null) { return; }

			//Get Repository download option info
			let repoData = {
				action:					'get_repo_install_info',
				repo:						repoName,
				installChoice:	installChoice.value,
				_guiAjaxNonce:	giu_ajaxnonce
			};
			$.post(ajaxurl, repoData, function(response) {
				//Populate view with results
				document.querySelector("#giu-install-plugin-modal .install-info").innerHTML = response;
			});
		});
	});
})(jQuery);
