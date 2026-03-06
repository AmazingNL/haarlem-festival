<?php
$section = $section ?? null;
$sectionId = (int) ($section?->getSectionId() ?? 0);
$pageId = (int) ($pageId ?? ($section?->getPageId() ?? 0));

$sectionType = (string) ($sectionType ?? ($section?->getSectionType() ?? ''));
$title = (string) ($title ?? ($section?->getTitle() ?? ''));
$sectionContent = (string) ($sectionContent ?? ($section?->getContent() ?? ''));
$imageId = (string) ($imageId ?? ($section?->getImageId() ?? ''));
$buttonText = (string) ($buttonText ?? ($section?->getButtonText() ?? ''));
$buttonLink = (string) ($buttonLink ?? ($section?->getButtonLink() ?? ''));
$sortOrder = (int) ($sortOrder ?? ($section?->getSortOrder() ?? 0));
$isPublished = (int) ($isPublished ?? ($section?->getIsPublished() ? 1 : 0));
?>

<section class="admin-wrap">
    <div class="admin-card admin-card--wide">

        <header class="admin-card-head">
            <div>
                <h1 class="admin-title">Edit Section</h1>
                <p class="admin-subtitle">Section #<?= htmlspecialchars((string) $sectionId) ?> for Page
                    #<?= htmlspecialchars((string) $pageId) ?></p>
            </div>

            <div class="admin-head-actions">
                <a class="btn btn-secondary" href="/admin/pages/viewPage">Back to Page</a>
            </div>
        </header>

        <form method="post" action="/admin/pageSection/<?= $pageId ?>/editSection">

            <input type="hidden" name="section_id" value="<?= htmlspecialchars((string) $sectionId) ?>">
            <input type="hidden" name="page_id" value="<?= htmlspecialchars((string) $pageId) ?>">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">


            <div class="form-grid form-grid--2">

                <div class="field">
                    <label for="sec_section_type">Section Type</label>
                    <input name="section_type" class="input" type="text" id="sec_section_type"
                        value="<?= htmlspecialchars($sectionType) ?>" required>
                </div>

                <div class="field">
                    <label for="sec_sort_order">Sort Order</label>
                    <input name="sort_order" class="input" type="number" id="sec_sort_order"
                        value="<?= htmlspecialchars((string) $sortOrder) ?>" min="0">
                </div>

                <div class="field field-full">
                    <label for="sec_title">Title</label>
                    <input name="title" class="input" type="text" id="sec_title"
                        value="<?= htmlspecialchars($title) ?>">
                </div>

                <div class="field field-full">
                    <label for="sec_content">Content</label>
                    <textarea name="content" class="js-wysiwyg" id="sec_content"
                        rows="10"><?= htmlspecialchars($sectionContent) ?></textarea>
                </div>

                <!-- IMPORTANT: must match tinymce-init.js -->
                <input type="hidden" name="image_id" id="section_image_id" value="<?= htmlspecialchars($imageId) ?>">

                <div class="field">
                    <label for="section_image_alt">Alt text for last uploaded image</label>
                    <input type="text" class="input" id="section_image_alt"
                        placeholder="Type alt text, then upload in TinyMCE">
                </div>

                <div class="field">
                    <label for="sec_button_text">Button Text</label>
                    <input name="button_text" class="input" type="text" id="sec_button_text"
                        value="<?= htmlspecialchars($buttonText) ?>">
                </div>

                <div class="field field-full">
                    <label for="sec_button_link">Button Link</label>
                    <input name="button_link" class="input" type="text" id="sec_button_link"
                        value="<?= htmlspecialchars($buttonLink) ?>">
                </div>

                <div>
                    <label>
                        <input class="published" type="checkbox" name="is_published" value="1" <?= htmlspecialchars($isPublished ? 'checked' : '') ?>>
                        Published
                    </label>
                </div>

            </div>

            <footer class="form-actions">
                <button type="submit" class="btn btn-primary">Update Section</button>
                <a class="btn btn-secondary" href="/admin/pageSection/<?= $pageId ?>/pageSectionList">Back</a>
            </footer>
        </form>

    </div>
</section>