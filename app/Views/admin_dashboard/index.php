<?php
declare(strict_types=1);

// Admin dashboard view
?>

<section class="admin-dashboard">
	<header class="admin-header">
		<h1>Admin Dashboard</h1>
		<p class="muted">Overview of site content and quick actions.</p>
	</header>

	<div class="dashboard-grid">
		<div class="card stats">
			<h3>Pages</h3>
			<p class="stat-number">3</p>
		</div>
		<div class="card stats">
			<h3>Users</h3>
			<p class="stat-number">12</p>
		</div>
		<div class="card stats">
			<h3>Published</h3>
			<p class="stat-number">8</p>
		</div>
	</div>

	<section class="recent-content">
		<div class="card">
			<div class="card-header">
				<h2>Recent Pages</h2>
				<a class="btn" href="/admin/pages/create">Add Page</a>
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
					<tr>
						<td>About</td>
						<td>Published</td>
						<td>Public</td>
						<td>2025-12-05</td>
						<td>
							<a href="#" class="btn small">Edit</a>
							<a href="#" class="btn danger small">Delete</a>
						</td>
					</tr>
					<tr>
						<td>Home</td>
						<td>Published</td>
						<td>Public</td>
						<td>2025-12-03</td>
						<td>
							<a href="#" class="btn small">Edit</a>
							<a href="#" class="btn danger small">Delete</a>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</section>
</section>

<style>
/* Minimal inline styles for dashboard layout — move to CSS file as needed */
.admin-header h1 { margin: 0 0 6px 0; }
.muted { color: #666; margin-top: 0; }
.dashboard-grid { display:flex; gap:16px; margin:16px 0; }
.card { background:#fff; padding:16px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.05); }
.card.stats { flex:1; text-align:center; }
.stat-number { font-size:28px; font-weight:700; margin:8px 0 0 0; }
.card-header { display:flex; justify-content:space-between; align-items:center; }
.table { width:100%; border-collapse:collapse; margin-top:12px; }
.table th, .table td { text-align:left; padding:10px; border-bottom:1px solid #eee; }
.btn { background:#f45; color:#fff; padding:8px 12px; border-radius:6px; text-decoration:none; }
.btn.small { padding:6px 8px; font-size:14px; }
.btn.danger { background:#e74c3c; }
</style>

<?php
// End of admin dashboard view
