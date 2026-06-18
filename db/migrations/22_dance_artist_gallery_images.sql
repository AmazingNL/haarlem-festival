-- Dance artist gallery image paths for artist detail pages.
USE haarlem_festival;

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/hardwell-1.jpg',
        '/assets/images/dance/gallery/hardwell-2.jpg',
        '/assets/images/dance/gallery/hardwell-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'hardwell';

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/armin-van-buuren-1.jpg',
        '/assets/images/dance/gallery/armin-van-buuren-2.jpg',
        '/assets/images/dance/gallery/armin-van-buuren-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'armin-van-buuren';

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/martin-garrix-1.jpg',
        '/assets/images/dance/gallery/martin-garrix-2.jpg',
        '/assets/images/dance/gallery/martin-garrix-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'martin-garrix';

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/tiesto-1.jpg',
        '/assets/images/dance/gallery/tiesto-2.jpg',
        '/assets/images/dance/gallery/tiesto-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'tiesto';

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/nicky-romero-1.jpg',
        '/assets/images/dance/gallery/nicky-romero-2.jpg',
        '/assets/images/dance/gallery/nicky-romero-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'nicky-romero';

UPDATE dance_artist
SET
    gallery_images = JSON_ARRAY(
        '/assets/images/dance/gallery/afrojack-1.jpg',
        '/assets/images/dance/gallery/afrojack-2.jpg',
        '/assets/images/dance/gallery/afrojack-3.jpg'
    ),
    updated_at = NOW()
WHERE slug = 'afrojack';
