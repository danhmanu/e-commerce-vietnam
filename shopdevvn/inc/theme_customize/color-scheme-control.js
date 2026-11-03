( function( api ) {
	var cssTemplate = wp.template( 'devvn-color-scheme' ),
		colorSchemeKeys = [
			'main_site_color',
			'second_site_color',
			'footer_bg_color',
			'footer_text_color',
			'footer_title_sidebar_color',
            'top_header_color',
            'top_header_t_color',
            'enable_site_color',
		],
		colorSettings = [
			'main_site_color',
			'second_site_color',
            'footer_bg_color',
            'footer_text_color',
            'footer_title_sidebar_color',
            'top_header_color',
            'top_header_t_color',
			'enable_site_color',
		];
	// Generate the CSS for the current Color Scheme.
	function updateCSS() {
		var scheme = api( 'main_site_color' )(),
			css,
			colors = _.object( colorSchemeKeys, '' );

		// Merge in color scheme overrides.
		_.each( colorSettings, function( setting ) {
			colors[ setting ] = api( setting )();
		} );

		css = cssTemplate( colors );

		api.previewer.send( 'update-color-scheme-css', css );
	}

	// Update the CSS whenever a color setting is changed.
	_.each( colorSettings, function( setting ) {
		api( setting, function( setting ) {
			setting.bind( updateCSS );
		} );
	} );
} )( wp.customize );