
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
