UPDATE `h5I5O_postmeta` as m 
JOIN `h5I5O_posts` as p ON m.post_id = p.ID 
SET m.meta_key = 'colors_color_ca_en' 
WHERE m.meta_key = 'product_details_0_colors_color_ca_en' 
AND p.post_type = 'products';


UPDATE `h5I5O_postmeta` as m 
JOIN `h5I5O_posts` as p ON m.post_id = p.ID 
SET m.meta_key = '_colors_color_ca_en' 
WHERE m.meta_value = 'field_5d55c5c0aeb2b' 
AND p.post_type = 'products';