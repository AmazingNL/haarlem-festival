-- Seed restaurant venues matching the yummy page slugs so reservations can link.
USE haarlem_festival;

INSERT INTO restaurant (name, slug, capacity)
SELECT 'Ratatouille Food & Wine', 'ratatouille', 60
WHERE NOT EXISTS (SELECT 1 FROM restaurant WHERE slug = 'ratatouille');

INSERT INTO restaurant (name, slug, capacity)
SELECT 'Bistro Toujours', 'bistro-toujours', 50
WHERE NOT EXISTS (SELECT 1 FROM restaurant WHERE slug = 'bistro-toujours');
