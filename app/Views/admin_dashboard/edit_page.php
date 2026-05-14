<?php
$pageId = (int) ($page->page_id ?? 0);

$valTitle = (string) ($page->title ?? '');
$valSlug = (string) ($page->slug ?? '');

$valStatus = is_object($page->status) && property_exists($page->status, 'value')
    ? (string) $page->status->value
    : (string) ($page->status ?? 'draft');
?>

<section class="admin-wrap">
    <div class="admin-card admin-card--wide">
        <header class="admin-card-head">
            <div>
                <h1 class="admin-title"><?= htmlspecialchars($valTitle ?: 'Edit Page', ENT_QUOTES, 'UTF-8') ?></h1>
                <p class="admin-subtitle">Update page details, then manage its sections.</p>
            </div>

            <a class="btn btn-secondary" href="/admin/pageSection/<?= $pageId ?>/viewPageSections">
                Manage Sections
            </a>
        </header>

        <!-- flash here... -->

        <form method="post" action="/admin/pages/<?= $pageId ?>/edit" class="admin-form">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-grid form-grid--2">
                <div class="field">
                    <label for="title">Title</label>
                    <input id="title" class="input" name="title" type="text" required
                        value="<?= htmlspecialchars($valTitle, ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="field">
                    <label for="slug">Slug</label>
                    <input id="slug" class="input" name="slug" type="text" required
                        value="<?= htmlspecialchars($valSlug, ENT_QUOTES, 'UTF-8') ?>">
                    <small class="hint">Example: jazz-night or history-of-haarlem</small>
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" class="select" name="status">
                        <option value="draft" <?= $valStatus === 'draft' ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= $valStatus === 'published' ? 'selected' : '' ?>>Published</option>
                        <option value="archived" <?= $valStatus === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>
                </div>
            </div>

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">Save Changes</button>
                <a class="btn btn-ghost" href="/admin/dashboard">Cancel</a>
            </div>
        </form>
    </div>
</section>