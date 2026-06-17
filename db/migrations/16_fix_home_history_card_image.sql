-- Home "History" activity card used an upside-down image asset; use the Grote Kerk photo from History CMS.
USE haarlem_festival;

UPDATE page_section ps
INNER JOIN page p ON p.page_id = ps.page_id
SET ps.content = JSON_SET(
    COALESCE(ps.content, JSON_OBJECT()),
    '$.card_two_image', '/assets/images/history/history-grote-kerk.jpg',
    '$.card_two_alt', 'The Grote Kerk in Haarlem'
)
WHERE p.slug = 'home'
  AND ps.section_type = 'cards_grid';
