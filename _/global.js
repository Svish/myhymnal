
$(function()
{
	$('input', '#search')
		.autocomplete({
			delay: 50,
			source: $('#search').attr('action'),
			select: searchSelect,
			focus: searchFocus,
			position: {my: 'right top', at: 'right bottom', collision: 'fit'}
		});

	$('a', '#debug-link')
		.click(debugClick);
});

function searchFocus(event, s)
{
	return false;
}

function searchSelect(event, s)
{
	window.location = Site.base_url + 'song/' + s.item.value;
	return false;
}

function debugClick()
{
	var url = $(this).attr('href');
	$.ajax(url, 
	{
		context: this,
		success: debugLoaded,
	});
	return false;
}

function debugLoaded(data)
{
	$(this)
		.remove();
	$('<pre id="debug">')
		.html(data)
		.appendTo('#content')
		.hide()
		.slideDown();
}