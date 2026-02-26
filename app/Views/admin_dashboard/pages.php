

<div class="cards">
	<div class="card">
		<div class="card-header">
			<a href="/admin/dashboard" class="btn-secondary">← Back</a>
			<h2>Recent Pages</h2>
			<a class="btn-primary" href="/admin/pages/createPage">Add Page</a>
		</div>

		<table class="table">
			<thead>
				<tr>
					<th>Title</th>
					<th>Status</th>
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
						$status = $p->status->value;
						$publishedOn = htmlspecialchars((string) ($p->created_at ?? '-'), ENT_QUOTES, 'UTF-8');
						?>
						<tr>
							<td><?= $title ?></td>
							<td><?= htmlspecialchars((string) $status) ?></td>
							<td><?= $publishedOn ?></td>
							<td>
								<a href="/admin/pages/<?= $id ?>/editForm" class="btn-primary">Edit</a>
								<a class="btn-primary" href="/admin/pageSection/<?= $id ?>/pageSectionForm">Add Page
									Section</a>
								<a href="/admin/dashboard/<?= $id ?>/delete" class="pill">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
