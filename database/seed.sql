INSERT INTO destinations
(category_id, name, division, district, description, best_season, avg_daily_cost, latitude, longitude, image)
VALUES
(1, 'Cox''s Bazar', 'Chattogram', 'Cox''s Bazar',
 'Home to one of the longest natural sea beaches in the world, stretching about 120 km. Popular for sunsets, seafood, surfing and nearby spots like Himchari and Inani Beach.',
 'November to March', 3500.00, 21.427200, 92.005800, 'coxs-bazar.jpg'),

(2, 'Sajek Valley', 'Chattogram', 'Rangamati',
 'A hill valley in the Chittagong Hill Tracts known for cloud-level views, sunrise, and the culture of the Chakma, Tripura and Pangkhua communities.',
 'October to February', 3000.00, 23.381900, 92.292700, 'sajek-valley.jpg'),

(3, 'Sundarbans', 'Khulna', 'Khulna',
 'The largest mangrove forest in the world and a UNESCO World Heritage Site, home of the Royal Bengal Tiger. Visited by boat tours through rivers and creeks.',
 'November to February', 6000.00, 21.949700, 89.183300, 'sundarbans.jpg'),

(4, 'Sreemangal', 'Sylhet', 'Moulvibazar',
 'Known as the tea capital of Bangladesh, with rolling tea gardens, Lawachara National Park and the famous seven-layer tea.',
 'October to March', 2500.00, 24.306500, 91.729600, 'sreemangal.jpg'),

(5, 'Paharpur', 'Rajshahi', 'Naogaon',
 'Site of Somapura Mahavihara, a large Buddhist monastery from the 8th century and a UNESCO World Heritage Site.',
 'October to March', 1500.00, 25.031100, 88.976400, 'paharpur.jpg'),

(6, 'Tanguar Haor', 'Sylhet', 'Sunamganj',
 'A large freshwater wetland and Ramsar site. In the rainy season it becomes a vast lake, and in winter it hosts many migratory birds.',
 'July to October and November to February', 3000.00, 25.150000, 91.050000, 'tanguar-haor.jpg'),

(7, 'Saint Martin', 'Chattogram', 'Cox''s Bazar',
 'The only coral island of Bangladesh in the Bay of Bengal, known for clear water, coral stones, coconut trees and quiet beaches.',
 'November to February', 4000.00, 20.627500, 92.322600, 'saint-martin.jpg'),

(5, 'Ahsan Manzil', 'Dhaka', 'Dhaka',
 'The pink palace on the bank of the Buriganga, once the residence of the Nawab of Dhaka. Now a museum showing the history of the region.',
 'November to February', 1200.00, 23.708600, 90.406000, 'ahsan-manzil.jpg'),

(8, 'Jaflong', 'Sylhet', 'Sylhet',
 'A scenic spot at the Bangladesh-India border known for the Piyain river, stone collection, hills and the Khasi community nearby.',
 'July to October and November to February', 2000.00, 25.166700, 92.016700, 'jaflong.jpg'),

(5, 'Mahasthangarh', 'Rajshahi', 'Bogura',
 'One of the earliest urban archaeological sites in Bangladesh, with ruins of the ancient city of Pundranagara and a site museum.',
 'October to March', 1200.00, 24.965000, 89.345000, 'mahasthangarh.jpg');
 INSERT INTO directory_items (destination_id, type, name, phone, address, price_range) VALUES
((SELECT id FROM destinations WHERE name = 'Cox''s Bazar'), 'hotel', 'Sayeman Beach Resort', '+880 1401-777888', 'Marine Drive Road, Cox''s Bazar 4700', NULL),
((SELECT id FROM destinations WHERE name = 'Cox''s Bazar'), 'hotel', 'Long Beach Hotel Cox''s Bazar', '+880 1755-660051', '14 Kalatoli, Hotel-Motel Zone, Cox''s Bazar 4700', NULL),
((SELECT id FROM destinations WHERE name = 'Cox''s Bazar'), 'restaurant', 'Palongki Inani', '+880 1766-668485', 'Marine Drive, Inani Beach, Jaliapalong, Cox''s Bazar 4750', 'Moderate'),
((SELECT id FROM destinations WHERE name = 'Cox''s Bazar'), 'restaurant', 'Salt Bistro & Cafe', '+880 1816-155651', '10 Kalatoli, Hotel-Motel Zone, Cox''s Bazar 4700', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Sajek Valley'), 'hotel', 'D''more Sajek Valley Hotel & Resort', '+880 1844-114834', 'Sajek Valley (97PR+GM6)', NULL),
((SELECT id FROM destinations WHERE name = 'Sajek Valley'), 'hotel', 'Megh Pahar Eco Resort', '+880 1817-722572', 'Dighinala-Sajek Road, Sajek', NULL),
((SELECT id FROM destinations WHERE name = 'Sajek Valley'), 'restaurant', 'Runhmui Restaurant', '+880 1648-338761', 'Sajek Valley (97PV+42W)', 'Budget'),
((SELECT id FROM destinations WHERE name = 'Sajek Valley'), 'restaurant', 'Montana Restaurant', '+880 1879-086308', 'Sajek Valley (97MV+M73)', 'Budget'),

((SELECT id FROM destinations WHERE name = 'Sundarbans'), 'hotel', 'Hotel Castle Salam', '+880 1833-321168', 'G, 8 KDA Avenue, Khulna 9000', NULL),
((SELECT id FROM destinations WHERE name = 'Sundarbans'), 'hotel', 'City Inn Limited', '+880 1711-298501', 'B-1, Mojid Sarony, KDA Avenue, Khulna 9100', NULL),
((SELECT id FROM destinations WHERE name = 'Sundarbans'), 'restaurant', 'Deshi Kitchen', '+880 1703-152082', '44 Mazid Soroni Road, Khulna 9100', 'Budget'),
((SELECT id FROM destinations WHERE name = 'Sundarbans'), 'restaurant', 'Helium Khulna', '+880 9639-203131', '27 KDA Approach Road, Khulna 9000', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Sreemangal'), 'hotel', 'Balishira Resort', '+880 1766-557760', 'Radhanagar, Sreemangal 3210', NULL),
((SELECT id FROM destinations WHERE name = 'Sreemangal'), 'hotel', 'Novem Eco Resort', '+880 1709-882001', 'Bishamoni, Radhanagar, Sreemangal 3210', NULL),
((SELECT id FROM destinations WHERE name = 'Sreemangal'), 'restaurant', 'Flaming Dine Rooftop', '+880 1328-508340', 'Sreemangal 3210 (8P5J+Q79)', 'Moderate'),
((SELECT id FROM destinations WHERE name = 'Sreemangal'), 'restaurant', 'Babui Pakhider Adda', '+880 1964-706076', 'Sreemangal (7QR8+WP)', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Paharpur'), 'hotel', 'Motel Chisty', '+880 1720-544445', 'Naogaon 6500 (RW4X+GRG)', NULL),
((SELECT id FROM destinations WHERE name = 'Paharpur'), 'hotel', 'Mollika Inn', '+880 1708-521015', 'Naogaon', NULL),
((SELECT id FROM destinations WHERE name = 'Paharpur'), 'restaurant', 'Quince Restaurant Naogaon', '+880 1886-955567', 'Naogaon (RWFJ+9G5)', NULL),
((SELECT id FROM destinations WHERE name = 'Paharpur'), 'restaurant', 'BP Restaurant and Cafe', '+880 1320-123924', 'Bangabaria Road, Naogaon', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Tanguar Haor'), 'hotel', 'Haor Bilash Guest House', '+880 1716-388439', 'Hajipara, Police Super Office Road, Sunamganj 3000', NULL),
((SELECT id FROM destinations WHERE name = 'Tanguar Haor'), 'hotel', 'The Peace Palace Luxury Hotel & Restaurant', '+880 1334-926868', 'Old Bus Stand Road, Sunamganj 3000', NULL),
((SELECT id FROM destinations WHERE name = 'Tanguar Haor'), 'restaurant', 'Ambrosia Family Dine and Party Centre', '+880 1886-900646', 'Hasan Nagar Road, Sunamganj', 'Moderate'),
((SELECT id FROM destinations WHERE name = 'Tanguar Haor'), 'restaurant', 'Shaheen''s Cafe & Restaurant', '+880 1712-239206', 'Kutubpur, Sunamganj 3000', 'Budget'),

((SELECT id FROM destinations WHERE name = 'Jaflong'), 'hotel', 'Jaflong Green Resort', '+880 1756-162000', 'Near BGB Camp, Jaflong 3151', NULL),
((SELECT id FROM destinations WHERE name = 'Jaflong'), 'hotel', 'Hotel Jaflong Point', '+880 1711-371423', 'Camp Road, Dawki 3156', NULL),
((SELECT id FROM destinations WHERE name = 'Jaflong'), 'restaurant', 'Shimanto View Restaurant', '+880 1773-621100', 'Near BGB Camp, Jaflong 3151', 'Budget'),
((SELECT id FROM destinations WHERE name = 'Jaflong'), 'restaurant', 'Sampan Restaurant', '+880 1732-225090', 'Jaflong-Tamabil Road, Jaflong', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Ahsan Manzil'), 'hotel', 'Hotel Seven Star (Residential)', '+880 1912-012551', '99 BCC Road, Thatari Bazar, Dhaka 1203', NULL),
((SELECT id FROM destinations WHERE name = 'Ahsan Manzil'), 'hotel', 'Hotel Grand Rahmania', '+880 1404-026069', '167/4 DIT Avenue, Dhaka 1000', NULL),
((SELECT id FROM destinations WHERE name = 'Ahsan Manzil'), 'restaurant', 'Beauty Lacchi and Faluda', '+880 1861-676779', '63/1 Kazi Alauddin Road, Nazira Bazar, Dhaka 1100', 'Budget'),
((SELECT id FROM destinations WHERE name = 'Ahsan Manzil'), 'restaurant', 'Kolkata Kacchi Ghor', '+880 1675-756585', '14 Abul Hasnat Road, Dhaka 1211', 'Moderate'),

((SELECT id FROM destinations WHERE name = 'Mahasthangarh'), 'hotel', 'Wood Burn Hotel', '+880 1755-000940', 'Park Road, Shatmatha, Bogura 5800', NULL),
((SELECT id FROM destinations WHERE name = 'Mahasthangarh'), 'hotel', 'Hotel La Villa', '+880 1774-974849', 'Altafunnessa Lane, Bogura 5800', NULL),
((SELECT id FROM destinations WHERE name = 'Mahasthangarh'), 'restaurant', 'Casuarina', '+880 1755-510119', 'Hotel Naz Garden, Bogura City Bypass, Bogura 5800', NULL),
((SELECT id FROM destinations WHERE name = 'Mahasthangarh'), 'restaurant', 'Helium Bogura', '+880 9639-203131', '4th Floor, NK Tower, Bogura', 'Moderate');
INSERT INTO emergency_contacts (destination_id, type, name, phone) VALUES
((SELECT id FROM destinations WHERE name = 'Cox''s Bazar'), 'hospital', 'Fouad Al-Khateeb Hospital', '+880 1790-342233'),
((SELECT id FROM destinations WHERE name = 'Sajek Valley'), 'hospital', 'Rangamati Popular Diagnostic & Hospital', '+880 1958-678136'),
((SELECT id FROM destinations WHERE name = 'Sundarbans'), 'hospital', 'Islami Bank Hospital, Khulna', '+880 1712-068684'),
((SELECT id FROM destinations WHERE name = 'Saint Martin'), 'hospital', 'Upazila Health Complex, Teknaf', '+880 1730-324470'),
((SELECT id FROM destinations WHERE name = 'Jaflong'), 'hospital', 'Ibn Sina Hospital Sylhet Ltd.', '+880 9610-009640'),
((SELECT id FROM destinations WHERE name = 'Paharpur'), 'hospital', 'Naogaon Ibn Sina Hospital & Diagnostic Center', '+880 9610-009616'),
((SELECT id FROM destinations WHERE name = 'Tanguar Haor'), 'hospital', 'Sunamganj Sadar Hospital', '+880 871-55251'),
((SELECT id FROM destinations WHERE name = 'Ahsan Manzil'), 'hospital', 'Khan Surgical (Mitford Road)', '+880 1677-128358'),
((SELECT id FROM destinations WHERE name = 'Mahasthangarh'), 'hospital', 'Ibn Sina Hospital & Diagnostic Center Bogura', '+880 1338-124712');
INSERT INTO packing_items (item_name, category) VALUES
('National ID card or passport', 'Documents'),
('Printed tickets and hotel bookings', 'Documents'),
('Cash in small notes', 'Documents'),
('Emergency contact list', 'Documents'),
('Comfortable walking shoes', 'Clothing'),
('Light cotton clothes', 'Clothing'),
('Light jacket or sweater', 'Clothing'),
('Raincoat or umbrella', 'Clothing'),
('Cap or hat', 'Clothing'),
('First aid kit', 'Health'),
('Oral rehydration salt (ORS)', 'Health'),
('Sunscreen', 'Health'),
('Mosquito repellent', 'Health'),
('Hand sanitizer', 'Health'),
('Personal medicines', 'Health'),
('Phone charger', 'Electronics'),
('Power bank', 'Electronics'),
('Torch or headlamp', 'Electronics'),
('Camera', 'Electronics'),
('Reusable water bottle', 'Other'),
('Snacks', 'Other'),
('Waterproof bag', 'Other'),
('Small backpack', 'Other');