-- ============================================================
-- 08_jazz_artist_pages.sql
-- Seed the two Jazz artist detail pages (Gare du Nord, The Nordanians) shown at
-- /jazz/artists/{slug}. Each page = one "jazz_artist" editorial section plus the
-- bookable "jazz_agenda_event" shows. Fully CMS content; admins can edit these or
-- add more artist pages in the dashboard. Re-running the seed resets both pages
-- to this baseline. The Featured Artists cards on /jazz already link here
-- (set in migration 23), so nothing else needs updating.
-- ============================================================
-- migrate:up
USE haarlem_festival;

-- ---------- Gare du Nord ----------
INSERT INTO page (title, slug, status)
SELECT 'Gare du Nord', 'gare-du-nord', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'gare-du-nord');

SET @gdn_page_id = (SELECT page_id FROM page WHERE slug = 'gare-du-nord' LIMIT 1);

DELETE FROM page_section WHERE page_id = @gdn_page_id;

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @gdn_page_id,
  'jazz_artist',
  JSON_OBJECT(
    'hero_image', '/assets/images/jazz/artists/gare-du-nord-hero.jpg',
    'intro_heading', 'Smooth Grooves and Cinematic Soul',
    'intro_body', 'Formed in the late 1990s in the Netherlands Gare du Nord is a studio-based project with a clear vision: to create sophisticated, groove-driven music that bridges jazz tradition and modern electronic production. Emerging at a time when lounge, downtempo, and nu-jazz were gaining international attention, the Gare du Nord evolved through multiple line-ups and creative phases, collaborating with a wide range of vocalists and musicians while remaining true to its signature blend of jazz, soul, and electronic textures.',
    'career_heading', 'Career Highlight',
    'career_body', 'Gare du Nord had an international breakthrough in the early 2000s, when the album Sex ''n'' Jazz became a defining release within the global nu-jazz and lounge scene. The album''s success led to widespread international airplay, strong sales across Europe and beyond, and positioning Gare du Nord as one of the leading Dutch acts in stylish, genre-blending jazz. Their music found its way into clubs, cafes, and curated playlists worldwide, cementing their reputation as pioneers of sophisticated, groove-driven jazz with a modern edge.',
    'career_image', '/assets/images/jazz/artists/gare-du-nord-career.jpg',
    'works_heading', 'Essential Works to Know',
    'works_intro', 'Over the years, Gare du Nord has built a rich and diverse catalog that reflects their evolving sound and creative vision. From early nu-jazz roots to more refined, soulful productions, each album captures a distinct moment in their musical journey. The following releases highlight the project''s signature blend of groove, atmosphere, and timeless style, and offer a perfect entry point into their music.',
    'album_one_title', 'Sex ''n'' Jazz',
    'album_one_text', 'Sex ''n'' Jazz is the breakthrough album that firmly established Gare du Nord on the international stage. It became a defining release of the nu-jazz era, resonating in clubs, lounges, and listening rooms worldwide. The album captures Gare du Nord''s core identity: cool, confident, and effortlessly sophisticated.',
    'album_one_image', '/assets/images/jazz/artists/gare-du-nord-album-1.jpg',
    'album_two_title', 'In Search of Exellounge',
    'album_two_text', 'In Search of Exellounge sees Gare du Nord further refining their signature sound with an even stronger focus on atmosphere and groove. Released as a follow-up to their breakthrough, the album explores deeper, more cinematic textures while maintaining the smooth blend of jazz, soul, and downtempo beats.',
    'album_two_image', '/assets/images/jazz/artists/gare-du-nord-album-2.jpg',
    'album_three_title', 'Rendezvous 8:02',
    'album_three_text', 'Rendezvous 8:02 captures a confident and mature phase in the career of Gare du Nord, deepening its signature blend of jazz, soul, and downtempo grooves, placing strong emphasis on mood, melody, and refined arrangements. The music feels smooth and cinematic, balancing relaxed rhythms with expressive vocals and rich harmonies.',
    'album_three_image', '/assets/images/jazz/artists/gare-du-nord-album-3.jpg',
    'listen_heading', 'Give em a Listen!',
    'listen_intro', 'Interested aren''t ya? Have a listen to one of their popular tracks',
    'listen_track_image', '/assets/images/jazz/artists/gare-du-nord-track.jpg',
    'listen_track_link', '#',
    'listen_track_title', 'Pablo''s Blues',
    'listen_track_artist', 'Gare du Nord',
    'listen_track_album', 'in search of exellounge',
    'schedule_heading', 'Event Appearances and Schedule',
    'schedule_intro', 'Now that you know everything about the artist, you can have a look at when Gare du Nord are going to be performing during our lively Haarlem Jazz Event',
    'closing_text', 'Come and enjoy some soulful tunes with us!'
  ),
  1,
  1
),
(
  @gdn_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Saturday',
    'venue', 'Patronaat, Main Hall',
    'title', 'Gare du Nord',
    'time_text', '18:00 - 19:00',
    'description', '',
    'price', '15',
    'image', '/assets/images/jazz/artists/gare-du-nord-show.jpg',
    'learn_more_link', ''
  ),
  2,
  1
),
(
  @gdn_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Sunday',
    'venue', 'Grote Markt',
    'title', 'Gare du Nord',
    'time_text', '20:00 - 21:00',
    'description', '',
    'price', '0',
    'image', '/assets/images/jazz/artists/gare-du-nord-show.jpg',
    'learn_more_link', ''
  ),
  3,
  1
);

-- ---------- The Nordanians ----------
INSERT INTO page (title, slug, status)
SELECT 'The Nordanians', 'the-nordanians', 'published'
WHERE NOT EXISTS (SELECT 1 FROM page WHERE slug = 'the-nordanians');

SET @nord_page_id = (SELECT page_id FROM page WHERE slug = 'the-nordanians' LIMIT 1);

DELETE FROM page_section WHERE page_id = @nord_page_id;

INSERT INTO page_section (page_id, section_type, content, sort_order, is_published)
VALUES
(
  @nord_page_id,
  'jazz_artist',
  JSON_OBJECT(
    'hero_image', '/assets/images/jazz/artists/the-nordanians-hero.jpg',
    'intro_heading', 'Groove, Energy & Pure Joy',
    'intro_body', 'The Nordanians emerged in the Netherlands in the early 2000s as a collective of seasoned musicians brought together by a shared love for groove-driven, instrumental music. Drawing inspiration from jazz-funk, soul, and Afro-influenced rhythms, the group quickly developed a recognizable sound built on tight interplay and infectious energy. Over the years, The Nordanians have become a familiar name on Dutch and international jazz and funk stages, earning a strong reputation for dynamic live performances and a body of work that reflects their commitment to musicianship, rhythm, and pure musical joy.',
    'career_heading', 'Career Highlight',
    'career_body', 'With a long-standing presence on the Dutch and international live circuit, the Nordanians have built a reputation as one of the Netherlands'' most energetic instrumental jazz-funk acts. Through acclaimed album releases and standout performances at jazz clubs and festivals, the band established itself as a go-to live act known for tight grooves, musical chemistry, and crowd-driven shows, cementing their status as a staple within the European groove and funk scene.',
    'career_image', '/assets/images/jazz/artists/the-nordanians-career.jpg',
    'works_heading', 'Essential Works to Know',
    'works_intro', 'Throughout their career, The Nordanians have released a series of albums that capture their signature energy, groove, and musical chemistry. Each record reflects a different moment in their evolution, highlighting their blend of jazz, funk, soul, and rhythmic experimentation. The following albums stand out as defining releases that showcase the band''s sound, creativity, and enduring impact on the instrumental groove scene.',
    'album_one_title', 'Tabla Rasa',
    'album_one_text', 'Tabla Rasa delivers a powerful fusion of electronic beats, live instrumentation, and global influences, creating music that is both hypnotic and explosive. Blending jazz, dance, and world rhythms, the band builds high-energy soundscapes driven by deep grooves, dynamic improvisation, and a strong physical pulse.',
    'album_one_image', '/assets/images/jazz/artists/the-nordanians-album-1.jpg',
    'album_two_title', 'Dr. Mysore',
    'album_two_text', 'Dr. Mysore blends electronic beats, jazz sensibilities, and global influences into an immersive, groove-driven sound. Moving fluidly between club energy and improvisational depth, the project creates rhythmic, forward-thinking music that''s both cerebral and physical. With a strong focus on atmosphere and pulse, Dr. Mysore delivers performances that invite listeners to move, explore, and get lost in the groove.',
    'album_two_image', '/assets/images/jazz/artists/the-nordanians-album-2.jpg',
    'album_three_title', '',
    'album_three_text', '',
    'album_three_image', '',
    'listen_heading', 'Give em a Listen!',
    'listen_intro', 'Interested aren''t ya? Have a listen to one of their popular tracks',
    'listen_track_image', '/assets/images/jazz/artists/the-nordanians-track.jpg',
    'listen_track_link', '#',
    'listen_track_title', 'Nasty Nordanian',
    'listen_track_artist', 'The Nordanians',
    'listen_track_album', 'Tabla Rasa',
    'schedule_heading', 'Event Appearances and Schedule',
    'schedule_intro', 'Now that you know everything about the artist, you can have a look at when The Nordanians are going to be performing during our lively Haarlem Jazz Event',
    'closing_text', 'Come and enjoy some soulful tunes with us!'
  ),
  1,
  1
),
(
  @nord_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Saturday',
    'venue', 'Patronaat, Third Hall',
    'title', 'The Nordanians',
    'time_text', '19:30 - 20:30',
    'description', '',
    'price', '15',
    'image', '/assets/images/jazz/artists/the-nordanians-show.jpg',
    'learn_more_link', ''
  ),
  2,
  1
),
(
  @nord_page_id,
  'jazz_agenda_event',
  JSON_OBJECT(
    'day', 'Sunday',
    'venue', 'Grote Markt',
    'title', 'The Nordanians',
    'time_text', '18:00 - 19:00',
    'description', '',
    'price', '0',
    'image', '/assets/images/jazz/artists/the-nordanians-show.jpg',
    'learn_more_link', ''
  ),
  3,
  1
);
