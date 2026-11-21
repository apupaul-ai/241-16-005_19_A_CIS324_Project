
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` (`id`, `name`) VALUES (1,'Beach'),(3,'Forest'),(6,'Haor'),(5,'Heritage'),(2,'Hill'),(7,'Island'),(8,'River'),(4,'Tea Garden');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` (`id`, `name`, `latitude`, `longitude`) VALUES (1,'Dhaka',23.810300,90.412500),(2,'Chattogram',22.356900,91.783200),(3,'Sylhet',24.894900,91.868700),(4,'Rajshahi',24.374500,88.604200),(5,'Khulna',22.845600,89.540300),(6,'Barishal',22.701000,90.353500),(7,'Rangpur',25.743900,89.275200),(8,'Mymensingh',24.747100,90.420300),(9,'Cumilla',23.460700,91.180900),(10,'Bogura',24.846500,89.377300);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `destinations` WRITE;
/*!40000 ALTER TABLE `destinations` DISABLE KEYS */;
INSERT INTO `destinations` (`id`, `category_id`, `name`, `division`, `district`, `description`, `best_season`, `avg_daily_cost`, `transfer_cost`, `latitude`, `longitude`, `image`) VALUES (1,1,'Cox\'s Bazar','Chattogram','Cox\'s Bazar','Home to one of the longest natural sea beaches in the world, stretching about 120 km. Popular for sunsets, seafood, surfing and nearby spots like Himchari and Inani Beach.','November to March',3500.00,0.00,21.427200,92.005800,'cox-bazar.jpg'),(2,2,'Sajek Valley','Chattogram','Rangamati','A hill valley in the Chittagong Hill Tracts known for cloud-level views, sunrise, and the culture of the Chakma, Tripura and Pangkhua communities.','October to February',3000.00,0.00,23.381900,92.292700,'sajek-valley.jpg'),(3,3,'Sundarbans','Khulna','Khulna','The largest mangrove forest in the world and a UNESCO World Heritage Site, home of the Royal Bengal Tiger. Visited by boat tours through rivers and creeks.','November to February',6000.00,0.00,21.949700,89.183300,'sundarbans.jpg'),(4,4,'Sreemangal','Sylhet','Moulvibazar','Known as the tea capital of Bangladesh, with rolling tea gardens, Lawachara National Park and the famous seven-layer tea.','October to March',2500.00,0.00,24.306500,91.729600,'sreemangal.jpg'),(5,5,'Paharpur','Rajshahi','Naogaon','Site of Somapura Mahavihara, a large Buddhist monastery from the 8th century and a UNESCO World Heritage Site.','October to March',1500.00,0.00,25.031100,88.976400,'paharpur.jpg'),(6,6,'Tanguar Haor','Sylhet','Sunamganj','A large freshwater wetland and Ramsar site. In the rainy season it becomes a vast lake, and in winter it hosts many migratory birds.','July to October and November to February',3000.00,0.00,25.150000,91.050000,'tanguar-haor.jpg'),(7,7,'Saint Martin','Chattogram','Cox\'s Bazar','The only coral island of Bangladesh in the Bay of Bengal, known for clear water, coral stones, coconut trees and quiet beaches.','November to February',4000.00,3700.00,20.627500,92.322600,'saint-martin.jpg'),(8,5,'Ahsan Manzil','Dhaka','Dhaka','The pink palace on the bank of the Buriganga, once the residence of the Nawab of Dhaka. Now a museum showing the history of the region.','November to February',1200.00,0.00,23.708600,90.406000,'ahsan-manzil.jpg'),(9,8,'Jaflong','Sylhet','Sylhet','A scenic spot at the Bangladesh-India border known for the Piyain river, stone collection, hills and the Khasi community nearby.','July to October and November to February',2000.00,0.00,25.166700,92.016700,'jaflong.jpg'),(10,5,'Mahasthangarh','Rajshahi','Bogura','One of the earliest urban archaeological sites in Bangladesh, with ruins of the ancient city of Pundranagara and a site museum.','October to March',1200.00,0.00,24.965000,89.345000,'mahasthangarh.jpg');
/*!40000 ALTER TABLE `destinations` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `directory_items` WRITE;
/*!40000 ALTER TABLE `directory_items` DISABLE KEYS */;
INSERT INTO `directory_items` (`id`, `destination_id`, `type`, `name`, `phone`, `address`, `price_range`) VALUES (1,1,'hotel','Sayeman Beach Resort','+880 1401-777888','Marine Drive Road, Cox\'s Bazar 4700',NULL),(2,1,'hotel','Long Beach Hotel Cox\'s Bazar','+880 1755-660051','14 Kalatoli, Hotel-Motel Zone, Cox\'s Bazar 4700',NULL),(3,1,'restaurant','Palongki Inani','+880 1766-668485','Marine Drive, Inani Beach, Jaliapalong, Cox\'s Bazar 4750','Moderate'),(4,1,'restaurant','Salt Bistro & Cafe','+880 1816-155651','10 Kalatoli, Hotel-Motel Zone, Cox\'s Bazar 4700','Moderate'),(5,2,'hotel','D\'more Sajek Valley Hotel & Resort','+880 1844-114834','Sajek Valley (97PR+GM6)',NULL),(6,2,'hotel','Megh Pahar Eco Resort','+880 1817-722572','Dighinala-Sajek Road, Sajek',NULL),(7,2,'restaurant','Runhmui Restaurant','+880 1648-338761','Sajek Valley (97PV+42W)','Budget'),(8,2,'restaurant','Montana Restaurant','+880 1879-086308','Sajek Valley (97MV+M73)','Budget'),(9,3,'hotel','Hotel Castle Salam','+880 1833-321168','G, 8 KDA Avenue, Khulna 9000',NULL),(10,3,'hotel','City Inn Limited','+880 1711-298501','B-1, Mojid Sarony, KDA Avenue, Khulna 9100',NULL),(11,3,'restaurant','Deshi Kitchen','+880 1703-152082','44 Mazid Soroni Road, Khulna 9100','Budget'),(12,3,'restaurant','Helium Khulna','+880 9639-203131','27 KDA Approach Road, Khulna 9000','Moderate'),(13,4,'hotel','Balishira Resort','+880 1766-557760','Radhanagar, Sreemangal 3210',NULL),(14,4,'hotel','Novem Eco Resort','+880 1709-882001','Bishamoni, Radhanagar, Sreemangal 3210',NULL),(15,4,'restaurant','Flaming Dine Rooftop','+880 1328-508340','Sreemangal 3210 (8P5J+Q79)','Moderate'),(16,4,'restaurant','Babui Pakhider Adda','+880 1964-706076','Sreemangal (7QR8+WP)','Moderate'),(17,5,'hotel','Motel Chisty','+880 1720-544445','Naogaon 6500 (RW4X+GRG)',NULL),(18,5,'hotel','Mollika Inn','+880 1708-521015','Naogaon',NULL),(19,5,'restaurant','Quince Restaurant Naogaon','+880 1886-955567','Naogaon (RWFJ+9G5)',NULL),(20,5,'restaurant','BP Restaurant and Cafe','+880 1320-123924','Bangabaria Road, Naogaon','Moderate'),(21,6,'hotel','Haor Bilash Guest House','+880 1716-388439','Hajipara, Police Super Office Road, Sunamganj 3000',NULL),(22,6,'hotel','The Peace Palace Luxury Hotel & Restaurant','+880 1334-926868','Old Bus Stand Road, Sunamganj 3000',NULL),(23,6,'restaurant','Ambrosia Family Dine and Party Centre','+880 1886-900646','Hasan Nagar Road, Sunamganj','Moderate'),(24,6,'restaurant','Shaheen\'s Cafe & Restaurant','+880 1712-239206','Kutubpur, Sunamganj 3000','Budget'),(25,9,'hotel','Jaflong Green Resort','+880 1756-162000','Near BGB Camp, Jaflong 3151',NULL),(26,9,'hotel','Hotel Jaflong Point','+880 1711-371423','Camp Road, Dawki 3156',NULL),(27,9,'restaurant','Shimanto View Restaurant','+880 1773-621100','Near BGB Camp, Jaflong 3151','Budget'),(28,9,'restaurant','Sampan Restaurant','+880 1732-225090','Jaflong-Tamabil Road, Jaflong','Moderate'),(29,8,'hotel','Hotel Seven Star (Residential)','+880 1912-012551','99 BCC Road, Thatari Bazar, Dhaka 1203',NULL),(30,8,'hotel','Hotel Grand Rahmania','+880 1404-026069','167/4 DIT Avenue, Dhaka 1000',NULL),(31,8,'restaurant','Beauty Lacchi and Faluda','+880 1861-676779','63/1 Kazi Alauddin Road, Nazira Bazar, Dhaka 1100','Budget'),(32,8,'restaurant','Kolkata Kacchi Ghor','+880 1675-756585','14 Abul Hasnat Road, Dhaka 1211','Moderate'),(33,10,'hotel','Wood Burn Hotel','+880 1755-000940','Park Road, Shatmatha, Bogura 5800',NULL),(34,10,'hotel','Hotel La Villa','+880 1774-974849','Altafunnessa Lane, Bogura 5800',NULL),(35,10,'restaurant','Casuarina','+880 1755-510119','Hotel Naz Garden, Bogura City Bypass, Bogura 5800',NULL),(36,10,'restaurant','Helium Bogura','+880 9639-203131','4th Floor, NK Tower, Bogura','Moderate');
/*!40000 ALTER TABLE `directory_items` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `emergency_contacts` WRITE;
/*!40000 ALTER TABLE `emergency_contacts` DISABLE KEYS */;
INSERT INTO `emergency_contacts` (`id`, `destination_id`, `type`, `name`, `phone`) VALUES (1,NULL,'other','National Emergency Service (Police, Fire, Ambulance)','999'),(2,NULL,'fire','Fire Service and Civil Defence','102'),(3,NULL,'hospital','National Health Information Line','16263'),(4,NULL,'other','National Helpline for Women and Children','109'),(5,NULL,'other','Government Information Service','333'),(6,1,'hospital','Fouad Al-Khateeb Hospital','+880 1790-342233'),(7,2,'hospital','Rangamati Popular Diagnostic & Hospital','+880 1958-678136'),(8,3,'hospital','Islami Bank Hospital, Khulna','+880 1712-068684'),(9,7,'hospital','Upazila Health Complex, Teknaf','+880 1730-324470'),(10,9,'hospital','Ibn Sina Hospital Sylhet Ltd.','+880 9610-009640'),(11,5,'hospital','Naogaon Ibn Sina Hospital & Diagnostic Center','+880 9610-009616'),(12,6,'hospital','Sunamganj Sadar Hospital','+880 871-55251'),(13,8,'hospital','Khan Surgical (Mitford Road)','+880 1677-128358'),(14,10,'hospital','Ibn Sina Hospital & Diagnostic Center Bogura','+880 1338-124712');
/*!40000 ALTER TABLE `emergency_contacts` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `packing_items` WRITE;
/*!40000 ALTER TABLE `packing_items` DISABLE KEYS */;
INSERT INTO `packing_items` (`id`, `item_name`, `category`) VALUES (1,'National ID card or passport','Documents'),(2,'Printed tickets and hotel bookings','Documents'),(3,'Cash in small notes','Documents'),(4,'Emergency contact list','Documents'),(5,'Comfortable walking shoes','Clothing'),(6,'Light cotton clothes','Clothing'),(7,'Light jacket or sweater','Clothing'),(8,'Raincoat or umbrella','Clothing'),(9,'Cap or hat','Clothing'),(10,'First aid kit','Health'),(11,'Oral rehydration salt (ORS)','Health'),(12,'Sunscreen','Health'),(13,'Mosquito repellent','Health'),(14,'Hand sanitizer','Health'),(15,'Personal medicines','Health'),(16,'Phone charger','Electronics'),(17,'Power bank','Electronics'),(18,'Torch or headlamp','Electronics'),(19,'Camera','Electronics'),(20,'Reusable water bottle','Other'),(21,'Snacks','Other'),(22,'Waterproof bag','Other'),(23,'Small backpack','Other');
/*!40000 ALTER TABLE `packing_items` ENABLE KEYS */;
UNLOCK TABLES;

LOCK TABLES `transport_modes` WRITE;
/*!40000 ALTER TABLE `transport_modes` DISABLE KEYS */;
INSERT INTO `transport_modes` (`id`, `name`, `cost_per_km`, `note`) VALUES (1,'Bus (non-AC)',2.40,'BRTA long-distance bus rate, Sept 2026. Real fares can be a little higher.'),(2,'Bus (AC)',5.00,'Estimate. AC fares are set by bus owners.'),(3,'Train (Shovon chair)',1.10,'Estimate. Only where a train runs on this route.'),(4,'Train (Snigdha)',2.00,'Estimate. Only where a train runs on this route.');
/*!40000 ALTER TABLE `transport_modes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

