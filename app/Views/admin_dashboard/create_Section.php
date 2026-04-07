<?php
$pageId = (int) ($page_id ?? 0);

use App\Models\Enum\SectionType;
use App\ViewModels\SectionFactory;
?>

<form class="admin-form admin-form--compact page-section-form" method="post" enctype="multipart/form-data"
    action="/admin/pageSection/<?= htmlspecialchars((string) $pageId) ?>/createPage">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '') ?>">


    <header class="form-head">
        <h2 class="form-title">Add Page Section</h2>
        <p class="form-subtitle">Use the editor to add text + images (TinyMCE).</p>
    </header>

    <div class="form-grid form-grid--2">

        <div class="field">
            <label for="sec_section_type">Section Type</label>
            <select id="sectionType" name="section_type" class="input" required>
                <?php
                $supportedTypes = array_filter(
                    SectionType::cases(),
                    fn(SectionType $case): bool => SectionFactory::returnSectionClass($case->value) !== null
                );
                ?>
                <?php foreach ($supportedTypes as $case): ?>
                    <option value="<?= htmlspecialchars($case->value) ?>">
                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $case->value))) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <section class="field" id="sections"></section>

        <div>
            <label class="checkbox-label">
                <input class="published" type="checkbox" name="is_published" value="1">
                Publish
            </label>
        </div>

    </div>

    <footer class="form-actions">
        <button type="submit" class="btn btn-primary">Save Section</button>
        <a class="btn btn-secondary" href="/admin/pageSection/<?= $pageId ?>/viewPageSections">Back</a>
    </footer>
</form>

<script>

    document.addEventListener('DOMContentLoaded', async () => {
        const sectionType = document.getElementById('sectionType');
        const sections = document.getElementById('sections') ?? null;

        async function loadSection() {
            const type = sectionType.value;
            try {
                const file = await fetch(`/admin/pageSection/render-fields?type=${encodeURIComponent(type)}`);
                if (!file.ok) throw new Error('Section fields not found');

                const html = await file.text();
                sections.innerHTML = html;

                // Section fields are injected dynamically, so I re-initialized TinyMCE.
                document.dispatchEvent(new CustomEvent('cms:content-updated'));
                if (typeof window.initTinyMceEditors === 'function') {
                    window.initTinyMceEditors();
                }
            }
            catch (error) {
                sections.innerHTML = 'Section fields could not be loaded';
            }
        }

        sectionType.addEventListener('change', loadSection);

        loadSection();
            // Ensure TinyMCE content is synced before form submission
            const form = document.querySelector('.page-section-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Sync TinyMCE editor content to textareas before submission
                    if (typeof tinymce !== 'undefined' && tinymce.editors && tinymce.editors.length > 0) {
                        tinymce.triggerSave();
                    }
                });
            }
    });
</script>