<?php
declare(strict_types=1);

/**
 * Expects `$data['page']` to be an array of `App\Models\Page` or empty array.
 */
$pages = $data['page'] ?? [];
if ($pages instanceof \App\Models\Page) {
	$pages = [$pages];
}
if (!is_array($pages)) {
	$pages = (array) $pages;
}

foreach ($pages as $p) {
	$count = 0;
	if (isset($p->user)) {
		$count = count($p->user);
	}
}
$pagesCount = count($pages);
$userCount = $data['userCount'] ?? 0;
$published = 0;
foreach ($pages as $p) {
	if (($p->status->value ?? '') === 'published') {
		$published++;
	}

}

?>

<section class="admin-wrapper">
	<aside class="admin-sidebar">
		<div class="brand">
			<span class="logo-text">H<span style="opacity:.9">
				<img class="logo-icon" src="./assets/svg/main/Star 3.svg" alt="logo">
			</span>RLEM</span>
		</div>

		<ul class="sidebar-nav">
			<li><a href="/admin" class="sidebar-link active">Dashboard</a></li>
			<li><a href="/admin/pages" class="sidebar-link">Pages</a></li>
			<li><a href="/admin/users" class="sidebar-link">Users</a></li>
			<li><a href="/admin/settings" class="sidebar-link">Settings</a></li>
		</ul>
	</aside>

	<div class="admin-panel">
		<div class="admin-header">
			<div>
				<h1 class="admin-title">Admin Dashboard</h1>
				<p class="muted">Overview of site content and quick actions.</p>
			</div>

			<div class="admin-actions">
				<a class="btn-primary" href="/admin/pages/createPage">Add Page</a>
			</div>
		</div>

		<div class="stats-grid">
			<div class="stat-card">
				<div class="stat-value"><?= $pagesCount ?></div>
				<div class="stat-label">Pages</div>
			</div>
			<div class="stat-card">
				<div class="stat-value"><?= $userCount ?></div>
				<div class="stat-label">Users</div>
			</div>
			<div class="stat-card">
				<div class="stat-value"><?= $published ?></div>
				<div class="stat-label">Published</div>
			</div>
		</div>

		<div class="cards">
			<div class="card">
				<div class="card-header">
					<h2>Recent Pages</h2>
					<a class="btn-primary" href="/admin/pages/createPage">Add Page</a>
				</div>

				<table class="table">
					<thead>
						<tr>
							<th>Title</th>
							<th>Status</th>
							<th>Visibility</th>
							<th>Publish On</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($pages)): ?>
							<tr>
								<td colspan="5" class="muted">No pages yet.</td>
							</tr>
						<?php else: ?>
							<?php foreach ($pages as $p):
								$id = (int) ($p->page_id ?? 0);
								$title = htmlspecialchars((string) ($p->title ?? ''), ENT_QUOTES, 'UTF-8');
								$status = $p->status->value ?? 'draft';
								$visibility = ((($p->status->value ?? (string) $p->status) === 'published')) ? 'Public' : 'Private';
								$publishedOn = htmlspecialchars((string) ($p->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
								?>
								<tr>
									<td><?= $title ?></td>
									<td><?= htmlspecialchars((string) $status) ?></td>
									<td><?= $visibility ?></td>
									<td><?= $publishedOn ?></td>
									<td>
										<a href="/admin/pages/<?= $id ?>/edit" class="btn-primary">Edit</a>
										<a href="#" class="pill">Delete</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="card">
				<h3>Quick Links</h3>
				<ul class="muted">
					<li><a href="/admin/pages">Manage Pages</a></li>
					<li><a href="/admin/users">Manage Users</a></li>
				</ul>
			</div>
		</div>
	</div>
</section>

<?php
