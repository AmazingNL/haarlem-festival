<?php
$sectionImage = $s['section_image'] ?? [];
$images = [];

$buildGalleryImage = static function (string $src, string $alt = '', string $caption = ''): array {
	$displayAlt = trim($alt) !== '' ? trim($alt) : 'Ratatouille restaurant gallery image';

	return [
		'src' => $src,
		'alt' => $displayAlt,
		'caption' => trim($caption),
	];
};

if (is_array($sectionImage)) {
	foreach ($sectionImage as $image) {
		if (is_array($image)) {
			$src = trim((string) ($image['src'] ?? ''));
			if ($src === '') {
				continue;
			}

			$images[] = $buildGalleryImage(
				$src,
				(string) ($image['alt'] ?? ''),
				(string) ($image['caption'] ?? '')
			);
			continue;
		}

		$src = trim((string) $image);
		if ($src !== '') {
			$images[] = $buildGalleryImage($src);
		}
	}
}

$title = trim((string) ($s['title'] ?? 'View Images'));
$mainImage = $images[0] ?? null;
?>

<?php if ($mainImage !== null): ?>
	<section class="ratatouille-copy-section ratatouille-gallery-section" data-ratatouille-gallery>
		<h2 class="ratatouille-section-title">
			<?= htmlspecialchars($title !== '' ? $title : 'View Images', ENT_QUOTES, 'UTF-8') ?>
		</h2>

		<figure class="ratatouille-gallery-main">
			<img
				data-ratatouille-gallery-main
				src="<?= htmlspecialchars((string) $mainImage['src'], ENT_QUOTES, 'UTF-8') ?>"
				alt="<?= htmlspecialchars((string) $mainImage['alt'], ENT_QUOTES, 'UTF-8') ?>"
				loading="lazy"
			>
			<?php if (($mainImage['caption'] ?? '') !== ''): ?>
				<figcaption data-ratatouille-gallery-caption>
					<?= htmlspecialchars((string) $mainImage['caption'], ENT_QUOTES, 'UTF-8') ?>
				</figcaption>
			<?php else: ?>
				<figcaption data-ratatouille-gallery-caption hidden></figcaption>
			<?php endif; ?>
		</figure>

		<?php if (count($images) > 1): ?>
			<div class="ratatouille-gallery-thumbs" aria-label="Choose gallery image">
				<?php foreach ($images as $index => $image): ?>
					<button
						class="ratatouille-gallery-thumb<?= $index === 0 ? ' is-active' : '' ?>"
						type="button"
						data-ratatouille-gallery-thumb
						data-src="<?= htmlspecialchars((string) $image['src'], ENT_QUOTES, 'UTF-8') ?>"
						data-alt="<?= htmlspecialchars((string) $image['alt'], ENT_QUOTES, 'UTF-8') ?>"
						data-caption="<?= htmlspecialchars((string) $image['caption'], ENT_QUOTES, 'UTF-8') ?>"
						aria-label="Show gallery image <?= $index + 1 ?>"
						aria-pressed="<?= $index === 0 ? 'true' : 'false' ?>"
					>
						<img
							src="<?= htmlspecialchars((string) $image['src'], ENT_QUOTES, 'UTF-8') ?>"
							alt=""
							loading="lazy"
						>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<script>
		(function () {
			const galleries = document.querySelectorAll('[data-ratatouille-gallery]');

			galleries.forEach(function (gallery) {
				const mainImage = gallery.querySelector('[data-ratatouille-gallery-main]');
				const caption = gallery.querySelector('[data-ratatouille-gallery-caption]');
				const thumbs = Array.from(gallery.querySelectorAll('[data-ratatouille-gallery-thumb]'));

				if (!mainImage || thumbs.length === 0) return;

				thumbs.forEach(function (thumb) {
					thumb.addEventListener('click', function () {
						const nextSrc = thumb.getAttribute('data-src') || '';
						const nextAlt = thumb.getAttribute('data-alt') || 'Ratatouille restaurant gallery image';
						const nextCaption = thumb.getAttribute('data-caption') || '';

						if (nextSrc === '') return;

						mainImage.src = nextSrc;
						mainImage.alt = nextAlt;

						if (caption) {
							caption.textContent = nextCaption;
							caption.hidden = nextCaption === '';
						}

						thumbs.forEach(function (item) {
							const isActive = item === thumb;
							item.classList.toggle('is-active', isActive);
							item.setAttribute('aria-pressed', isActive ? 'true' : 'false');
						});
					});
				});
			});
		})();
	</script>
<?php endif; ?>
