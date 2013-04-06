
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

	$('a', '.song .keys')
		.click(keyChange);

	$(document)
		.ajaxStart(function() {
			$('#activity-indicator').stop(true).animate({opacity: 1}, 'fast');
		})
		.ajaxStop(function() {
			$('#activity-indicator').stop(true, true).animate({opacity: 0}, 'slow');
		});
});

function keyChange()
{
	$.get($(this).attr('href'), keyChanged);
	return false;
}

function keyChanged(data)
{
	$('a', '.song .keys')
		.each(function(){
			if($(this).text() == data.key)
				$(this).addClass('key');
			else
				$(this).removeClass('key');
		});

	$('.lyrics', '.song')
		.replaceWith(data.html);
}

function searchFocus(event, s)
{
	return false;
}

function searchSelect(event, s)
{
	window.location = Site.base_url + 'song/' + s.item.value;
	return false;
}
