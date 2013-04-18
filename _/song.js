$(function()
{
	$('.spotify-link')
		.click(onSpotifyClick);

	$('.c', '.verse')
		.highlight('[A-G][♯♭]?', 'k');

});


function onSpotifyClick()
{
	// Remove old player
	removeSpotifyPlayer();

	// Get HTML
	var uri = $(this).data('spotify-uri');
	var html = $('#spotify-template')
		.clone()
		.html()
		.replace(/:uri/, uri);

	// Append
	html = $($.trim(html))
		.appendTo('.song');

	// Activate button
	html
		.children('button')
		.click(removeSpotifyPlayer);

	// Fade in on iframe load
	html
		.css({opacity: 0})
		.children('iframe')
		.load(function()
		{
			console.info('player loaded...');
			$(this)
				.parent()
				.animate({opacity:1}, 'fast');
		});

	return false;
}

function removeSpotifyPlayer()
{

	$('.spotify-player')
		.fadeOut('fast', function()
		{
			$(this).remove();
		});
}