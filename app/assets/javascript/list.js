var list = {
	init:function() {
		$('.update-list-item').change(function() {
			var tr = $(this).closest('tr');

			var id = tr.data('itemId'),
				name = tr.data('itemName'),
				amount = parseInt($(this).val());

			var message = 'Updating the quantity for ' + name;
			if (amount <= 0)
				message = name + ' will be deleted from your list';

			$.ajax({
				url: '/list/edit',
				type: 'post',
				data: { 'item-id' : id, 'amount' : amount },
				beforeSend:function() {
					global.noty({
						type: amount <= 0 ? 'warning' : 'information',
						text: message
					});
				}
			});
		});

		$('.delete-list-item').click(function() {
			var tr = $(this).closest('tr');

			var id = tr.data('itemId'),
				name = tr.data('itemName');

			$.ajax({
				url: '/list/delete',
				type: 'post',
				data: { 'item-id' : id },
				beforeSend:function() {
					global.noty({
						type: 'warning',
						text: 'Deleting ' + name + ' from your list'
					});
				},
				complete:function() {
					global.fade_and_destroy(tr);
					setTimeout(list.check_if_empty, 1000);
				}
			});
		});
	},
	check_if_empty:function() {
		if ($('#list tbody tr').length == 0)
			window.location = '/list';
	}
}

$(list.init);