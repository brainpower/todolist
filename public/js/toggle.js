$('.list-row').on('click', (e) => {
	const target = $(e.target);
	const id = target.attr("data-id");
	if (id) {
		const newState = target.hasClass('finished') ? 0 : 1;

		console.log('list item with id', id, 'clicked');
		$.ajax(`?action=update&entry=${id}&state=${newState}`,
			{
				dataType : 'json',
				success: (data, status, xhr) => {
					console.log('data:', data, 'newState:', newState);
					if (data["status"] === "success") {
						if (newState > 0) {
							target.addClass('finished');
							target.removeClass('unstarted');
						} else {
							target.addClass('unstarted');
							target.removeClass('finished');
						}
					}
				},
				error: (xhr, status, error) => {
					console.log('status:', status, 'error:', error, 'xhr:', xhr);
				}
			});
	}
});