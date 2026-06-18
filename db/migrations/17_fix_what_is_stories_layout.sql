-- migrate:up
-- Convert what_is_stories content from HTML to JSON so image_path is available to the template.
USE haarlem_festival;

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET
    ps.title   = 'What Is Stories in Haarlem?',
    ps.content = JSON_OBJECT(
        'image_path', '/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg',
        'image_alt',  'Performer on stage in Haarlem',
        'html', CONCAT(
            '<h3>The Experience</h3>',
            '<p>Stories in Haarlem transforms intimate venues across the city into portals to other worlds. ',
            'Whether you''re listening to historical accounts from the Corrie ten Boom House, contemporary ',
            'immigrant narratives, or enchanting children''s tales, each session invites you into a shared ',
            'journey of imagination and connection.</p>',
            '<p>Each story is shaped by its setting. Small theatres, cafés, living rooms, and community ',
            'spaces become part of the narrative, creating a closeness between storyteller and audience.</p>'
        )
    )
WHERE p.slug = 'stories'
  AND ps.section_type = 'what_is_stories';

-- migrate:down
UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET
    ps.title   = 'The Experience',
    ps.content = CONCAT(
        '<p>Stories is a unique festival strand celebrating the art of oral storytelling. ',
        'Local and international performers take the stage to weave tales of myth, memory, and meaning — ',
        'inviting audiences of all ages into worlds built entirely from words.</p>',
        '<p>Whether you are discovering a new genre or returning to a childhood favourite, ',
        'Stories offers an unforgettable evening under the Haarlem sky.</p>',
        '<img class="wis-image" src="/assets/images/stories/antonio-molinari-22FwbFrPvpU-unsplash.jpg" alt="Audience at a storytelling event">'
    )
WHERE p.slug = 'stories'
  AND ps.section_type = 'what_is_stories';
