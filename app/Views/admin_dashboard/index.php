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
<!-- content for the admin right column (layout provides the left sidebar) -->
<div class="d-flex justify-content-between align-items-start mb-3">
	<div>
		<h1 class="h3">Admin Dashboard</h1>
		<p class="text-muted mb-0">Overview of site content and quick actions.</p>
	</div>
	<div>
		<a class="btn btn-primary" href="/admin/pages/createPage">Add Page</a>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-4">
		<div class="card text-center">
			<div class="card-body">
				<div class="h2 mb-0"><?= $pagesCount ?></div>
				<div class="text-muted">Pages</div>
			</div>
		</div>
	</div>
	<div class="col-4">
		<div class="card text-center">
			<div class="card-body">
				<div class="h2 mb-0"><?= $userCount ?></div>
				<div class="text-muted">Users</div>
			</div>
		</div>
	</div>
	<div class="col-4">
		<div class="card text-center">
			<div class="card-body">
				<div class="h2 mb-0"><?= $published ?></div>
				<div class="text-muted">Published</div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0">Recent Pages</h5>
	</div>
	<div class="card-body p-0">
		<div class="table-responsive">
			<table class="table table-hover mb-0">
				<thead class="table-light">
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
							<td colspan="5" class="text-muted p-3">No pages yet.</td>
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
								<td>
									<a href="/admin/pages/<?= $id ?>/editForm" class="btn btn-sm btn-outline-primary">Edit</a>
									<a href="/admin/pageSection/<?= $id ?>/pageSectionForm"
										class="btn btn-sm btn-outline-secondary">Add Section</a>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</section>
</div>

<?php
