UPDATE page_section
SET content = JSON_REMOVE(
    content,
    '$.selected_day',
    '$.selected_time',
    '$.selected_language',
    '$.selected_ticket'
)
WHERE section_type = 'history_book_tour_booking';

UPDATE page_section
SET content = JSON_SET(
    content,
    '$.default_day', 'Thursday',
    '$.day_one_time_one_guides', 'Jan-Willem (Dutch), Frederic (English)',
    '$.day_one_time_two_guides', 'Jan-Willem (Dutch), Frederic (English)',
    '$.day_one_time_three_guides', 'Jan-Willem (Dutch), Frederic (English)',
    '$.day_two_time_one_guides', 'Annet (Dutch), Williams (English)',
    '$.day_two_time_two_guides', 'Annet (Dutch), Williams (English), Kim (Chinese)',
    '$.day_two_time_three_guides', 'Annet (Dutch), Williams (English)',
    '$.day_three_time_one_guides', 'Annet + Jan-Willem (Dutch), Frederic + William (English)',
    '$.day_three_time_two_guides', 'Annet + Jan-Willem (Dutch), Frederic + William (English), Kim (Mandarin)',
    '$.day_three_time_three_guides', 'Jan-Willem (Dutch), Frederic (English), Kim (Mandarin)',
    '$.day_four_time_one_guides', 'Lisa + Annet (Dutch), Deirdre + Frederic (English), Kim (Mandarin)',
    '$.day_four_time_two_guides', 'Lisa + Annet + Jan-Willem (Dutch), Deirdre + Frederic + William (English), Kim + Susan (Mandarin)',
    '$.day_four_time_three_guides', 'Jan-Willem (Dutch), William (English)'
)
WHERE section_type = 'history_book_tour_schedule';
