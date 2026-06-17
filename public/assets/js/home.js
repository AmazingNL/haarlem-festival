document.addEventListener('DOMContentLoaded', function () {
	const galleryButtons = document.querySelectorAll('[data-gallery-target]');

	galleryButtons.forEach(function (button) {
		button.addEventListener('click', function () {
			const targetId = button.getAttribute('data-gallery-target');
			const direction = Number(button.getAttribute('data-gallery-direction') || '1');
			const viewport = targetId ? document.getElementById(targetId) : null;

			if (!viewport) {
				return;
			}

			const distance = viewport.clientWidth * 0.75;
			viewport.scrollBy({
				left: distance * direction,
				behavior: 'smooth',
			});
		});
	});
});
