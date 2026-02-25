<section class="admin-wrap">
    <header class="admin-head">
        <h1 class="admin-title"><?= htmlspecialchars($title ?? 'Create Page') ?></h1>
        <p class="admin-subtitle">Create a page, then add sections below.</p>
    </header>

    <form method="post" action="/admin/pages/create" id="create-page-form" class="admin-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">

        <div class="form-grid">
            <div class="field">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" required placeholder="e.g. Jazz Night" />
            </div>

            <div class="field">
                <label for="slug">Slug</label>
                <input id="slug" type="text" name="slug" required placeholder="e.g. jazz-night" />
                <small class="hint">Use lowercase and hyphens.</small>
            </div>

            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="field field-full">
                <label for="page_content">Content</label>
                <textarea enctype="multipart/form-data" id="page_content" name="content" class="js-wysiwyg" rows="10"
                    placeholder="Write the main page content..."></textarea>
            </div>
        </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Page</button>
                <a href="/admin/dashboard" class="btn btn-ghost">Cancel</a>
            </div>
    </form>
</section>