<?php
use App\Models\Enum\SectionType;
use App\ViewModels\SectionFactory;

$sectionId = (int) ($section_id ?? 0);
$pageId = (int) ($pageId ?? 0);
$sectionType = (string) ($sectionType ?? '');
$sortOrder = (int) ($sortOrder ?? 0);
$isPublished = (int) ($isPublished ?? 0);
$sectionData = is_array($sectionData ?? null) ? $sectionData : [];

?>

<form class="admin-form admin-form--compact page-section-form" method="post" enctype="multipart/form-data"
    action="/admin/pageSection/<?= htmlspecialchars((int) $sectionId) ?>/editSection">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">
    <input type="hidden" name="section_id" value="<?= htmlspecialchars($sectionId) ?>">
    <input type="hidden" name="page_id" value="<?= htmlspecialchars($pageId) ?>">
    <input type="hidden" name="section_type" value="<?= htmlspecialchars((string) $sectionType) ?>">

    <header class="form-head">
        <h2 class="form-title">Edit <?= htmlspecialchars(strtoupper($sectionType)) ?></h2>
        <p class="form-subtitle">Use the editor to add text + images (TinyMCE).</p>
    </header>

    <div class="form-grid form-grid--2">

        <section class="field" id="sections">
            <?= require __DIR__ . '/section_partial_view/index.php' ?>
        </section>

        <div class="field">
            <label for="sort_order">Sort Order</label>
            <input id="sort_order" name="sort_order" type="number" class="input"
                value="<?= htmlspecialchars((string) $sortOrder) ?>" min="0">
        </div>

        <div>
            <label class="checkbox-label">
                <input class="published" type="checkbox" name="is_published" value="1" <?= $isPublished ? 'checked' : '' ?>>
                Publish
            </label>
        </div>

    </div>

    <footer class="form-actions">
        <button type="submit" class="btn btn-primary">Save Section</button>
        <a class="btn btn-secondary" href="/admin/pageSection/<?= $pageId ?>/viewPageSections">Back</a>
    </footer>
</form>
