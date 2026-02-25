<?php

$pages = $data['page'] ?? [];
$flash = $flash ?? [];
$pagesCount = count($pages);
$userCount = $data['userCount'] ?? 0;

// Count published pages safely (handles enum objects or raw strings)
$published = 0;
foreach ($pages as $p) {
	$status = is_object($p->status) && property_exists($p->status, 'value')
		? $p->status->value
		: (string) ($p->status ?? '');
	if ($status === 'published') {
		$published++;
	}
}

?>
<main class="main-content">
	<div class="main-container">
		<aside class="admin-sidebar">
			<div class="brand">
				<span class="logo-text">H<span style="opacity:.9">
						<img class="logo-icon" src="./assets/svg/main/Star 3.svg" alt="logo">
					</span>RLEM</span>
			</div>

			<ul class="sidebar-nav">
				<li><a href="/admin" class="nav-link sidebar-link active">Dashboard</a></li>
				<li><a href="/admin/pages/viewPage" class="nav-link sidebar-link">Pages</a></li>
				<li><a href="/admin/users" class="nav-link sidebar-link">Users</a></li>
				<li><a href="/admin/settings" class="nav-link sidebar-link">Settings</a></li>
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

				<div class="card">
					<div class="card-header">
						<h2>Recent Pages</h2>
					</div>

					<table class="table">
						<thead>
								<th>Title</th>
								<th>Status</th>
								<th>Visibility</th>
								<th>Publish On</th>
								<th>Action</th>
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
									$statusVal = is_object($p->status) && property_exists($p->status, 'value')
										? $p->status->value
										: (string) ($p->status ?? 'draft');
									$visibility = ($statusVal === 'published') ? 'Public' : 'Private';
									$publishedOn = htmlspecialchars((string) ($p->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
									?>
									<tr>
										<td><?= $title ?></td>
										<td><?= htmlspecialchars((string) $statusVal, ENT_QUOTES, 'UTF-8') ?></td>
										<td><?= $visibility ?></td>
										<td><?= $publishedOn ?></td>
										<td class="a">
											<a href="/admin/pages/<?= $id ?>/editForm" class="btn-primary">Edit</a>
											<a class="btn-primary" href="/admin/pageSection/<?= $id ?>/pageSectionForm">Add Page
												Section</a>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
		</div>
	</div>
</main>

<?php
