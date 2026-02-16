/**
 * Navigation Menu Toggle for Mobile
 * Handles hamburger menu open/close on mobile devices
 */

document.addEventListener('DOMContentLoaded', function () {
	const menuToggle = document.querySelector('.menu-toggle');
	const navLinks = document.querySelector('.nav-links');

	if (!menuToggle || !navLinks) return;

	// Toggle menu on hamburger click
	menuToggle.addEventListener('click', function () {
		menuToggle.classList.toggle('active');
		navLinks.classList.toggle('active');
	});

	// Close menu when a link is clicked
	const navLinksItems = document.querySelectorAll('.nav-link');
	navLinksItems.forEach((link) => {
		link.addEventListener('click', function () {
			menuToggle.classList.remove('active');
			navLinks.classList.remove('active');
		});
	});

	// Close menu when clicking outside
	document.addEventListener('click', function (event) {
		const isClickInsideNav = navLinks.contains(event.target);
		const isClickOnToggle = menuToggle.contains(event.target);

		if (!isClickInsideNav && !isClickOnToggle && navLinks.classList.contains('active')) {
			menuToggle.classList.remove('active');
			navLinks.classList.remove('active');
		}
	});

	// Handle window resize to reset mobile menu on larger screens
	window.addEventListener('resize', function () {
		if (window.innerWidth >= 768) {
			menuToggle.classList.remove('active');
			navLinks.classList.remove('active');
		}
	});
});
