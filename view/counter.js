(function($){
	const apiUrl = `${window.location.origin}/api/get.php`;
	const resetUrl = `${window.location.origin}/api/reset.php`;
	const incrementUrl = `${window.location.origin}/api/increment.php`;

	function updateDisplay(data) {
		if (!data) return;
		$('#counter').text(data.quantity);
		if (data.updateDate) {
			$('#updateDate').text('Last update: ' + data.updateDate.date);
		}
	}

	function fetchCounter() {
		$.ajax({
			url: apiUrl,
			method: 'GET',
			dataType: 'json'
		}).done(function (data) {
			updateDisplay(data);
		}).fail(function (xhr, status, err) {
			console.error('Error fetching counter:', err);
		});
	}

	$(document).ready(function () {
		function count() {
			$.ajax({
				url: incrementUrl,
				method: 'PUT',
				dataType: 'json'
			}).done(function (data) {
				updateDisplay(data);
			}).fail(function (xhr, status, err) {
				console.error('Error incrementing counter:', err);
			});
		}

		count();

		// poll every 5 seconds
		setInterval(fetchCounter, 5000);

		$('#resetBtn').on('click', function () {
			if (!confirm('Are you sure you want to reset the counter to 0?')) return;

			$.ajax({
				url: resetUrl,
				method: 'PUT',
				dataType: 'json'
			}).done(function (resp) {
				updateDisplay(resp);
			}).fail(function () {
				$('#message').css('color', '#ff6666').text('Reset request failed');
			});
		});
	});
})(jQuery);


