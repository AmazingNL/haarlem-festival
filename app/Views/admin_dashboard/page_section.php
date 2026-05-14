<?php
$section = $pageSection;
$sectionTitle = static function (array $section): string {
    $title = trim((string) ($section['title'] ?? ''));
    if ($title !== '') {
        return $title;
    }

    $content = json_decode((string) ($section['content'] ?? ''), true);
    if (is_array($content)) {
        foreach (['heading', 'title', 'item_one_label', 'card_one_title', 'button_text'] as $key) {
            $value = trim((string) ($content[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }
    }

    return ucwords(str_replace('_', ' ', (string) ($section['section_type'] ?? 'Section')));
};
?>

<?php /* Sections list: show all sections for this page with edit/delete actions */ ?>
<?php if (!empty($section)): ?>
    <div class="admin-panel">
        <div class="admin-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="/admin/pages/viewPage" class="btn-secondary">← Back</a>
                <h2 class="admin-title">Page Sections</h2>
            </div>
            <div class="admin-actions">
                <a class="btn-primary" href="/admin/pageSection/<?= $pageId ?>/pageSectionForm">Add Section</a>
            </div>
        </div>

        <div class="card">
            <div class="sections-table">
                <div class="sections-row sections-row--head">
                    <div>Sort Order</div>
                    <div>Type</div>
                    <div>Title</div>
                    <div>Status / Actions</div>
                </div>

                <?php foreach ($section as $s):

                    $id = $s['section_id'] ?? 0;
                    $type = $s['section_type'] ?? '';
                    $title = $sectionTitle($s);
                    $order = isset($s['sort_order']) ? (int) $s['sort_order'] + 1 : 0;
                    $pub = !empty($s['is_published']);
                    $PageId = $s['page_id'] ?? $pageId;

                    ?>
                    <div class="sections-row">
                        <div><strong>#<?= htmlspecialchars((string) $order) ?></strong></div>
                        <div class="muted"><?= htmlspecialchars($type) ?></div>
                        <div class="section-title" style="font-weight:700;"><?= htmlspecialchars($title) ?></div>
                        <div class="row-actions">
                            <?php if ($pub): ?><span class="pill">Published</span><?php endif; ?>
                            <a class="btn-secondary" href="/admin/pageSection/<?= $id ?>/editSectionForm">Edit</a>
                            <a href="/admin/pageSection/<?= $id ?>/deleteSection" class="inline-form btn-danger btn" 
                            style="display:inline-block;margin:0;">
                            Delete
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="admin-panel">
        <div class="admin-header">
            <div style="display:flex;align-items:center;gap:12px;">
                <a href="/admin" class="btn-secondary">← Back</a>
                <h2 class="admin-title">Page Sections</h2>
            </div>
        </div>
        <div class="card admin-empty">
            <p>No sections found for this page yet.</p>
        </div>
    </div>
<?php endif; ?>
