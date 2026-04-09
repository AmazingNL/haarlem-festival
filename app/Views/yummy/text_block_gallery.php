<?php
$currentType = trim((string) ($s['section_type'] ?? ''));

$text = $textSection ?? (($currentType === 'text_block') ? $s : []);
$gallery = $gallerySection ?? (($currentType === 'gallery') ? $s : []);

$sectionImage = $gallery['section_image'] ?? '';
$images = [];

if (is_array($sectionImage)) {
	foreach ($sectionImage as $image) {
		if (is_array($image)) {
			$src = trim((string) ($image['src'] ?? ''));
			if ($src === '') {
				continue;
			}

			$images[] = [
				'src' => $src,
				'alt' => trim((string) ($image['alt'] ?? '')),
				'caption' => trim((string) ($image['caption'] ?? '')),
			];
			continue;
		}

		$imagePath = trim((string) $image);
		if ($imagePath !== '') {
			$images[] = [
				'src' => $imagePath,
				'alt' => '',
				'caption' => '',
			];
		}
	}
}
?>

<section class="food-culture-combined">
	<?php if (!empty($text['title'])): ?>
		<h1 class="food-culture-title">
			<?= htmlspecialchars(strtoupper((string) $text['title']), ENT_QUOTES, 'UTF-8') ?>
		</h1>
	<?php endif; ?>

	<div class="food-culture-combined-grid">
		<article class="food-culture-copy">
			<?php if (!empty($text['sub_title'])): ?>
				<h5 class="food-culture-copy-title">
					<?= htmlspecialchars((string) $text['sub_title'], ENT_QUOTES, 'UTF-8') ?>
				</h5>
			<?php endif; ?>
			<div class="food-culture-copy-body"><?= (string) ($text['article'] ?? '') ?></div>
		</article>

		<div class="food-culture-images" aria-label="Food culture gallery">
			<?php foreach ($images as $img): ?>
				<figure>
					<img
						src="<?= htmlspecialchars((string) ($img['src'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
						alt="<?= htmlspecialchars((string) (($img['alt'] ?? '') !== '' ? $img['alt'] : 'Gallery image'), ENT_QUOTES, 'UTF-8') ?>"
						loading="lazy"
					>
					<?php if (($img['caption'] ?? '') !== ''): ?>
						<figcaption><?= htmlspecialchars((string) $img['caption'], ENT_QUOTES, 'UTF-8') ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<style>
	.food-culture-combined {
		color: var(--color-text-header);
		max-width: 1300px;
		margin: 0 auto 40px;
		padding: 0 24px;
	}

	.food-culture-title {
		text-align: center;
		letter-spacing: 0.12em;
		text-transform: uppercase;
		margin-bottom: 30px;
		font-size: 38px;
		line-height: 1.2;
	}

	.food-culture-combined-grid {
		display: grid;
		grid-template-columns: minmax(300px, 1fr) minmax(520px, 1.2fr);
		gap: 28px;
		align-items: start;
	}

	.food-culture-copy-title {
		margin: 0 0 10px;
		font-size: 34px;
		line-height: 1.15;
	}

	.food-culture-copy-body {
		font-size: 16px;
		line-height: 1.7;
		max-width: 60ch;
	}

	.food-culture-images {
		display: grid;
		grid-template-columns: repeat(3, minmax(0, 1fr));
		gap: 10px;
	}

	.food-culture-images figure {
		margin: 0;
	}

	.food-culture-images img {
		display: block;
		width: 90%;
		height: 180px;
		object-fit: cover;
		border-radius: 14px;
	}

	.food-culture-images figcaption {
		margin-top: 4px;
		font-size: 10px;
		text-align: center;
		opacity: 0.8;
	}

	@media (max-width: 1024px) {
		.food-culture-combined-grid {
			grid-template-columns: 1fr;
			gap: 20px;
		}

		.food-culture-images {
			grid-template-columns: repeat(3, minmax(0, 1fr));
		}
	}

	@media (max-width: 720px) {
		.food-culture-combined {
			padding: 0 14px;
		}

		.food-culture-combined-title {
			font-size: 22px;
		}

		.food-culture-copy-title {
			font-size: 28px;
		}

		.food-culture-images {
			grid-template-columns: repeat(2, minmax(0, 1fr));
		}
	}
</style>