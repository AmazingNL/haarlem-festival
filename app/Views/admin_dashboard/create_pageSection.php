<?php
$pageId = (int) ($page_id ?? 0);
?>

<form class="admin-form admin-form--compact page-section-form" method="post"
    action="/admin/pageSection/<?= htmlspecialchars((string) $pageId) ?>/createPage">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">


    <header class="form-head">
        <h2 class="form-title">Add Page Section</h2>
        <p class="form-subtitle">Use the editor to add text + images (TinyMCE).</p>
    </header>

    <div class="form-grid form-grid--2">

        <div class="field">
            <label for="sec_section_type">Section Type</label>
            <input name="section_type" class="input" type="text" id="sec_section_type"
                placeholder="e.g. food_culture, hero, text_block" required>
        </div>

        <div class="field">
            <label for="sec_sort_order">Sort Order</label>
            <input name="sort_order" class="input" type="number" id="sec_sort_order" value="0" min="0">
        </div>

        <div class="field field-full">
            <label for="sec_title">Title</label>
            <input name="title" class="input" type="text" id="sec_title" placeholder="Section heading">
        </div>

        <div class="field field-full">
            <label for="sec_content">Content</label>
            <textarea name="content" class="js-wysiwyg" id="sec_content" rows="10"></textarea>
            <small class="hint">Use the TinyMCE image button to upload images.</small>
        </div>

        <!-- IMPORTANT: these IDs must match tinymce-init.js -->
        <input type="hidden" name="image_id" id="section_image_id" value="">
        <div class="field">
            <label for="section_image_alt">Alt text for last uploaded image</label>
            <input type="text" class="input" id="section_image_alt" placeholder="e.g. Stroopwafel on a plate">
            <small class="hint">Fill this before uploading an image if your upload endpoint requires alt_text.</small>
        </div>

        <div class="field">
            <label for="sec_button_text">Button Text</label>
            <input name="button_text" class="input" type="text" id="sec_button_text">
        </div>

        <div class="field field-full">
            <label for="sec_button_link">Button Link</label>
            <input name="button_link" class="input" type="text" id="sec_button_link" placeholder="/yummy/food-culture">
        </div>

        <div>
            <label class="checkbox-label">
                <input class="published" type="checkbox" name="is_published" value="1">
                Publish
            </label>
        </div>

    </div>

    <footer class="form-actions">
        <button type="submit" class="btn btn-primary">Save Section</button>
        <a class="btn btn-secondary" href="/admin/pageSection/<?= $pageId ?>/pageSectionList">Back</a>
    </footer>
</form>