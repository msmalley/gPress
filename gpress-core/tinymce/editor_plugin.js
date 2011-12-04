(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('gPress');

	tinymce.create('tinymce.plugins.gPress', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mcegPress', function() {
				ed.windowManager.open({
					file : url + '/gpress.php',
					width : 420,
					height : 540,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('gPress', {
				title : 'gPress.desc',
				cmd : 'mcegPress',
				image : url + '/gpress.png'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('gPress', n.nodeName == 'IMG');
			});
		}, 

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : 'gPress TinyMCE Plugin',
				author : 'PressBuddies',
				authorurl : 'http://pressbuddies.com',
				infourl : 'http://wordpress.org/extend/plugins/gpress/',
				version : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('gPress', tinymce.plugins.gPress);
})();