-- Dance artist image paths for overview and artist detail pages.
USE haarlem_festival;

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/hardwell.jpg',
    image_alt = 'Hardwell performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'hardwell';

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/armin-van-buuren.jpg',
    image_alt = 'Armin van Buuren performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'armin-van-buuren';

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/martin-garrix.jpg',
    image_alt = 'Martin Garrix performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'martin-garrix';

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/tiesto.jpg',
    image_alt = 'Tiësto performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'tiesto';

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/nicky-romero.jpg',
    image_alt = 'Nicky Romero performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'nicky-romero';

UPDATE dance_artist
SET
    image_path = '/assets/images/dance/afrojack.jpg',
    image_alt = 'Afrojack performing at Haarlem Dance',
    updated_at = NOW()
WHERE slug = 'afrojack';
