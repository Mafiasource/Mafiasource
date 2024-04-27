/*
 Navicat MySQL Data Transfer

 Source Server         : Mafiasource
 Source Server Type    : MySQL
 Source Server Version : 100329
 Source Host           : *:3306
 Source Schema         : ms

 Target Server Type    : MySQL
 Target Server Version : 100329
 File Encoding         : 65001

 Date: 04/07/2021 18:21:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for airplane
-- ----------------------------
DROP TABLE IF EXISTS `airplane`;
CREATE TABLE `airplane`  (
  `id` smallint NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NOT NULL DEFAULT 0,
  `power` smallint NOT NULL DEFAULT 10,
  `position` smallint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of airplane
-- ----------------------------
INSERT INTO `airplane` VALUES (0, 'Geen', NULL, 0, 0, 6, 1, 0);
INSERT INTO `airplane` VALUES (1, 'Fokker DR-1', 'bad8f55b.jpg', 200000, 11, 0, 1, 0);
INSERT INTO `airplane` VALUES (2, 'Havilland DH 82A', 'c05ad5fb.jpg', 500000, 27, 1, 1, 0);
INSERT INTO `airplane` VALUES (3, 'Fleet-7', '89a9ca23.jpg', 1100000, 38, 2, 1, 0);
INSERT INTO `airplane` VALUES (4, 'Douglas DC-3', 'e8978f01.jpg', 2000000, 52, 3, 1, 0);
INSERT INTO `airplane` VALUES (5, 'Cassna', '4cf01792.jpg', 3500000, 67, 4, 1, 0);
INSERT INTO `airplane` VALUES (6, 'Lear Yet', '7a057466.jpg', 4500000, 78, 5, 1, 0);

-- ----------------------------
-- Table structure for bank_log
-- ----------------------------
DROP TABLE IF EXISTS `bank_log`;
CREATE TABLE `bank_log`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `senderID` bigint NULL DEFAULT NULL,
  `receiverID` bigint NULL DEFAULT NULL,
  `amount` bigint NULL DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `senderID`(`senderID`) USING BTREE,
  INDEX `receiverID`(`receiverID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of bank_log
-- ----------------------------

-- ----------------------------
-- Table structure for bullet_factory
-- ----------------------------
DROP TABLE IF EXISTS `bullet_factory`;
CREATE TABLE `bullet_factory`  (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `possessID` int NOT NULL DEFAULT 0,
  `bullets` int NOT NULL DEFAULT 0,
  `priceEachBullet` int NOT NULL DEFAULT 2500,
  `production` int NOT NULL DEFAULT 10000,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of bullet_factory
-- ----------------------------
INSERT INTO `bullet_factory` VALUES (1, 1, 10000, 2500, 0);
INSERT INTO `bullet_factory` VALUES (2, 2, 10000, 2500, 0);
INSERT INTO `bullet_factory` VALUES (3, 3, 10000, 2500, 0);
INSERT INTO `bullet_factory` VALUES (4, 4, 10000, 2500, 0);
INSERT INTO `bullet_factory` VALUES (5, 5, 10000, 2500, 0);
INSERT INTO `bullet_factory` VALUES (6, 6, 10000, 2500, 0);

-- ----------------------------
-- Table structure for business
-- ----------------------------
DROP TABLE IF EXISTS `business`;
CREATE TABLE `business`  (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `opening_price` float(6, 2) NOT NULL,
  `last_price` float(6, 2) NOT NULL,
  `close_price` float(6, 2) NOT NULL,
  `high_price` float(6, 2) NOT NULL,
  `low_price` float(6, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of business
-- ----------------------------
INSERT INTO `business` VALUES (1, 'McDopals', 127.32, 127.32, 127.32, 127.32, 127.32);
INSERT INTO `business` VALUES (2, 'Palmart', 157.12, 157.12, 157.12, 157.12, 157.12);
INSERT INTO `business` VALUES (3, 'QRadio', 27.03, 27.03, 27.03, 27.03, 27.03);
INSERT INTO `business` VALUES (4, 'Fibernet', 57.56, 57.56, 57.56, 57.56, 57.56);
INSERT INTO `business` VALUES (5, 'KFB', 79.20, 79.20, 79.20, 79.20, 79.20);
INSERT INTO `business` VALUES (6, 'DanFashion', 36.00, 36.00, 36.00, 36.00, 36.00);
INSERT INTO `business` VALUES (7, 'LibertyBank', 299.21, 299.21, 299.21, 299.21, 299.21);
INSERT INTO `business` VALUES (8, 'DN-Insurance', 71.70, 71.70, 71.70, 71.70, 71.70);
INSERT INTO `business` VALUES (9, 'NationalBank', 301.23, 301.23, 301.23, 301.23, 301.23);
INSERT INTO `business` VALUES (10, 'Teleslut', 46.32, 46.32, 46.32, 46.32, 46.32);
INSERT INTO `business` VALUES (11, 'LifeTV', 22.00, 22.00, 22.00, 22.00, 22.00);
INSERT INTO `business` VALUES (12, 'MintMusic', 26.21, 26.21, 26.21, 26.21, 26.21);
INSERT INTO `business` VALUES (13, 'SATimes', 34.11, 34.11, 34.11, 34.11, 34.11);
INSERT INTO `business` VALUES (14, 'Lifetech', 121.96, 121.96, 121.96, 121.96, 121.96);
INSERT INTO `business` VALUES (15, 'Dolcom-Insurance', 78.21, 78.21, 78.21, 78.21, 78.21);
INSERT INTO `business` VALUES (16, 'DeltaMarket', 111.50, 111.50, 111.50, 111.50, 111.50);
INSERT INTO `business` VALUES (17, 'Edison', 32.00, 32.00, 32.00, 32.00, 32.00);
INSERT INTO `business` VALUES (18, 'SwekTV', 23.21, 23.21, 23.21, 23.21, 23.21);
INSERT INTO `business` VALUES (19, 'Omazon', 46.00, 46.00, 46.00, 46.00, 46.00);
INSERT INTO `business` VALUES (20, 'CBeans', 117.01, 117.01, 117.01, 117.01, 117.01);
INSERT INTO `business` VALUES (21, 'Netfix', 87.70, 87.70, 87.70, 87.70, 87.70);
INSERT INTO `business` VALUES (22, 'BurgerQueen', 157.08, 157.08, 157.08, 157.08, 157.08);
INSERT INTO `business` VALUES (23, 'Spacetravel', 350.10, 350.10, 350.10, 350.10, 350.10);
INSERT INTO `business` VALUES (24, 'NYCustoms', 52.06, 52.06, 52.06, 52.06, 52.06);
INSERT INTO `business` VALUES (25, 'HashHarvest', 99.33, 99.33, 99.33, 99.33, 99.33);
INSERT INTO `business` VALUES (26, 'GamePlanet', 52.10, 52.10, 52.10, 52.10, 52.10);
INSERT INTO `business` VALUES (27, 'LPS', 61.00, 61.00, 61.00, 61.00, 61.00);
INSERT INTO `business` VALUES (28, 'FlyMS', 115.09, 115.09, 115.09, 115.09, 115.09);
INSERT INTO `business` VALUES (29, 'Copy-Inc', 29.98, 29.98, 29.98, 29.98, 29.98);
INSERT INTO `business` VALUES (30, 'Ovondo', 25.02, 25.02, 25.02, 25.02, 25.02);

-- ----------------------------
-- Table structure for business_history
-- ----------------------------
DROP TABLE IF EXISTS `business_history`;
CREATE TABLE `business_history`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `businessID` smallint NOT NULL,
  `close_day` float(6, 2) NOT NULL,
  `highest_day` float(6, 2) NOT NULL,
  `lowest_day` float(6, 2) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10891 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of business_history
-- ----------------------------

-- ----------------------------
-- Table structure for business_news
-- ----------------------------
DROP TABLE IF EXISTS `business_news`;
CREATE TABLE `business_news`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `businessID` mediumint NOT NULL,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of business_news
-- ----------------------------
INSERT INTO `business_news` VALUES (1, 'Topman geeft toe dat personeelslid fraude heeft gepleegd', 'Topman admits that staff member has committed fraud', 6, 2);
INSERT INTO `business_news` VALUES (2, 'wacht niet en onderneemt meteen actie', 'doesn\'t wait to undertake actions', 19, 1);
INSERT INTO `business_news` VALUES (3, 'personeel komt in opstand', 'employees revolt against bad practices in their company', 2, 4);
INSERT INTO `business_news` VALUES (4, 'product prijzen stijgen voor een onbekende reden', 'product prices raised for no known reasons', 16, 4);
INSERT INTO `business_news` VALUES (5, 'de moord op de CEO zorgt voor een keerpunt in de zaken!', 'murder on a CEO creates turning point in business', 26, 2);

-- ----------------------------
-- Table structure for business_stock
-- ----------------------------
DROP TABLE IF EXISTS `business_stock`;
CREATE TABLE `business_stock`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NOT NULL,
  `businessID` smallint NOT NULL,
  `payed_ea` float(6, 2) NOT NULL,
  `amount` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of business_stock
-- ----------------------------

-- ----------------------------
-- Table structure for change_email
-- ----------------------------
DROP TABLE IF EXISTS `change_email`;
CREATE TABLE `change_email`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NOT NULL,
  `key` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of change_email
-- ----------------------------

-- ----------------------------
-- Table structure for city
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city`  (
  `id` tinyint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `stateID` int NULL DEFAULT NULL COMMENT 'couple=state&factor=id&show=name',
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of city
-- ----------------------------
INSERT INTO `city` VALUES (1, 1, 'Honolulu', 1, 1, 0);
INSERT INTO `city` VALUES (2, 1, 'Kahului', 2, 1, 0);
INSERT INTO `city` VALUES (3, 1, 'Kailua Kona', 3, 1, 0);
INSERT INTO `city` VALUES (4, 2, 'Los Angeles', 4, 1, 0);
INSERT INTO `city` VALUES (5, 2, 'San Diego', 5, 1, 0);
INSERT INTO `city` VALUES (6, 2, 'San Fransisco', 6, 1, 0);
INSERT INTO `city` VALUES (7, 3, 'Buffalo', 7, 1, 0);
INSERT INTO `city` VALUES (8, 3, 'NY City', 8, 1, 0);
INSERT INTO `city` VALUES (9, 3, 'Kingston', 9, 1, 0);
INSERT INTO `city` VALUES (10, 4, 'Denver', 10, 1, 0);
INSERT INTO `city` VALUES (11, 4, 'Colorado Springs', 11, 1, 0);
INSERT INTO `city` VALUES (12, 4, 'Grand Junction', 12, 1, 0);
INSERT INTO `city` VALUES (13, 5, 'Dallas', 13, 1, 0);
INSERT INTO `city` VALUES (14, 5, 'El Paso', 14, 1, 0);
INSERT INTO `city` VALUES (15, 5, 'San Antonio', 15, 1, 0);
INSERT INTO `city` VALUES (16, 6, 'Jacksonville', 16, 1, 0);
INSERT INTO `city` VALUES (17, 6, 'Miami', 17, 1, 0);
INSERT INTO `city` VALUES (18, 6, 'Panama City', 18, 1, 0);

-- ----------------------------
-- Table structure for cms
-- ----------------------------
DROP TABLE IF EXISTS `cms`;
CREATE TABLE `cms`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `naam` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content_nl` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `content_en` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of cms
-- ----------------------------
INSERT INTO `cms` VALUES (1, 'Slider1', '<h2>Online Mafia RPG</h2>\r\n\r\n<p>In de wereld van Mafiasource kan jij samen met vrienden en familie de staten en steden van Amerika veroveren!</p>\r\n', '<h2>Online Mafia RPG</h2>\r\n\r\n<p>In the world of Mafiasource you can conquer the States and Cities of America together with friends and family</p>\r\n', 0, 1, 0);
INSERT INTO `cms` VALUES (2, 'Slider2', '<h2>Carjacking</h2>\r\n\r\n<p>Steel de mooiste voertuigen en vul meerdere garages met deze pracht wagens!</p>\r\n', '<h2>Carjacking</h2>\r\n\r\n<p>Steal the most beautiful vehicles and fill multiple garages with these beauties!</p>\r\n', 1, 1, 0);
INSERT INTO `cms` VALUES (3, 'Slider3', '<h2>Drugs smokkelen</h2>\r\n\r\n<p>Ontwikkel de beste smokkel-route en maak miljoenen naarmate je stijgt in allerhande levels!</p>\r\n', '<h2>Smuggle drugs</h2>\r\n\r\n<p>Develop the best smugglers route and make millions as you evolve in all kinds of levels!</p>\r\n', 2, 1, 0);
INSERT INTO `cms` VALUES (4, 'Slider4', '<h2>Imperium opbouwen</h2>\r\n\r\n<p>Beheer tal van bezittingen of besteel anderen van hun bezit en noem jezelf de koning van bepaalde staten of steden!</p>\r\n', '<h2>Build an empire</h2>\r\n\r\n<p>Poses multiple buildings or rob others for their possessions and be able to call yourself the king of certain states or cities!</p>\r\n', 3, 1, 0);
INSERT INTO `cms` VALUES (5, 'Statistieken', '<h2>Game Statistieken</h2>\r\n\r\n<p>#1 Speler: {bestPlayer}<br />\r\nNieuwste lid: {newestMember}<br />\r\n<br />\r\nBeste familie: {bestFam}<br />\r\nMeeste familie geld: {richestFam}<br />\r\n<br />\r\nKillerking: {killerking}<br />\r\nEervolste speler: {honored}<br />\r\n<br />\r\nEr zijn op dit moment {playersKilled}<strong> spelers dood</strong><br />\r\n<br />\r\nIn totaal werden er {unitsSmuggled} smokkeleenheden <strong>gesmokkeld</strong><br />\r\n<br />\r\nEr zijn in totaal {creditsWon}Â <strong>verdient</strong> onder alle leden<br />\r\n<br />\r\nDit is onze {roundInfo}</p>\r\n', '<h2>Game Statistics</h2>\r\n\r\n<p>#1 Player: {bestPlayer}<br />\r\nNewest member: {newestMember}<br />\r\n<br />\r\nBest family: {bestFam}<br />\r\nMost family money: {richestFam}<br />\r\n<br />\r\nKillerking: {killerking}<br />\r\nMost honored: {honored}<br />\r\n<br />\r\nAt this moment {playersKilled}<strong> players </strong>are <strong>dead</strong><br />\r\n<br />\r\nA total amount of {unitsSmuggled} units were <strong>smuggled</strong> in and outside of the states<br />\r\n<br />\r\nOur players have <strong>earned</strong> a total amount of {creditsWon}<br />\r\n<br />\r\nthis is our {roundInfo}</p>\r\n', 4, 1, 0);
INSERT INTO `cms` VALUES (6, 'Algemene voorwaarden', '<h1>Algemene voorwaarden voor Mafiasource</h1>\r\n\r\n<h2>Invoering</h2>\r\n\r\n<p>Deze website-standaardvoorwaarden die op deze webpagina zijn geschreven, zullen uw gebruik van onze website, Mafiasource, toegankelijk op https://mafiasource.nl beheren.</p>\r\n\r\n<p>Deze voorwaarden worden volledig toegepast en hebben invloed op uw gebruik van deze website. Door deze website te gebruiken, stemde u ermee in om alle hierin beschreven voorwaarden te accepteren. U mag deze website niet gebruiken als u het niet eens bent met een van deze algemene voorwaarden voor deze website. Deze algemene voorwaarden zijn gegenereerd met behulp van het <a href=\"https://www.termsandcondiitionssample.com\">sjabloon met algemene voorwaarden</a>.</p>\r\n\r\n<p>Kinderen of personen jonger dan 13 jaar mogen deze Website niet gebruiken.</p>\r\n\r\n<h2>Intellectuele eigendomsrechten</h2>\r\n\r\n<p>Behalve de inhoud die u bezit, bezitten Mafiasource en / of haar licentiegevers volgens deze Voorwaarden alle intellectuele eigendomsrechten en materialen op deze Website.</p>\r\n\r\n<p>U krijgt alleen een beperkte licentie om het materiaal op deze website te bekijken.</p>\r\n\r\n<h2>Beperkingen</h2>\r\n\r\n<p>U bent specifiek beperkt tot het volgende:</p>\r\n\r\n<ul>\r\n	<li>publiceren van websitemateriaal in andere media;</li>\r\n	<li>het verkopen, in sublicentie geven en / of anderszins commercialiseren van enig Website-materiaal;</li>\r\n	<li>openbaar materiaal van de website uitvoeren en / of tonen;</li>\r\n	<li>gebruik van deze website op een manier die schadelijk is of kan zijn voor deze website;</li>\r\n	<li>gebruik van deze website op een manier die de gebruikerstoegang tot deze website beÃ¯nvloedt;</li>\r\n	<li>het gebruik van deze website in strijd met de toepasselijke wet- en regelgeving of op enigerlei wijze kan schade toebrengen aan de website, of aan een persoon of zakelijke entiteit;</li>\r\n	<li>deelnemen aan datamining, data-winning, data-extractie of enige andere soortgelijke activiteit met betrekking tot deze website;</li>\r\n	<li>gebruik van deze website om reclame of marketing te maken.</li>\r\n</ul>\r\n\r\n<p>Bepaalde delen van deze Website hebben geen toegang door u en Mafiasource kan de toegang door u tot enig deel van deze Website op elk gewenst moment naar eigen goeddunken verder beperken. Elke gebruikers-ID en elk wachtwoord dat u voor deze website heeft, is vertrouwelijk en u moet ook vertrouwelijk blijven.</p>\r\n\r\n<h2>Uw inhoud</h2>\r\n\r\n<p>In deze algemene voorwaarden voor deze website betekent \"uw inhoud\" alle audio-, videotekst, afbeeldingen of ander materiaal dat u op deze website wilt weergeven. Door uw inhoud weer te geven, verleent u Mafiasource een niet-exclusieve, wereldwijde onherroepelijke, sublicentieerbare licentie voor het gebruiken, reproduceren, aanpassen, publiceren, vertalen en distribueren in alle media.</p>\r\n\r\n<p>Uw inhoud moet van u zijn en mag geen inbreuk maken op de rechten van derden. Mafiasource behoudt zich het recht voor om uw inhoud op elk moment zonder kennisgeving van deze website te verwijderen.</p>\r\n\r\n<h2>Uw privacy</h2>\r\n\r\n<p><a href=\"/privacy-policy\">Lees Privacybeleid</a>Â a.u.b.</p>\r\n\r\n<h2>Geen garanties</h2>\r\n\r\n<p>Deze website wordt geleverd \"zoals deze is\", met alle fouten, en Mafiasource geeft geen verklaringen of garanties van welke aard dan ook met betrekking tot deze website of de materialen op deze website. Niets op deze website mag worden geÃ¯nterpreteerd als een advies.</p>\r\n\r\n<h2>Beperking van aansprakelijkheid</h2>\r\n\r\n<p>In geen geval zal Mafiasource, noch een van haar functionarissen, directeuren en werknemers, aansprakelijk kunnen worden gesteld voor alles dat voortvloeit uit of op enige wijze verband houdt met uw gebruik van deze Website, ongeacht of deze aansprakelijkheid contractueel is. Mafiasource, inclusief zijn functionarissen, directeuren en werknemers, kan niet aansprakelijk worden gesteld voor enige indirecte, gevolg- of speciale aansprakelijkheid die voortvloeit uit of in enig opzicht verband houdt met uw gebruik van deze website.</p>\r\n\r\n<h2>Schadeloosstelling</h2>\r\n\r\n<p>Hierbij vrijwaart u Mafiasource volledig voor en tegen alle en / of alle aansprakelijkheden, kosten, eisen, oorzaken van actie, schade en kosten die op enigerlei wijze verband houden met uw schending van een van de bepalingen van deze Voorwaarden.</p>\r\n\r\n<h2>Scheidbaarheid</h2>\r\n\r\n<p>Als een bepaling van deze Voorwaarden ongeldig wordt bevonden onder de toepasselijke wetgeving, worden dergelijke bepalingen verwijderd zonder de overige bepalingen hierin te beÃ¯nvloeden.</p>\r\n\r\n<h2>Variatie van voorwaarden</h2>\r\n\r\n<p>Mafiasource is gerechtigd deze Voorwaarden te allen tijde naar eigen goeddunken te herzien, en door deze Website te gebruiken, wordt van u verwacht dat u deze Voorwaarden op gezette tijden doorneemt.</p>\r\n\r\n<h2>Toewijzing</h2>\r\n\r\n<p>Het is Mafiasource toegestaan zijn rechten en / of verplichtingen onder deze Voorwaarden zonder enige kennisgeving toe te wijzen, over te dragen en uit te besteden. Het is echter niet toegestaan om uw rechten en / of verplichtingen onder deze Voorwaarden toe te wijzen, over te dragen of uit te besteden.</p>\r\n\r\n<h2>Volledige overeenkomst</h2>\r\n\r\n<p>Deze Voorwaarden vormen de gehele overeenkomst tussen Mafiasource en u met betrekking tot uw gebruik van deze Website en vervangen alle eerdere overeenkomsten en afspraken.</p>\r\n\r\n<h2>Toepasselijk recht en jurisdictie</h2>\r\n\r\n<p>Deze Voorwaarden worden beheerst door en geÃ¯nterpreteerd in overeenstemming met de wetten van de Staat van BelgiÃ«, en u onderwerpt zich aan de niet-exclusieve jurisdictie van de staats- en federale rechtbanken die zijn gevestigd in BelgiÃ« voor de beslechting van eventuele geschillen.</p>\r\n', '<h1>Terms and Conditions for Mafiasource</h1>\r\n\r\n<h2>Introduction</h2>\r\n\r\n<p>These Website Standard Terms and Conditions written on this webpage shall manage your use of our website, Mafiasource accessible at https://mafiasource.nl.</p>\r\n\r\n<p>These Terms will be applied fully and affect to your use of this Website. By using this Website, you agreed to accept all terms and conditions written in here. You must not use this Website if you disagree with any of these Website Standard Terms and Conditions. These Terms and Conditions have been generated with the help of the <a href=\"https://www.termsandcondiitionssample.com\">Terms And Conditions Template</a>.</p>\r\n\r\n<p>Chilldren or people below 13 years old are not allowed to use this Website.</p>\r\n\r\n<h2>Intellectual Property Rights</h2>\r\n\r\n<p>Other than the content you own, under these Terms, Mafiasource and/or its licensors own all the intellectual property rights and materials contained in this Website.</p>\r\n\r\n<p>You are granted limited license only for purposes of viewing the material contained on this Website.</p>\r\n\r\n<h2>Restrictions</h2>\r\n\r\n<p>You are specifically restricted from all of the following:</p>\r\n\r\n<ul>\r\n	<li>publishing any Website material in any other media;</li>\r\n	<li>selling, sublicensing and/or otherwise commercializing any Website material;</li>\r\n	<li>publicly performing and/or showing any Website material;</li>\r\n	<li>using this Website in any way that is or may be damaging to this Website;</li>\r\n	<li>using this Website in any way that impacts user access to this Website;</li>\r\n	<li>using this Website contrary to applicable laws and regulations, or in any way may cause harm to the Website, or to any person or business entity;</li>\r\n	<li>engaging in any data mining, data harvesting, data extracting or any other similar activity in relation to this Website;</li>\r\n	<li>using this Website to engage in any advertising or marketing.</li>\r\n</ul>\r\n\r\n<p>Certain areas of this Website are restricted from being access by you and Mafiasource may further restrict access by you to any areas of this Website, at any time, in absolute discretion. Any user ID and password you may have for this Website are confidential and you must maintain confidentiality as well.</p>\r\n\r\n<h2>Your Content</h2>\r\n\r\n<p>In these Website Standard Terms and Conditions, \"Your Content\" shall mean any audio, video text, images or other material you choose to display on this Website. By displaying Your Content, you grant Mafiasource a non-exclusive, worldwide irrevocable, sub licensable license to use, reproduce, adapt, publish, translate and distribute it in any and all media.</p>\r\n\r\n<p>Your Content must be your own and must not be invading any third-party\'s rights. Mafiasource reserves the right to remove any of Your Content from this Website at any time without notice.</p>\r\n\r\n<h2>Your Privacy</h2>\r\n\r\n<p>Please <a href=\"/privacy-policy\">read Privacy Policy</a>.</p>\r\n\r\n<h2>No warranties</h2>\r\n\r\n<p>This Website is provided \"as is,\" with all faults, and Mafiasource express no representations or warranties, of any kind related to this Website or the materials contained on this Website. Also, nothing contained on this Website shall be interpreted as advising you.</p>\r\n\r\n<h2>Limitation of liability</h2>\r\n\r\n<p>In no event shall Mafiasource, nor any of its officers, directors and employees, shall be held liable for anything arising out of or in any way connected with your use of this Website whether such liability is under contract. Mafiasource, including its officers, directors and employees shall not be held liable for any indirect, consequential or special liability arising out of or in any way related to your use of this Website.</p>\r\n\r\n<h2>Indemnification</h2>\r\n\r\n<p>You hereby indemnify to the fullest extent Mafiasource from and against any and/or all liabilities, costs, demands, causes of action, damages and expenses arising in any way related to your breach of any of the provisions of these Terms.</p>\r\n\r\n<h2>Severability</h2>\r\n\r\n<p>If any provision of these Terms is found to be invalid under any applicable law, such provisions shall be deleted without affecting the remaining provisions herein.</p>\r\n\r\n<h2>Variation of Terms</h2>\r\n\r\n<p>Mafiasource is permitted to revise these Terms at any time as it sees fit, and by using this Website you are expected to review these Terms on a regular basis.</p>\r\n\r\n<h2>Assignment</h2>\r\n\r\n<p>The Mafiasource is allowed to assign, transfer, and subcontract its rights and/or obligations under these Terms without any notification. However, you are not allowed to assign, transfer, or subcontract any of your rights and/or obligations under these Terms.</p>\r\n\r\n<h2>Entire Agreement</h2>\r\n\r\n<p>These Terms constitute the entire agreement between Mafiasource and you in relation to your use of this Website, and supersede all prior agreements and understandings.</p>\r\n\r\n<h2>Governing Law & Jurisdiction</h2>\r\n\r\n<p>These Terms will be governed by and interpreted in accordance with the laws of the State of Belgium, and you submit to the non-exclusive jurisdiction of the state and federal courts located in Belgium for the resolution of any disputes.</p>', 5, 1, 0);
INSERT INTO `cms` VALUES (7, 'Privacy beleid', '<h1>Privacybeleid van Mafiasource</h1>\r\n\r\n<p>Mafiasource beheert de https://mafiasource.nlÂ website, die de SERVICE levert.</p>\r\n\r\n<p>Mafiasource biedt niet de mogelijkheid om via hun website cookies te verwijderen maar als eindgebruiker heeft u wel totale controle over onze en derde-partij cookies.</p>\r\n\r\n<p>Deze pagina wordt gebruikt om websitebezoekers te informeren over ons beleid met betrekking tot het verzamelen, gebruiken en vrijgeven van persoonlijke informatie als iemand besluit onze Service, de Mafiasource-website, te gebruiken.</p>\r\n\r\n<p>Als u ervoor kiest om onze Service te gebruiken, stemt u in met het verzamelen en gebruiken van informatie in verband met dit beleid. De persoonlijke informatie die we verzamelen, wordt gebruikt voor het leveren en verbeteren van de service. We zullen uw informatie met niemand gebruiken of delen, behalve zoals beschreven in dit privacybeleid.</p>\r\n\r\n<p>De termen die in dit privacybeleid worden gebruikt, hebben dezelfde betekenis als in onze <a href=\"/terms-and-conditions\">algemene voorwaarden</a>, die toegankelijk zijn op https://mafiasource.nl, tenzij anders bepaald in dit privacybeleid. Ons privacybeleid is gemaakt met behulp van hetÂ <a href=\"https://www.privacypolicytemplate.net\">privacybeleidssjabloon</a>.</p>\r\n\r\n<h2>Verzameling en gebruik van informatie</h2>\r\n\r\n<p>Voor een betere ervaring tijdens het gebruik van onze Service, kunnen we u vragen om ons bepaalde persoonlijk identificeerbare informatie te verstrekken, inclusief maar niet beperkt tot uw naam, telefoonnummer en postadres. De informatie die we verzamelen, wordt gebruikt om contact met u op te nemen of om u te identificeren.</p>\r\n\r\n<h2>Loggegevens</h2>\r\n\r\n<p>We willen u laten weten dat wanneer u onze Service bezoekt, we informatie verzamelen die uw browser naar ons verzendt en die Loggegevens wordt genoemd. Deze loggegevens kunnen informatie bevatten zoals het internetprotocoladres (\"IP\") van uw computer, browserversie, pagina\'s van onze service die u bezoekt, de tijd en datum van uw bezoek, de tijd doorgebracht op die pagina\'s en andere statistieken.</p>\r\n\r\n<h2>Cookies</h2>\r\n\r\n<p>Cookies zijn bestanden met een kleine hoeveelheid gegevens die gewoonlijk als een anonieme unieke identificatie wordt gebruikt. Deze worden naar uw browser verzonden vanaf de website die u bezoekt en worden opgeslagen op de harde schijf van uw computer.</p>\r\n\r\n<p>Onze website gebruikt deze \"cookies\" om informatie te verzamelen en onze service te verbeteren. U kunt deze cookies accepteren of weigeren en weten wanneer een cookie naar uw computer wordt verzonden. Als u ervoor kiest om onze cookies te weigeren, kunt u mogelijk sommige delen van onze Service niet gebruiken.<br />\r\n<br />\r\nDe essentiele cookies voor Mafiasource zijn de volgende cookies per naam:</p>\r\n\r\n<ul>\r\n	<li>mafiasource_Session: Deze cookie wordt als anonieme unieke identificatie gebruikt en word ook tijdelijk opgeslagen op onze servers om geldige beveiligingssleutels uit te lenen.</li>\r\n	<li>lang: Deze cookie bevat je laatste actieve taal instelling zijnde \'nl\' voor Nederlands of \'en\' voor Engels. Niet noodzakelijk maar het cachen van deze cookie kan de website snelheid verbeteren.</li>\r\n</ul>\r\n\r\n<h2>Dienstverleners</h2>\r\n\r\n<p>We kunnen externe bedrijven en personen in dienst nemen om de volgende redenen:</p>\r\n\r\n<ul>\r\n	<li>Om onze service te vergemakkelijken;</li>\r\n	<li>Om de Service namens ons te verlenen;</li>\r\n	<li>Servicegerelateerde services uitvoeren; of</li>\r\n	<li>Om ons te helpen analyseren hoe onze Service wordt gebruikt.</li>\r\n</ul>\r\n\r\n<p>We willen onze servicegebruikers informeren dat deze derden toegang hebben tot uw persoonlijke gegevens. De reden is om de taken die hen zijn toegewezen namens ons uit te voeren. Ze zijn echter verplicht om de informatie niet bekend te maken of voor andere doeleinden te gebruiken.</p>\r\n\r\n<h2>Veiligheid</h2>\r\n\r\n<p>Wij waarderen uw vertrouwen in het verstrekken van uw persoonlijke informatie aan ons, dus streven wij ernaar om commercieel aanvaardbare middelen te gebruiken om deze te beschermen. Maar vergeet niet dat geen enkele verzendmethode via internet of elektronische opslag 100% veilig en betrouwbaar is en we kunnen de absolute veiligheid ervan niet garanderen.</p>\r\n\r\n<h2>Links naar andere sites</h2>\r\n\r\n<p>Onze service kan links naar andere sites bevatten. Als u op een link van derden klikt, wordt u naar die site geleid. Merk op dat deze externe sites niet door ons worden beheerd. Daarom raden wij u ten zeerste aan om het privacybeleid van deze websites te bekijken. We hebben geen controle over en aanvaarden geen verantwoordelijkheid voor de inhoud, het privacybeleid of de praktijken van sites of services van derden.</p>\r\n\r\n<h2>Privacy van kinderen</h2>\r\n\r\n<p>Onze Services zijn niet bedoeld voor personen jonger dan 13 jaar. We verzamelen niet bewust persoonlijk identificeerbare informatie van kinderen jonger dan 13 jaar. In het geval dat we ontdekken dat een kind jonger dan 13 jaar ons persoonlijke informatie heeft verstrekt, verwijderen we dit onmiddellijk van onze servers. Als u een ouder of voogd bent en weet dat uw kind ons persoonlijke informatie heeft verstrekt, neem dan contact met ons op zodat we de nodige acties kunnen ondernemen.</p>\r\n\r\n<h2>Wijzigingen in dit privacybeleid</h2>\r\n\r\n<p>We kunnen ons privacybeleid van tijd tot tijd bijwerken. We raden u daarom aan deze pagina regelmatig te controleren op wijzigingen. We zullen u op de hoogte brengen van eventuele wijzigingen door het nieuwe privacybeleid op deze pagina te plaatsen. Deze wijzigingen worden onmiddellijk van kracht nadat ze op deze pagina zijn geplaatst.</p>\r\n\r\n<h2>Cookies verwijderen</h2>\r\n\r\n<p>Hieronder vind je een lijst met de nodige informatie per verschillende browser om cookies te verwijderen, houd er wel reking mee dat websites zonder cookies mogelijk niet meer optimaal functioneren.</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://support.google.com/chrome/answer/95647?hl=nl\" target=\"_blank\">Google Chrome</a></li>\r\n	<li><a href=\"https://support.mozilla.org/nl/kb/cookies-informatie-websites-computer-opgeslagen?redirectlocale=nl&redirectslug=cookies-informatie-websites-op-uw-computer-opslaan\" target=\"_blank\">Mozilla Firefox</a></li>\r\n	<li><a href=\"https://support.microsoft.com/nl-nl/microsoft-edge/cookies-verwijderen-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09\" target=\"_blank\">Microsoft Edge</a></li>\r\n	<li><a href=\"https://support.apple.com/nl-nl/HT201265\" target=\"_blank\">Safari</a></li>\r\n	<li><a href=\"http://help.opera.com/Linux/10.10/nl/cookies.html\" target=\"_blank\">Opera</a></li>\r\n</ul>\r\n\r\n<p>Om cookies uit onze app te verwijderen, kunt u de hele app verwijderen of u kunt de instellingen van de app beheren om alle opslag en cache te verwijderen. Open je de app daarna opnieuw dan zullen opnieuw in eerste instantie noodzakelijke cookies opgeslagen worden.</p>\r\n\r\n<h2>Neem contact op</h2>\r\n\r\n<p>Als u vragen of suggesties heeft over ons privacybeleid, aarzel dan niet om contact met ons op te nemen via email naar: <a href=\"\">info@mafiasource.nl</a></p>\r\n', '<h1>Privacy Policy of Mafiasource</h1>\r\n\r\n<p>Mafiasource operates the https://mafiasource.nl website, which provides the SERVICE.</p>\r\n\r\n<p>Mafiasource does not offer the option to delete cookies through their website, but as an end user you have total control over our and third-party cookies.</p>\r\n\r\n<p>This page is used to inform website visitors regarding our policies with the collection, use, and disclosure of Personal Information if anyone decided to use our Service, the Mafiasource website.</p>\r\n\r\n<p>If you choose to use our Service, then you agree to the collection and use of information in relation with this policy. The Personal Information that we collect are used for providing and improving the Service. We will not use or share your information with anyone except as described in this Privacy Policy.</p>\r\n\r\n<p>The terms used in this Privacy Policy have the same meanings as in our <a href=\"/terms-and-conditions\">Terms and Conditions</a>, which is accessible at https://mafiasource.nl, unless otherwise defined in this Privacy Policy. Our Privacy Policy was created with the help of the <a href=\"https://www.privacypolicytemplate.net\">Privacy Policy Template</a>.</p>\r\n\r\n<h2>Information Collection and Use</h2>\r\n\r\n<p>For a better experience while using our Service, we may require you to provide us with certain personally identifiable information, including but not limited to your name, phone number, and postal address. The information that we collect will be used to contact or identify you.</p>\r\n\r\n<h2>Log Data</h2>\r\n\r\n<p>We want to inform you that whenever you visit our Service, we collect information that your browser sends to us that is called Log Data. This Log Data may include information such as your computerâ€™s Internet Protocol (\"IP\") address, browser version, pages of our Service that you visit, the time and date of your visit, the time spent on those pages, and other statistics.</p>\r\n\r\n<h2>Cookies</h2>\r\n\r\n<p>Cookies are files with small amount of data that is commonly used as an anonymous unique identifier. These are sent to your browser from the website that you visit and are stored on your computerâ€™s hard drive.</p>\r\n\r\n<p>Our website uses these \"cookies\" to collectÂ information and to improve our Service. You have the option to either accept or refuse these cookies, and know when a cookie is being sent to your computer. If you choose to refuse our cookies, you may not be able to use some portions of our Service.<br />\r\n<br />\r\nThe essential cookies for Mafiasource are the following cookies by name:</p>\r\n\r\n<ul>\r\n	<li>mafiasource_Session: This cookie is used as an anonymous unique identifier and is also temporarily stored on our servers to lend valid security tokens.</li>\r\n	<li>lang: This cookie contains your last active language setting, which is \'nl\' for Dutch or \'en\'Â for English. Not necessary, but caching this cookie can improve website speed.</li>\r\n</ul>\r\n\r\n<h2>Service Providers</h2>\r\n\r\n<p>We may employ third-party companies and individuals due to the following reasons:</p>\r\n\r\n<ul>\r\n	<li>To facilitate our Service;</li>\r\n	<li>To provide the Service on our behalf;</li>\r\n	<li>To perform Service-related services; or</li>\r\n	<li>To assist us in analyzing how our Service is used.</li>\r\n</ul>\r\n\r\n<p>We want to inform our Service users that these third parties have access to your Personal Information. The reason is to perform the tasks assigned to them on our behalf. However, they are obligated not to disclose or use the information for any other purpose.</p>\r\n\r\n<h2>Security</h2>\r\n\r\n<p>We value your trust in providing us your Personal Information, thus we are striving to use commercially acceptable means of protecting it. But remember that no method of transmission over the internet, or method of electronic storage is 100% secure and reliable, and we cannot guarantee its absolute security.</p>\r\n\r\n<h2>Links to Other Sites</h2>\r\n\r\n<p>Our Service may contain links to other sites. If you click on a third-party link, you will be directed to that site. Note that these external sites are not operated by us. Therefore, we strongly advise you to review the Privacy Policy of these websites. We have no control over, and assume no responsibility for the content, privacy policies, or practices of any third-party sites or services.</p>\r\n\r\n<h2>Childrenâ€™s Privacy</h2>\r\n\r\n<p>Our Services do not address anyone under the age of 13. We do not knowingly collect personal identifiable information from children under 13. In the case we discover that a child under 13 has provided us with personal information, we immediately delete this from our servers. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us so that we will be able to do necessary actions.</p>\r\n\r\n<h2>Changes to This Privacy Policy</h2>\r\n\r\n<p>We may update our Privacy Policy from time to time. Thus, we advise you to review this page periodically for any changes. We will notify you of any changes by posting the new Privacy Policy on this page. These changes are effective immediately, after they are posted on this page.</p>\r\n\r\n<h2>Remove Cookies</h2>\r\n\r\n<p>Below you will find a list with the necessary information for each browser to delete cookies, but keep in mind that websites without cookies may no longer function optimally.</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://support.google.com/chrome/answer/95647?hl=en\" target=\"_blank\">Google Chrome</a></li>\r\n	<li><a href=\"https://support.mozilla.org/en-US/kb/cookies-information-websites-store-on-your-computer?redirectlocale=en-US&redirectslug=Cookies\" target=\"_blank\">Mozilla Firefox</a></li>\r\n	<li><a href=\"https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09\" target=\"_blank\">Microsoft Edge</a></li>\r\n	<li><a href=\"https://support.apple.com/en-us/HT201265\" target=\"_blank\">Safari</a></li>\r\n	<li><a href=\"http://help.opera.com/Linux/10.10/en/cookies.html\" target=\"_blank\">Opera</a></li>\r\n</ul>\r\n\r\n<p>To remove cookies from our App you can either delete the whole app or you can manage the app its settings to remove all storage and cache.Â If you open the app again afterwards, necessary cookies will initially be stored again.</p>\r\n\r\n<h2>Contact Us</h2>\r\n\r\n<p>If you have any questions or suggestions about our Privacy Policy, do not hesitate to contact us by email at: <a href=\"mailto:info@mafiasource.nl\">info@mafiasource.nl</a></p>\r\n', 6, 1, 0);
INSERT INTO `cms` VALUES (8, 'Spelregels', '<p><strong>Site regels</strong><br />\r\n- De makers van het spel garanderen niet dat er geen fouten in het spel zitten.<br />\r\n- Het is verplicht een gevonden bug zo snel mogelijk te melden.<br />\r\n- Het is verplicht in het Nederlands te communiceren op de Nederlandstalige versie van het spel.<br />\r\n- Het is verboden bugs te misbruiken.<br />\r\n- Het is verboden om met meer dan 1 account (dubbelaccounts) te spelen met als enige uitzondering van het publieke ms-demo account.<br />\r\n- Het is verboden een grove, beledigende, pornografische of discriminerende nickname/username te kiezen.<br />\r\n- Het is verboden reclame te maken voor zaken buiten de game.<br />\r\n- Het is verboden real-life zaken te verkopen op/via deze site.<br />\r\n- Het is verboden real-life zaken te ruilen voor in-game zaken en omgekeerd.<br />\r\n- Het is verboden accounts te verkopen/weggeven.<br />\r\n- Het is verboden te spammen.<br />\r\n- Het is verboden grove, beledigende, pornografische of discriminerende zaken te posten.<br />\r\n- Het is verboden te schelden.<br />\r\n- Het is verboden te flamen (ruzie uitlokken).<br />\r\n- Het is verboden mensen real-life te bedreigen.<br />\r\n- Het is verboden om privÃ©Â informatie van anderen te verspreiden.<br />\r\n- Het is verboden enquetes te voeren op deze site. Tenzij je toestemming hebt van een teamlid.<br />\r\n<br />\r\n<strong>Algemene regels</strong><br />\r\n- Toon respect voor de teamleden, respecteer dus hun beslissingen.<br />\r\n- Toon ook respect voor andere leden.<br />\r\n- Geen gezeur om teamlid te worden.<br />\r\n- Er wordt geen uitleg gegeven over waarschuwingen en bans. Ze worden ook niet teruggedraaid.<br />\r\n- Donaties / Credits blijven Ã©Ã©n ronde geldig. De duur van 1Â ronde staat niet vast.<br />\r\n- Je hebt geen enkele rechten op je account.<br />\r\n- Admins mogen je account aanpassen of verwijderen zonder reden.<br />\r\n<br />\r\n<strong>Forum regels</strong><br />\r\n- Blijf bij het onderwerp van het topic, ga dus niet off-topic.<br />\r\n- Geef je topic een duidelijke titel.<br />\r\n- Zet je topic in het juiste subforum.<br />\r\n- Vooraleer je een vraag stelt, kijk dan eerst of die vraag niet reeds behandelt is in een andere topic. Hier kun je de zoekfunctie voor gebruiken.<br />\r\n- Het is verplicht Nederlands te praten op de Nederlandstalige versie van het forum.<br />\r\n- Het is verboden om nutteloze reacties te posten.<br />\r\n- Het is verboden nutteloze topics aan te maken.<br />\r\n- Het is verboden meerdere keren hetzelfde topic aan te maken.<br />\r\n- Het is verboden meerdere keren dezelfde reactie te posten.<br />\r\n- Het is verboden een gesloten topic opnieuw aan te maken.<br />\r\n- Het is verboden te schelden.<br />\r\n- Het is verboden te flamen (ruzie uitlokken).<br />\r\n- Het is verboden moedwillig je aantal posts te verhogen.<br />\r\n- Het is verboden andere spelers te beledigen.<br />\r\n- Het is verboden andere spelers te bedreigen. Dit geld voor zowel in-game als real-life bedreigingen.<br />\r\n- Het is verboden andere spelers te beschuldigen.<br />\r\n- Het is verboden reclame te maken.<br />\r\n- Het is verboden requests (bijvoorbeeld een familierequest) te posten.<br />\r\n- Het is verboden beledigend/pornografisch materiaal te posten.<br />\r\n- Het is verboden te spammen.<br />\r\n- Het is verboden privÃ© informatie (telefoonnummers, e-mailadressen, ...) van anderen te posten.<br />\r\n- Het is verboden enquetes te plaatsen, tenzij je toestemming hebt van een mod of een admin.<br />\r\n- Het is verboden reacties te posten in BrEeZaH of uitsluitend in HOOFDLETTERS.<br />\r\n- Het is verboden het OT topic aan te maken. Deze mag alleen door een mod of een admin aangemaakt worden.<br />\r\n- Het is verboden om zaken te verkopen op het forum. Dit geldt voor zowel real-life als in-game zaken.<br />\r\n<br />\r\n<br />\r\n<strong>Website voorwaarden</strong><br />\r\n<br />\r\nDe website eigenaren zijn niet aansprakelijk voor eventuele schade of verlies opgelopen tijdens het spelen van dit spel. Ook zijn de eigenaren niet aansprakelijk voor de content (plaatjes, teksten, fimpjes etc.) die de gebruikers op de site plaatsen. De voorwaarden kunnen worden aangepast zonder de leden hiervan op de hoogte te stellen. Personen jonger dan 16 jaar dienen toestemming te vragen aan hun ouders om de donatieshop te gebruiken. Eventuele kosten die zijn opgelopen door het doneren zijn niet terug te verhalen op de eigenaren. Het donateurschap is alleen geldig op het account waarop het besteld is en het is niet mogelijk om dit over te plaatsen naar een ander account. Mocht de website offline gaan of mocht er verlies van gegevens plaatsvinden dan is het geld voor het donateurschap niet terug te halen. Het donateurschap kan op elk moment worden ingetrokken om wat voor reden dan ook. Het verzamelen van illegale credits is het proces van het aanmaken van (nep)accounts en het gebruiken van de donatiebeloningen om (max.) credits te ontvangen terwijl alle donatiewaarde wordt gecorrigeerd. In het geval dat we een dergelijk misdrijf ontdekken, word(t/en) de overtreder(s) verbannen. Om een betaal-om-te-winnen gameplay te beperken zijn credits bij donatie beloningen gelimiteerd tot 5,000 elke 31 dagen. Bij overtreding van een van deze punten zal een moderator of een admin bepalen of je gewaarschuwd of meteen verbannen wordt. Inactieve accounts die langer dan 2 jaar niet online zijn gekomen zullen permanent verwijderd worden zonder enige mogelijkheid tot terugdraaien.</p>\r\n\r\n<p>Zie ook: <a href=\"/terms-and-conditions\"><strong>Algemene voorwaarden</strong></a> & <a href=\"/privacy-policy\"><strong>Privacybeleid</strong></a>.</p>\r\n', '<p><strong>Site rules</strong><br />\r\n- The creators of the game cannot guarantee the absense of bugs.<br />\r\n- It is mandatory to report a found bug as soon as possible.<br />\r\n- It is mandatory to communicate in the English language on the English version of the game.<br />\r\n- It is forbidden to abuse bugs.<br />\r\n- It is forbidden to play with more than 1 account (double accounts) with the only exception of the public ms-demo account.<br />\r\n- It is forbidden to set a gross, offensive, pornographic or discriminating nickname/username.<br />\r\n- It is forbidden to advertise for non-game related affairs.<br />\r\n- It is forbidden to sell real-life items on/through this site.<br />\r\n- It is forbidden to exchange real-life goods for in-game goods and vice-versa.<br />\r\n- It is forbidden to sell/givaway accounts.<br />\r\n- It is forbidden to spam.<br />\r\n- It is forbidden to post gross, offensive, pornographic or discriminating content.<br />\r\n- It is forbidden to swear.<br />\r\n- It is forbidden to flame (provoke a quarrel).<br />\r\n- It is forbidden to threaten people in real-life.<br />\r\n- It is forbidden to spead private information regarding others.<br />\r\n- It is forbidden to start surveys on this site. Unless you have a team member\'s approval.<br />\r\n<br />\r\n<strong>General rules</strong><br />\r\n- Show respect for team members, so respect their decisions.<br />\r\n- Also show respect for other members.<br />\r\n- No begging to become a team member.<br />\r\n- No explenations wil be given regarding bans and warns. They wil not be reversable.<br />\r\n- Donations / Credits stay valid for an entire round. There\'s no fixed time as to how long a round lasts.<br />\r\n- You don\'t own any rights for your account.<br />\r\n- Admins can edit or delete your account without a reason.<br />\r\n<br />\r\n<strong>Forum rules</strong><br />\r\n- Stay within the topic\'s subject, don\'t go off-topic.<br />\r\n- Give your topic a clear title.<br />\r\n- Place your topic in the correct subforum.<br />\r\n- Before you ask a question, look if your question hasn\'t been answered yet in another topic. You can use the search function for this.<br />\r\n- It is mandatory to speak English on the English version of the forum.<br />\r\n- It is forbidden to post useless reactions.<br />\r\n- It is forbidden to create useless topics.<br />\r\n- It is forbidden to create a single topic more than once.<br />\r\n- It is forbidden to post the same reaction more than once.<br />\r\n- It is forbidden to recreate a closed topic.<br />\r\n- It is forbidden to scold.<br />\r\n- It is forbidden to flame (provoke a quarrel).<br />\r\n- It is forbidden to raise your posts count willfully.<br />\r\n- It is forbidden to offend other players.<br />\r\n- It is forbidden to threaten other players. This applies for both in-game and real-life threats.<br />\r\n- It is forbidden to accuse other players.<br />\r\n- It is forbidden to advertise.<br />\r\n- It is forbidden to post requests (for example a family request).<br />\r\n- It is forbidden to post offensive/pornographic material.<br />\r\n- It is forbidden to spam.<br />\r\n- It is forbidden to post private information (phone numbers, email addresses, ...) from others.<br />\r\n- It is forbidden to place surveys, unless you have a team member\'s approval.<br />\r\n- It is forbidden to post reactions in BrEeZaH or exclusively CAPITAL LETTERS.<br />\r\n- It is forbidden to create the OT. This topic may only be created by a moderator.<br />\r\n- It is forbidden to sell goods on the forum. This applies for both in-game and real-life goods.<br />\r\n<br />\r\n<br />\r\n<strong>Website conditions</strong><br />\r\n<br />\r\nThe website owners are not liable for any damage or loss incurred while playing this game. The owners are also not responsible for the content (pictures, texts, films, etc.) that the users place on the site. The conditions can be adjusted without informing the members. Persons under the age of 16 must request permission from their parents to use the donation shop. Any costs incurred by donating can not be recovered from the owners. The donorship is only valid on the account on which it was ordered and it is not possible to transfer this to another account. Should the website go offline or if there is loss of data than the money for the donorship can not be refunded. The donorship can be withdrawn at any time for whatever reason. Collecting illicit credits is the process of creating (fake) accounts and using the donation rewards to receive (max.) credits while correcting all donation value. In the case we discover such offence the offender(s) will be banned. To limit a pay-to-win gameplay, credits on donation rewards are limited to 5,000 each 31 days. In case of violation of one of these points, a moderator or an admin will determine whether you are warned or immediately banned. Inactive accounts that have not been online for more than 2 years will be removed permanently without any rollback possibilities.</p>\r\n\r\n<p>See also: <a href=\"/terms-and-conditions\"><strong>Terms and conditions</strong></a> & <a href=\"/privacy-policy\"><strong>Privacy policy</strong></a>.</p>\r\n', 7, 1, 0);
INSERT INTO `cms` VALUES (9, 'Link Partners', '<h1>Onze externe linkpartners</h1>\r\n\r\n<p>Hieronder vind je een lijst met websites die Mafiasource steunen en andersom. Dankzij deze websites kan Mafiasource groeien! Webmaster en interesse in een link-back samenwerking? Neem contact op met <a href=\"mailto:info@mafiasource.nl\">info@mafiasource.nl</a>.</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://www.spelensite.be/\" rel=\"noreferrer\" target=\"_blanc\">Spelensite</a></li>\r\n	<li><a href=\"https://www.maffia-toplijst.com/\" rel=\"noreferrer\" target=\"_blanc\">Mafia Toplijst</a></li>\r\n	<li><a href=\"https://newrpg.com/\" rel=\"noreferrer\" target=\"_blanc\">NewRPG</a></li>\r\n</ul>\r\n', '<h1>Our external link partners</h1>\r\n\r\n<p>Below is a list of websites that support Mafiasource and vice versa. Thanks to these websites, Mafiasource can grow! Webmaster and interested in a link-back collaboration? Get in touch with <a href=\"mailto:info@mafiasource.nl\">info@mafiasource.nl</a>.</p>\r\n\r\n<ul>\r\n	<li><a href=\"https://www.spelensite.be/\" rel=\"noreferrer\" target=\"_blanc\">Spelensite</a></li>\r\n	<li><a href=\"https://www.maffia-toplijst.com/\" rel=\"noreferrer\" target=\"_blanc\">Mafia Toplijst</a></li>\r\n	<li><a href=\"https://newrpg.com/\" rel=\"noreferrer\" target=\"_blanc\">NewRPG</a></li>\r\n</ul>\r\n', 8, 1, 0);
INSERT INTO `cms` VALUES (10, 'Download de app', '<h1>Download de app</h1>\r\n\r\n<p>Zet je avontuur nog sneller voort in de toekomst! We bieden momenteel enkel ondersteuning voor onze Google Play Store app gebruikers.</p>\r\n\r\n<h3>Android</h3>\r\n\r\n<p><a href=\"https://play.google.com/store/apps/details?id=com.mafiasource.webviewapp&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1\" rel=\"noreferrer\" target=\"_blanc\">https://play.google.com/store/apps/details?id=com.mafiasource.webviewapp</a> (Google Play Store)<br />\r\n<br />\r\nAndere platformen dan Android kunnen onze web app installeren via een browser naar keuze. Zorg er voor dat je over de meest recente browser updates beschikt. Multiplatform en apple toestellen leggen uit hoe je een website als app kan installeren.</p>\r\n\r\n<h3>Multiplatform</h3>\r\n\r\n<p><a href=\"https://allthings.how/how-to-install-any-website-as-app-using-edge-chrome-browser/\" rel=\"noreferrer\" target=\"_blanc\">https://allthings.how/how-to-install-any-website-as-app-using-edge-chrome-browser/</a> (Chrome of Edge)</p>\r\n\r\n<h3>Apple toestellen</h3>\r\n\r\n<p><a href=\"https://www.maketecheasier.com/save-web-page-as-home-screen-app-ios/\" rel=\"noreferrer\" target=\"_blanc\">https://www.maketecheasier.com/save-web-page-as-home-screen-app-ios/</a> (Safari)</p>\r\n\r\n<h2>Android debug-app</h2>\r\n\r\n<p>Als je onze debug-app wilt uitproberen, zorg er dan voor dat je de Android-instelling \'Apps van derden toestaan\' inschakelt en je zou in staat moeten zijn om te downloaden, installeren en te spelen!</p>\r\n\r\n<p>Onze meest recente debug-app als (niet-vertrouwde) .apk kan je <a href=\"https://download.mafiasource.nl/web/downloads/msapp.apk\" rel=\"noreferrer\">hier downloaden</a> (enkel Android) <small>v1.0.6</small></p>\r\n\r\n<p>Scan: <a href=\"https://www.virustotal.com/gui/file/610fe175322ebe0f939bd833432f4689b4a76b2bcf47e684f2c2ceb18a2d279a/detection\" rel=\"noreferrer\" target=\"_blanc\">VirusTotal.com</a></p>\r\n\r\n<h2>Mafiasource op GitHub</h2>\r\n\r\n<p>Je eigen Crimeclub starten, knutselen in onze source code of uitpluizen hoe Mafiasource werkt? De volgende GitHub repo bevat alles wat je nodig hebt om van start te kunnen gaan. Volg aandachtig de meegeleverde installatie instructies om je eigen Mafiasource copy on- of offline werkende te krijgen.</p>\r\n\r\n<p><a href=\"https://www.github.com/Mafiasource/Mafiasource\" rel=\"noreferrer\" target=\"_blanc\">https://www.github.com/Mafiasource/Mafiasource</a></p>\r\n', '<h1>Download the app</h1>\r\n\r\n<p>Continue your adventure even faster into the future! We currently only offer support for our Google Play Store app users.</p>\r\n\r\n<h3>Android</h3>\r\n\r\n<p><a href=\"https://play.google.com/store/apps/details?id=com.mafiasource.webviewapp&pcampaignid=pcampaignidMKT-Other-global-all-co-prtnr-py-PartBadge-Mar2515-1\" rel=\"noreferrer\" target=\"_blanc\">https://play.google.com/store/apps/details?id=com.mafiasource.webviewapp</a> (Google Play Store)<br />\r\n<br />\r\nPlatforms other than Android can install our web app through a browser of choice. Make sure you have the most recent browser updates. Multiplatform and apple devices explain how you can install a website as an app.</p>\r\n\r\n<h3>Multiplatform</h3>\r\n\r\n<p><a href=\"https://allthings.how/how-to-install-any-website-as-app-using-edge-chrome-browser/\" rel=\"noreferrer\" target=\"_blanc\">https://allthings.how/how-to-install-any-website-as-app-using-edge-chrome-browser/</a> (Chrome or Edge)</p>\r\n\r\n<h3>Apple devices</h3>\r\n\r\n<p><a href=\"https://www.maketecheasier.com/save-web-page-as-home-screen-app-ios/\" rel=\"noreferrer\" target=\"_blanc\">https://www.maketecheasier.com/save-web-page-as-home-screen-app-ios/</a> (Safari)</p>\r\n\r\n<h2>Android debug app</h2>\r\n\r\n<p>If you want to try out our debug app, make sure to enable the Android setting \'Allow third-party apps\' and you should be able to download, install and play!</p>\r\n\r\n<p>Our most recent debug app as (untrusted) .apk can be <a href=\"https://download.mafiasource.nl/web/downloads/msapp.apk\" rel=\"noreferrer\">downloaded here</a> (Android only) <small>v1.0.6</small></p>\r\n\r\n<p>Scan: <a href=\"https://www.virustotal.com/gui/file/610fe175322ebe0f939bd833432f4689b4a76b2bcf47e684f2c2ceb18a2d279a/detection\" rel=\"noreferrer\" target=\"_blanc\">VirusTotal.com</a></p>\r\n\r\n<h2>Mafiasource on GitHub</h2>\r\n\r\n<p>Starting your own Crimeclub, tinkering in our source code or figuring out how Mafiasource works? The following GitHub repo contains everything you need to get started. Carefully follow the included installation instructions to get your own Mafiasource copy working online or offline.</p>\r\n\r\n<p><a href=\"https://www.github.com/Mafiasource/Mafiasource\" rel=\"noreferrer\" target=\"_blanc\">https://www.github.com/Mafiasource/Mafiasource</a></p>\r\n', 9, 1, 0);
INSERT INTO `cms` VALUES (11, 'Doneren', '<p>Door op onderstaande knop op \"doorgaan\" te drukken ga je akkoord met onze <a href=\"/terms-and-conditions\"><span style=\"color:#3498db\"><strong>algemene voorwaarden</strong></span></a>, onze <a href=\"/game/information/rules\"><span style=\"color:#3498db\"><strong>regels</strong></span></a> en onderstaande voorwaarden:</p>\r\n\r\n<ul>\r\n	<li>De eigenaar van het betalingsmiddel moet toestemming hebben gegeven voor het doneren.</li>\r\n	<li>Personen onder de 18 jaar moeten toestemming hebben van hun ouders of voogd om te doneren.</li>\r\n	<li>Mafiasource.nl staat niet garant voor het mislukken van donaties.</li>\r\n	<li>Mafiasource.nl geeft geen geld maar enkel credits terug bij een mislukte bewezen donatie.</li>\r\n	<li>Mafiasource.nl bewaard enkel: transactiecode, bedrag, gebruikersnaam en transactiedatum wat anonimiteit op onze server kan bieden.</li>\r\n	<li>Exact 31 dagen na je laatste donatie reset je credits beloning limiet.</li>\r\n	<li>Meerdere opeenvolgende donaties reset ook je laatste donatie datum.</li>\r\n	<li>Credits worden beloond vanaf â‚¬1,00 tot â‚¬50,00 in donatiewaarde. (100 - 5,000 credits)</li>\r\n	<li>De donatie credits bonus zijn 1 ronde geldig.</li>\r\n</ul>\r\n\r\n<h4>Verdien credits</h4>\r\n\r\n<p>Zonder doneren kan je op volgende manieren aan credits komen: spontane misdaden plegen, voertuigen stelen, hoeren pimpen en eenheden smokkelen. Ook kan je mits wat geluk credits verdienen met luckyboxen die voornamelijk gratis verkrijgbaar zijn elke dag bij daily challenges. Ook met Missies > Publieke missie kan af en toe luckyboxen of credits gewonnen worden.</p>\r\n', '<p>By clicking the \"continue\" button below you agree to our <a href=\"/terms-and-conditions\"><span style=\"color:#3498db\"><strong>terms and conditions</strong></span></a>, our <a href=\"/game/information/rules\"><span style=\"color:#3498db\"><strong>rules</strong></span></a> and the conditions below:</p>\r\n\r\n<ul>\r\n	<li>The owner of the payment method must have given permission for the donation.</li>\r\n	<li>Persons under the age of 18 must have parental or guardian consent to donate.</li>\r\n	<li>Mafiasource.nl is not liable for the failure of donations.</li>\r\n	<li>Mafiasource.nl does not return money but only credits in case of a failed proven donation.</li>\r\n	<li>Mafiasource.nl only stores: transaction code, amount, username and transaction date, which can provide anonymity on our server.</li>\r\n	<li>Exactly 31 days after your last donation your credits reward limit will reset.</li>\r\n	<li>Multiple consecutive donations will also reset your last donation date.</li>\r\n	<li>Credits are rewarded from â‚¬1.00 to â‚¬50.00 in donation value. (100 - 5,000 credits)</li>\r\n	<li>The donation credits bonus are valid for 1 round.</li>\r\n</ul>\r\n\r\n<h4>Don\'t own Euro?</h4>\r\n\r\n<p>The amount of credits you will receive depends on your currency its value converted to euro (â‚¬), minus any PayPal conversion fees. Make sure at least 1 euro excluding a conversion fee is donated to receive your credits immediately. Conversion fee <em>is not</em> the transaction / processing fee and usually a small pennies amount.</p>\r\n\r\n<h4>Earn credits</h4>\r\n\r\n<p>Without donating you can get credits doing the following: commit spontaneous crimes, stealing vehicles, pimping hoes and smuggling units. With a bit of luck you can also earn credits with lucky boxes that are mainly available for free every day at daily challenges. Also with Missions > Public mission you can occasionally win lucky boxes or credits.</p>\r\n', 10, 1, 0);

-- ----------------------------
-- Table structure for crime
-- ----------------------------
DROP TABLE IF EXISTS `crime`;
CREATE TABLE `crime`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type=upload',
  `level` smallint NOT NULL DEFAULT 1,
  `minProfit` int NOT NULL,
  `maxProfit` int NOT NULL,
  `difficulty` smallint NOT NULL DEFAULT 5,
  `maxRankPoints` tinyint(1) NOT NULL DEFAULT 1,
  `donatorID` tinyint(1) NULL DEFAULT 0 COMMENT 'couple=donator&factor=id&show=donator_nl',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of crime
-- ----------------------------
INSERT INTO `crime` VALUES (1, 'Besteel een kind', 'Pak het vers zakgeld van een kleine jongen.', 'Rob a child', 'Take fresh pocket money from a little kid.', '798191fd.jpg', 1, 1, 3, 1, 1, 0, 0, 1, 0);
INSERT INTO `crime` VALUES (2, 'Overval een nachtwinkel', 'Overval de nachtwinkel bediende juist voor sluiting tijd.  ', 'Rob a nightshop', 'Rob a nighshop owner just before he closes his shop.', '171ecb55.jpg', 3, 2, 7, 2, 1, 0, 1, 1, 0);
INSERT INTO `crime` VALUES (3, 'Train snelle handen', 'Train je snelheid om geld uit de kassa te stelen tijdens het shoppen.', 'Train quick hands', 'Practice quick hands to take some quick cash while shopping.', '718eed59.png', 4, 5, 20, 4, 1, 0, 2, 1, 0);
INSERT INTO `crime` VALUES (4, 'Breek in een huisje ', 'Breek in in een huisje in het bos.', 'Break into a tiny house', 'Break into a tiny house in the woods.', 'fc705fdf.jpg', 7, 7, 30, 6, 1, 0, 3, 1, 0);
INSERT INTO `crime` VALUES (5, 'Besteel een verzekeraar', 'Steel geld uit de kluis van het kantoor.', 'Rob a insurance broker', 'Steal money from the vault inside the office.', 'e7a487f8.jpg', 9, 10, 50, 7, 1, 0, 4, 1, 0);
INSERT INTO `crime` VALUES (6, 'Hack een bank automaat', 'Kraak de encrypty van een geld automaat.', 'Hack an ATM', 'Crack the encryption of an ATM.', '7cdf4f70.jpg', 12, 15, 75, 7, 1, 0, 5, 1, 0);
INSERT INTO `crime` VALUES (7, 'Beroof een magazijn', 'Breek in bij het magazijn van een internetbedrijf.', 'Rob a storage facility', 'Break into the storage facility of an internet company.', '3b01f9e2.png', 15, 20, 125, 8, 1, 0, 6, 1, 0);
INSERT INTO `crime` VALUES (8, 'Beroof een juwelier ', 'Koop een horloge bij de plaatselijke juwelier en beroof dan de kassa.', 'Rob a jewelry store', 'Buy a watch at the local jewelry store and rob the cash desk afterwards.', 'bb2177a4.jpg', 18, 25, 130, 13, 2, 0, 7, 1, 0);
INSERT INTO `crime` VALUES (9, 'breek in bij een villa ', 'Sluip in villa een terwijl niemand thuis is.', 'Break into a villa', 'Sneak inside a villa while nobody is home.', '832a9967.jpg', 21, 30, 150, 8, 1, 0, 8, 1, 0);
INSERT INTO `crime` VALUES (10, 'Ontvoer een zakenman', 'Vraag los geld voor de zakenman die je hebt ontvoerd.', 'Kidnap a business man.', 'Demand a ransom for the business man you\'ve kidnapped.', '68d56042.jpg', 23, 50, 175, 9, 2, 0, 9, 1, 0);
INSERT INTO `crime` VALUES (11, 'Breek in bij een bankier ', 'Besteel de vakantie woning van een bankier.', 'Rob a banker', 'Break into the holiday house from a banker.', '404a15ef.jpg', 25, 70, 190, 10, 1, 0, 10, 1, 0);
INSERT INTO `crime` VALUES (12, 'Saboteer een geldtransport ', 'Besteel het geld uit de vrachtwagen onderweg naar de bank.', 'Sabotage a money transfer', 'Steal the money from a truck transferring money to the bank.', 'f6ffb09b.jpg', 29, 85, 230, 11, 2, 0, 11, 1, 0);
INSERT INTO `crime` VALUES (13, 'Beroof een bank ', 'Beroof de plaatselijke bank met een wapen ', 'Rob a local bank', 'Rob a local bank with a gun.', '0dbfc357.png', 32, 150, 400, 12, 3, 5, 12, 1, 0);
INSERT INTO `crime` VALUES (14, 'Steel chinees porselein ', 'Breek in bij een magazijn en neem alle chineese draken beeldjes mee.', 'Steal  chinese porcelain', 'Break into a warehouse and take all chinese dragon statues.', '5320691c.jpg', 33, 380, 610, 14, 2, 0, 13, 1, 0);
INSERT INTO `crime` VALUES (15, 'Beroof een garage', 'Steel een voertuig in een goed draaiende garage.', 'Rob a garage', 'Steal a vehicle in a well running garage.', '3ec63ed3.jpg', 36, 700, 900, 14, 3, 0, 14, 1, 0);
INSERT INTO `crime` VALUES (16, 'Ontvoer een politicus ', 'Ontvoer een regionale politicus en eis losgeld.', 'Kidnap a politician', ' Kidnap a regional politician and demand a ransom.', '4c8426eb.jpg', 39, 1100, 1300, 15, 3, 10, 16, 1, 0);
INSERT INTO `crime` VALUES (17, 'Beroof een bank', 'Beroof een kluis in de bank.', 'Rob a bank', 'Rob a safe in the bank.', 'b7314bcb.jpg', 40, 1250, 1450, 16, 4, 0, 17, 1, 0);
INSERT INTO `crime` VALUES (18, 'Pleeg fraude ', 'Speel met de cijfertjes op je belastingsbrief.', 'Commit fraude', 'Play with the numbers on your tax bill.', 'bb834023.jpg', 43, 1350, 1950, 17, 3, 0, 18, 1, 0);
INSERT INTO `crime` VALUES (19, 'Steel een luxe boot ', 'Ga naar de haven en vaar weg met een luxe boot.', ' Steal a luxury boat', 'Make your way to the harbor and sail away by luxury boat.', '4311c386.jpg', 44, 1500, 2200, 19, 4, 0, 19, 1, 0);
INSERT INTO `crime` VALUES (20, 'Steel wapens ', 'Steel de aller nieuwste wapen van het leger. ', 'Steal weapons', 'Steal the Army\'s newest weapon.', '2c547de9.jpg', 47, 1600, 4000, 20, 5, 0, 20, 1, 0);
INSERT INTO `crime` VALUES (21, 'Beroof een bank', 'Pleeg een overval in de nationale bank. ', 'Rob a bank', 'Robbery in the national bank.', 'a90e718f.jpg', 50, 1750, 5000, 21, 5, 0, 21, 1, 0);
INSERT INTO `crime` VALUES (22, 'Beroof een garage', 'Steel de auto\'s uit een chic garage.', 'Rob a garage', 'Steal the cars from a chic garage.', '1990116c.jpg', 53, 1900, 7000, 23, 4, 5, 22, 1, 0);
INSERT INTO `crime` VALUES (23, 'Ontvoer een bankier ', 'Ontvoer een goed verdienende bankier en eis losgeld aan de familie.', 'Kidnap a banker', 'Kidnap a high-income banker and demand a ransom from the family.', '81d1c60a.jpg', 55, 2000, 8000, 24, 5, 0, 23, 1, 0);
INSERT INTO `crime` VALUES (24, 'Beroof een museum ', 'Steel de schilderijen uit de nationale museum.', 'Rob a museum', 'Steal the paintings from the national museum.', 'ec15d951.jpg', 58, 2500, 9500, 27, 5, 10, 24, 1, 0);
INSERT INTO `crime` VALUES (25, 'Steel drugs', 'Steel drugs van een groothandel.', 'Steal drugs', 'Steal drugs from a wholesaler.', '0e478a1c.jpg', 61, 3000, 10000, 27, 4, 0, 25, 1, 0);
INSERT INTO `crime` VALUES (26, 'Breek in een magazijn ', 'Breek in bij een magazijn van autohandelaars.', 'Break into a warehouse', 'Break into a car dealer warehouse.', 'c08feae1.jpg', 64, 3500, 12000, 29, 5, 0, 26, 1, 0);
INSERT INTO `crime` VALUES (27, 'Beroof een juwelier', 'Wacht het juiste moment af om een juwelier te beroven.', ' Rob a jeweler', 'Wait for the right time to rob a jeweler.', '9c2abc70.jpg', 67, 4000, 15000, 30, 6, 5, 27, 1, 0);
INSERT INTO `crime` VALUES (28, '', '', '', '', '', 1, 0, 0, 5, 6, 0, 28, 1, 1);
INSERT INTO `crime` VALUES (29, '', '', '', '', '', 1, 0, 0, 5, 4, 0, 29, 1, 1);
INSERT INTO `crime` VALUES (30, 'Zwerver beroven', 'Beroof een zwerver van zijn geld.', ' Robbing hobo', ' Rob a drifter of his money.', 'd75c921b.jpg', 70, 4500, 20000, 33, 6, 0, 30, 1, 0);
INSERT INTO `crime` VALUES (31, '', '', '', '', '', 1, 0, 0, 5, 5, 0, 31, 1, 1);
INSERT INTO `crime` VALUES (32, 'Ontvoer de president', 'Ontvoer de president en eis losgeld.', 'Kidnap the president', 'Kidnap the president and demand a ransom.', 'c81659f6.jpg', 73, 5000, 25000, 34, 7, 10, 32, 1, 0);
INSERT INTO `crime` VALUES (33, 'Beroof FC Barcelona', 'Steel het geld van de rekening van FC  Barcelona.', 'Rob FC Barcelona', 'Steal the money from FC Barcelona\'s account.', 'adedf5be.png', 75, 6000, 30000, 36, 6, 0, 33, 1, 0);
INSERT INTO `crime` VALUES (34, 'Beroof Nexive', 'Steel al het geld van Nexive zijn bankrekening.', 'Rob Nexive', 'Steal all the money from Nexive\'s bank account.', 'c9a0bf41.jpg', 78, 7000, 35000, 37, 7, 0, 34, 1, 0);
INSERT INTO `crime` VALUES (35, '', '', '', '', '', 1, 0, 0, 5, 7, 0, 35, 1, 1);
INSERT INTO `crime` VALUES (36, '', '', '', '', '', 1, 0, 0, 5, 8, 0, 36, 1, 1);
INSERT INTO `crime` VALUES (37, 'Ontvoer Lionel Messi', 'Ontvoer Messi en vraag losgeld.', 'Kidnap Lionel Messi', 'Kidnap Messi and demand ransom.', '9f659297.jpg', 81, 8500, 40000, 38, 9, 0, 37, 1, 0);
INSERT INTO `crime` VALUES (38, 'Beroof een mediawinkel', 'Steel het geld uit de kassa van een mediawinkel.', 'Rob a media store', 'Steal the money from a media store\'s cash register.', '7a935892.png', 84, 9500, 45000, 39, 8, 5, 38, 1, 0);
INSERT INTO `crime` VALUES (39, 'Beroof een politie agent', 'Steel het geld en wapen van een politie agent.', 'Rob a police officer', 'Steal a cop\'s money and weapon.', '47289363.jpg', 87, 10000, 50000, 43, 9, 0, 39, 1, 0);
INSERT INTO `crime` VALUES (40, 'Beroof Alcazar', 'Steel het geld van Alcazar\'s bankrekening.', 'Rob Alcazar', 'Steal the money from Alcazar\'s bank account.', '1fce0187.jpg', 91, 12500, 57500, 5, 1, 10, 40, 1, 0);
INSERT INTO `crime` VALUES (41, 'Beroof het casino', 'Kraak de kluis van een casino.', 'Rob the casino', 'Crack a casino\'s safe.', '7d43381c.jpg', 94, 15000, 65000, 43, 9, 0, 41, 1, 0);
INSERT INTO `crime` VALUES (42, 'Overval het ziekenhuis', 'Steel geld en medicijnen uit het ziekenhuis.', 'Rob the hospital', 'Steal money and medicines from the hospital.', '884ce90b.jpg', 97, 20000, 70000, 45, 9, 10, 42, 1, 0);
INSERT INTO `crime` VALUES (43, '', '', '', '', '', 1, 0, 0, 5, 1, 0, 43, 1, 1);
INSERT INTO `crime` VALUES (44, 'Beroof het koninklijk paleis', 'Steel al het geld van de koning en koningin.', '', '', 'd820bf3c.jpg', 93, 0, 0, 5, 1, 0, 44, 1, 1);
INSERT INTO `crime` VALUES (45, 'Ontvoer Zlatan Ibrahimovic', 'Ontvoer Ibrahimovic en vraag losgeld.', 'Kidnap Zlatan Ibrahimovic', 'Kidnap Ibrahimovic and demand ransom.', 'def02a18.jpg', 99, 35000, 80000, 47, 9, 0, 45, 1, 0);
INSERT INTO `crime` VALUES (46, '', '', '', '', '', 1, 0, 0, 5, 1, 0, 46, 1, 1);
INSERT INTO `crime` VALUES (47, 'Beroof het europees parlement', 'Steel het geld van de politiekers.', '', '', '6063a63a.jpg', 97, 0, 0, 5, 1, 0, 47, 1, 1);
INSERT INTO `crime` VALUES (48, 'Beroof MDev', 'Beroof het geld van de bankrekening van MDev.', 'Rob MDev', ' Rob the money from MDev\'s bank account.', '1139e485.png', 100, 50000, 100000, 51, 9, 0, 48, 1, 0);

-- ----------------------------
-- Table structure for crime_org
-- ----------------------------
DROP TABLE IF EXISTS `crime_org`;
CREATE TABLE `crime_org`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `name_en` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'select=Alleen,Twee,Vier',
  `minProfit` int NOT NULL,
  `maxProfit` int NOT NULL,
  `difficulty` smallint NOT NULL DEFAULT 5,
  `waitingTimeCompletion` smallint NOT NULL DEFAULT 120,
  `travelTimeCompletion` smallint NOT NULL DEFAULT 120,
  `prisonTimeBusted` smallint NOT NULL DEFAULT 150,
  `position` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of crime_org
-- ----------------------------
INSERT INTO `crime_org` VALUES (1, 'Overval een tankstation', 'Bereid je voor op actie terwijl je een groot tankstation op je eentje beroofd.', 'Rob a gas station', 'Prepare for some heat as you\'ll take on a big gas station on your own.', '', 1, 30000, 60000, 16, 120, 0, 120, 1, 1, 0);
INSERT INTO `crime_org` VALUES (2, 'Job op Route 66', 'Ga samen met een criminele partner de moederweg van America bestormen op zoek naar grote ladingen vol buit!', 'Route 66 Job', 'Storm the mother road of America with a partner in crime in search of big cargo full of loot!', '', 2, 130000, 360000, 22, 240, 0, 150, 2, 1, 0);
INSERT INTO `crime_org` VALUES (3, 'Uitje naar Las Vegas', 'Ga met 4 genieten van de vakantie van je leven, of niet? Misdaad bevat een 4 minuten reistijd buiten Mafiasource voor alle deelnemers!', 'Trip to Las Vegas', 'Prepare with 4 to enjoy the vacation of your life, or not? Crime includes a 4 minute travel trip outside of Mafiasource for all participants!', '', 3, 1100000, 5500000, 38, 520, 240, 180, 3, 1, 0);

-- ----------------------------
-- Table structure for crime_org_prep
-- ----------------------------
DROP TABLE IF EXISTS `crime_org_prep`;
CREATE TABLE `crime_org_prep`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `orgCrimeID` int NOT NULL DEFAULT 0,
  `userID` bigint NOT NULL DEFAULT 0,
  `job` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'select=driver,raider',
  `participantID` bigint NOT NULL,
  `participant2ID` bigint NOT NULL DEFAULT 0,
  `participant3ID` bigint NOT NULL,
  `userReady` tinyint(1) NOT NULL DEFAULT 0,
  `participantReady` tinyint(1) NOT NULL DEFAULT 0,
  `participant2Ready` tinyint(1) NOT NULL DEFAULT 0,
  `participant3Ready` tinyint(1) NOT NULL DEFAULT 0,
  `garageID` int NOT NULL DEFAULT 0,
  `weaponType` tinyint(1) NOT NULL DEFAULT 0,
  `intelType` tinyint(1) NOT NULL DEFAULT 0,
  `commitTime` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of crime_org_prep
-- ----------------------------

-- ----------------------------
-- Table structure for daily_challenge
-- ----------------------------
DROP TABLE IF EXISTS `daily_challenge`;
CREATE TABLE `daily_challenge`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `challengeID` tinyint(1) NOT NULL DEFAULT 0,
  `amount` int NOT NULL DEFAULT 0,
  `rewardType` tinyint(1) NOT NULL DEFAULT 0,
  `rewardAmount` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of daily_challenge
-- ----------------------------
INSERT INTO `daily_challenge` VALUES (1, 1, 41, 2, 55);
INSERT INTO `daily_challenge` VALUES (2, 8, 4500, 3, 18);
INSERT INTO `daily_challenge` VALUES (3, 9, 31, 4, 102);

-- ----------------------------
-- Table structure for detective
-- ----------------------------
DROP TABLE IF EXISTS `detective`;
CREATE TABLE `detective`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `victimID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `startDate` datetime NULL DEFAULT NULL,
  `hours` tinyint NOT NULL DEFAULT 2,
  `timeFound` bigint NOT NULL DEFAULT 1800,
  `shadow` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `foundCityID` tinyint NOT NULL DEFAULT 0 COMMENT 'couple=city&factor=id&show=name',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of detective
-- ----------------------------

-- ----------------------------
-- Table structure for donate
-- ----------------------------
DROP TABLE IF EXISTS `donate`;
CREATE TABLE `donate`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `sandbox` tinyint(1) NOT NULL DEFAULT 0,
  `tx` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `amount` float(10, 2) NOT NULL DEFAULT 0.00,
  `net_amount` float(10, 2) NOT NULL DEFAULT 0.00,
  `credits` bigint NOT NULL DEFAULT 0,
  `date` datetime NOT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of donate
-- ----------------------------

-- ----------------------------
-- Table structure for donator
-- ----------------------------
DROP TABLE IF EXISTS `donator`;
CREATE TABLE `donator`  (
  `id` int NOT NULL,
  `donator_nl` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `donator_en` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `colorCode` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of donator
-- ----------------------------
INSERT INTO `donator` VALUES (0, 'Lid', 'Member', '#FFFFFF', 1, 1, 0);
INSERT INTO `donator` VALUES (1, 'Donateur', 'Donator', '#F7FF15', 2, 1, 0);
INSERT INTO `donator` VALUES (5, 'VIP', 'VIP', '#42A6C6', 3, 1, 0);
INSERT INTO `donator` VALUES (10, 'Gold Member', 'Gold Member', '#F56D23', 4, 1, 0);


-- ----------------------------
-- Table structure for donator_list
-- ----------------------------
DROP TABLE IF EXISTS `donator_list`;
CREATE TABLE `donator_list`  (
  `id` int NOT NULL COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of donator_list
-- ----------------------------

-- ----------------------------
-- Table structure for drug_liquid
-- ----------------------------
DROP TABLE IF EXISTS `drug_liquid`;
CREATE TABLE `drug_liquid`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'select=drugs,drank',
  `smuggleID` int NOT NULL DEFAULT 0,
  `units` mediumint NOT NULL DEFAULT 0,
  `time` bigint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of drug_liquid
-- ----------------------------

-- ----------------------------
-- Table structure for equipment
-- ----------------------------
DROP TABLE IF EXISTS `equipment`;
CREATE TABLE `equipment`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NULL DEFAULT NULL,
  `type` char(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '',
  `equipmentID` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of equipment
-- ----------------------------

-- ----------------------------
-- Table structure for family
-- ----------------------------
DROP TABLE IF EXISTS `family`;
CREATE TABLE `family`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bossUID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `underbossUID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `bankmanagerUID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `forummodUID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `vip` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `money` bigint NOT NULL DEFAULT 0,
  `image` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `startDate` datetime NOT NULL,
  `join` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'type=yesno',
  `leaveCosts` int NOT NULL DEFAULT 0,
  `profile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `crusher` smallint NOT NULL DEFAULT 0 COMMENT 'select=0,1000,2500,5000',
  `converter` smallint NOT NULL DEFAULT 0 COMMENT 'select=0,1000,2500,5000',
  `mercenariesUsed` int NOT NULL DEFAULT 0,
  `bullets` bigint NOT NULL DEFAULT 0,
  `bulletFactory` smallint NOT NULL DEFAULT 0,
  `bfProduction` int NOT NULL DEFAULT 0 COMMENT 'select=0,500,2500,5000,25000,50000,250000,500000',
  `brothel` smallint NOT NULL DEFAULT 0,
  `cBulletFactory` bigint NOT NULL DEFAULT 0,
  `cBrothel` bigint NOT NULL DEFAULT 0,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `by_name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family
-- ----------------------------

-- ----------------------------
-- Table structure for family_alliance
-- ----------------------------
DROP TABLE IF EXISTS `family_alliance`;
CREATE TABLE `family_alliance`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `allianceFamilyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `requesterFamilyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `familyID`(`familyID`) USING BTREE,
  INDEX `allianceFamilyID`(`allianceFamilyID`) USING BTREE,
  INDEX `requesterFamilyID`(`requesterFamilyID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_alliance
-- ----------------------------

-- ----------------------------
-- Table structure for family_bank_log
-- ----------------------------
DROP TABLE IF EXISTS `family_bank_log`;
CREATE TABLE `family_bank_log`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `familyID` int NULL DEFAULT NULL,
  `senderID` bigint NULL DEFAULT NULL,
  `receiverID` bigint NULL DEFAULT NULL,
  `amount` bigint NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_bank_log
-- ----------------------------

-- ----------------------------
-- Table structure for family_bf_donation_log
-- ----------------------------
DROP TABLE IF EXISTS `family_bf_donation_log`;
CREATE TABLE `family_bf_donation_log`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `amount` bigint NOT NULL DEFAULT 0,
  `amountAll` bigint NOT NULL DEFAULT 0,
  `lastDonation` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_bf_donation_log
-- ----------------------------

-- ----------------------------
-- Table structure for family_bf_send_log
-- ----------------------------
DROP TABLE IF EXISTS `family_bf_send_log`;
CREATE TABLE `family_bf_send_log`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `senderID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `receiverID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `amount` int NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_bf_send_log
-- ----------------------------

-- ----------------------------
-- Table structure for family_brothel_whore
-- ----------------------------
DROP TABLE IF EXISTS `family_brothel_whore`;
CREATE TABLE `family_brothel_whore`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `whores` int NOT NULL DEFAULT 0,
  `position` bigint NULL DEFAULT NULL,
  `active` smallint NOT NULL DEFAULT 1,
  `deleted` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_brothel_whore
-- ----------------------------

-- ----------------------------
-- Table structure for family_crime
-- ----------------------------
DROP TABLE IF EXISTS `family_crime`;
CREATE TABLE `family_crime`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `starterUID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `num_participants` smallint NULL DEFAULT 3,
  `participants` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '',
  `familyID` int NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `stateID` smallint NULL DEFAULT 0 COMMENT 'couple=state&factor=id&show=name',
  `mercenaries` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `crime` smallint NULL DEFAULT 1,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_crime
-- ----------------------------

-- ----------------------------
-- Table structure for family_donation_log
-- ----------------------------
DROP TABLE IF EXISTS `family_donation_log`;
CREATE TABLE `family_donation_log`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `familyID` int NULL DEFAULT NULL,
  `userID` bigint NULL DEFAULT NULL,
  `amount` bigint NULL DEFAULT NULL,
  `amountAll` bigint NULL DEFAULT NULL,
  `lastDonation` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_donation_log
-- ----------------------------

-- ----------------------------
-- Table structure for family_garage
-- ----------------------------
DROP TABLE IF EXISTS `family_garage`;
CREATE TABLE `family_garage`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'small',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_garage
-- ----------------------------

-- ----------------------------
-- Table structure for family_join_invite
-- ----------------------------
DROP TABLE IF EXISTS `family_join_invite`;
CREATE TABLE `family_join_invite`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` enum('Join','Invite') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Join',
  `userID` bigint NOT NULL COMMENT 'couple=user&factor=id&show=username',
  `familyID` int NOT NULL COMMENT 'couple=family&factor=id&show=name',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_join_invite
-- ----------------------------

-- ----------------------------
-- Table structure for family_mercenary_log
-- ----------------------------
DROP TABLE IF EXISTS `family_mercenary_log`;
CREATE TABLE `family_mercenary_log`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `mercenaries` smallint NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_mercenary_log
-- ----------------------------

-- ----------------------------
-- Table structure for family_raid
-- ----------------------------
DROP TABLE IF EXISTS `family_raid`;
CREATE TABLE `family_raid`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `stateID` smallint NULL DEFAULT NULL COMMENT 'couple=state&factor=id&show=name',
  `familyID` int NULL DEFAULT NULL COMMENT 'couple=family&factor=id&show=name',
  `leaderID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `driverID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `bombExpertID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `weaponExpertID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `driverReady` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `bombExpertReady` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `weaponExpertReady` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `garageID` int NULL DEFAULT NULL COMMENT 'couple=garage&factor=id&show=id',
  `bombType` tinyint(1) NULL DEFAULT NULL COMMENT 'select=1xTNT,2xTNT,1xC4,2xC4',
  `weaponType` tinyint(1) NULL DEFAULT NULL COMMENT 'select=UZI,Stg44,M4',
  `bullets` smallint NULL DEFAULT 0,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `stateID`(`stateID`) USING BTREE,
  INDEX `familyID`(`familyID`) USING BTREE,
  INDEX `leaderID`(`leaderID`) USING BTREE,
  INDEX `driverID`(`driverID`) USING BTREE,
  INDEX `bombExpertID`(`bombExpertID`) USING BTREE,
  INDEX `weaponExpertID`(`weaponExpertID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of family_raid
-- ----------------------------

-- ----------------------------
-- Table structure for fifty_game
-- ----------------------------
DROP TABLE IF EXISTS `fifty_game`;
CREATE TABLE `fifty_game`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Contant,Hoeren,Eerpunten',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `amount` bigint NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of fifty_game
-- ----------------------------

-- ----------------------------
-- Table structure for forum_category
-- ----------------------------
DROP TABLE IF EXISTS `forum_category`;
CREATE TABLE `forum_category`  (
  `id` smallint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `category_nl` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `category_en` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `viewStatusID` smallint NOT NULL DEFAULT 7 COMMENT 'couple=status&factor=id&show=status_nl',
  `interactStatusID` smallint NOT NULL DEFAULT 7 COMMENT 'couple=status&factor=id&show=status_nl',
  `familyForum` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `donatorID` smallint NOT NULL DEFAULT 0 COMMENT 'couple=donator&factor=id&show=donator_nl',
  `position` smallint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of forum_category
-- ----------------------------
INSERT INTO `forum_category` VALUES (1, 'Nieuws Forum', 'News Forum', 'Bekijk hier alle nieuws en update berichten van Mafiasource.', 'View all news and updates from Mafiasource here.', '1546b770.png', 7, 6, 0, 0, 0, 0, 0);
INSERT INTO `forum_category` VALUES (2, 'Game Forum', 'Game Forum', 'Volg hier alle onderwerpen i.v.m. de game.', 'Follow all subjects regarding the game here.', '67424752.png', 7, 7, 0, 0, 1, 1, 0);
INSERT INTO `forum_category` VALUES (3, 'IdeeÃ«n Forum', 'Ideas Forum', 'Deel een idee, stem op een idee en volg hier ideeÃ«n van andere spelers.', 'Share an idea, vote for an idea and follow ideas from other players.', '3a35ca5f.png', 7, 7, 0, 1, 2, 1, 0);
INSERT INTO `forum_category` VALUES (4, 'Design Forum', 'Design Forum', 'Toon hier je creative designs en beoordeel het werk van anderen!', 'Show off your creative designs here and rate the work of others!', 'b0cd1899.png', 7, 7, 0, 0, 3, 1, 0);
INSERT INTO `forum_category` VALUES (5, 'Help Forum', 'Help Forum', 'Stel hier vragen i.v.m. de game of help andere spelers met hun vragen.', 'Ask your questions regarding the game here or answer those of other players.', '3f8ce1d5.png', 7, 7, 0, 0, 4, 1, 0);
INSERT INTO `forum_category` VALUES (6, 'Bug Forum', 'Bug Forum', 'Hier kunnen bugs en fouten gemeld worden.', 'Bugs and errors can be reported here.', 'e030f48b.png', 7, 7, 0, 0, 5, 1, 0);
INSERT INTO `forum_category` VALUES (7, 'Algemeen Forum', 'General Forum', 'Ga discussies aan over niet Mafiasource gerelateerde onderwerpen.', 'Discuss not Mafiasource related subjects here.', 'de57f304.png', 7, 7, 0, 0, 6, 1, 0);
INSERT INTO `forum_category` VALUES (8, 'Familie Forum', 'Family Forum', 'Een forum voor jouw en je familie leden.', 'A forum for you and your family members.', 'aee12c1b.png', 7, 7, 1, 0, 7, 1, 0);
INSERT INTO `forum_category` VALUES (9, 'VIP Forum', 'VIP Forum', 'Exclusief forum voor alle VIP leden of hoger', 'Exclusive forum for all VIP members or higher', 'adae0096.png', 7, 7, 0, 5, 8, 1, 0);
INSERT INTO `forum_category` VALUES (10, 'Team Forum', 'Team Forum', 'Team leden kunnen hier discussiÃ«ren.', ' Team members can discuss here.', 'b9a5327a.png', 6, 6, 0, 0, 9, 1, 0);

-- ----------------------------
-- Table structure for forum_reaction
-- ----------------------------
DROP TABLE IF EXISTS `forum_reaction`;
CREATE TABLE `forum_reaction`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `topicID` int NOT NULL DEFAULT 0 COMMENT 'couple=forum_topic&factor=id&show=lang,title',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `date` datetime NULL DEFAULT NULL,
  `lastEditTime` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of forum_reaction
-- ----------------------------

-- ----------------------------
-- Table structure for forum_read
-- ----------------------------
DROP TABLE IF EXISTS `forum_read`;
CREATE TABLE `forum_read`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `userID` bigint NULL DEFAULT NULL,
  `topicID` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of forum_read
-- ----------------------------

-- ----------------------------
-- Table structure for forum_status
-- ----------------------------
DROP TABLE IF EXISTS `forum_status`;
CREATE TABLE `forum_status`  (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `status_nl` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_en` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture_read` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `picture_unread` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `position` tinyint(1) NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of forum_status
-- ----------------------------
INSERT INTO `forum_status` VALUES (1, 'Gesloten', 'Closed', 'a68232fe.png', 'a8a04a3b.png', 1, 1, 0);
INSERT INTO `forum_status` VALUES (2, 'Open', 'Open', '8b6becd5.png', '1bd1e320.png', 2, 1, 0);
INSERT INTO `forum_status` VALUES (3, 'Gepind & Gesloten', 'Pinned & Closed', 'bc1cf9d4.png', 'f07d0101.png', 3, 1, 0);
INSERT INTO `forum_status` VALUES (4, 'Gepind', 'Pinned', '74c287e7.png', '8b343b5e.png', 4, 1, 0);
INSERT INTO `forum_status` VALUES (5, 'Belangrijk & Gesloten', 'Important & Closed', '54fe406c.png', '7847de95.png', 5, 1, 0);
INSERT INTO `forum_status` VALUES (6, 'Belangrijk', 'Important', 'bbae0e2f.png', '810fc93f.png', 6, 1, 0);

-- ----------------------------
-- Table structure for forum_topic
-- ----------------------------
DROP TABLE IF EXISTS `forum_topic`;
CREATE TABLE `forum_topic`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `categoryID` smallint NOT NULL DEFAULT 1 COMMENT 'couple=forum_category&factor=id&show=category_nl',
  `starterUID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `lang` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nl' COMMENT 'type=disabled',
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `date` datetime NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 2 COMMENT 'couple=forum_status&factor=id&show=status_nl',
  `lastMsgTime` bigint NOT NULL DEFAULT 0,
  `cleanUrl` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of forum_topic
-- ----------------------------

-- ----------------------------
-- Table structure for garage
-- ----------------------------
DROP TABLE IF EXISTS `garage`;
CREATE TABLE `garage`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userGarageID` int NOT NULL DEFAULT 0 COMMENT 'couple=user_garage&factor=id&show=id',
  `famGarageID` int NOT NULL DEFAULT 0 COMMENT 'couple=family_garage&factor=id&show=id',
  `vehicleID` int NOT NULL DEFAULT 0 COMMENT 'couple=vehicle&factor=id&show=name',
  `damage` smallint NOT NULL DEFAULT 0,
  `tires` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Dayton,Bridgestone,Michelin',
  `engine` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=V8,V10,V12',
  `exhaust` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Carbon,Skunk2 Racing Power,Invidia Q300 Titanium',
  `shockAbsorbers` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Basic Twin Tube,Acceleration Sensitive,Coilover',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of garage
-- ----------------------------

-- ----------------------------
-- Table structure for ground
-- ----------------------------
DROP TABLE IF EXISTS `ground`;
CREATE TABLE `ground`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `gID` int NULL DEFAULT NULL COMMENT 'type=disabled',
  `stateID` smallint NULL DEFAULT NULL COMMENT 'couple=state&factor=id&show=name',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `building1` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `building2` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `building3` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `building4` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `building5` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `cBuilding1` bigint NULL DEFAULT 0,
  `cBuilding2` bigint NULL DEFAULT 0,
  `cBuilding3` bigint NULL DEFAULT 0,
  `cBuilding4` bigint NULL DEFAULT 0,
  `cBuilding5` bigint NULL DEFAULT 0,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 483 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of ground
-- ----------------------------
INSERT INTO `ground` VALUES (1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0);
INSERT INTO `ground` VALUES (2, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 1, 0);
INSERT INTO `ground` VALUES (3, 3, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 1, 0);
INSERT INTO `ground` VALUES (4, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 1, 0);
INSERT INTO `ground` VALUES (5, 5, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 1, 0);
INSERT INTO `ground` VALUES (6, 6, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 1, 0);
INSERT INTO `ground` VALUES (7, 7, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 7, 1, 0);
INSERT INTO `ground` VALUES (8, 8, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8, 1, 0);
INSERT INTO `ground` VALUES (9, 9, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 9, 1, 0);
INSERT INTO `ground` VALUES (10, 10, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 1, 0);
INSERT INTO `ground` VALUES (11, 11, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 11, 1, 0);
INSERT INTO `ground` VALUES (12, 12, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12, 1, 0);
INSERT INTO `ground` VALUES (13, 13, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 13, 1, 0);
INSERT INTO `ground` VALUES (14, 14, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 14, 1, 0);
INSERT INTO `ground` VALUES (15, 15, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 15, 1, 0);
INSERT INTO `ground` VALUES (16, 16, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 16, 1, 0);
INSERT INTO `ground` VALUES (17, 17, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 17, 1, 0);
INSERT INTO `ground` VALUES (18, 18, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 18, 1, 0);
INSERT INTO `ground` VALUES (19, 19, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 19, 1, 0);
INSERT INTO `ground` VALUES (20, 20, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, 1, 0);
INSERT INTO `ground` VALUES (21, 21, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 21, 1, 0);
INSERT INTO `ground` VALUES (22, 22, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 22, 1, 0);
INSERT INTO `ground` VALUES (23, 23, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 23, 1, 0);
INSERT INTO `ground` VALUES (24, 24, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 24, 1, 0);
INSERT INTO `ground` VALUES (25, 25, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 25, 1, 0);
INSERT INTO `ground` VALUES (26, 26, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 26, 1, 0);
INSERT INTO `ground` VALUES (27, 27, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 27, 1, 0);
INSERT INTO `ground` VALUES (28, 28, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 28, 1, 0);
INSERT INTO `ground` VALUES (29, 29, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 29, 1, 0);
INSERT INTO `ground` VALUES (30, 30, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 30, 1, 0);
INSERT INTO `ground` VALUES (31, 31, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 31, 1, 0);
INSERT INTO `ground` VALUES (32, 32, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 32, 1, 0);
INSERT INTO `ground` VALUES (33, 33, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 33, 1, 0);
INSERT INTO `ground` VALUES (34, 34, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 34, 1, 0);
INSERT INTO `ground` VALUES (35, 35, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 35, 1, 0);
INSERT INTO `ground` VALUES (36, 36, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 36, 1, 0);
INSERT INTO `ground` VALUES (37, 37, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 37, 1, 0);
INSERT INTO `ground` VALUES (38, 38, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 38, 1, 0);
INSERT INTO `ground` VALUES (39, 39, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 39, 1, 0);
INSERT INTO `ground` VALUES (40, 40, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40, 1, 0);
INSERT INTO `ground` VALUES (41, 41, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 41, 1, 0);
INSERT INTO `ground` VALUES (42, 42, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 42, 1, 0);
INSERT INTO `ground` VALUES (43, 43, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 43, 1, 0);
INSERT INTO `ground` VALUES (44, 44, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 44, 1, 0);
INSERT INTO `ground` VALUES (45, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45, 1, 0);
INSERT INTO `ground` VALUES (46, 2, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 46, 1, 0);
INSERT INTO `ground` VALUES (47, 3, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 47, 1, 0);
INSERT INTO `ground` VALUES (48, 4, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 48, 1, 0);
INSERT INTO `ground` VALUES (49, 5, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 49, 1, 0);
INSERT INTO `ground` VALUES (50, 6, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 50, 1, 0);
INSERT INTO `ground` VALUES (51, 7, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 51, 1, 0);
INSERT INTO `ground` VALUES (52, 8, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 52, 1, 0);
INSERT INTO `ground` VALUES (53, 9, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 53, 1, 0);
INSERT INTO `ground` VALUES (54, 10, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 54, 1, 0);
INSERT INTO `ground` VALUES (55, 11, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 55, 1, 0);
INSERT INTO `ground` VALUES (56, 12, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 56, 1, 0);
INSERT INTO `ground` VALUES (57, 13, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 57, 1, 0);
INSERT INTO `ground` VALUES (58, 14, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 58, 1, 0);
INSERT INTO `ground` VALUES (59, 15, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 59, 1, 0);
INSERT INTO `ground` VALUES (60, 16, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 60, 1, 0);
INSERT INTO `ground` VALUES (61, 17, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 61, 1, 0);
INSERT INTO `ground` VALUES (62, 18, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 62, 1, 0);
INSERT INTO `ground` VALUES (63, 19, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 63, 1, 0);
INSERT INTO `ground` VALUES (64, 20, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 64, 1, 0);
INSERT INTO `ground` VALUES (65, 21, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 65, 1, 0);
INSERT INTO `ground` VALUES (66, 22, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 66, 1, 0);
INSERT INTO `ground` VALUES (67, 23, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 67, 1, 0);
INSERT INTO `ground` VALUES (68, 24, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 68, 1, 0);
INSERT INTO `ground` VALUES (69, 25, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 69, 1, 0);
INSERT INTO `ground` VALUES (70, 26, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 70, 1, 0);
INSERT INTO `ground` VALUES (71, 27, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 71, 1, 0);
INSERT INTO `ground` VALUES (72, 28, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 72, 1, 0);
INSERT INTO `ground` VALUES (73, 29, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 73, 1, 0);
INSERT INTO `ground` VALUES (74, 30, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 74, 1, 0);
INSERT INTO `ground` VALUES (75, 31, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 75, 1, 0);
INSERT INTO `ground` VALUES (76, 32, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 76, 1, 0);
INSERT INTO `ground` VALUES (77, 33, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 77, 1, 0);
INSERT INTO `ground` VALUES (78, 34, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 78, 1, 0);
INSERT INTO `ground` VALUES (79, 35, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 79, 1, 0);
INSERT INTO `ground` VALUES (80, 36, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 80, 1, 0);
INSERT INTO `ground` VALUES (81, 37, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 81, 1, 0);
INSERT INTO `ground` VALUES (82, 38, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 82, 1, 0);
INSERT INTO `ground` VALUES (83, 39, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 83, 1, 0);
INSERT INTO `ground` VALUES (84, 40, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 84, 1, 0);
INSERT INTO `ground` VALUES (85, 41, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 85, 1, 0);
INSERT INTO `ground` VALUES (86, 42, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 86, 1, 0);
INSERT INTO `ground` VALUES (87, 43, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 87, 1, 0);
INSERT INTO `ground` VALUES (88, 44, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 88, 1, 0);
INSERT INTO `ground` VALUES (89, 45, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 89, 1, 0);
INSERT INTO `ground` VALUES (90, 46, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 90, 1, 0);
INSERT INTO `ground` VALUES (91, 47, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 91, 1, 0);
INSERT INTO `ground` VALUES (92, 48, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 92, 1, 0);
INSERT INTO `ground` VALUES (93, 49, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 93, 1, 0);
INSERT INTO `ground` VALUES (94, 50, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 94, 1, 0);
INSERT INTO `ground` VALUES (95, 51, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 95, 1, 0);
INSERT INTO `ground` VALUES (96, 52, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 96, 1, 0);
INSERT INTO `ground` VALUES (97, 53, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 97, 1, 0);
INSERT INTO `ground` VALUES (98, 54, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 98, 1, 0);
INSERT INTO `ground` VALUES (99, 55, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 99, 1, 0);
INSERT INTO `ground` VALUES (100, 56, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 1, 0);
INSERT INTO `ground` VALUES (101, 57, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 101, 1, 0);
INSERT INTO `ground` VALUES (102, 1, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 102, 1, 0);
INSERT INTO `ground` VALUES (103, 2, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 103, 1, 0);
INSERT INTO `ground` VALUES (104, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 104, 1, 0);
INSERT INTO `ground` VALUES (105, 4, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 105, 1, 0);
INSERT INTO `ground` VALUES (106, 5, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 106, 1, 0);
INSERT INTO `ground` VALUES (107, 6, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 107, 1, 0);
INSERT INTO `ground` VALUES (108, 7, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 108, 1, 0);
INSERT INTO `ground` VALUES (109, 8, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 109, 1, 0);
INSERT INTO `ground` VALUES (110, 9, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 110, 1, 0);
INSERT INTO `ground` VALUES (111, 10, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 111, 1, 0);
INSERT INTO `ground` VALUES (112, 11, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 112, 1, 0);
INSERT INTO `ground` VALUES (113, 12, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 113, 1, 0);
INSERT INTO `ground` VALUES (114, 13, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 114, 1, 0);
INSERT INTO `ground` VALUES (115, 14, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 115, 1, 0);
INSERT INTO `ground` VALUES (116, 15, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 116, 1, 0);
INSERT INTO `ground` VALUES (117, 16, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 117, 1, 0);
INSERT INTO `ground` VALUES (118, 17, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 118, 1, 0);
INSERT INTO `ground` VALUES (119, 18, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 119, 1, 0);
INSERT INTO `ground` VALUES (120, 19, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 120, 1, 0);
INSERT INTO `ground` VALUES (121, 20, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 121, 1, 0);
INSERT INTO `ground` VALUES (122, 21, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 122, 1, 0);
INSERT INTO `ground` VALUES (123, 22, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 123, 1, 0);
INSERT INTO `ground` VALUES (124, 23, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 124, 1, 0);
INSERT INTO `ground` VALUES (125, 24, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 125, 1, 0);
INSERT INTO `ground` VALUES (126, 25, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 126, 1, 0);
INSERT INTO `ground` VALUES (127, 26, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 127, 1, 0);
INSERT INTO `ground` VALUES (128, 27, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 128, 1, 0);
INSERT INTO `ground` VALUES (129, 28, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 129, 1, 0);
INSERT INTO `ground` VALUES (130, 29, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 130, 1, 0);
INSERT INTO `ground` VALUES (131, 30, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 131, 1, 0);
INSERT INTO `ground` VALUES (132, 31, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 132, 1, 0);
INSERT INTO `ground` VALUES (133, 32, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 133, 1, 0);
INSERT INTO `ground` VALUES (134, 33, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 134, 1, 0);
INSERT INTO `ground` VALUES (135, 34, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 135, 1, 0);
INSERT INTO `ground` VALUES (136, 35, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 136, 1, 0);
INSERT INTO `ground` VALUES (137, 36, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 137, 1, 0);
INSERT INTO `ground` VALUES (138, 37, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 138, 1, 0);
INSERT INTO `ground` VALUES (139, 38, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 139, 1, 0);
INSERT INTO `ground` VALUES (140, 39, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 140, 1, 0);
INSERT INTO `ground` VALUES (141, 40, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 141, 1, 0);
INSERT INTO `ground` VALUES (142, 41, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 142, 1, 0);
INSERT INTO `ground` VALUES (143, 42, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 143, 1, 0);
INSERT INTO `ground` VALUES (144, 43, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 144, 1, 0);
INSERT INTO `ground` VALUES (145, 44, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 145, 1, 0);
INSERT INTO `ground` VALUES (146, 45, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 146, 1, 0);
INSERT INTO `ground` VALUES (147, 46, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 147, 1, 0);
INSERT INTO `ground` VALUES (148, 47, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 148, 1, 0);
INSERT INTO `ground` VALUES (149, 48, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 149, 1, 0);
INSERT INTO `ground` VALUES (150, 49, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 150, 1, 0);
INSERT INTO `ground` VALUES (151, 50, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 151, 1, 0);
INSERT INTO `ground` VALUES (152, 51, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 152, 1, 0);
INSERT INTO `ground` VALUES (153, 52, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 153, 1, 0);
INSERT INTO `ground` VALUES (154, 53, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 154, 1, 0);
INSERT INTO `ground` VALUES (155, 54, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 155, 1, 0);
INSERT INTO `ground` VALUES (156, 55, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 156, 1, 0);
INSERT INTO `ground` VALUES (157, 56, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 157, 1, 0);
INSERT INTO `ground` VALUES (158, 57, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 158, 1, 0);
INSERT INTO `ground` VALUES (159, 58, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 159, 1, 0);
INSERT INTO `ground` VALUES (160, 59, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 160, 1, 0);
INSERT INTO `ground` VALUES (161, 60, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 161, 1, 0);
INSERT INTO `ground` VALUES (162, 1, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 162, 1, 0);
INSERT INTO `ground` VALUES (163, 2, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 163, 1, 0);
INSERT INTO `ground` VALUES (164, 3, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 164, 1, 0);
INSERT INTO `ground` VALUES (165, 4, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 165, 1, 0);
INSERT INTO `ground` VALUES (166, 5, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 166, 1, 0);
INSERT INTO `ground` VALUES (167, 6, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 167, 1, 0);
INSERT INTO `ground` VALUES (168, 7, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 168, 1, 0);
INSERT INTO `ground` VALUES (169, 8, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 169, 1, 0);
INSERT INTO `ground` VALUES (170, 9, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 170, 1, 0);
INSERT INTO `ground` VALUES (171, 10, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 171, 1, 0);
INSERT INTO `ground` VALUES (172, 11, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 172, 1, 0);
INSERT INTO `ground` VALUES (173, 12, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 173, 1, 0);
INSERT INTO `ground` VALUES (174, 13, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 174, 1, 0);
INSERT INTO `ground` VALUES (175, 14, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 175, 1, 0);
INSERT INTO `ground` VALUES (176, 15, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 176, 1, 0);
INSERT INTO `ground` VALUES (177, 16, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 177, 1, 0);
INSERT INTO `ground` VALUES (178, 17, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 178, 1, 0);
INSERT INTO `ground` VALUES (179, 18, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 179, 1, 0);
INSERT INTO `ground` VALUES (180, 19, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 180, 1, 0);
INSERT INTO `ground` VALUES (181, 20, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 181, 1, 0);
INSERT INTO `ground` VALUES (182, 21, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 182, 1, 0);
INSERT INTO `ground` VALUES (183, 22, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 183, 1, 0);
INSERT INTO `ground` VALUES (184, 23, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 184, 1, 0);
INSERT INTO `ground` VALUES (185, 24, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 185, 1, 0);
INSERT INTO `ground` VALUES (186, 25, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 186, 1, 0);
INSERT INTO `ground` VALUES (187, 26, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 187, 1, 0);
INSERT INTO `ground` VALUES (188, 27, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 188, 1, 0);
INSERT INTO `ground` VALUES (189, 28, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 189, 1, 0);
INSERT INTO `ground` VALUES (190, 29, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 190, 1, 0);
INSERT INTO `ground` VALUES (191, 30, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 191, 1, 0);
INSERT INTO `ground` VALUES (192, 31, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 192, 1, 0);
INSERT INTO `ground` VALUES (193, 32, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 193, 1, 0);
INSERT INTO `ground` VALUES (194, 33, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 194, 1, 0);
INSERT INTO `ground` VALUES (195, 34, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 195, 1, 0);
INSERT INTO `ground` VALUES (196, 35, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 196, 1, 0);
INSERT INTO `ground` VALUES (197, 36, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 197, 1, 0);
INSERT INTO `ground` VALUES (198, 37, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 198, 1, 0);
INSERT INTO `ground` VALUES (199, 38, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 199, 1, 0);
INSERT INTO `ground` VALUES (200, 39, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 200, 1, 0);
INSERT INTO `ground` VALUES (201, 40, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 201, 1, 0);
INSERT INTO `ground` VALUES (202, 41, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 202, 1, 0);
INSERT INTO `ground` VALUES (203, 42, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 203, 1, 0);
INSERT INTO `ground` VALUES (204, 43, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 204, 1, 0);
INSERT INTO `ground` VALUES (205, 44, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 205, 1, 0);
INSERT INTO `ground` VALUES (206, 45, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 206, 1, 0);
INSERT INTO `ground` VALUES (207, 46, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 207, 1, 0);
INSERT INTO `ground` VALUES (208, 47, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 208, 1, 0);
INSERT INTO `ground` VALUES (209, 48, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 209, 1, 0);
INSERT INTO `ground` VALUES (210, 49, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 210, 1, 0);
INSERT INTO `ground` VALUES (211, 50, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 211, 1, 0);
INSERT INTO `ground` VALUES (212, 51, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 212, 1, 0);
INSERT INTO `ground` VALUES (213, 52, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 213, 1, 0);
INSERT INTO `ground` VALUES (214, 53, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 214, 1, 0);
INSERT INTO `ground` VALUES (215, 54, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 215, 1, 0);
INSERT INTO `ground` VALUES (216, 55, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 216, 1, 0);
INSERT INTO `ground` VALUES (217, 56, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 217, 1, 0);
INSERT INTO `ground` VALUES (218, 57, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 218, 1, 0);
INSERT INTO `ground` VALUES (219, 58, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 219, 1, 0);
INSERT INTO `ground` VALUES (220, 59, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 220, 1, 0);
INSERT INTO `ground` VALUES (221, 60, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 221, 1, 0);
INSERT INTO `ground` VALUES (222, 61, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 222, 1, 0);
INSERT INTO `ground` VALUES (223, 62, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 223, 1, 0);
INSERT INTO `ground` VALUES (224, 63, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 224, 1, 0);
INSERT INTO `ground` VALUES (225, 64, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 225, 1, 0);
INSERT INTO `ground` VALUES (226, 65, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 226, 1, 0);
INSERT INTO `ground` VALUES (227, 66, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 227, 1, 0);
INSERT INTO `ground` VALUES (228, 67, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 228, 1, 0);
INSERT INTO `ground` VALUES (229, 68, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 229, 1, 0);
INSERT INTO `ground` VALUES (230, 69, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 230, 1, 0);
INSERT INTO `ground` VALUES (231, 70, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 231, 1, 0);
INSERT INTO `ground` VALUES (232, 71, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 232, 1, 0);
INSERT INTO `ground` VALUES (233, 72, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 233, 1, 0);
INSERT INTO `ground` VALUES (234, 73, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 234, 1, 0);
INSERT INTO `ground` VALUES (235, 74, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 235, 1, 0);
INSERT INTO `ground` VALUES (236, 75, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 236, 1, 0);
INSERT INTO `ground` VALUES (237, 76, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 237, 1, 0);
INSERT INTO `ground` VALUES (238, 77, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 238, 1, 0);
INSERT INTO `ground` VALUES (239, 78, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 239, 1, 0);
INSERT INTO `ground` VALUES (240, 79, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 240, 1, 0);
INSERT INTO `ground` VALUES (241, 80, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 241, 1, 0);
INSERT INTO `ground` VALUES (242, 81, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 242, 1, 0);
INSERT INTO `ground` VALUES (243, 82, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 243, 1, 0);
INSERT INTO `ground` VALUES (244, 83, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 244, 1, 0);
INSERT INTO `ground` VALUES (245, 84, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 245, 1, 0);
INSERT INTO `ground` VALUES (246, 85, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 246, 1, 0);
INSERT INTO `ground` VALUES (247, 86, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 247, 1, 0);
INSERT INTO `ground` VALUES (248, 87, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 248, 1, 0);
INSERT INTO `ground` VALUES (249, 88, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 249, 1, 0);
INSERT INTO `ground` VALUES (250, 89, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 250, 1, 0);
INSERT INTO `ground` VALUES (251, 90, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 251, 1, 0);
INSERT INTO `ground` VALUES (252, 91, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 252, 1, 0);
INSERT INTO `ground` VALUES (253, 92, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 253, 1, 0);
INSERT INTO `ground` VALUES (254, 93, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 254, 1, 0);
INSERT INTO `ground` VALUES (255, 94, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 255, 1, 0);
INSERT INTO `ground` VALUES (256, 95, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 256, 1, 0);
INSERT INTO `ground` VALUES (257, 96, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 257, 1, 0);
INSERT INTO `ground` VALUES (258, 97, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 258, 1, 0);
INSERT INTO `ground` VALUES (259, 98, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 259, 1, 0);
INSERT INTO `ground` VALUES (260, 99, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 260, 1, 0);
INSERT INTO `ground` VALUES (261, 100, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 261, 1, 0);
INSERT INTO `ground` VALUES (262, 101, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 262, 1, 0);
INSERT INTO `ground` VALUES (263, 102, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 263, 1, 0);
INSERT INTO `ground` VALUES (264, 103, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 264, 1, 0);
INSERT INTO `ground` VALUES (265, 104, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 265, 1, 0);
INSERT INTO `ground` VALUES (266, 105, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 266, 1, 0);
INSERT INTO `ground` VALUES (267, 106, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 267, 1, 0);
INSERT INTO `ground` VALUES (268, 107, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 268, 1, 0);
INSERT INTO `ground` VALUES (269, 108, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 269, 1, 0);
INSERT INTO `ground` VALUES (270, 109, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 270, 1, 0);
INSERT INTO `ground` VALUES (271, 110, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 271, 1, 0);
INSERT INTO `ground` VALUES (272, 111, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 272, 1, 0);
INSERT INTO `ground` VALUES (273, 112, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 273, 1, 0);
INSERT INTO `ground` VALUES (274, 113, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 274, 1, 0);
INSERT INTO `ground` VALUES (275, 114, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 275, 1, 0);
INSERT INTO `ground` VALUES (276, 115, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 276, 1, 0);
INSERT INTO `ground` VALUES (277, 116, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 277, 1, 0);
INSERT INTO `ground` VALUES (278, 117, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 278, 1, 0);
INSERT INTO `ground` VALUES (279, 118, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 279, 1, 0);
INSERT INTO `ground` VALUES (280, 119, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 280, 1, 0);
INSERT INTO `ground` VALUES (281, 120, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 281, 1, 0);
INSERT INTO `ground` VALUES (282, 121, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 282, 1, 0);
INSERT INTO `ground` VALUES (283, 122, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 283, 1, 0);
INSERT INTO `ground` VALUES (284, 123, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 284, 1, 0);
INSERT INTO `ground` VALUES (285, 124, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 285, 1, 0);
INSERT INTO `ground` VALUES (286, 125, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 286, 1, 0);
INSERT INTO `ground` VALUES (287, 126, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 287, 1, 0);
INSERT INTO `ground` VALUES (288, 127, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 288, 1, 0);
INSERT INTO `ground` VALUES (289, 128, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 289, 1, 0);
INSERT INTO `ground` VALUES (290, 129, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 290, 1, 0);
INSERT INTO `ground` VALUES (291, 130, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 291, 1, 0);
INSERT INTO `ground` VALUES (292, 131, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 292, 1, 0);
INSERT INTO `ground` VALUES (293, 132, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 293, 1, 0);
INSERT INTO `ground` VALUES (294, 133, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 294, 1, 0);
INSERT INTO `ground` VALUES (295, 134, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 295, 1, 0);
INSERT INTO `ground` VALUES (296, 135, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 296, 1, 0);
INSERT INTO `ground` VALUES (297, 136, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 297, 1, 0);
INSERT INTO `ground` VALUES (298, 137, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 298, 1, 0);
INSERT INTO `ground` VALUES (299, 138, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 299, 1, 0);
INSERT INTO `ground` VALUES (300, 139, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 300, 1, 0);
INSERT INTO `ground` VALUES (301, 140, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 301, 1, 0);
INSERT INTO `ground` VALUES (302, 141, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 302, 1, 0);
INSERT INTO `ground` VALUES (303, 142, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 303, 1, 0);
INSERT INTO `ground` VALUES (304, 143, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 304, 1, 0);
INSERT INTO `ground` VALUES (305, 144, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 305, 1, 0);
INSERT INTO `ground` VALUES (306, 145, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 306, 1, 0);
INSERT INTO `ground` VALUES (307, 146, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 307, 1, 0);
INSERT INTO `ground` VALUES (308, 147, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 308, 1, 0);
INSERT INTO `ground` VALUES (309, 148, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 309, 1, 0);
INSERT INTO `ground` VALUES (310, 149, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 310, 1, 0);
INSERT INTO `ground` VALUES (311, 150, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 311, 1, 0);
INSERT INTO `ground` VALUES (312, 151, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 312, 1, 0);
INSERT INTO `ground` VALUES (313, 152, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 313, 1, 0);
INSERT INTO `ground` VALUES (314, 153, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 314, 1, 0);
INSERT INTO `ground` VALUES (315, 154, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 315, 1, 0);
INSERT INTO `ground` VALUES (316, 155, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 316, 1, 0);
INSERT INTO `ground` VALUES (317, 156, 4, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 317, 1, 0);
INSERT INTO `ground` VALUES (318, 1, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 318, 1, 0);
INSERT INTO `ground` VALUES (319, 2, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 319, 1, 0);
INSERT INTO `ground` VALUES (320, 3, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 320, 1, 0);
INSERT INTO `ground` VALUES (321, 4, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 321, 1, 0);
INSERT INTO `ground` VALUES (322, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 322, 1, 0);
INSERT INTO `ground` VALUES (323, 6, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 323, 1, 0);
INSERT INTO `ground` VALUES (324, 7, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 324, 1, 0);
INSERT INTO `ground` VALUES (325, 8, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 325, 1, 0);
INSERT INTO `ground` VALUES (326, 9, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 326, 1, 0);
INSERT INTO `ground` VALUES (327, 10, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 327, 1, 0);
INSERT INTO `ground` VALUES (328, 11, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 328, 1, 0);
INSERT INTO `ground` VALUES (329, 12, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 329, 1, 0);
INSERT INTO `ground` VALUES (330, 13, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 330, 1, 0);
INSERT INTO `ground` VALUES (331, 14, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 331, 1, 0);
INSERT INTO `ground` VALUES (332, 15, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 332, 1, 0);
INSERT INTO `ground` VALUES (333, 16, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 333, 1, 0);
INSERT INTO `ground` VALUES (334, 17, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 334, 1, 0);
INSERT INTO `ground` VALUES (335, 18, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 335, 1, 0);
INSERT INTO `ground` VALUES (336, 19, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 336, 1, 0);
INSERT INTO `ground` VALUES (337, 20, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 337, 1, 0);
INSERT INTO `ground` VALUES (338, 21, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 338, 1, 0);
INSERT INTO `ground` VALUES (339, 22, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 339, 1, 0);
INSERT INTO `ground` VALUES (340, 23, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 340, 1, 0);
INSERT INTO `ground` VALUES (341, 24, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 341, 1, 0);
INSERT INTO `ground` VALUES (342, 25, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 342, 1, 0);
INSERT INTO `ground` VALUES (343, 26, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 343, 1, 0);
INSERT INTO `ground` VALUES (344, 27, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 344, 1, 0);
INSERT INTO `ground` VALUES (345, 28, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 345, 1, 0);
INSERT INTO `ground` VALUES (346, 29, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 346, 1, 0);
INSERT INTO `ground` VALUES (347, 30, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 347, 1, 0);
INSERT INTO `ground` VALUES (348, 31, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 348, 1, 0);
INSERT INTO `ground` VALUES (349, 32, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 349, 1, 0);
INSERT INTO `ground` VALUES (350, 33, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 350, 1, 0);
INSERT INTO `ground` VALUES (351, 34, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 351, 1, 0);
INSERT INTO `ground` VALUES (352, 35, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 352, 1, 0);
INSERT INTO `ground` VALUES (353, 36, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 353, 1, 0);
INSERT INTO `ground` VALUES (354, 37, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 354, 1, 0);
INSERT INTO `ground` VALUES (355, 38, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 355, 1, 0);
INSERT INTO `ground` VALUES (356, 39, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 356, 1, 0);
INSERT INTO `ground` VALUES (357, 40, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 357, 1, 0);
INSERT INTO `ground` VALUES (358, 41, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 358, 1, 0);
INSERT INTO `ground` VALUES (359, 42, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 359, 1, 0);
INSERT INTO `ground` VALUES (360, 43, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 360, 1, 0);
INSERT INTO `ground` VALUES (361, 44, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 361, 1, 0);
INSERT INTO `ground` VALUES (362, 45, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 362, 1, 0);
INSERT INTO `ground` VALUES (363, 46, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 363, 1, 0);
INSERT INTO `ground` VALUES (364, 47, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 364, 1, 0);
INSERT INTO `ground` VALUES (365, 48, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 365, 1, 0);
INSERT INTO `ground` VALUES (366, 49, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 366, 1, 0);
INSERT INTO `ground` VALUES (367, 50, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 367, 1, 0);
INSERT INTO `ground` VALUES (368, 51, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 368, 1, 0);
INSERT INTO `ground` VALUES (369, 52, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 369, 1, 0);
INSERT INTO `ground` VALUES (370, 53, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 370, 1, 0);
INSERT INTO `ground` VALUES (371, 54, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 371, 1, 0);
INSERT INTO `ground` VALUES (372, 55, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 372, 1, 0);
INSERT INTO `ground` VALUES (373, 56, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 373, 1, 0);
INSERT INTO `ground` VALUES (374, 57, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 374, 1, 0);
INSERT INTO `ground` VALUES (375, 58, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 375, 1, 0);
INSERT INTO `ground` VALUES (376, 59, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 376, 1, 0);
INSERT INTO `ground` VALUES (377, 60, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 377, 1, 0);
INSERT INTO `ground` VALUES (378, 61, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 378, 1, 0);
INSERT INTO `ground` VALUES (379, 62, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 379, 1, 0);
INSERT INTO `ground` VALUES (380, 63, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 380, 1, 0);
INSERT INTO `ground` VALUES (381, 64, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 381, 1, 0);
INSERT INTO `ground` VALUES (382, 65, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 382, 1, 0);
INSERT INTO `ground` VALUES (383, 66, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 383, 1, 0);
INSERT INTO `ground` VALUES (384, 67, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 384, 1, 0);
INSERT INTO `ground` VALUES (385, 68, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 385, 1, 0);
INSERT INTO `ground` VALUES (386, 69, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 386, 1, 0);
INSERT INTO `ground` VALUES (387, 70, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 387, 1, 0);
INSERT INTO `ground` VALUES (388, 71, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 388, 1, 0);
INSERT INTO `ground` VALUES (389, 72, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 389, 1, 0);
INSERT INTO `ground` VALUES (390, 73, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 390, 1, 0);
INSERT INTO `ground` VALUES (391, 74, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 391, 1, 0);
INSERT INTO `ground` VALUES (392, 75, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 392, 1, 0);
INSERT INTO `ground` VALUES (393, 76, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 393, 1, 0);
INSERT INTO `ground` VALUES (394, 77, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 394, 1, 0);
INSERT INTO `ground` VALUES (395, 78, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 395, 1, 0);
INSERT INTO `ground` VALUES (396, 79, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 396, 1, 0);
INSERT INTO `ground` VALUES (397, 80, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 397, 1, 0);
INSERT INTO `ground` VALUES (398, 81, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 398, 1, 0);
INSERT INTO `ground` VALUES (399, 82, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 399, 1, 0);
INSERT INTO `ground` VALUES (400, 83, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 400, 1, 0);
INSERT INTO `ground` VALUES (401, 84, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 401, 1, 0);
INSERT INTO `ground` VALUES (402, 85, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 402, 1, 0);
INSERT INTO `ground` VALUES (403, 86, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 403, 1, 0);
INSERT INTO `ground` VALUES (404, 87, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 404, 1, 0);
INSERT INTO `ground` VALUES (405, 88, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 405, 1, 0);
INSERT INTO `ground` VALUES (406, 89, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 406, 1, 0);
INSERT INTO `ground` VALUES (407, 90, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 407, 1, 0);
INSERT INTO `ground` VALUES (408, 91, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 408, 1, 0);
INSERT INTO `ground` VALUES (409, 92, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 409, 1, 0);
INSERT INTO `ground` VALUES (410, 93, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 410, 1, 0);
INSERT INTO `ground` VALUES (411, 94, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 411, 1, 0);
INSERT INTO `ground` VALUES (412, 95, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 412, 1, 0);
INSERT INTO `ground` VALUES (413, 96, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 413, 1, 0);
INSERT INTO `ground` VALUES (414, 97, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 414, 1, 0);
INSERT INTO `ground` VALUES (415, 98, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 415, 1, 0);
INSERT INTO `ground` VALUES (416, 99, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 416, 1, 0);
INSERT INTO `ground` VALUES (417, 100, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 417, 1, 0);
INSERT INTO `ground` VALUES (418, 101, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 418, 1, 0);
INSERT INTO `ground` VALUES (419, 102, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 419, 1, 0);
INSERT INTO `ground` VALUES (420, 103, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 420, 1, 0);
INSERT INTO `ground` VALUES (421, 104, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 421, 1, 0);
INSERT INTO `ground` VALUES (422, 105, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 422, 1, 0);
INSERT INTO `ground` VALUES (423, 106, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 423, 1, 0);
INSERT INTO `ground` VALUES (424, 107, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 424, 1, 0);
INSERT INTO `ground` VALUES (425, 108, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 425, 1, 0);
INSERT INTO `ground` VALUES (426, 109, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 426, 1, 0);
INSERT INTO `ground` VALUES (427, 110, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 427, 1, 0);
INSERT INTO `ground` VALUES (428, 111, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 428, 1, 0);
INSERT INTO `ground` VALUES (429, 112, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 429, 1, 0);
INSERT INTO `ground` VALUES (430, 113, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 430, 1, 0);
INSERT INTO `ground` VALUES (431, 1, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 431, 1, 0);
INSERT INTO `ground` VALUES (432, 2, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 432, 1, 0);
INSERT INTO `ground` VALUES (433, 3, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 433, 1, 0);
INSERT INTO `ground` VALUES (434, 4, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 434, 1, 0);
INSERT INTO `ground` VALUES (435, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 435, 1, 0);
INSERT INTO `ground` VALUES (436, 6, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 436, 1, 0);
INSERT INTO `ground` VALUES (437, 7, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 437, 1, 0);
INSERT INTO `ground` VALUES (438, 8, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 438, 1, 0);
INSERT INTO `ground` VALUES (439, 9, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 439, 1, 0);
INSERT INTO `ground` VALUES (440, 10, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 440, 1, 0);
INSERT INTO `ground` VALUES (441, 11, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 441, 1, 0);
INSERT INTO `ground` VALUES (442, 12, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 442, 1, 0);
INSERT INTO `ground` VALUES (443, 13, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 443, 1, 0);
INSERT INTO `ground` VALUES (444, 14, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 444, 1, 0);
INSERT INTO `ground` VALUES (445, 15, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 445, 1, 0);
INSERT INTO `ground` VALUES (446, 16, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 446, 1, 0);
INSERT INTO `ground` VALUES (447, 17, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 447, 1, 0);
INSERT INTO `ground` VALUES (448, 18, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 448, 1, 0);
INSERT INTO `ground` VALUES (449, 19, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 449, 1, 0);
INSERT INTO `ground` VALUES (450, 20, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 450, 1, 0);
INSERT INTO `ground` VALUES (451, 21, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 451, 1, 0);
INSERT INTO `ground` VALUES (452, 22, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 452, 1, 0);
INSERT INTO `ground` VALUES (453, 23, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 453, 1, 0);
INSERT INTO `ground` VALUES (454, 24, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 454, 1, 0);
INSERT INTO `ground` VALUES (455, 25, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 455, 1, 0);
INSERT INTO `ground` VALUES (456, 26, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 456, 1, 0);
INSERT INTO `ground` VALUES (457, 27, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 457, 1, 0);
INSERT INTO `ground` VALUES (458, 28, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 458, 1, 0);
INSERT INTO `ground` VALUES (459, 29, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 459, 1, 0);
INSERT INTO `ground` VALUES (460, 30, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 460, 1, 0);
INSERT INTO `ground` VALUES (461, 31, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 461, 1, 0);
INSERT INTO `ground` VALUES (462, 32, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 462, 1, 0);
INSERT INTO `ground` VALUES (463, 33, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 463, 1, 0);
INSERT INTO `ground` VALUES (464, 34, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 464, 1, 0);
INSERT INTO `ground` VALUES (465, 35, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 465, 1, 0);
INSERT INTO `ground` VALUES (466, 36, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 466, 1, 0);
INSERT INTO `ground` VALUES (467, 37, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 467, 1, 0);
INSERT INTO `ground` VALUES (468, 38, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 468, 1, 0);
INSERT INTO `ground` VALUES (469, 39, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 469, 1, 0);
INSERT INTO `ground` VALUES (470, 40, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 470, 1, 0);
INSERT INTO `ground` VALUES (471, 41, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 471, 1, 0);
INSERT INTO `ground` VALUES (472, 42, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 472, 1, 0);
INSERT INTO `ground` VALUES (473, 43, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 473, 1, 0);
INSERT INTO `ground` VALUES (474, 44, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 474, 1, 0);
INSERT INTO `ground` VALUES (475, 45, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 475, 1, 0);
INSERT INTO `ground` VALUES (476, 46, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 476, 1, 0);
INSERT INTO `ground` VALUES (477, 47, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 477, 1, 0);
INSERT INTO `ground` VALUES (478, 48, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 478, 1, 0);
INSERT INTO `ground` VALUES (479, 49, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 479, 1, 0);
INSERT INTO `ground` VALUES (480, 50, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 480, 1, 0);
INSERT INTO `ground` VALUES (481, 51, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 481, 1, 0);
INSERT INTO `ground` VALUES (482, 52, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 482, 1, 0);

-- ----------------------------
-- Table structure for ground_building
-- ----------------------------
DROP TABLE IF EXISTS `ground_building`;
CREATE TABLE `ground_building`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_en` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NULL DEFAULT NULL,
  `income` int NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of ground_building
-- ----------------------------
INSERT INTO `ground_building` VALUES (1, 'Casino', 'Casino', 'a0a66328.jpg', 250000, 16540, 1, 1, 0);
INSERT INTO `ground_building` VALUES (2, 'Coffeeshop', 'Coffeeshop', '81833045.jpg', 200000, 14260, 2, 1, 0);
INSERT INTO `ground_building` VALUES (3, 'Irish Pub', 'Irish Pub', '2c025ffc.jpg', 120000, 10200, 3, 1, 0);
INSERT INTO `ground_building` VALUES (4, 'Motel', 'Motel', '3c4f6a24.jpg', 80000, 8300, 4, 1, 0);
INSERT INTO `ground_building` VALUES (5, 'Nachtclub', 'Nightclub', '9800cae2.jpg', 40000, 7700, 5, 1, 0);

-- ----------------------------
-- Table structure for gym_competition
-- ----------------------------
DROP TABLE IF EXISTS `gym_competition`;
CREATE TABLE `gym_competition`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `cityID` smallint NOT NULL DEFAULT 0 COMMENT 'couple=city&factor=id&show=name',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Armdrukken,Sprinten,Touwtrekken,Triatlon,Worstelen',
  `stake` int NOT NULL DEFAULT 50,
  `participantID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `winnerID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `startDate` datetime NULL DEFAULT NULL,
  `endDate` datetime NULL DEFAULT NULL,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of gym_competition
-- ----------------------------

-- ----------------------------
-- Table structure for helpsystem
-- ----------------------------
DROP TABLE IF EXISTS `helpsystem`;
CREATE TABLE `helpsystem`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `routename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content_nl` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `content_en` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 113 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of helpsystem
-- ----------------------------
INSERT INTO `helpsystem` VALUES (1, 'game', '<p>Hier kan je alle nieuws en updates volgen van Mafiasource. Op deze pagina vind je de laatste 5 nieuws/update berichten</p>\r\n', '<p>Here you can follow the latest news and updates from Mafiasource. On this page you can find the latest 5 news/update messages.</p>\r\n', 45, 1, 0);
INSERT INTO `helpsystem` VALUES (2, 'news', '<p>Hier kan je alle nieuws en updates volgen van Mafiasource. Op deze pagina vind je de laatste 5 nieuws/update berichten</p>\r\n', '<p>Here you can follow the latest news and updates from Mafiasource. On this page you can find the latest 5 news/update messages.</p>\r\n', 75, 1, 0);
INSERT INTO `helpsystem` VALUES (3, 'news-news', '<p>De laatste 15 nieuws berichten op een rijtje volgens de publiceer datum.</p>\r\n', '<p>The latest 15 news messaes in a row ordered by publish date.</p>\r\n', 76, 1, 0);
INSERT INTO `helpsystem` VALUES (4, 'news-updates', '<p>De laatste 15 update berichten op een rijtje volgens de publiceer datum.</p>\r\n', '<p>The latest 15 update messaes in a row ordered by publish date.</p>\r\n', 77, 1, 0);
INSERT INTO `helpsystem` VALUES (5, 'status', '<p>Hier kun je de status en informatie van je account bekijken. Je ziet hier bijvoorbeeld hoelang je nog moet wachten voor je weer iets kunt doen. Ook is hier jouw referral-link te vinden.</p>\r\n\r\n<p>Als je deze link doorstuurt naar andere mensen kunnen zij zich via jouw referral aanmelden en krijg jij  een extra beloning en 1 referral. Wanneer een referral credits koopt, zul jij ook wat geld krijgen als beloning.<br />\r\n<br />\r\nMeer uitleg hierover is te vinden bij \"Klik hier om de referraluitleg te zien!\" onder je referral-link.<br />\r\n<br />\r\nVerder vind je hier nog je gemiddelde score per uur en met wat je account is uitgerust</p>\r\n', '<p>Here you can view your status and information regarding your account. You see how long you have to wait before executing another action. Also your referral link is available.</p>\r\n\r\n<p>If you send this link to people you know than they can register through your referral link and you get an extra reward and referral. When a referral buys credits you will recieve money as a gift!<br />\r\n<br />\r\nMore information regarding the referral profit system can be found when pressing the link \"Click here for additional referral information\" on the status page.<br />\r\n<br />\r\nRest of the info regards your average score each hour and with what your account is equipped.</p>\r\n', 100, 1, 0);
INSERT INTO `helpsystem` VALUES (6, 'bank', '<strong>Bank</strong><br />\r\nHier kan je geld naar anderen versturen of contant geld op de bank storten of andersom. Wanneer je geld stuurt naar een andere speler, gaan er transactiekosten af. Hoe hoog dit percentage is, hangt af van welke status de <strong>andere </strong>speler heeft. Wanneer de andere speler lid is gaat er 5% transactiekosten af. Is hij donateur, V.I.P. of Gold Member dan gaat er respectievelijk 4%, 3% en 2% aan transactiekosten van het overgemaakte bedrag af. Donatuer, V.I.P. of Gold Member wordt je in de <em>donatieshop</em>!<br />\r\n<br />\r\nElke dag om 0.00 uur krijg je rente op je bank gestort. De rente wordt berekend over je geld op de bank. Tot de $500,000,000 is de rente 5%. Heb je meer geld dan krijg je 1% rente over je bank geld.<br />\r\n<br />\r\nWanneer de bank geen eigenaar heeft kan deze gekocht worden voor $1,000,000. De eigenaar van de bank verdient geld doordat er transactiekosten worden afgedragen bij het doen van een transactie.<br />\r\n<br />\r\n<strong>Zwitserse Bank</strong><br />\r\nNormaal gesproken raak je jouw bezittingen en dus ook je geld kwijt als je dood gaat. Heb je je geld echter op de Zwitserse bank gezet, dan houd je dit geld! Mocht je dood gaan, kan je dus een mooie start maken met het geld dat je op je Zwitserse bank hebt gezet. Hier ontvang je echter geen rente. Als je geld op de Zwitserse bank zet, gaan er 5%<br />\r\ntransactiekosten af. Je kunt hier maximaal $100,000,000 storten.<br />\r\n<br />\r\n<strong>Financieel/Logboeken</strong><br />\r\nBij logboeken zie je de laatste 10 banktransacties. Bij financieel is een overzicht van je inkomsten te zien. Je kunt hier zien hoeveel je hoeren, je land en je bezittingen aan inkomsten opbrengen.', '<strong>Bank</strong><br />\r\nHere you can send money to others or deposit cash in the bank or vice versa. When you send money to a other player, transaction costs are deducted. How high this percentage is depends on which status the <strong>other</strong> player has. When the other player is a member, a 5% transaction fee is deducted. Is he a Donator, V.I.P. or Gold Member then 4%, 3% and 2%, respectively, in transaction costs will be deducted from the amount transferred. Donator, V.I.P. or Gold Become a member in the donation shop!<br />\r\n<br />\r\nEvery day at 00.00 you get interest paid into your bank. The interest is calculated on your money in the bank. Till the<br />\r\n$500,000,000 the interest is 5%. If you have more money, you will receive 1% interest on your bank money.<br />\r\n<br />\r\nIf the bank has no owner, it can be bought for $1,000,000. The owner of the bank makes money because transaction costs are paid when making a transaction.<br />\r\n<br />\r\n<strong>Swiss Bank</strong><br />\r\nNormally you lose your possessions and therefore also your money when you die. Do you have your money on the<br />\r\nSwiss bank set, then you keep this money! So if you die, you can make a good start with the money you sent to your Swiss bank. However, you will not receive any interest here. If you put money in the Swiss bank you will pay some transaction costs. You can deposit up to $100,000,000 here.<br />\r\n<br />\r\n<strong>Financial/Logs</strong><br />\r\nIn logs you see the last 10 bank transactions. Under financial you can see an overview of your income. You can here see how much income your whores, your land and your possessions bring in.', 3, 1, 0);
INSERT INTO `helpsystem` VALUES (7, 'swiss-bank', '<strong>Zwitserse Bank</strong><br />\r\nNormaal gesproken raak je jouw bezittingen en dus ook je geld kwijt als je dood gaat. Heb je je geld echter op de Zwitserse bank gezet, dan houd je dit geld! Mocht je dood gaan, kan je dus een mooie start maken met het geld dat je op<br />\r\nje Zwitserse bank hebt gezet. Hier ontvang je echter geen rente. Als je geld op de Zwitserse bank zet, gaan er 5%<br />\r\ntransactiekosten af. Je kunt hier maximaal $100,000,000 storten.', '<strong>Swiss Bank</strong><br />\r\nNormally you lose your possessions and therefore also your money when you die. Do you have your money on the Swiss bank set, then you keep this money! So if you die, you can make a good start with the money you sent to your Swiss bank. However, you will not receive any interest here. If you put money in the Swiss bank you will pay some transaction costs. You can deposit up to $100,000,000 here.', 107, 1, 0);
INSERT INTO `helpsystem` VALUES (8, 'financial', '<strong>Financieel/Logboeken</strong><br />\r\nBij logboeken zie je de laatste 10 banktransacties. Bij financieel is een overzicht van je inkomsten te zien. Je kunt hier zien hoeveel je hoeren, je land en je bezittingen aan inkomsten opbrengen.', '<strong>Financial/Logs</strong><br />\r\nIn logs you see the last 10 bank transactions. Under financial you can see an overview of your income. You can here see how much income your whores, your land and your possessions bring in.', 44, 1, 0);
INSERT INTO `helpsystem` VALUES (9, 'bank-logs', '<strong>Financieel/Logboeken</strong><br />\r\nBij logboeken zie je de laatste 10 banktransacties. Bij financieel is een overzicht van je inkomsten te zien. Je kunt hier zien hoeveel je hoeren, je land en je bezittingen aan inkomsten opbrengen.', '<strong>Financial/Logs</strong><br />\r\nIn logs you see the last 10 bank transactions. Under financial you can see an overview of your income. You can here see how much income your whores, your land and your possessions bring in.', 4, 1, 0);
INSERT INTO `helpsystem` VALUES (10, 'prison', '<p>Hier zitten alle spelers die zijn opgepakt door de politie hun straf uit. Dit kan doordat een <em>misdaad</em>, het <em>stelen van een voertuig</em> of het <em>smokkelen</em> van goederen is mislukt. De tijd hoe lang je vastzit loopt af. Het bedrag dat afloopt is de afkoopsom die op dat moment moet worden neergelegd om jezelf of iemand anders uit te kopen. Als je niet vastzit in de gevangenis kan je proberen de spelers die wel vast zitten uit te breken. Bij een geslaagde uitbraakpoging ga je in rank omhoog en is de andere speler uit de gevangenis. Wanneer je poging mislukt wordt je zelf opgepakt en de tijd verlengd van de speler waarbij de uitbraakpoging is mislukt.<br />\r\n<br />\r\nDe gevangenis kan worden gekocht voor $1,000,000 wanneer deze van niemand is. Wanneer een speler zichzelf of een andere speler uitkoopt, gaat een deel van dit bedrag naar de eigenaar van de gevangenis. Er is maar één eigenaar van de gevangenis in het hele spel.</p>\r\n', 'Here all players who have been arrested by the police are serving their sentences. This is possible because of a <em>crime</em>, the <em>theft of a vehicle</em> or the <em>smuggling </em>of goods has failed. The time for how long you are stuck is running out. The amount that expires is the lump sum payment that must be deposited at that time to buy out yourself or someone else.<br />\r\nIf you are not stuck in prison you can try to break out the players that are stuck. With a successful breakout attempt, you go up in rank and the other player is out of jail. When your attempt fails you become yourself picked up and extended the time of the player whose breakout attempt failed.<br />\r\n<br />\r\nThe prison can be bought for $1,000,000 if it doesn\'t belong to anyone. When a player himself or another player buy out, part of this amount goes to the owner of the prison. There is only one owner of the prison throughout the game.', 82, 1, 0);
INSERT INTO `helpsystem` VALUES (11, 'famprison', '<p>Hier zitten alle familieleden die zijn opgepakt door de politie hun straf uit. Dit kan doordat een <em>misdaad</em>, het <em>stelen van een voertuig</em> of het <em>smokkelen </em>van <em>goederen</em> is mislukt. De tijd hoe lang je vastzit loopt af. Het bedrag dat afloopt is de afkoopsom die op dat moment moet worden neergelegd om jezelf of iemand anders uit te kopen. Als je niet vastzit in de gevangenis kan je proberen de spelers die wel vast zitten uit te breken. Bij een geslaagde uitbraakpoging ga je in rank omhoog en is de andere speler uit de gevangenis. Wanneer je poging mislukt wordt je zelf opgepakt en de tijd verlengd van de speler waarbij de uitbraakpoging is mislukt.<br />\r\n<br />\r\nDe gevangenis kan worden gekocht voor $1,000,000 wanneer deze van niemand is. Wanneer een speler zichzelf of een andere speler uitkoopt, gaat een deel van dit bedrag naar de eigenaar van de gevangenis. Er is maar één eigenaar van de gevangenis in het hele spel.</p>\r\n', 'All family members who have been arrested by the police are serving their sentences here. This is possible because of a <em>crime</em>, the <em>theft of a vehicle</em> or the <em>smuggling </em>of goods has failed. The time for how long you are stuck is running out.<br />\r\nThe amount that expires is the lump sum payment that must be deposited at that time to buy out yourself or someone else. If you are not stuck in prison you can try to break out the players that are stuck. With a successful breakout attempt, you go up in rank and the other player is out of jail. When your attempt fails you become yourself picked up and extended the time of the player whose breakout attempt failed.<br />\r\n<br />\r\nThe prison can be bought for $ 1,000,000 if it doesn\'t belong to anyone. When a player himself or another player buy out, part of this amount goes to the owner of the prison. There is only one owner of the prison throughout the game.', 40, 1, 0);
INSERT INTO `helpsystem` VALUES (12, 'honor-points', 'Eerpunten zijn waardevol en 1 van de weinige bezittingen die niet verloren gaan wanneer je word vermoord. Ben je opzoek naar meer Eerpunten? <a href=\"/game/family-crimes\"><span style=\"color:#3498db\"><strong>Familie misdaden</strong></span></a> kunnen je gegarandeerd aan eerpunten helpen, hoe meer deelnemers deelnemen aan de familie misdaad (max 23) hoe meer eerpunten (max 23) per succesvolle misdaad je kan verdienen.', 'Honor points are valuable and one of the few possessions that are not lost when you are killed. Are you looking for more Honor points? <a href=\"/game/family-crimes\"><span style=\"color:#3498db\"><strong>Family crimes</strong></span></a> are guaranteed to give you honor points, the more participants in the family crime (max 23) the more honor points (max 23) you can earn per successful crime.', 55, 1, 0);
INSERT INTO `helpsystem` VALUES (13, 'send-honor-points', 'Hier kan je vaste aantallen Eerpunten verzenden naar andere spelers. Alle eerpunten die je verzend zullen jouw geen score meer opleveren per uur, zie <a href=\"/game/status\"><span style=\"color:#3498db\"><strong>Status</strong></span></a>. Jouw verzonden Eerpunten zullen vanaf dan ieder uur score opleveren voor de ontvanger.', 'Here you can send fixed numbers of Honor points to other players. Any honor points you send will no longer gain you score each hour, see <a href=\"/game/status\"><span style=\"color:#3498db\"><strong>Status</strong></span></a>. Your sent Honor points will from then on gain score each hour for the recipient.', 88, 1, 0);
INSERT INTO `helpsystem` VALUES (14, 'travel-airplane', 'Hier kan je per vliegtuig naar een andere stad reizen. De reiskosten bedragen tot $1,500 per keer. De eigenaar van het vliegveld ontvangt dit bedrag. Wanneer het bureau geen eigenaar heeft kan deze gekocht worden voor $1,000,000.<br />\r\n<br />\r\nHet reizen naar een andere stad duurt 4 tot 190 seconden. Wanneer je smokkelwaar bij je draagt, is er een kans dat je wordt opgepakt door de douane. De douane zal dan je smokkelwaar innemen en je moet je straf in de gevangenis uitzitten!', 'Here you can travel by airplane to another city. Travel costs are up to $1,500 at a time. The owner of the airport receives this amount. If the agency has no owner, it can be purchased for $1,000,000.<br />\r\n<br />\r\nTraveling to another city takes 4 to 190 seconds. If you carry contraband with you, there is a chance that you will be picked up by customs. Border patrol will then confiscate your contraband and you will have to serve your sentence in jail!', 109, 1, 0);
INSERT INTO `helpsystem` VALUES (15, 'travel-train', 'Hier kan je per trein naar een andere stad reizen. De reiskosten bedragen tot $1,500 per keer. De eigenaar van het vliegveld ontvangt dit bedrag. Wanneer het bureau geen eigenaar heeft kan deze gekocht worden voor $1,000,000.<br />\r\n<br />\r\nHet reizen naar een andere stad duurt 4 tot 190 seconden. Wanneer je smokkelwaar bij je draagt, is er een kans dat je wordt opgepakt door de douane. De douane zal dan je smokkelwaar innemen en je moet je straf in de gevangenis uitzitten!', 'Here you can travel by train to another city. Travel costs are up to $1,500 at a time. The owner of the airport receives this amount. If the agency has no owner, it can be purchased for $1,000,000.<br />\r\n<br />\r\nTraveling to another city takes 4 to 190 seconds. If you carry contraband with you, there is a chance that you will be picked up by customs. Border patrol will then confiscate your contraband and you will have to serve your sentence in jail!', 111, 1, 0);
INSERT INTO `helpsystem` VALUES (16, 'travel-bus', 'Hier kan je per bus naar een andere stad reizen. De reiskosten bedragen tot $1,500 per keer. De eigenaar van het vliegveld ontvangt dit bedrag. Wanneer het bureau geen eigenaar heeft kan deze gekocht worden voor $1,000,000.<br />\r\n<br />\r\nHet reizen naar een andere stad duurt 4 tot 190 seconden. Wanneer je smokkelwaar bij je draagt, is er een kans dat je wordt opgepakt door de douane. De douane zal dan je smokkelwaar innemen en je moet je straf in de gevangenis uitzitten!', 'Here you can travel by bus to another city. Travel costs are up to $1,500 at a time. The owner of the airport receives this amount. If the agency has no owner, it can be purchased for $1,000,000.<br />\r\n<br />\r\nTraveling to another city takes 4 to 190 seconds. If you carry contraband with you, there is a chance that you will be picked up by customs. Border patrol will then confiscate your contraband and you will have to serve your sentence in jail!', 110, 1, 0);
INSERT INTO `helpsystem` VALUES (17, 'travel-vehicle', 'Hier kan je per voertuig naar een andere stad reizen. De reiskosten bedragen tot $1,500 per keer. De eigenaar van het vliegveld ontvangt dit bedrag. Wanneer het bureau geen eigenaar heeft kan deze gekocht worden voor $1,000,000.<br />\r\n<br />\r\nHet reizen naar een andere stad duurt 4 tot 190 seconden. Wanneer je smokkelwaar bij je draagt, is er een kans dat je wordt opgepakt door de douane. De douane zal dan je smokkelwaar innemen en je moet je straf in de gevangenis uitzitten!', 'Here you can travel by vehicle to another city. Travel costs are up to $1,500 at a time. The owner of the airport receives this amount. If the agency has no owner, it can be purchased for $1,000,000.<br />\r\n<br />\r\nTraveling to another city takes 4 to 190 seconds. If you carry contraband with you, there is a chance that you will be picked up by customs. Border patrol will then confiscate your contraband and you will have to serve your sentence in jail!', 112, 1, 0);
INSERT INTO `helpsystem` VALUES (18, 'market', 'Credits', '', 63, 0, 0);
INSERT INTO `helpsystem` VALUES (19, 'market-whores', 'Hoeren', '', 65, 0, 0);
INSERT INTO `helpsystem` VALUES (20, 'market-honor-points', 'Eerpunten', '', 64, 0, 0);
INSERT INTO `helpsystem` VALUES (21, 'stock-exchange', 'Beurs lijst', '', 102, 0, 0);
INSERT INTO `helpsystem` VALUES (22, 'stock-exchange-business', 'Bedrijf overzicht', '', 103, 0, 0);
INSERT INTO `helpsystem` VALUES (23, 'stock-exchange-news', 'Nieuws', '', 104, 0, 0);
INSERT INTO `helpsystem` VALUES (24, 'stock-exchange-portfolio', 'Portfolio', '', 105, 0, 0);
INSERT INTO `helpsystem` VALUES (25, 'equipment-stores', '<strong>Wapens</strong><br />\r\nHier kan je wapens kopen. Hoe beter het wapen is dat je wilt kopen, hoe duurder het wapen is en hoe meer wapenervaring je nodig hebt. De hoeveelheid wapenervaring die je nodig hebt voor de wapens is het gemiddelde van wapen ervaring en wapen training welke te vinden is bij <em>moorden</em>. Mocht je bijvoorbeeld een wapen training van 20% hebben, en een wapen ervaring van 0%, dan is je gemiddelde 10% en kan je dus een desert eagle kopen. Hoe beter je wapen, hoe minder kogels je moet gebruiken om een andere speler te vermoorden.<br />\r\n<br />\r\n<strong>Bescherming</strong><br />\r\nHier kan je bescherming kopen. Hoe beter de bescherming is die je hebt aangeschaft, hoe meer kogels een andere speler moet gebruiken om jou te vermoorden. Het kopen van bescherming maakt de kans groter dat je een moordpoging op jou overleeft.<br />\r\n<br />\r\n<strong>Vliegtuigen</strong><br />\r\nHier kun je vliegtuigen kopen die je nodig hebt om landjes te kunnen bombarderen bij <em>plattegrond</em>. Hoe beter je vliegtuig is, hoe groter de kans is dat je aanslag op een landje slaagt.<br />\r\n<br />\r\nWanneer je uitrusting koopt, zal er 25% van het aankoopbedrag naar de eigenaar van de uitrustingwinkel gaan. Als de<br />\r\nuitrustingwinkel geen eigenaar heeft, kan je deze kopen voor $1,000,000.', '<strong>Weapons</strong><br />\r\nHere you can buy weapons. The better the weapon you want to buy, the more expensive the weapon is and the more weapon experience you need. The amount of weapon experience you need for the weapons is the average of weapon experience and weapon training found in kills. For example, if you have a weapon training of 20%, and a weapon experience of 0%, then your average is 10% and you can buy a desert eagle. The better your weapon, the fewer bullets you have to use to kill another player.<br />\r\n<br />\r\n<strong>Protection</strong><br />\r\nHere you can buy protection. The better the protection you have purchased, the more bullets another player must use to kill you. Buying protection makes you more likely to survive an assassination attempt on you.<br />\r\n<br />\r\n<strong>Airplanes</strong><br />\r\nHere you can buy planes that you need to bomb countries at the map. The better your plane is, the greater the chance that your attack on a country will succeed.<br />\r\n<br />\r\nWhen you buy equipment, 25% of the purchase amount will go to the owner of the equipment store. As the equipment store does not have an owner, you can purchase it for $1,000,000.', 15, 1, 0);
INSERT INTO `helpsystem` VALUES (26, 'equipment-stores-protection', '<strong>Bescherming</strong><br />\r\nHier kan je bescherming kopen. Hoe beter de bescherming is die je hebt aangeschaft, hoe meer kogels een andere speler<br />\r\nmoet gebruiken om jou te vermoorden. Het kopen van bescherming maakt de kans groter dat je een moordpoging op jou<br />\r\noverleeft.', '<strong>Protection</strong><br />\r\nHere you can buy protection. The better the protection you have purchased, the more bullets another player must use to kill you. Buying protection makes you more likely to survive an assassination attempt on you.', 17, 1, 0);
INSERT INTO `helpsystem` VALUES (27, 'equipment-stores-airplanes', '<strong>Vliegtuigen</strong><br />\r\nHier kun je vliegtuigen kopen die je nodig hebt om landjes te kunnen bombarderen bij <em>plattegrond</em>. Hoe beter je vliegtuig is, hoe groter de kans is dat je aanslag op een landje slaagt.', '<strong>Airplanes</strong><br />\r\nHere you can buy planes that you need to bomb countries at the map. The better your plane is, the greater the chance that your attack on a country will succeed.', 16, 1, 0);
INSERT INTO `helpsystem` VALUES (28, 'estate-agency', '<strong>Makelaardij</strong><br />\r\nDoor het kopen van een woning ga je omhoog in rank. Let wel op dat je per woning maar 1 keer omhoog kan gaan in rank! Daarnaast zorgt een woning ervoor dat je minder snel dood gaat. Er moeten namelijk meer kogels op je worden geschoten als je een woning bezit. Hoe beter de woning, hoe meer kogels je moet schieten. De eigenaar van de makelaardij krijgt een deel van het bedrag als je een woning koopt. Je kunt je woning ook verkopen voor 60% van de aankoopprijs. Wanneer de makelaardij geen eigenaar heeft, kan deze gekocht worden voor $500,000.<br />\r\n ', '<strong>Estate Agency</strong><br />\r\nBy buying a home you go up in rank. Note that you can only go up in rank once per home! In addition, a home ensures that you die less quickly. More bullets have to be shot at you if you own a house. The better the house, the more bullets you have to shoot. The owner of the brokerage receives part of the amount when you buy a home. You can also sell your home for 60% of the purchase price. If the brokerage has no owner, it can be purchased for $500,000.', 18, 1, 0);
INSERT INTO `helpsystem` VALUES (29, 'bullet-factories', '<strong>Kogelfabrieken</strong><br />\r\nHier kan je kogels kopen. Kogels heb je nodig om iemand anders aan te vallen, wanneer je geen mes gebruikt. Ook is het handig om genoeg kogels te hebben als backfire. Je backfire, dit is het aantal kogels dat je terugschiet, kan je aanpassen bij <em>uitrusting</em>. De kogelprijs wordt bepaald door de eigenaar van de kogelfabriek. De kogelprijs kan minimaal $200 en maximaal $2,500.- zijn. Je kan alleen kogels kopen in de staat waar je op dat moment bent. Als je in een andere staat kogels wilt kopen, moet je er eerst naartoe <em>reizen</em>.<br />\r\n<br />\r\nDe eigenaar van de kogelfabriek moet kogels produceren. Elk heel uur (bijvoorbeeld: 12.00 uur, 13.00 uur etc.) loopt deze productie af. Bij productie zie je hoeveel er per uur wordt geproduceerd. Bij kogels staat het totaal aantal kogels dat de kogelfabriek op voorraad heeft.<br />\r\n<br />\r\nWanneer de kogelfabriek geen eigenaar heeft, kan deze gekocht worden voor $1,000,000.', '<strong>Bullet Factories</strong><br />\r\nHere you can buy bullets. You need bullets to attack someone else when you are not using a knife. It is also useful to have enough bullets as a backfire. Your backfire, this is the number of bullets you shoot back, can be adjusted in <em>equipment</em>. The bullet price is determined by the bullet factory owner. The bullet price can be a minimum of $200 and a maximum of $2,500.- You can only buy bullets in the state where you are at that moment. If you want to buy bullets in another state, you have to travel there first.<br />\r\n<br />\r\nThe owner of the bullet factory has to produce bullets. This production ends every hour on the hour (for example: 12:00 noon, 1:00 pm, etc.). In production you see how much is produced per hour. Bullets show the total number of bullets that the bullet factory has in stock.<br />\r\n<br />\r\nIf the bullet factory has no owner, it can be purchased for $1,000,000.', 6, 1, 0);
INSERT INTO `helpsystem` VALUES (30, 'hitlist', 'Als je een speler vermoordt die op de aanslagenlijst staat, ontvang je geld. De beloning geeft aan hoeveel geld je krijgt als je deze speler vermoordt. Bedenk wel dat je dit geld alleen krijgt als je niet doodgaat door de backfire van de andere speler. Spelers die op de aanslagenlijst staan kunnen ook worden uitgekocht voor het bedrag waarvoor deze op de lijst staan. Je kan ook zelf iemand op de aanslagenlijst zetten. Als je dit anoniem doet moet je 25% bij het bedrag betalen. Doe je dit niet anoniem, dan ziet iedereen dat jij een speler op de aanslagenlijst hebt gezet.', 'If you kill a player who is on the attack list, you will receive money. The reward indicates how much money you get if you kill this player. Keep in mind that you only get this money if you don\'t die from the other player\'s backfire. Players who are on the attack list can also be bought out for the amount for which they are on the list. You can also put someone on the attack list yourself. If you do this anonymously you have to pay 25% of the amount. If you do not do this anonymously, everyone will see that you have put a player on the attack list.', 54, 1, 0);
INSERT INTO `helpsystem` VALUES (31, 'murder', '<strong>Moorden</strong><br />\r\nHier kan je andere spelers vermoorden. Kogels om te vermoorden kan je kopen bij de <em>kogelfabriek</em> en een wapen kan je kopen bij <em>uitrusting</em>. Je kan alleen een speler vermoorden die maximaal 3 ranken hoger of lager is dan jij. Legendary dons kunnen alleen elkaar aanvallen. Het aantal kogels dat je moet schieten hangt af van de rank van de andere speler, welk wapen je gebruikt, welke bescherming de andere speler heeft en welke <em>woning</em> de andere speler bezit.<br />\r\n<br />\r\nJe kan ook iemand aanvallen met een mes, je krijgt hierdoor wel wapen ervaring, maar je zult met een mes nooit iemand in één keer kunnen vermoorden. Let er ook op dat je dood kunt gaan door de backfire van een andere speler!<br />\r\n<br />\r\nWanneer je een speler vermoord is deze al zijn bezittingen: hoeren, gebouwen, land en geld, kwijt. De eerpunten, het aantal kills en publieke missie aantal ben je dus niet kwijt als je dood gaat. Mocht je een speler vermoorden die een gebouw bezit dan neem je dit gebouw over, mits je niet doodgaat door zijn backfire.<br />\r\n<br />\r\n<strong>Backfire</strong><br />\r\nBackfire geeft aan hoeveel kogels je terug schiet als iemand jou aanvalt. Je kan een percentage van je kogels terugschieten of een vast aantal kogels. Ook kan je de optie kiezen waarbij je al je kogels terugschiet. Daarnaast kan je kiezen dat je evenveel kogels als je aanvaller gebruikt om terug te schieten, of de helft hiervan. Kogels kunnen worden gekocht bij <em>kogelfabriek</em>.<br />\r\n<br />\r\n<strong>Detective</strong><br />\r\nJe kunt ook een detective inhuren zodat deze de persoon zoekt voor jou. Het is overigens <strong>niet verplicht</strong> om een detective in te huren! Het duurt een half uur tot een uur voordat je je slachtoffer hebt gevonden. Als je wilt dat de detective je slachtoffer blijft volgen, dan moet je hem langer inhuren. Wil je deze speler vermoorden, dan moet je eerst naar de staat <em>reizen</em> waar hij op dat moment zit. Als lid kan je maximaal 1 detective inhuren, als donateur 3, als VIP 5, en als Gold member 10. Je kan dit worden in de <em>donatieshop</em>. De eigenaar van het detectivebureau verdient geld als er een detective wordt ingehuurd. Wanneer het detectivebureau geen eigenaar heeft, kan deze gekocht worden voor $1,000,000.<br />\r\n<br />\r\n<strong>WapenTraining/Logboeken</strong><br />\r\nBij logboeken kun je de laatste 10 aanvallen zien. Bij wapen training zie je hoe hoog je wapen ervaring en wapen training is. Wapen ervaring gaat omhoog door spelers aan te vallen. Wapen training kan je om de 5* minuten trainen.<br />\r\n<br />\r\n* Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 2,5 minuten.', '<strong>Murder</strong><br />\r\nHere you can kill other players. Bullets to kill can be bought at the bullet factory and a weapon can be bought at <em>equipment</em>. You can only kill a player who is a maximum of 3 ranks higher or lower than you. Legendary down can only attack each other. The number of bullets you have to shoot depends on the rank of the other player, which weapon you use, what protection the other player has and what house the other player owns.<br />\r\n<br />\r\nYou can also attack someone with a knife, this will give you weapon experience, but you will never be able to kill someone at once with a knife. Also be aware that you can die from another player\'s backfire!<br />\r\n<br />\r\nWhen you kill a player, he loses all his possessions: whores, buildings, land and money. So you don\'t lose the honor points, the number of kills and public mission amount when you die. If you kill a player who owns a building, you take over that building, provided you don\'t die from his backfire.<br />\r\n<br />\r\n<strong>Backfire</strong><br />\r\nBackfire indicates how many bullets you return when someone attacks you. You can return a percentage of your bullets or a fixed number of bullets. You can also choose the option where you shoot all your bullets back. In addition, you can choose to use as many bullets as your attacker to fire back, or half of them. Bullets can be purchased from <em>bullet factory</em>.<br />\r\n<br />\r\n<strong>Detective</strong><br />\r\nYou can also hire a detective to find the person for you. It is by the way <strong>not obligatory</strong>  to hire a detective! It takes half an hour to an hour to find your victim. If you want the detective to keep following your victim, you\'ll have to hire him longer. If you want to kill this player, you must first <em>travel</em> to the state where he is at that moment. As a member you can hire a maximum of 1 detective, as a donor 3 as a VIP 5, and as a Gold member 10. You can become this in the <em>donation shop</em>. The owner of the detective agency makes money when a detective is hired. If the detective agency has no owner, it can be bought for $1,000,000.<br />\r\n<br />\r\n<strong>WapenTraining/Logboeken</strong><br />\r\nIn logs you can see the last 10 attacks. With weapon training you can see how high your weapon experience and weapon training is. Weapon experience increases by attacking players. Weapon training can train you every 5* minutes.<br />\r\n<br />\r\n* For owners of a Fastpass (for sale in the <em>Donation Shop</em>) the waiting time is 2.5 minutes.', 69, 1, 0);
INSERT INTO `helpsystem` VALUES (32, 'murder-backfire', '<strong>Backfire</strong><br />\r\nBackfire geeft aan hoeveel kogels je terug schiet als iemand jou aanvalt. Je kan een percentage van je kogels terugschieten of een vast aantal kogels. Ook kan je de optie kiezen waarbij je al je kogels terugschiet. Daarnaast kan je kiezen dat je evenveel kogels als je aanvaller gebruikt om terug te schieten, of de helft hiervan. Kogels kunnen worden gekocht bij <em>kogelfabriek</em>.', '<strong>Backfire</strong><br />\r\nBackfire indicates how many bullets you return when someone attacks you. You can return a percentage of your bullets or a fixed number of bullets. You can also choose the option where you shoot all your bullets back. In addition, you can choose to use as many bullets as your attacker to fire back, or half of them. Bullets can be purchased from <em>bullet factory</em>.', 70, 1, 0);
INSERT INTO `helpsystem` VALUES (33, 'murder-detective', '<strong>Detective</strong><br />\r\nJe kunt ook een detective inhuren zodat deze de persoon zoekt voor jou. Het is overigens <strong>niet verplicht</strong> om een detective in te huren! Het duurt een half uur tot een uur voordat je je slachtoffer hebt gevonden. Als je wilt dat de detective je slachtoffer blijft volgen, dan moet je hem langer inhuren. Wil je deze speler vermoorden, dan moet je eerst naar de staat <em>reizen</em> waar hij op dat moment zit. Als lid kan je maximaal 1 detective inhuren, als donateur 3, als VIP 5, en als Gold member 10. Je kan dit worden in de <em>donatieshop</em>. De eigenaar van het detectivebureau verdient geld als er een detective wordt ingehuurd. Wanneer het detectivebureau geen eigenaar heeft, kan deze gekocht worden voor $1,000,000.', '<strong>Detective</strong><br />\r\nYou can also hire a detective to find the person for you. It is by the way <strong>not obligatory</strong>  to hire a detective! It takes half an hour to an hour to find your victim. If you want the detective to keep following your victim, you\'ll have to hire him longer. If you want to kill this player, you must first <em>travel</em> to the state where he is at that moment. As a member you can hire a maximum of 1 detective, as a donor 3 as a VIP 5, and as a Gold member 10. You can become this in the <em>donation shop</em>. The owner of the detective agency makes money when a detective is hired. If the detective agency has no owner, it can be bought for $1,000,000.', 71, 1, 0);
INSERT INTO `helpsystem` VALUES (34, 'murder-mercenaries', 'Huurlingen', '', 73, 1, 0);
INSERT INTO `helpsystem` VALUES (35, 'murder-weapon-training', '<strong>WapenTraining/Logboeken</strong><br />\r\nBij logboeken kun je de laatste 10 aanvallen zien. Bij wapen training zie je hoe hoog je wapen ervaring en wapen training is. Wapen ervaring gaat omhoog door spelers aan te vallen. Wapen training kan je om de 5* minuten trainen.<br />\r\n<br />\r\n* Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 2,5 minuten.', '<strong>WapenTraining/Logboeken</strong><br />\r\nIn logs you can see the last 10 attacks. With weapon training you can see how high your weapon experience and weapon training is. Weapon experience increases by attacking players. Weapon training can train you every 5* minutes.<br />\r\n<br />\r\n* For owners of a Fastpass (for sale in the <em>Donation Shop</em>) the waiting time is 2.5 minutes.', 74, 1, 0);
INSERT INTO `helpsystem` VALUES (36, 'murder-logs', '<strong>WapenTraining/Logboeken</strong><br />\r\nBij logboeken kun je de laatste 10 aanvallen zien. Bij wapen training zie je hoe hoog je wapen ervaring en wapen training is. Wapen ervaring gaat omhoog door spelers aan te vallen. Wapen training kan je om de 5* minuten trainen.<br />\r\n<br />\r\n* Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 2,5 minuten.', '<strong>WapenTraining/Logboeken</strong><br />\r\nIn logs you can see the last 10 attacks. With weapon training you can see how high your weapon experience and weapon training is. Weapon experience increases by attacking players. Weapon training can train you every 5* minutes.<br />\r\n<br />\r\n* For owners of a Fastpass (for sale in the <em>Donation Shop</em>) the waiting time is 2.5 minutes.', 72, 1, 0);
INSERT INTO `helpsystem` VALUES (37, 'hospital', 'Als je gewond bent geraakt omdat je iemand hebt beschoten of doordat je door iemand bent beschoten, kun je jezelf hier genezen! Daarnaast kan je hier spelers uit je familie die gewond zijn genezen.<br />\r\n<br />\r\nDe kosten voor het genezen zullen naar de eigenaar van het ziekenhuis gaan. Als het ziekenhuis geen eigenaar heeft, kan je deze kopen voor $1,000,000.', 'If you were injured because you shot at someone or because you were shot at by someone, you can heal yourself here! You can also heal injured players from your family here.<br />\r\n<br />\r\nThe cost of healing will go to the owner of the hospital. If the hospital doesn\'t have an owner, you can buy it for $1,000,000.', 56, 1, 0);
INSERT INTO `helpsystem` VALUES (38, 'red-light-district', '<strong>Hoeren Pimpen</strong><br />\r\nHier kan je hoeren pimpen door op pimpen te klikken, je zal dan een willekeurig aantal hoeren pimpen. Hoeren pimpen kan elke 5* minuten. Hoe hoger je rank, hoe meer hoeren je kan pimpen per keer. Hoeren op straat verdienen tussen de $10** en $20 per uur, dit verschilt per uur en wordt willekeurig berekend.<br />\r\n<br />\r\nAls je hoeren gepimpt hebt, kan je ze ook achter ramen zetten. Dan verdienen je hoeren tussen de $15** en $25 per uur, dit verschilt ook per uur en wordt ook willekeurig berekend.<br />\r\n<br />\r\n*Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 2,5 minuten.<br />\r\n** Gold Members krijgen gemiddeld $3 meer per hoer! Gold Member wordt je in de <em>donatieshop</em>.<br />\r\n<br />\r\n<strong>Ramen Kopen</strong><br />\r\nJe ziet hier ook het totaal aantal ramen van het Red Light District, hoeveel ramen er in gebruik zijn en hoeveel ramen er nog beschikbaar zijn. Als er geen ramen meer beschikbaar zijn moet de eigenaar nieuwe ramen kopen. Je kan dan naar een andere staat <em>reizen</em> en je hoeren ergens anders achter ramen zetten.<br />\r\n<br />\r\nJe ziet ook hoeveel hoeren jij achter ramen hebt staan per staat. Je kan hoeren per staat weghalen door de gewenste staat aan te vinken en dan de hoeveelheid hoeren die je wilt weghalen in te vullen en op weghalen te klikken.<br />\r\n<br />\r\nDe eigenaar van een Red Light District verdient geld als spelers hoeren achter een raam zetten. Hij verdient dan $10 per hoer. Wanneer de Red Light District geen eigenaar heeft kan deze gekocht worden voor $1,000,000.', '<strong>Pimp Hoes</strong><br />\r\nHere you can pimp hoes by clicking on pimp, you will then pimp any number of hoes. Pimping hoes can be done every 5* minutes. The higher your rank, the more whores you can pimp at a time. Hookers on the street earn between $10** and $20 per hour, this varies per hour and is calculated randomly.<br />\r\n<br />\r\nIf you pimped hoes, you can also put them behind windows. Then your hookers earn between $15** and $25 per hour, this also varies per hour and is also calculated randomly.<br />\r\n<br />\r\n*For owners of a Fastpass (for sale in the Donatieshop) the waiting time is 2.5 minutes.<br />\r\n** Gold Members get an average of $3 more per hooker! You become a Gold Member in the donation shop.<br />\r\n<br />\r\n<strong>Buy Windows</strong><br />\r\nYou can also see the total number of windows in the Red Light District, how many windows are in use and how many windows are still available. If there are no more windows available, the owner has to buy new windows. You can then travel to another state and put your hookers behind windows somewhere else.<br />\r\n<br />\r\nYou also see how many hookers you have behind windows per state. You can remove hookers per state by checking the desired state and then enter the amount of hookers you want to remove and click on remove.<br />\r\n<br />\r\nThe owner of a Red Light District earns money when players put hookers behind a window. He then earns $10 per hoe. If the Red Light District has no owner, it can be purchased for $1,000,000.', 86, 1, 0);
INSERT INTO `helpsystem` VALUES (39, 'buy-hooker-windows', '<strong>Ramen Kopen</strong><br />\r\nJe ziet hier ook het totaal aantal ramen van het Red Light District, hoeveel ramen er in gebruik zijn en hoeveel ramen er nog beschikbaar zijn. Als er geen ramen meer beschikbaar zijn moet de eigenaar nieuwe ramen kopen. Je kan dan naar een andere staat <em>reizen</em> en je hoeren ergens anders achter ramen zetten.<br />\r\n<br />\r\nJe ziet ook hoeveel hoeren jij achter ramen hebt staan per staat. Je kan hoeren per staat weghalen door de gewenste staat aan te vinken en dan de hoeveelheid hoeren die je wilt weghalen in te vullen en op weghalen te klikken.<br />\r\n<br />\r\nDe eigenaar van een Red Light District verdient geld als spelers hoeren achter een raam zetten. Hij verdient dan $10 per hoer. Wanneer de Red Light District geen eigenaar heeft kan deze gekocht worden voor $1,000,000.', '<strong>Buy Windows</strong><br />\r\nYou can also see the total number of windows in the Red Light District, how many windows are in use and how many windows are still available. If there are no more windows available, the owner has to buy new windows. You can then travel to another state and put your hookers behind windows somewhere else.<br />\r\n<br />\r\nYou also see how many hookers you have behind windows per state. You can remove hookers per state by checking the desired state and then enter the amount of hookers you want to remove and click on remove.<br />\r\n<br />\r\nThe owner of a Red Light District earns money when players put hookers behind a window. He then earns $10 per hoe. If the Red Light District has no owner, it can be purchased for $1,000,000.', 7, 1, 0);
INSERT INTO `helpsystem` VALUES (40, 'gym', 'Hier kan je trainen om vervolgens een wedstrijd te doen tegen andere spelers. De speler die het beste presteert zal de wedstrijd winnen. Je moet in dezelfde staat als waar de wedstrijd is gestart. Bij sportschool informatie kan je zien hoe vaak je gewonnen en verloren hebt, wat je sportschool kracht of cardio is en wat je totale winst is. Je kan ook zelf een wedstrijd tegen iemand starten.', 'Here you can train and then compete against other players. The player who performs the best will win the match. You must be in the same state as where the match started. At gym information you can see how often you have won and lost, what your gym strength or cardio is and what your total gain is. You can also start a match against someone yourself.', 53, 1, 0);
INSERT INTO `helpsystem` VALUES (41, 'ground-map', '<strong>Plattegrond</strong><br />\r\nHier zijn de plattegronden van alle staten weergegeven. Elk vierkantje is een stukje land. Het plaatje dat op een vierkant staat is het icon van een familie. Door op het vierkant te klikken zie je welke speler het land bezit, en welke gebouwen hij erop heeft gebouwd. Je kan het stukje land en de gebouwen die erop staan overnemen door het stukje land te bombarderen. Je moet hiervoor een vliegtuig kopen in de <em>uitrusting winkel</em>. Hoe beter je vliegtuig is en hoe meer bommen je gebruikt, hoe groter de kans is dat je aanslag slaagt. Je kan om de 15* minuten een aanslag plegen. Het maximum aantal landjes dat je kan beheren is 10. Er is een overzicht van de landjes die je in bezit hebt bij <em>bezittingen.</em> Daarnaast kan je gebouwen kopen op je landje, deze gebouwen zullen inkomsten genereren.<br />\r\n<br />\r\n*Voor eigenaren van een Fastpass (te koop in de Donatieshop) is de wachttijd 7,5 minuten.', '<strong>Ground Map</strong><br />\r\nHere are the maps of all states. Each square is a piece of land. The picture on a square is the icon of a family. By clicking on the square you can see which player owns the land, and which buildings he has built on it. You can take over the piece of land and the buildings on it by bombing the piece of land. You need to buy a plane from the equipment shop for this. The better your plane is and the more bombs you use, the more likely your attack is successful. You can make an attack every 15* minutes. The maximum number of lands that you can manage is 10. There is an overview of the lands that you have in possessions. In addition, you can buy buildings on your land, these buildings will generate income.<br />\r\n<br />\r\n*For owners of a Fastpass (for sale in the Donatieshop) the waiting time is 7.5 minutes.', 52, 1, 0);
INSERT INTO `helpsystem` VALUES (42, 'missions', 'Hier kun je ronde missies en publieke missies doen.<br />\r\n<br />\r\n<strong>Missies</strong><br />\r\nRonde missies duren 1 ronde lang. Je krijgt beloningen als je deze missies behaald. De missies verhogen steeds. (Bijvoorbeeld: auto\'s stelen moet je level 100 hebben voor je de missie helemaal hebt uitgespeeld!) De beloningen die je krijgt, staan er bij vermeld.<br />\r\n<br />\r\n<strong>Publieke Missie</strong><br />\r\nPublieke missies zijn missies die 1 uur duren (Bijvoorbeeld van 13:00 - 14:00 of 18:00 - 19:00). Er zijn verschillende missies. Wanneer jij in de top-10 staat, krijg jij een beloning. Hoe hoog deze beloning is, kun je ook zien. Let op: om mee te doen aan de publieke missie van zaterdag moet je minimaal Donateur zijn; om mee te doen aan de publieke missie van zondag moet je VIP zijn.', 'Here you can do round missions and public missions.<br />\r\n<br />\r\n<strong>Missions</strong><br />\r\nRound missions last for 1 round. You get rewards when you complete these missions. The missions keep increasing. (Example: Stealing cars requires you to reach level 100 before you complete the mission!) The rewards you get are listed there.<br />\r\n<br />\r\n<strong>Public Mission</strong><br />\r\nPublic missions are missions that last 1 hour (for example from 13:00 - 14:00 or 18:00 - 19:00). There are several missions. When you are in the top 10, you get a reward. You can also see how high this reward is. Please note: to participate in Saturday\'s public mission you must be at least a Donor; to participate in Sunday\'s public mission you must be VIP.', 67, 1, 0);
INSERT INTO `helpsystem` VALUES (43, 'missions-public-mission', '<strong>Publieke Missie</strong><br />\r\nPublieke missies zijn missies die 1 uur duren (Bijvoorbeeld van 13:00 - 14:00 of 18:00 - 19:00). Er zijn verschillende missies. Wanneer jij in de top-10 staat, krijg jij een beloning. Hoe hoog deze beloning is, kun je ook zien. Let op: om mee te doen aan de publieke missie van zaterdag moet je minimaal Donateur zijn; om mee te doen aan de publieke missie van zondag moet je VIP zijn.', '<strong>Public Mission</strong><br />\r\nPublic missions are missions that last 1 hour (for example from 13:00 - 14:00 or 18:00 - 19:00). There are several missions. When you are in the top 10, you get a reward. You can also see how high this reward is. Please note: to participate in Saturday\'s public mission you must be at least a Donor; to participate in Sunday\'s public mission you must be VIP.', 68, 1, 0);
INSERT INTO `helpsystem` VALUES (44, 'daily-challenges', '', '', 10, 0, 0);
INSERT INTO `helpsystem` VALUES (45, 'possessions', 'Hier is zowel een overzicht te zien van alle bezittingen per staat als van je eigen bezittingen. Je kan in het balkje de staat selecteren waar je de bezittingen van wilt weergeven. Onder elke bezitting staat de eigenaar. Je kan bezittingen op verschillende manieren verkrijgen. Je kan ze kopen als niemand hem beheert. Je moet hiervoor wel in de staat zijn waar de bezitting is, en je moet dan naar de pagina van de desbetreffende bezitting gaan. Ook kan je een bezitting op de <em>markt</em> kopen. Daarnaast kan je een beheerder van een bezitting proberen te vermoorden bij <em>moorden</em>. Let wel op dat je zijn backfire moet overleven om de bezitting daadwerkelijk over te nemen. Ten slotte kunnen bezittingen ook naar elkaar verzonden worden.<br />\r\n<br />\r\nBij beheer zie je je eigen bezittingen en de landen die je bezit. Om een landje te krijgen moet je naar <em>plattegrond</em> gaan en daar de help lezen. Als je al een landje hebt kan je via de knop bekijken direct naar een landje gaan en eventueel gebouwen kopen die inkomsten genereren. Bij bezittingen beheren zijn veel opties mogelijk. Zo kan je als je een kogelfabriek beheert je productie en kogelprijs aanpassen, als je een Red Light District beheert kan je het aantal ramen uitbreiden en mocht je een casino in beheer hebben dan kan je de maximale inzet instellen. Ook kan je hier je bezitting wegdoen of weggeven aan een andere speler. Ten slotte zie je ook hoeveel winst je bezitting totaal en per uur maakt.<br />\r\n<br />\r\nLet op: elke familie kan maximaal twee van elk soort bezitting bezitten! Als bijvoorbeeld twee spelers in je familie allebei een Roulette bezitten, kan jij geen derde roulette beheren.<br />\r\n<br />\r\nLet op: Bezittingen in Hawaii kunnen alleen beheert worden door Gold Members! Als je een detectivebureau wilt beheren moet je donateur zijn. Om de loterij te beheren moet je V.I.P. zijn. Als je de gevangenis wilt beheren moet je Gold Member zijn. Donateur, V.I.P. en Gold Member wordt je in de <em>donatieshop</em>.', 'Here you can see an overview of all assets per state as well as your own assets. In the bar you can select the state where you want to display the assets. Under each property is the owner. You can acquire assets in several ways. You can buy them if no one manages them. To do this, you must be in the state where the asset is, and you must then go to the page of the relevant asset. You can also buy a property on the market. In addition, you can try to kill an asset manager in murder. Note that you have to survive his backfire to actually take over the possession. Finally, possessions can also be sent to each other.<br />\r\n<br />\r\nIn management you see your own assets and the countries you own. To get a land you have to go to map and read the help there. If you already have a country, you can go directly to a country via the view button and possibly buy buildings that generate income. There are many options for managing assets. For example, if you manage a bullet factory you can adjust your production and bullet price, if you manage a Red Light District you can expand the number of windows and if you manage a casino you can set the maximum bet. You can also dispose of your property here or give it away to another player. Finally, you also see how much profit your property makes in total and per hour.<br />\r\n<br />\r\nNote: Each family can own up to two of each asset type! For example, if two players in your family both own a Roulette, you cannot manage a third roulette.<br />\r\n<br />\r\nNote: Properties in Hawaii can only be managed by Gold Members! If you want to run a detective agency you have to be a donor. To manage the lottery you must V.I.P. If you want to manage the prison you must be a Gold Member. Donor, V.I.P. and you become a Gold Member in the donation shop.', 81, 1, 0);
INSERT INTO `helpsystem` VALUES (46, 'donation-shop', '<strong>Donateur</strong><br />\r\n<br />\r\n- Een gele naam!<br />\r\n- 5% korting op alle voertuigen in alle winkels!<br />\r\n- <s>1,000,000</s> 2,500,000 max. stocks bij de beurs!<br />\r\n- <s>10</s> 15 vrienden / blokkeren!<br />\r\n- <s>1,000</s> 1,500 tekens in je profiel!<br />\r\n- <s>5%</s> 4% transactiekosten bij de bank!<br />\r\n- <s>5</s> 7 units kunnen produceren bij drugslab/drankbrouwerij!<br />\r\n- Ieder weekend 25% minder wachttijden behalve Gevangenis & Reizen!<br />\r\n- <s>5</s> 7 lucky boxen kunnen dragen!<br />\r\n<br />\r\n<strong>VIP</strong><br />\r\n<br />\r\n- Een blauwe naam en alle donateur voordelen!<br />\r\n- 5% korting in alle uitrusting winkels!<br />\r\n- <s>1,000,000</s> 5,000,000 max. stocks bij de beurs!<br />\r\n- <s>10</s> 20 vrienden / blokkeren!<br />\r\n- <s>1,000</s> 2,000 tekens in je profiel!<br />\r\n- <s>5%</s> 3% transactiekosten bij de bank!<br />\r\n- <s>5</s> 15 units kunnen produceren bij drugslab/drankbrouwerij!<br />\r\n- Toegang tot 4 extra misdaden!<br />\r\n- Toegang tot het VIP forum!<br />\r\n<s>- Muziek in je profiel!</s><br />\r\n- <s>3</s> 5 detectives inhuren!<br />\r\n- <s>5</s> 10 lucky boxen kunnen dragen!<br />\r\n<br />\r\n<strong>Gold Member</strong><br />\r\n<br />\r\n- Een gouden naam en alle donateur/V.I.P. voordelen!<br />\r\n- Hoeren verdienen gemiddeld $3 per hoer meer!<br />\r\n- 10% korting in alle makelaardijen!<br />\r\n- <s>1,000</s> 3,000 tekens in je profiel!<br />\r\n- <s>5</s> 20 units kunnen produceren bij drugslab/drankbrouwerij!<br />\r\n- Toegang tot 9 extra misdaden!<br />\r\n- Hogere smokkel prijzen dus meer winst in Honolulu!<br />\r\n- Ieder weekend 50% minder wachttijden behalve Gevangenis & Reizen!<br />\r\n- <s>3</s> 10 detectives inhuren!<br />\r\n- <s>5</s> 15 lucky boxen kunnen dragen!<br />\r\n<br />\r\nIeder weekend minder wachten vanaf Vrijdag 14:00 t.e.m. Maandag 14:00<br />\r\n<br />\r\n<strong>VIP familie</strong><br />\r\n<br />\r\n- Een blauwe familienaam!<br />\r\n- <s>1%</s> 2% rente!<br />\r\n- <s>1,000</s> 2,000 tekens in het familie profiel!<br />\r\n- Forum Mod instellen!<br />\r\n<s>- Extra familie missie Own the World!</s><br />\r\n- Familie zal nooit uitsterven!<br />\r\n<br />\r\n<small>Alle donatie statussen zijn 1 ronde geldig op het account of de familie waarop het is aangekocht. Deze kunnen niet naar andere accounts of families overgedragen worden.</small>', '<strong>Donator</strong><br />\r\n<br />\r\n- A yellow name<br />\r\n- 5% discount on all vehicles in all shops!<br />\r\n- <s>1,000,000</s> 2,500,000 max. stocks at the stock exchange!<br />\r\n- <s>10</s> 15 friends in your friendslist!<br />\r\n- <s>1,000</s> 1,500 characters in your profile!<br />\r\n- <s>5%</s> 4% transaction costs at the bank!<br />\r\n- <s>5</s> 7 units to produce in your drugslab/liquids brewery!<br />\r\n- Every weekend 25% less waiting times except Prison & Travel!<br />\r\n- <s>5</s> 7 lucky boxes holding capacity!<br />\r\n<br />\r\n<strong>VIP</strong><br />\r\n<br />\r\n- A blue name and all Donator benefits!<br />\r\n- 5% discount in all equipment stores!<br />\r\n- <s>1,000,000</s> 5,000,000 max. stocks at the stock exchange!<br />\r\n- <s>10</s> 20 friends in your friendslist!<br />\r\n- <s>1,000</s> 2,000 characters in your profile!<br />\r\n- <s>5%</s> 3% transaction costs at the bank!<br />\r\n- <s>5</s> 15 units to produce in your drugslab/liquids brewery!<br />\r\n- Access to 4 extra crimes!<br />\r\n- Access to the VIP forum!<br />\r\n<s>- Music in your profile!</s><br />\r\n- <s>3</s> 5 detectives to hire!<br />\r\n- <s>5</s> 10 lucky boxes holding capacity!<br />\r\n<br />\r\n<strong>Gold Member</strong><br />\r\n<br />\r\n- A golden name and all Donator/VIP benefits!<br />\r\n- Hoes earn an average of $3 each hoe more!<br />\r\n- 10% discount in all estate agencies!<br />\r\n- <s>1,000</s> 3,000 characters in your profile!<br />\r\n- <s>5</s> 20 units to produce in your drugslab/liquids brewery!<br />\r\n- Access to 9 extra crimes!<br />\r\n- Higher smuggle prices equals more profit in Honolulu!<br />\r\n- Every weekend 50% less waiting times except Prison & Travel!<br />\r\n- <s>3</s> 10 detectives to hire!<br />\r\n- <s>5</s> 15 lucky boxes holding capacity!<br />\r\n<br />\r\nEvery weekend less waiting times from Friday 2:00PM to Monday 2:00PM<br />\r\n<br />\r\n<strong>VIP family</strong><br />\r\n<br />\r\n- A blue family name!<br />\r\n- <s>1%</s> 2% interest!<br />\r\n- <s>1,000</s> 2,000 characters in the family profile!<br />\r\n- Appoint a Forum Mod!<br />\r\n<s>- Extra family mission Own the World!</s><br />\r\n- Family will never die out!<br />\r\n<br />\r\n<small>All donation statuses are 1 round valid on the account or family it was purchased on. These cannot be transferred to other accounts or families.</small>', 12, 1, 0);
INSERT INTO `helpsystem` VALUES (47, 'poll', 'Hier kan je jouw mening geven over stellingen die betrekking hebben op het spel. Via het pollsysteem wordt om jouw mening gevraagd. Deze mening wordt vaak gebruikt bij het maken van beslissingen door de eigenaars van het spel. Ook is hier de poll geschiedenis te zien. Je kan hier de resultaten van vorige polls bekijken.', 'Here you can give your opinion on statements related to the game. The poll system asks for your opinion. This opinion is often used when making decisions by the owners of the game. The poll history can also be seen here. You can view the results of previous polls here.', 79, 1, 0);
INSERT INTO `helpsystem` VALUES (48, 'poll-history', 'Hier kan je jouw mening geven over stellingen die betrekking hebben op het spel. Via het pollsysteem wordt om jouw mening gevraagd. Deze mening wordt vaak gebruikt bij het maken van beslissingen door de eigenaars van het spel. Ook is hier de poll geschiedenis te zien. Je kan hier de resultaten van vorige polls bekijken.', 'Here you can give your opinion on statements related to the game. The poll system asks for your opinion. This opinion is often used when making decisions by the owners of the game. The poll history can also be seen here. You can view the results of previous polls here.', 80, 1, 0);
INSERT INTO `helpsystem` VALUES (49, 'game-forum', 'In-game forum', '', 46, 0, 0);
INSERT INTO `helpsystem` VALUES (50, 'game-forum-cat', 'In-game forum categorie', '', 47, 0, 0);
INSERT INTO `helpsystem` VALUES (51, 'game-forum-cat-topic', 'In-game forum topic', '', 48, 0, 0);
INSERT INTO `helpsystem` VALUES (52, 'shoutbox', '<strong>Shoutbox</strong><br />\r\nHier kan je korte stukjes tekst achterlaten. Je kan hier chatten met de rest van de spelers, discussies voeren en reclame maken zolang het betrekking heeft op spelopties (bijvoorbeeld leden voor je <em>familie</em> zoeken of iets dat je op de <em>markt</em> hebt geplaatst aanprijzen). Zorg ervoor dat je de regels van de shoutbox goed hebt doorgelezen zodat je niet voor verassingen komt te staan! De regels zijn te vinden onder shoutbox. Bij overtreding van een van deze regels kan je gewaarschuwd of verbannen worden. Als je zelf een overtreding ziet kan je het bericht melden door op het knopje meld te klikken. Misbruik van het meldsysteem zal bestraft worden.<br />\r\n<br />\r\nJe moet steeds wachten totdat je een berichtje kan plaatsen, dit om spammen op de shoutbox te voorkomen. Een gewoon lid moet 2 minuten wachten, donateurs 1.5minuut, V.I.P\'s 1 minuut en Gold Members 30 seconden. Je kan dit worden in de <em>donatieshop</em>.', '<strong>Shoutbox</strong><br />\r\nHere you can leave short pieces of text. You can chat with the rest of the players, discuss and advertise here as long as it relates to game options (for example, find members for your family or promote something you\'ve placed on the <em>market</em>). Make sure you\'ve read the rules of the shoutbox carefully so you don\'t get any surprises! The rules can be found under shoutbox. Violation of any of these rules may result in a warning or ban. If you see a violation yourself, you can report the message by clicking the report button. Abuse of the reporting system will be punished.<br />\r\n<br />\r\nYou always have to wait until you can post a message, this to prevent spamming on the shoutbox. A regular member has to wait 2 minutes, donors 1.5 minutes, VIPs 1 minute and Gold Members 30 seconds. You can become this in the <em>donation shop</em>.', 90, 1, 0);
INSERT INTO `helpsystem` VALUES (53, 'soccer-betting', '', '', 99, 1, 0);
INSERT INTO `helpsystem` VALUES (54, 'fifty-games', 'Contant', '', 41, 0, 0);
INSERT INTO `helpsystem` VALUES (55, 'fifty-games-whores', 'Hoeren', '', 43, 0, 0);
INSERT INTO `helpsystem` VALUES (56, 'fifty-games-honor-points', 'Eerpunten', '', 42, 0, 0);
INSERT INTO `helpsystem` VALUES (57, 'dobbling', '<strong>Dobbelen</strong><br />\r\nBij dobbelen gaat het erom wie een hoger getal gooit, jij of de eigenaar van de dobbeltafel. De eigenaar kan de maximale inzet bepalen. De maximale inzet die de eigenaar kan bepalen is $500,000. de minimale inzet is $100. Wanneer jij hoger gooit dan je tegenstander win je de inzet van de tegenstander en krijg je je eigen inzet terug. Als je lager gooit dan je tegenstander raak je je inzet kwijt. Je kunt ook gelijkspelen en dan krijg je alleen je inzet terug.<br />\r\n<br />\r\nAls de dobbeltafel geen eigenaar heeft, kun je deze kopen voor $500,000. Daarnaast kun je de eigenaar van de dobbeltafel blut spelen. Dit gebeurt als de eigenaar niet meer genoeg geld op zijn <em>bank</em> heeft om je uit te betalen. Als je de eigenaar blut speelt, krijg jij de dobbeltafel in je bezit!', '<strong>Dobbling</strong><br />\r\nThe dice roll is all about who rolls a higher number, you or the owner of the dice table. The owner can determine the maximum bet. The maximum bet the owner can set is $500,000. the minimum bet is $100. When you roll higher than your opponent, you win the opponent\'s bet and get your own bet back. If you roll lower than your opponent, you lose your bet. You can also draw and then you only get your bet back.<br />\r\n<br />\r\nIf the dice table has no owner, you can buy it for $500,000. In addition, you can play the owner of the dice table broke. This happens when the owner no longer has enough money in his bank to pay you. If you play the owner broke, you get the dice table!', 11, 1, 0);
INSERT INTO `helpsystem` VALUES (58, 'racetrack', 'Hier kun je inzetten op een racepaard die een kleur draagt, waarvan jij verwacht dat die gaat winnen. In het kleurvakje staat hoe groot de kans is dat je wint en hoe vaak je je inzet terug wint. Als je bijvoorbeeld op rood inzet dan is de kans dat je wint 1 op de 4. Mocht je ook daadwerkelijk winnen, dan krijg je je inzet 4 keer terug (bijvoorbeeld: je zet $100 in, je wint dan $400). De eigenaar van de racetrack bepaalt de maximale inzet. De maximale inzet die de eigenaar kan bepalen is $500,000; de minimale inzet is $100.<br />\r\n<br />\r\nAls de racetrack geen eigenaar heeft, kun je deze kopen voor $500,000. Daarnaast kan je de eigenaar van de racetrack blut spelen. Dit gebeurt als de eigenaar niet meer genoeg geld op zijn <em>bank</em> heeft om je uit te betalen. Als je de eigenaar blut speelt, krijg jij de racetrack in je bezit!', 'Here you can bet on a racehorse that wears a color that you expect to win. The color box shows how big the chance is that you will win and how often you win back your bet. For example, if you bet on red, the chance that you will win is 1 in 4. If you actually win, you get your bet back 4 times (for example: you bet $100, you win $400). The owner of the racetrack determines the maximum bet. The maximum bet the owner can set is $500,000; the minimum bet is $100.<br />\r\n<br />\r\nIf the racetrack has no owner, you can buy it for $500,000. In addition, you can play the racetrack owner broke. This happens when the owner no longer has enough money in his bank to pay you. If you play the owner broke, you get the racetrack!', 84, 1, 0);
INSERT INTO `helpsystem` VALUES (59, 'roulette', 'Bij roulette kun je op verschillende manieren geld inzetten. Je kunt je geld inzetten op een kleur: als je de kleur goed hebt, verdubbelt je inzet. Wanneer de kleur niet jouw gekozen kleur is, verlies je je inzet. Hetzelfde geldt voor inzetten op even of oneven nummers. Je kunt ook inzetten op een specifiek nummer. Als de roulette op jouw nummer valt, win je 36 keer je inzet terug. De kolommen en de nummer groepen (bijvoorbeeld 1-18 en 1-12) zijn opties die ertussen liggen. Het aantal keer dat je je inzet terugwint hangt ervan af hoe groot de kans is dat je wint.<br />\r\n<br />\r\nDe eigenaar kan de maximale inzet bepalen. De maximale inzet die de eigenaar kan bepalen is $500,000; de minimale inzet is $100. Als de roulette geen eigenaar heeft, kun je deze kopen voor $500,000. Daarnaast kan je de eigenaar van roulette blut spelen. Dit gebeurt als de eigenaar niet meer genoeg geld op zijn <em>bank</em> heeft om je uit te betalen. Als je de eigenaar blut speelt, krijg jij de roulette in je bezit!', 'You can bet money in different ways in roulette. You can bet your money on a suit: if you get the suit right, your bet doubles. If the color is not your chosen color, you lose your bet. The same goes for bets on even or odd numbers. You can also bet on a specific number. If the roulette lands on your number, you will win back 36 times your stake. The columns and number groups (for example, 1-18 and 1-12) are options that lie in between. The number of times you win back your bet depends on how likely you are to win.<br />\r\n<br />\r\nThe owner can determine the maximum bet. The maximum bet the owner can set is $500,000; the minimum bet is $100. If the roulette has no owner, you can buy it for $500,000. In addition, you can play the owner of roulette broke. This happens when the owner no longer has enough money in his bank to pay you. If you play the owner broke, you get the roulette!', 87, 1, 0);
INSERT INTO `helpsystem` VALUES (60, 'slot-machine', '', '', 91, 0, 0)
INSERT INTO `helpsystem` VALUES (61, 'blackjack', '<strong>Blackjack</strong><br />\r\nHet doel van het spel is om de eigenaar van de blackjack te verslaan. Hierbij moet je proberen zo dicht mogelijk bij - of op de 21 punten te komen. Wanneer het lukt dichter bij de 21 te komen dan de eigenaar van de blackjack en onder de 21 te blijven win je het spel. Als het totaal van de kaarten van de eigenaar van de blackjack dichter de 21 nadert dan jouw kaarten, verlies je. Als je boven de 21 punten uitkomt verlies je je inzet sowieso, behalve als de eigenaar van de blackjack de 21 punten nog verder overschrijdt. Als je precies 21 hebt heb je een blackjack, je wint dan 4 keer je inzet (min inzet) terug.<br />\r\n<br />\r\nKaarten met afbeeldingen (Boer, Vrouw, Heer) zijn 10 punten waard. De kaarten 2 - 10 hebben de waarde die zij aangeven. De aas is of 1 of 11 punten waard (bepalend is welke waarde het dichtst de 21 benadert, maar daar niet overheen gaat). Als de dealer evenveel heeft als de speler is het een gelijkspel.<br />\r\n<br />\r\nDe eigenaar kan de maximale inzet bepalen. De maximale inzet die de eigenaar kan bepalen is $500,000, de minimale inzet is $100. Als de blackjack geen eigenaar heeft, kun je deze kopen voor $500,000. Daarnaast kan je de eigenaar van de blackjack blut spelen. Dit gebeurt als de eigenaar niet meer genoeg geld op zijn <em>bank</em> heeft om je uit te betalen. Als je de eigenaar blut speelt, krijg jij de blackjack in je bezit!', '<strong>Blackjack</strong><br />\r\nThe object of the game is to beat the owner of the blackjack. You should try to get as close as possible to - or get to 21 points. When it manages to get closer to 21 than the owner of the blackjack and under 21 to stay you win the game. If the total of the blackjack owner\'s cards is closer to 21 than yours cards, you lose. If you go above 21 points, you lose your bet anyway, unless the owner of the blackjack exceeds 21 points even further. If you have exactly 21 you have a blackjack, you win 4 times (minus stake) your bet back.<br />\r\n<br />\r\nImage cards (Jack, Queen, King) are worth 10 points. Cards 2 - 10 have the value they indicate. The ace is worth either 1 or 11 points (whichever is closest to 21, but not exceeding it, determines). If the dealer has the same amount as the player, it is a tie.<br />\r\n<br />\r\nThe owner can determine the maximum bet. The maximum bet that the owner can set is $500,000, the minimum bet is $100. If the blackjack has no owner, you can buy it for $500,000. In addition, you can own the playing blackjack broke. This happens when the owner no longer has enough money in his bank to pay you. If you play the owner broke, you get the blackjack!', 5, 1, 0);
INSERT INTO `helpsystem` VALUES (62, 'lottery', 'Je kan hier elke dag één lot kopen voor $150,000. Je maakt dan drie keer kans om een prijs te winnen. De waarde van de prijzen zijn gegeven. Ook is hier te zien wie de winnaars van de vorige dag zijn. De trekking van de loterij vindt dagelijks om 19:00 plaats behalve op Vrijdag.<br />\r\n<br />\r\nAls de loterij geen eigenaar heeft kun je deze kopen voor $500,000. Hoewel er van de meeste <em>bezittingen</em> één per staat is, is er maar één loterij voor het hele spel.', 'You can buy one ticket here every day for $150,000. You then have three times the chance to win a prize. The value of the prizes are given. Here you can also see who the winners of the previous day are. The lottery draw takes place daily at 07:00 pm. servertime.<br />\r\n<br />\r\nIf the lottery has no owner, you can buy it for $500,000. While there is one per state for most assets, there is only one lottery for the entire game.', 61, 1, 0);
INSERT INTO `helpsystem` VALUES (63, 'lottery-week', 'Je kan hier elke dag één lot kopen voor $300,000. Je maakt dan negen keer kans om een prijs te winnen. De waarde van de prijzen zijn gegeven. Ook is hier te zien wie de winnaars van de vorige dag zijn. De trekking van de loterij vindt wekelijks op Vrijdag om 19:00 plaats.<br />\r\n<br />\r\nAls de loterij geen eigenaar heeft kun je deze kopen voor $500,000. Hoewel er van de meeste <em>bezittingen</em> één per staat is, is er maar één loterij voor het hele spel.', 'You can buy one ticket here every day for $300,000. You then have a nine times chance to win a prize. The value of the prizes are given. Here you can also see who the winners of the previous day are. The lottery draw takes place every week on Friday at 07:00 pm. servertime except on Friday.<br />\r\n<br />\r\nIf the lottery has no owner, you can buy it for $500,000. While there is one per state for most assets, there is only one lottery for the entire game.', 62, 1, 0);
INSERT INTO `helpsystem` VALUES (64, 'profile', 'Op je profiel kun je veel informatie zien van andere spelers, maar ook van jou. De linkerkolom weergeeft het aantal views dat de speler heeft, de naam en avatar. Ook zie je hier de vrienden van de speler.<br />\r\n<br />\r\nDe middelste kolom weergeeft de statistieken van een speler. Je kunt hier de naam zien en je ziet hoeveel geld, eerpunten, referrals, kills en hoeveel leven deze speler heeft. Daarnaast zie je in welke familie de speler zit en welke rank de speler is.<br />\r\n<br />\r\nIn de rechterkolom zie je de bezittingen, ronde missies en levels van de speler. Ook kun je daaronder een bericht versturen en de speler aanvallen.<br />\r\n<br />\r\nVerderop het profiel kun je bij sommige spelers één of meerdere videoclips zien. Dit ligt eraan of de speler een clip heeft ingesteld (dit kan pas vanaf de status V.I.P.).<br />\r\n<br />\r\nAls laatste zie je dan het profiel. Hierop kan iedere speler tekst zetten.', 'On your profile you can see a lot of information from other players, but also from you. The left column shows the number of views the player has, name and avatar. You can also see the player\'s friends here.<br />\r\n<br />\r\nThe middle column shows a player\'s stats. You can see the name here and see how much money, honor points, referrals, kills and how much life this player has. In addition, you can see which family the player is in and which rank the player is.<br />\r\n<br />\r\nIn the right column you can see the player\'s possessions, round missions and levels. You can also send a message below and attack the player.<br />\r\n<br />\r\nFurther down the profile you can see one or more video clips for some players. This depends on whether the player has set a clip (this is only possible from the status V.I.P.).<br />\r\n<br />\r\nFinally, you will see the profile. Any player can put text on it.', 83, 1, 0);
INSERT INTO `helpsystem` VALUES (65, 'crimes', '<strong>Misdaden</strong><br />\r\nHier kun je misdaden plegen. Je misdaad kan slagen, je krijgt dan geld en gaat omhoog in rank. Je misdaad kan daarentegen ook mislukken. Als je misdaad mislukt zijn er twee mogelijkheden: je kan ontsnappen of je wordt opgepakt door de politie en moet je straf uitzitten in de gevangenis. Iedere misdaad heeft een wachttijd van 90* seconden.<br />\r\n<br />\r\nAls je wordt opgepakt door de politie zal je 90 seconden in de gevangenis moeten wachten, tenzij iemand je uitbreekt of uitkoopt. Naarmate je level stijgt, zullen ook je inkomsten stijgen van de gelukte misdaad. Het maximaal te behalen level is 100.<br />\r\n<br />\r\n*Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 45 seconden.', '<strong>Crimes</strong><br />\r\nHere you can commit crimes. Your crime can succeed, you will get money and go up in rank. Your crime can however, also fail. If your crime fails there are two options: you can escape or you will be arrested by the police and you have to serve sentence in jail. Every crime has a waiting time of 120* seconds.<br />\r\n<br />\r\nIf you are caught by the police you will have to wait 90 seconds in jail unless someone breaks out or buys you out. As you level up, your income from the successful crime will also increase. To achieve the maximum level is 100.<br />\r\n<br />\r\n*For owners of a time bonus (for sale in the <em>Donation Shop</em>) the waiting time is reduced.', 9, 1, 0);
INSERT INTO `helpsystem` VALUES (66, 'organized-crimes', 'Georganiseerde misdaden', '', 78, 0, 0);
INSERT INTO `helpsystem` VALUES (67, 'steal-vehicles', 'Hier kan je voertuigen stelen. Je misdaad kan slagen, je krijgt dan geld en gaat omhoog in rank. Je misdaad kan daarentegen ook mislukken. Als het stelen mislukt zijn er twee mogelijkheden: je kan ontsnappen of je wordt opgepakt door de politie en moet je straf uitzitten in de gevangenis. Iedere diefstal heeft een wachttijd van 120* seconden.<br />\r\n<br />\r\nAls je wordt opgepakt door de politie zal je 150 seconden in de gevangenis moeten wachten, tenzij iemand je uitbreekt of uitkoopt. Naarmate je level stijgt, zullen ook je inkomsten stijgen van de gelukte diefstal. Het maximaal te behalen level is 100. <br />\r\n<br />\r\n*Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 60 seconden.', 'Here you can steal vehicles. Your crime can succeed, you get money and go up in rank. Your crime, on the other hand, can also fail. If the theft fails, there are two options: you can escape or you will be caught by the police and have to serve your sentence in prison. Every theft has a waiting time of 120* seconds.<br />\r\n<br />\r\nIf you are caught by the police you will have to wait 150 seconds in jail unless someone breaks out or buys you out. As you level up, so will your income from the successful theft. The maximum level that can be achieved is 100.<br />\r\n<br />\r\n*For owners of a Fastpass (for sale in the <em>Donation Shop</em>) the waiting time is 60 seconds.', 101, 1, 0);
INSERT INTO `helpsystem` VALUES (68, 'drugs-liquids', '<strong>Drugslab en Drankbrouwerij</strong><br />\r\nHier kan je drugs en drank maken. De kosten zijn $5,000 per productie-unit. Een productie-unit produceert 10 minuten lang en zal dan tussen de 50 en 100 drugs of drank produceren. Je kan je units binnenhalen door ze te selecteren en op units binnenhalen te klikken. Dan krijg je de geproduceerde drugs en drank en kan je ze verkopen. Een gewoon lid, donateur, V.I.P. en Gold Member kunnen respectievelijk 5, 7, 12 en 20 productie-units maken.', '<strong>Drugslab and Liquids Brewery</strong><br />\r\nHere you can make drugs and liquids. The cost is $5,000 per unit of production. A production unit produces 10 minutes long and will then produce between 50 and 100 drugs or liquor. You can bring in your units by selecting them and clicking on bring in units. Then you get the produced drugs and liquids and you can sell them. an ordinary member, donor, V.I.P. and Gold Member can make 5, 7, 12 and 20 production units respectively.', 13, 1, 0);
INSERT INTO `helpsystem` VALUES (69, 'drugs-liquids-liquids', '<strong>Drugslab en Drankbrouwerij</strong><br />\r\nHier kan je drugs en drank maken. De kosten zijn $5,000 per productie-unit. Een productie-unit produceert 10 minuten lang en zal dan tussen de 50 en 100 drugs of drank produceren. Je kan je units binnenhalen door ze te selecteren en op units binnenhalen te klikken. Dan krijg je de geproduceerde drugs en drank en kan je ze verkopen. Een gewoon lid, donateur, V.I.P. en Gold Member kunnen respectievelijk 5, 7, 12 en 20 productie-units maken.', '<strong>Drugslab and Liquids Brewery</strong><br />\r\nHere you can make drugs and liquids. The cost is $5,000 per unit of production. A production unit produces 10 minutes long and will then produce between 50 and 100 drugs or liquor. You can bring in your units by selecting them and clicking on bring in units. Then you get the produced drugs and liquids and you can sell them. an ordinary member, donor, V.I.P. and Gold Member can make 5, 7, 12 and 20 production units respectively.', 14, 1, 0);
INSERT INTO `helpsystem` VALUES (70, 'smuggling', '<strong>Smokkelen</strong><br />\r\nEr zijn 5 verschillende goederen die je kan smokkelen: drugs, drank, vuurwerk, wapens en exotische dieren. Op level 1 kan je van elk goed maar 1 type smokkelen, met weinig winst. Naarmate je level hoger wordt komen er meer types vrij bij elk goed. Deze kun je herkennen aan het donkere balkje en zonder \'aanklik\'-knop. Het getal wat ervoor staat, staat voor het level waarop dat type beschikbaar komt. Hoe hoger het level is waarop een type beschikbaar komt, hoe meer winst je kan maken met het smokkelen. Zo zul je met het smokkelen van Wapens -> Waterstofbommen het meeste verdienen.<br />\r\n<br />\r\nJe level gaat omhoog door het smokkelen van zoveel mogelijk eenheden. Daarom is het slim om een route te zoeken waarmee je zoveel mogelijk goederen in één keer mee kan nemen, zodat je level zo snel mogelijk omhoog gaat (waardoor je ook meer winst kan maken). Met behulp van de winstindex kan je de beste smokkelroute uitzoeken.<br />\r\n<br />\r\nLet er wel op dat je tijdens het smokkelen kan worden opgepakt. Dit gebeurt als de douane je betrapt wanneer je naar een andere staat probeert te <em>reizen</em> en je smokkelwaar bij je hebt. Al je goederen worden dan ingenomen en je moet je straf uitzitten in de <em>gevangenis</em>.<br />\r\n<br />\r\n<strong>Winstindex</strong><br />\r\nOnder het tabblad winstindex vind je een tabel die je kan gebruiken om een goede smokkelroute te vinden. De eerste stap is kiezen of je een tabel van drugs of van drank wilt zien. Daarna kan je met het schuifmenu kiezen welke soort drugs of drank je wilt bekijken. Getallen die in het groen staan betekenen winst en getallen in het rood betekenen verlies. Stel je zit in Colorado en je wilt heroine naar Hawaii brengen. Dan zal je dus $78 winst per eenheid maken, want er staat een groene $78. Je moet dus altijd van de bovenste rij naar de linkse kolom kijken en niet omgekeerd. In Honolulu zijn de drugs en drank het meeste waard, dus het kan handig zijn om naar Hawaii te smokkelen. Voor Gold Members zijn de prijzen in Honolulu extra hoog. Gold Member kan je worden in de donatieshop!', '<strong>Smuggling</strong><br />\r\nThere are 5 different goods you can smuggle: drugs, liquids, fireworks, weapons and exotic animals. At level 1 you can smuggle only 1 of each good, with little profit. As you level up, more types are released for each good. You can recognize this by the dark bar and without a \'click\' button. The number in front of it represents the level at which that type becomes available. The higher the level at which a type becomes available, the more profit you can make from smuggling. For example, you will earn the most by smuggling Weapons -> Hydrogen Bombs.<br />\r\n<br />\r\nYou level up by smuggling as many units as possible. That\'s why it\'s smart to find a route that allows you to take as many goods as possible at once, so that your level goes up as quickly as possible (which also allows you to make more profit). You can use the profit index to find out the best smuggling route.<br />\r\n<br />\r\nKeep in mind that you can be arrested while smuggling. This happens if the border patrol catch you when you are trying to <em>travel</em> to another state while carrying contraband. All your units will then be confiscated and you will have to serve your sentence in <em>prison</em>.<br />\r\n<br />\r\n<strong>Profit Index</strong><br />\r\nUnder the profit index tab you will find a table that you can use to find a good smuggling route. The first step is to choose whether you want to see a table of drugs or of liquids. Then you can use the sliding menu to choose which type of drugs or liquids you want to view. Numbers in green mean profit and numbers in red mean loss. Suppose you are in Colorado and you want to bring heroin to Hawaii. So then you will make $78 profit per unit, because there is a green $78. So you should always look from the top row to the left column and not the other way around. In Honolulu, the drugs and booze are the most valuable, so smuggling to Hawaii can be useful. Prices in Honolulu are extra high for Gold Members. You can become a Gold Member in the donation shop!', 92, 1, 0);
INSERT INTO `helpsystem` VALUES (71, 'smuggling-liquids', '<strong>Smokkelen</strong><br />\r\nEr zijn 5 verschillende goederen die je kan smokkelen: drugs, drank, vuurwerk, wapens en exotische dieren. Op level 1 kan je van elk goed maar 1 type smokkelen, met weinig winst. Naarmate je level hoger wordt komen er meer types vrij bij elk goed. Deze kun je herkennen aan het donkere balkje en zonder \'aanklik\'-knop. Het getal wat ervoor staat, staat voor het level waarop dat type beschikbaar komt. Hoe hoger het level is waarop een type beschikbaar komt, hoe meer winst je kan maken met het smokkelen. Zo zul je met het smokkelen van Wapens -> Waterstofbommen het meeste verdienen.<br />\r\n<br />\r\nJe level gaat omhoog door het smokkelen van zoveel mogelijk eenheden. Daarom is het slim om een route te zoeken waarmee je zoveel mogelijk goederen in één keer mee kan nemen, zodat je level zo snel mogelijk omhoog gaat (waardoor je ook meer winst kan maken). Met behulp van de winstindex kan je de beste smokkelroute uitzoeken.<br />\r\n<br />\r\nLet er wel op dat je tijdens het smokkelen kan worden opgepakt. Dit gebeurt als de douane je betrapt wanneer je naar een andere staat probeert te <em>reizen</em> en je smokkelwaar bij je hebt. Al je goederen worden dan ingenomen en je moet je straf uitzitten in de <em>gevangenis</em>.', '<strong>Smuggling</strong><br />\r\nThere are 5 different goods you can smuggle: drugs, liquids, fireworks, weapons and exotic animals. At level 1 you can smuggle only 1 of each good, with little profit. As you level up, more types are released for each good. You can recognize this by the dark bar and without a \'click\' button. The number in front of it represents the level at which that type becomes available. The higher the level at which a type becomes available, the more profit you can make from smuggling. For example, you will earn the most by smuggling Weapons -> Hydrogen Bombs.<br />\r\n<br />\r\nYou level up by smuggling as many units as possible. That\'s why it\'s smart to find a route that allows you to take as many goods as possible at once, so that your level goes up as quickly as possible (which also allows you to make more profit). You can use the profit index to find out the best smuggling route.<br />\r\n<br />\r\nKeep in mind that you can be arrested while smuggling. This happens if the border patrol catch you when you are trying to <em>travel</em> to another state while carrying contraband. All your units will then be confiscated and you will have to serve your sentence in <em>prison</em>.', 95, 1, 0);
INSERT INTO `helpsystem` VALUES (72, 'smuggling-fireworks', '<strong>Smokkelen</strong><br />\r\nEr zijn 5 verschillende goederen die je kan smokkelen: drugs, drank, vuurwerk, wapens en exotische dieren. Op level 1 kan je van elk goed maar 1 type smokkelen, met weinig winst. Naarmate je level hoger wordt komen er meer types vrij bij elk goed. Deze kun je herkennen aan het donkere balkje en zonder \'aanklik\'-knop. Het getal wat ervoor staat, staat voor het level waarop dat type beschikbaar komt. Hoe hoger het level is waarop een type beschikbaar komt, hoe meer winst je kan maken met het smokkelen. Zo zul je met het smokkelen van Wapens -> Waterstofbommen het meeste verdienen.<br />\r\n<br />\r\nJe level gaat omhoog door het smokkelen van zoveel mogelijk eenheden. Daarom is het slim om een route te zoeken waarmee je zoveel mogelijk goederen in één keer mee kan nemen, zodat je level zo snel mogelijk omhoog gaat (waardoor je ook meer winst kan maken). Met behulp van de winstindex kan je de beste smokkelroute uitzoeken.<br />\r\n<br />\r\nLet er wel op dat je tijdens het smokkelen kan worden opgepakt. Dit gebeurt als de douane je betrapt wanneer je naar een andere staat probeert te <em>reizen</em> en je smokkelwaar bij je hebt. Al je goederen worden dan ingenomen en je moet je straf uitzitten in de <em>gevangenis</em>.', '<strong>Smuggling</strong><br />\r\nThere are 5 different goods you can smuggle: drugs, liquids, fireworks, weapons and exotic animals. At level 1 you can smuggle only 1 of each good, with little profit. As you level up, more types are released for each good. You can recognize this by the dark bar and without a \'click\' button. The number in front of it represents the level at which that type becomes available. The higher the level at which a type becomes available, the more profit you can make from smuggling. For example, you will earn the most by smuggling Weapons -> Hydrogen Bombs.<br />\r\n<br />\r\nYou level up by smuggling as many units as possible. That\'s why it\'s smart to find a route that allows you to take as many goods as possible at once, so that your level goes up as quickly as possible (which also allows you to make more profit). You can use the profit index to find out the best smuggling route.<br />\r\n<br />\r\nKeep in mind that you can be arrested while smuggling. This happens if the border patrol catch you when you are trying to <em>travel</em> to another state while carrying contraband. All your units will then be confiscated and you will have to serve your sentence in <em>prison</em>.', 94, 1, 0);
INSERT INTO `helpsystem` VALUES (73, 'smuggling-weapons', '<strong>Smokkelen</strong><br />\r\nEr zijn 5 verschillende goederen die je kan smokkelen: drugs, drank, vuurwerk, wapens en exotische dieren. Op level 1 kan je van elk goed maar 1 type smokkelen, met weinig winst. Naarmate je level hoger wordt komen er meer types vrij bij elk goed. Deze kun je herkennen aan het donkere balkje en zonder \'aanklik\'-knop. Het getal wat ervoor staat, staat voor het level waarop dat type beschikbaar komt. Hoe hoger het level is waarop een type beschikbaar komt, hoe meer winst je kan maken met het smokkelen. Zo zul je met het smokkelen van Wapens -> Waterstofbommen het meeste verdienen.<br />\r\n<br />\r\nJe level gaat omhoog door het smokkelen van zoveel mogelijk eenheden. Daarom is het slim om een route te zoeken waarmee je zoveel mogelijk goederen in één keer mee kan nemen, zodat je level zo snel mogelijk omhoog gaat (waardoor je ook meer winst kan maken). Met behulp van de winstindex kan je de beste smokkelroute uitzoeken.<br />\r\n<br />\r\nLet er wel op dat je tijdens het smokkelen kan worden opgepakt. Dit gebeurt als de douane je betrapt wanneer je naar een andere staat probeert te <em>reizen</em> en je smokkelwaar bij je hebt. Al je goederen worden dan ingenomen en je moet je straf uitzitten in de <em>gevangenis</em>.', '<strong>Smuggling</strong><br />\r\nThere are 5 different goods you can smuggle: drugs, liquids, fireworks, weapons and exotic animals. At level 1 you can smuggle only 1 of each good, with little profit. As you level up, more types are released for each good. You can recognize this by the dark bar and without a \'click\' button. The number in front of it represents the level at which that type becomes available. The higher the level at which a type becomes available, the more profit you can make from smuggling. For example, you will earn the most by smuggling Weapons -> Hydrogen Bombs.<br />\r\n<br />\r\nYou level up by smuggling as many units as possible. That\'s why it\'s smart to find a route that allows you to take as many goods as possible at once, so that your level goes up as quickly as possible (which also allows you to make more profit). You can use the profit index to find out the best smuggling route.<br />\r\n<br />\r\nKeep in mind that you can be arrested while smuggling. This happens if the border patrol catch you when you are trying to <em>travel</em> to another state while carrying contraband. All your units will then be confiscated and you will have to serve your sentence in <em>prison</em>.', 98, 1, 0);
INSERT INTO `helpsystem` VALUES (74, 'smuggling-exotic-animals', '<strong>Smokkelen</strong><br />\r\nEr zijn 5 verschillende goederen die je kan smokkelen: drugs, drank, vuurwerk, wapens en exotische dieren. Op level 1 kan je van elk goed maar 1 type smokkelen, met weinig winst. Naarmate je level hoger wordt komen er meer types vrij bij elk goed. Deze kun je herkennen aan het donkere balkje en zonder \'aanklik\'-knop. Het getal wat ervoor staat, staat voor het level waarop dat type beschikbaar komt. Hoe hoger het level is waarop een type beschikbaar komt, hoe meer winst je kan maken met het smokkelen. Zo zul je met het smokkelen van Wapens -> Waterstofbommen het meeste verdienen.<br />\r\n<br />\r\nJe level gaat omhoog door het smokkelen van zoveel mogelijk eenheden. Daarom is het slim om een route te zoeken waarmee je zoveel mogelijk goederen in één keer mee kan nemen, zodat je level zo snel mogelijk omhoog gaat (waardoor je ook meer winst kan maken). Met behulp van de winstindex kan je de beste smokkelroute uitzoeken.<br />\r\n<br />\r\nLet er wel op dat je tijdens het smokkelen kan worden opgepakt. Dit gebeurt als de douane je betrapt wanneer je naar een andere staat probeert te <em>reizen</em> en je smokkelwaar bij je hebt. Al je goederen worden dan ingenomen en je moet je straf uitzitten in de <em>gevangenis</em>.', '<strong>Smuggling</strong><br />\r\nThere are 5 different goods you can smuggle: drugs, liquids, fireworks, weapons and exotic animals. At level 1 you can smuggle only 1 of each good, with little profit. As you level up, more types are released for each good. You can recognize this by the dark bar and without a \'click\' button. The number in front of it represents the level at which that type becomes available. The higher the level at which a type becomes available, the more profit you can make from smuggling. For example, you will earn the most by smuggling Weapons -> Hydrogen Bombs.<br />\r\n<br />\r\nYou level up by smuggling as many units as possible. That\'s why it\'s smart to find a route that allows you to take as many goods as possible at once, so that your level goes up as quickly as possible (which also allows you to make more profit). You can use the profit index to find out the best smuggling route.<br />\r\n<br />\r\nKeep in mind that you can be arrested while smuggling. This happens if the border patrol catch you when you are trying to <em>travel</em> to another state while carrying contraband. All your units will then be confiscated and you will have to serve your sentence in <em>prison</em>.', 93, 1, 0);
INSERT INTO `helpsystem` VALUES (75, 'smuggling-profit-index', '<strong>Winstindex</strong><br />\r\nOnder het tabblad winstindex vind je een tabel die je kan gebruiken om een goede smokkelroute te vinden. De eerste stap is kiezen of je een tabel van drugs of van drank wilt zien. Daarna kan je met het schuifmenu kiezen welke soort drugs of drank je wilt bekijken. Getallen die in het groen staan betekenen winst en getallen in het rood betekenen verlies. Stel je zit in Colorado en je wilt heroine naar Hawaii brengen. Dan zal je dus $78 winst per eenheid maken, want er staat een groene $78. Je moet dus altijd van de bovenste rij naar de linkse kolom kijken en niet omgekeerd. In Honolulu zijn de drugs en drank het meeste waard, dus het kan handig zijn om naar Hawaii te smokkelen. Voor Gold Members zijn de prijzen in Honolulu extra hoog. Gold Member kan je worden in de <em>donatieshop!</em>', '<strong>Profit Index</strong><br />\r\nUnder the profit index tab you will find a table that you can use to find a good smuggling route. The first step is to choose whether you want to see a table of drugs or of liquids. Then you can use the sliding menu to choose which type of drugs or liquids you want to view. Numbers in green mean profit and numbers in red mean loss. Suppose you are in Colorado and you want to bring heroin to Hawaii. So then you will make $78 profit per unit, because there is a green $78. So you should always look from the top row to the left column and not the other way around. In Honolulu, the drugs and booze are the most valuable, so smuggling to Hawaii can be useful. Prices in Honolulu are extra high for Gold Members. You can become a Gold Member in the donation shop!', 96, 1, 0);
INSERT INTO `helpsystem` VALUES (76, 'smuggling-profit-index-unit', '<strong>Winstindex</strong><br />\r\nOnder het tabblad winstindex vind je een tabel die je kan gebruiken om een goede smokkelroute te vinden. De eerste stap is kiezen of je een tabel van drugs of van drank wilt zien. Daarna kan je met het schuifmenu kiezen welke soort drugs of drank je wilt bekijken. Getallen die in het groen staan betekenen winst en getallen in het rood betekenen verlies. Stel je zit in Colorado en je wilt heroine naar Hawaii brengen. Dan zal je dus $78 winst per eenheid maken, want er staat een groene $78. Je moet dus altijd van de bovenste rij naar de linkse kolom kijken en niet omgekeerd. In Honolulu zijn de drugs en drank het meeste waard, dus het kan handig zijn om naar Hawaii te smokkelen. Voor Gold Members zijn de prijzen in Honolulu extra hoog. Gold Member kan je worden in de <em>donatieshop!</em>', '<strong>Profit Index</strong><br />\r\nUnder the profit index tab you will find a table that you can use to find a good smuggling route. The first step is to choose whether you want to see a table of drugs or of liquids. Then you can use the sliding menu to choose which type of drugs or liquids you want to view. Numbers in green mean profit and numbers in red mean loss. Suppose you are in Colorado and you want to bring heroin to Hawaii. So then you will make $78 profit per unit, because there is a green $78. So you should always look from the top row to the left column and not the other way around. In Honolulu, the drugs and booze are the most valuable, so smuggling to Hawaii can be useful. Prices in Honolulu are extra high for Gold Members. You can become a Gold Member in the donation shop!', 97, 1, 0);
INSERT INTO `helpsystem` VALUES (77, 'garage', '<strong>Garage</strong><br />\r\nHier zie je alle voertuigen die je hebt gestolen. Je kan je voertuigen verkopen of crushen en converten. Wanneer je je<br />\r\nauto eerst repareert levert de auto meer geld op. Je auto crushen en converten betekent dat je kogels van je auto\'s maakt. Wanneer je auto veel schade heeft en de kogelprijzen hoog zijn kan dit een goedkopere manier zijn om aan kogels te komen. Ook kan je je auto\'s ombouwen tot racecar, hiervoor maakt het niet uit of je auto schade heeft. Het ombouwen tot race car kost $50,000.<br />\r\n<br />\r\nEr kunnen maximaal 10 voertuigen in je garage. Als V.I.P. kan je 15 voertuigen in je garage houden. V.I.P. kan je worden in de <em>donatieshop</em>. Je moet eerst een crusher en converter kopen om voertuigen om te kunnen zetten naar kogels. Crushers en converters zijn in verschillende maten te koop.<br />\r\n<br />\r\nBij race cars zie je de voertuigen die je hebt omgebouwd tot race car. Je ziet van al je race cars de eigenschappen. Hoe hoger het percentage, hoe beter de eigenschap. Mocht je je race auto willen verkopen dan kan je deze weer ombouwen tot normaal voertuig. Ook kan je je race car pimpen om de eigenschappen van je race car te verbeteren.<br />\r\n<br />\r\nOm je race car te gebruiken moet je naar <em>streetrace</em> gaan!<br />\r\n<br />\r\nWanneer de garage geen eigenaar heeft kan deze gekocht worden voor $1,000,000.', '<strong>Garage</strong><br />\r\nHere you can see all the vehicles you have stolen. You can sell or crush and convert your vehicles. when you repair the car first, the car will make more money. Crushing and converting your car means turning your cars into bullets. If your car has a lot of damage and bullet prices are high, this can be a cheaper way to get bullets. You can also convert your cars into racing cars, for this it does not matter whether your car is damaged. Converting to a race car costs $50,000.<br />\r\n<br />\r\nUp to 10 vehicles can be in your garage. If V.I.P. can keep 15 vehicles in your garage. V.I.P. you can become in the donation shop. You must first buy a crusher and converter to convert vehicles into bullets. Crushers and converters are available in different sizes.<br />\r\n<br />\r\nAt race cars you see the vehicles that you have converted into a race car. You can see the characteristics of all your race cars. The higher the percentage, the better the property. If you want to sell your race car, you can convert it back into a normal vehicle. You can also pimp your race car to improve the properties of your race car.<br />\r\n<br />\r\nTo use your race car you have to go to streetrace!<br />\r\n<br />\r\nIf the garage has no owner, it can be purchased for $1,000,000.', 49, 1, 0);
INSERT INTO `helpsystem` VALUES (78, 'garage-shop', 'Voertuigen winkel', '', 50, 0, 0);
INSERT INTO `helpsystem` VALUES (79, 'garage-shop-vehicle', 'Winkel meer info', '', 51, 0, 0);
INSERT INTO `helpsystem` VALUES (80, 'streetrace', '', '', 106, 1, 0);
INSERT INTO `helpsystem` VALUES (81, 'family-list', '<strong>Familie Lijst</strong><br />\r\nHier zie je een lijst van alle families. Naast de naam van de familie zie je ook nog het geld en de leden van de familie. Je kan via deze pagina ook je familie verlaten, een familie zoeken en een familie joinen. Onder het kopje joinen zie je of de familie open staat voor nieuwe leden. De families zijn gesorteerd op de totale score rank van de familie leden.<br />\r\n<br />\r\nEen voordeel van in een familie zitten is dat je meer als team speelt en je een beroep kan doen op je familieleden. Zoek een familie op die bij je past! In familieverband het spel spelen zorgt voor veel spelers voor meer speel plezier.', '<strong>Family List</strong><br />\r\nHere you see a list of all families. In addition to the name of the family, you also see the money and the members of the family. You can also leave your family, search for a family and join a family via this page. Under the heading join you can see if the family is open to new members. The families are sorted by the total score rank of the family members.<br />\r\n<br />\r\nAn advantage of being in a family is that you play more as a team and you can call on your family members. Find a family that suits you! Playing the game as a family provides many players with more fun.', 26, 1, 0);
INSERT INTO `helpsystem` VALUES (82, 'family-page', '<strong>Familie Pagina</strong><br />\r\nJe ziet hier de informatie van de familie en het profiel van de familie. Ook is er een overzicht van de spelers die in de familie zitten. Bij familie missies zie je welke missies de betreffende familie heeft gehaald.', '<strong>Family Page</strong><br />\r\nHere you see the information of the family and the profile of the family. There is also an overview of the players in the family. At family missions you can see which missions the relevant family has completed.', 35, 1, 0);
INSERT INTO `helpsystem` VALUES (83, 'family-bank', '<strong>Familie Bank</strong><br />\r\nHier zie je het totaal bedrag dat op de familie bank staat. Hier zie je naar welke spelers geld van de bank gestuurd is, hoeveel en op welke tijd. Je moet baas of bankbeheerder van een familie zijn om geld van de familie bank te sturen naar een familie lid. Ook zie je hier hoeveel rente er op de familie bank komt. De rente wordt elke dag om 0.00 uur gestort. V.I.P. families krijgen tot $5,000,000,000 2% rente per dag, na dit bedrag is de rente 1%. Niet-V.I.P. families krijgen 1% rente tot $5,000,000,000. Boven dit bedrag krijgen ze <strong>geen</strong> rente meer. Je kan je familie V.I.P. maken in de <em>donatieshop</em>.<br />\r\n<br />\r\nBij doneren kan je geld op de familie bank storten. Je ziet hier de totale donatie van alle spelers en de donatie vanaf<br />\r\neen bepaalde datum. Deze datum wordt vastgesteld door de baas van de familie. De baas kan de donaties resetten waardoor de donaties in de laatste kolom op $0 komen te staan. Het totaalbedrag kan echter niet gereset worden.', '<strong>Family Bank</strong><br />\r\nHere you see the total amount in the family bank. Here you can see to which players money has been sent from the bank, how much and at what time. You have to be a boss or bank manager of a family to send money from the family bank to a family member. Here you can also see how much interest will be paid on the family bank. The interest is deposited every day at 0.00 am. V.I.P. families receive up to $5,000,000,000 2% interest per day, after this amount the interest is 1%. Non-V.I.P. families get 1% interest up to $5,000,000,000. Above this amount, they will <strong>no</strong> longer receive interest. You can make your family V.I.P. in the donation shop.<br />\r\n<br />\r\nWhen donating you can deposit money into the family bank. Here you can see the total donation of all players and the donation from a specific date. This date is set by the boss of the family. The boss can reset the donations causing the donations in the last column to be $0. However, the total amount cannot be reset.', 19, 1, 0);
INSERT INTO `helpsystem` VALUES (84, 'family-bank-manage', '<strong>Familie Bank</strong><br />\r\nHier zie je het totaal bedrag dat op de familie bank staat. Hier zie je naar welke spelers geld van de bank gestuurd is, hoeveel en op welke tijd. Je moet baas of bankbeheerder van een familie zijn om geld van de familie bank te sturen naar een familie lid. Ook zie je hier hoeveel rente er op de familie bank komt. De rente wordt elke dag om 0.00 uur gestort. V.I.P. families krijgen tot $5,000,000,000 2% rente per dag, na dit bedrag is de rente 1%. Niet-V.I.P. families krijgen 1% rente tot $5,000,000,000. Boven dit bedrag krijgen ze <strong>geen</strong> rente meer. Je kan je familie V.I.P. maken in de <em>donatieshop</em>.<br />\r\n<br />\r\nBij doneren kan je geld op de familie bank storten. Je ziet hier de totale donatie van alle spelers en de donatie vanaf<br />\r\neen bepaalde datum. Deze datum wordt vastgesteld door de baas van de familie. De baas kan de donaties resetten waardoor de donaties in de laatste kolom op $0 komen te staan. Het totaalbedrag kan echter niet gereset worden.', '<strong>Family Bank</strong><br />\r\nHere you see the total amount in the family bank. Here you can see to which players money has been sent from the bank, how much and at what time. You have to be a boss or bank manager of a family to send money from the family bank to a family member. Here you can also see how much interest will be paid on the family bank. The interest is deposited every day at 0.00 am. V.I.P. families receive up to $5,000,000,000 2% interest per day, after this amount the interest is 1%. Non-V.I.P. families get 1% interest up to $5,000,000,000. Above this amount, they will <strong>no</strong> longer receive interest. You can make your family V.I.P. in the donation shop.<br />\r\n<br />\r\nWhen donating you can deposit money into the family bank. Here you can see the total donation of all players and the donation from a specific date. This date is set by the boss of the family. The boss can reset the donations causing the donations in the last column to be $0. However, the total amount cannot be reset.', 20, 1, 0);
INSERT INTO `helpsystem` VALUES (85, 'family-shoutbox', '<strong>Familie Shoutbox</strong><br />\r\nOp de familie shoutbox kunnen alleen spelers uit de familie met elkaar communiceren. Je kan zo bijvoorbeeld tactieken bespreken die je alleen met de familie wil delen. Ook kan je hier gezellig praten met je familie. Let er wel op dat ook hier de shoutbox regels van toepassing zijn! De regels zijn te vinden onder shoutbox. Bij overtreding van een van deze regels kan je gewaarschuwd of verbannen worden. Als je zelf een overtreding ziet kan je het bericht melden door op het knopje meld te klikken. Misbruik van het meldsysteem zal bestraft worden.', '<strong>Family Shoutbox</strong><br />\r\nOn the family shoutbox, only players from the family can communicate with each other. For example, you can discuss tactics that you only want to share with the family. You can also have a nice chat with your family here. Please note that the shoutbox rules also apply here! The rules can be found under shoutbox. Violation of any of these rules may result in a warning or ban. If you see a violation yourself, you can report the message by clicking the report button. Abuse of the reporting system will be punished.', 39, 1, 0);
INSERT INTO `helpsystem` VALUES (86, 'family-garage', '<strong>Familie Garage</strong><br />\r\nHier vind je de auto\'s die je familie bij familie misdaad heeft gestolen deze kunnen verkocht geworden of crushen en converten waarbij er kogels bij je familie komt die de familie bazen kunnen verdelen onder de spelers, of als ze hem verkopen komt er geld bij de familiebank bij.', '<strong>Family Garage</strong><br />\r\nHere you will find the cars that your family has stolen in family crime these can be sold or crushed and converted where bullets will come to your family which the family bosses can divide among the players or if they sell it money will be added to the family bank.', 22, 1, 0);
INSERT INTO `helpsystem` VALUES (87, 'family-garage-crusher-converter', '<strong>Familie Garage</strong><br />\r\nHier vind je de auto\'s die je familie bij familie misdaad heeft gestolen deze kunnen verkocht geworden of crushen en converten waarbij er kogels bij je familie komt die de familie bazen kunnen verdelen onder de spelers, of als ze hem verkopen komt er geld bij de familiebank bij.', '<strong>Family Garage</strong><br />\r\nHere you will find the cars that your family has stolen in family crime these can be sold or crushed and converted where bullets will come to your family which the family bosses can divide among the players or if they sell it money will be added to the family bank.', 23, 1, 0);
INSERT INTO `helpsystem` VALUES (88, 'family-properties', 'Kogelfabriek', '', 36, 0, 0);
INSERT INTO `helpsystem` VALUES (89, 'family-properties-brothel', 'Bordeel', '', 37, 0, 0);
INSERT INTO `helpsystem` VALUES (90, 'family-crimes', '<strong>Familie Misdaden</strong><br />\r\nHier kan je samen met je familie leden een misdaad doen. Je ziet het aantal deelnemers en hoeveel deelnemers er mee moeten doen. Het aantal misdaden dat je uitvoert is gelijk aan het aantal deelnemers min één. Als je bijvoorbeeld met zijn vieren een familie misdaad start zal je drie misdaden doen. Wanneer minimaal één familie misdaad slaagt ontvangt elke deelnemer drie eerpunten.<br />\r\n<br />\r\nDaarnaast wordt er per geslaagde misdaad een auto gestolen die in de <em>familie garage</em> komt en gaan de spelers in rank omhoog. De misdaad kan echter slagen voor bepaalde deelnemers terwijl hij voor andere deelnemers niet slaagt. Deze deelnemers komen dan in de <em>gevangenis</em>. De deelnemers die in de gevangenis komen krijgen wel drie eerpunten, mits er minstens één deelnemer wel slaagt voor de familie misdaad.<br />\r\n<br />\r\nJe kan een familie misdaad elke 150* seconden doen.<br />\r\n<br />\r\n*Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 75 seconden.', '<strong>Family Crimes</strong><br />\r\nHere you can commit a crime together with your family members. You see the number of participants and how many participants have to participate. The number of crimes you perform is equal to the number of participants minus one. For example, if you start a family crime with four of you, you will commit three crimes. When at least one family crime succeeds, each participant receives three honor points.<br />\r\n<br />\r\nIn addition, for each successful crime, a car is stolen that enters the family garage and the players increase in rank. However, the crime can pass for certain participants while failing for other participants. These participants then end up in prison. Contestants who end up in prison will get three honors points, provided at least one contestant passes the family crime.<br />\r\n<br />\r\nYou can do a family crime every 150* seconds.<br />\r\n<br />\r\n*For owners of a Fastpass (for sale in the Donation Shop) the waiting time is 75 seconds.', 21, 1, 0);
INSERT INTO `helpsystem` VALUES (91, 'family-raid', '<strong>Familie Overval</strong><br />\r\nOverval een bank met je familieleden, hiervoor heb je 4 functies nodig:<br />\r\n- De baas, die organiseert alles<br />\r\n- Een Chauffeur, die zorgt voor een auto.<br />\r\n- Een wapenexpert, die zorgt voor de wapens en de kogels<br />\r\n- Een bommenexpert, hij zorgt voor de explosieven<br />\r\n- Als het lukt winnen alle spelers geld, mislukt het beland je voor een uur in de gevangenis of moet je een uur wachten voordat je hem opnieuw kan doen.', '<strong>Family Raid</strong><br />\r\nRob a bank with your family members, for this you need 4 functions:<br />\r\n- The boss, he organizes everything<br />\r\n- A driver who takes care of a car.<br />\r\n- A weapons expert, who takes care of the weapons and the bullets<br />\r\n- A bomb expert, he takes care of the explosives<br />\r\n- If it succeeds all players win money, if it fails you will end up in jail for an hour or you will have to wait an hour before you can do it again.', 38, 1, 0);
INSERT INTO `helpsystem` VALUES (92, 'family-missions', '<strong>Familie Missies</strong><br />\r\nHier is een overzicht van alle familie missies die je als familie kan halen. De uitleg van de familie missies staat<br />\r\nweergegeven bij elke missie. De beloningen voor het halen van de missies worden op de familie bank gestort. De missie<br />\r\nOwn the World is alleen voor V.I.P. families. Je kan je familie V.I.P. maken in de <em>donatieshop</em>.<br />\r\n<br />\r\nVoor alle missies, met uitzondering van Own the World, geldt dat ze uit verschillende stappen bestaan. Heb je<br />\r\nbijvoorbeeld met je familie 50 auto\'s gestolen dan krijg je een beloning van $5.000.000. Je hebt echter dan nog niet<br />\r\ndeze missie gehaald. Voor de volgende missie zal je meer auto\'s moeten stelen voor een hogere beloning. Hoeveel<br />\r\nstappen er zijn voordat je de missie gehaald hebt is een verassing. Wanneer je al deze stappen hebt volbracht en dus de<br />\r\nfamilie missie hebt gehaald, zal het icon verschijnen op je <em>familie pagina</em>.', '', 34, 1, 0);
INSERT INTO `helpsystem` VALUES (93, 'family-history', '<strong>Familie Geschiedenis</strong><br />\r\nHier is een overzicht van alle familie aanvallen te zien. Je ziet hier welke andere spelers door jouw familie zijn aangevallen en de laatst gepleegde aanvallen op de familie. Je kan bijvoorbeeld wraak nemen op iemand die je familielid heeft aangevallen.', '<strong>Family History</strong><br />\r\nHere you can see an overview of all family attacks. Here you can see which other players have been attacked by your family and the most recent attacks on the family. For example, you can take revenge on someone who attacked your family member.', 24, 1, 0);
INSERT INTO `helpsystem` VALUES (94, 'family-management', 'Leden', '', 27, 0, 0);
INSERT INTO `helpsystem` VALUES (95, 'family-management-profile', 'Fam Beheer Profiel', '', 32, 0, 0);
INSERT INTO `helpsystem` VALUES (96, 'family-management-mass-message', 'Massa bericht', '', 30, 0, 0);
INSERT INTO `helpsystem` VALUES (97, 'family-management-message', 'Fam bericht', '', 31, 0, 0);
INSERT INTO `helpsystem` VALUES (98, 'family-management-alliances', 'Alianties', '', 28, 0, 0);
INSERT INTO `helpsystem` VALUES (99, 'family-management-manage-family', 'Fam beheer', '', 29, 0, 0);
INSERT INTO `helpsystem` VALUES (100, 'family-invitations', '<strong>Familie Uitnodigigen</strong><br />\r\nHier zie je een overzicht van de uitnodigingen die je hebt gekregen van verschillende <em>families.</em> Accepteer of weiger een uitnodiging als je een familie wilt joinen of niet. Een voordeel van in een familie zitten is dat je meer als team speelt en je een beroep kan doen op je familieleden. Zoek een familie die bij je past!', '<strong>Family Invitations</strong><br />\r\nHere you can see an overview of the invitations you have received from different families. Accept or decline an invitation if you want to join a family or not. An advantage of being in a family is that you play more as a team and you can call on your family members. Find a family that suits you!', 25, 1, 0);
INSERT INTO `helpsystem` VALUES (101, 'create-family', 'Hier kun je je eigen familie stichten. De enige voorwaarden zijn dat je minimaal de rank Legendary Godfather moet hebben met alle levels op 10 of hoger. Nadat je je eigen familie hebt gesticht, kun je proberen familieleden te vinden door spelers uit te nodigen.', 'Here you can start your own family. The only conditions are that you must be at least the rank Legendary Godfather with all levels at 10 or higher. After starting your own family, you can try to find relatives by inviting players.', 8, 1, 0);
INSERT INTO `helpsystem` VALUES (102, 'family-message', '<strong>Familie Bericht</strong><br />\r\nHier kan je een familie bericht lezen. Hier kunnen bijvoorbeeld tips in staan die altijd handig zijn of smokkelroutes en plaatjes. Enkel de leden binnen de familie kunnen dit lezen. Alleen de baas en onderbaas kunnen dit bericht aanpassen.', '<strong>Family Message</strong><br />\r\nHere you can read a family message. For example, this can contain tips that are always useful or smuggling routes and pictures. Only members of the family can read this. Only the boss and underboss can edit this message.', 33, 1, 0);
INSERT INTO `helpsystem` VALUES (103, 'share-mafiasource', '', '', 89, 0, 0);
INSERT INTO `helpsystem` VALUES (104, 'members', '<strong>Toplijst</strong><br />\r\nBij toplijst zie je een overzicht van alle leden. Je ziet welke rank de spelers zijn, welke geld rank ze hebben, in welke familie ze zitten en hoeveel leven ze hebben. De spelers zijn gesorteerd op score rank. Ook is hier een zoekmachine waarbij je naar specifieke leden kan zoeken. Je kan ook op rank sorteren.<br />\r\n<br />\r\nDe score rank wordt elk heel uur berekend. Het is een optelsom vanaf het moment dat je geregistreerd bent. De rank die je bent, het aantal hoeren, eerpunten, landjes en kills tellen mee voor je score rank.<br />\r\n<br />\r\n<strong>Online Leden</strong><br />\r\nBij online spelers zie je de spelers die de laatste 5 minuten online zijn. De legenda legt uit waar de kleuren voor staan die de spelers hebben.', '<strong>Toplist</strong><br />\r\nAt toplist you see an overview of all members. You see what rank the players are, what money rank they have, what family they are in and how much life they have. The players are sorted by score rank. There is also a search engine here where you can search for specific members. You can also sort by rank.<br />\r\n<br />\r\nThe score rank is calculated every hour. It is a sum from the moment you are registered. The rank you are, the number of hoes, honor points, ground lands and kills count towards your score rank.<br />\r\n<br />\r\n<strong>Online Members</strong><br />\r\nWith online players you see the players who have been online in the last 5 minutes. The legend explains what the colors for that the players have.', 66, 1, 0);
INSERT INTO `helpsystem` VALUES (105, 'toplist', '<strong>Toplijst</strong><br />\r\nBij toplijst zie je een overzicht van alle leden. Je ziet welke rank de spelers zijn, welke geld rank ze hebben, in welke familie ze zitten en hoeveel leven ze hebben. De spelers zijn gesorteerd op score rank. Ook is hier een zoekmachine waarbij je naar specifieke leden kan zoeken. Je kan ook op rank sorteren.<br />\r\n<br />\r\nDe score rank wordt elk heel uur berekend. Het is een optelsom vanaf het moment dat je geregistreerd bent. De rank die je bent, het aantal hoeren, eerpunten, landjes en kills tellen mee voor je score rank.<br />\r\n<br />\r\n<strong>Online Leden</strong><br />\r\nBij online spelers zie je de spelers die de laatste 5 minuten online zijn. De legenda legt uit waar de kleuren voor staan die de spelers hebben.', '<strong>Toplist</strong><br />\r\nAt toplist you see an overview of all members. You see what rank the players are, what money rank they have, what family they are in and how much life they have. The players are sorted by score rank. There is also a search engine here where you can search for specific members. You can also sort by rank.<br />\r\n<br />\r\nThe score rank is calculated every hour. It is a sum from the moment you are registered. The rank you are, the number of hoes, honor points, ground lands and kills count towards your score rank.<br />\r\n<br />\r\n<strong>Online Members</strong><br />\r\nWith online players you see the players who have been online in the last 5 minutes. The legend explains what the colors for that the players have.', 108, 1, 0);
INSERT INTO `helpsystem` VALUES (106, 'information', 'Bij informatie kun je de Statistieken, regels en teamleden van Mafiasource zien.<br />\r\n<br />\r\n<strong>Statistieken/Hall of Fame</strong><br />\r\nHier kun je alle Statistieken zien van Mafiasource. Je ziet hier het aantal geregistreerde, verbannen en vermoorde leden. Ook zie je alle geldstatistieken van Mafiasource.<br />\r\n<br />\r\nVerder kun je hier nog meer Statistieken vinden die te maken hebben met Mafiasource.<br />\r\n<br />\r\n<strong>Regels</strong><br />\r\nHier zie je alle regels die gelden op Mafiasource. Hou je dan ook aan deze regels.<br />\r\n<br />\r\n<strong>Team</strong><br />\r\nHier vind je alle teamleden van Mafiasource. Deze teamleden onderhouden het spel en helpen je waar nodig.', 'At information you can see the Statistics, rules and team members of Mafiasource.<br />\r\n<br />\r\n<strong>Statistics/Hall of Fame</strong><br />\r\nHere you can see all Statistics from Mafiasource. Here you can see the number of registered, banned and murdered members. You can also see all the money statistics from Mafiasource.<br />\r\n<br />\r\nYou can also find more Statistics related to Mafiasource here.<br />\r\n<br />\r\n<strong>Rules</strong><br />\r\nHere you can see all the rules that apply to Mafiasource. So stick to these rules.<br />\r\n<br />\r\n<strong>Team</strong><br />\r\nHere you will find all team members of Mafiasource. These team members maintain the game and help you where necessary.', 57, 1, 0);
INSERT INTO `helpsystem` VALUES (107, 'information-rules', '<strong>Regels</strong><br />\r\nHier zie je alle regels die gelden op Mafiasource. Hou je dan ook aan deze regels.', '<strong>Rules</strong><br />\r\nHere you can see all the rules that apply to Mafiasource. So stick to these rules.', 59, 1, 0);
INSERT INTO `helpsystem` VALUES (108, 'information-team-members', '<strong>Team</strong><br />\r\nHier vind je alle teamleden van Mafiasource. Deze teamleden onderhouden het spel en helpen je waar nodig.', '<strong>Team</strong><br />\r\nHere you will find all team members of Mafiasource. These team members maintain the game and help you where necessary.', 60, 1, 0);
INSERT INTO `helpsystem` VALUES (109, 'information-hall-of-fame', '<strong>Statistieken/Hall of Fame</strong><br />\r\nHier kun je alle Statistieken zien van Mafiasource. Je ziet hier het aantal geregistreerde, verbannen en vermoorde leden. Ook zie je alle geldstatistieken van Mafiasource.<br />\r\n<br />\r\nVerder kun je hier nog meer Statistieken vinden die te maken hebben met Mafiasource.', '<strong>Statistics/Hall of Fame</strong><br />\r\nHere you can see all Statistics from Mafiasource. Here you can see the number of registered, banned and murdered members. You can also see all the money statistics from Mafiasource.<br />\r\n<br />\r\nYou can also find more Statistics related to Mafiasource here.', 58, 1, 0);
INSERT INTO `helpsystem` VALUES (110, 'ranks-score', 'Op deze pagina kun je alle vereiste zien die jij nodig hebt om een bepaalde rank te worden. Ook zie je hier een aantal extra opties die je krijgt wanneer je een bepaalde rank hebt bereikt.<br />\r\n<br />\r\n<span style=\"color:#ffffff\">Een <strong>basic</strong> mode houd in:<br />\r\n<br />\r\n<em>- Geen extra\'s.</em><br />\r\n<br />\r\n<br />\r\nEen <strong>Master</strong> mode houd in:<br />\r\n<br />\r\n<em>- Nieuwe misdaad: Big Score.<br />\r\n- Trager ranken.<br />\r\n- Meer eerpunten bij familie misdaden.<br />\r\n- Een master medaille voor je naam.<br />\r\n- Bij het plegen van misdaden kleine kans om een luckybox te vinden.</em><br />\r\n<br />\r\n<br />\r\nEen <strong>Hero</strong> mode houd in:<br />\r\n<br />\r\n<em>- Nieuwe misdaad: Big Score.<br />\r\n- Nog trager ranken.<br />\r\n- Meer eerpunten bij familie misdaden.<br />\r\n- Een hero medaille voor je naam.<br />\r\n- Je word <u>professioneel</u> in je beroep.<br />\r\n- Bij het plegen van misdaden matige kans om een luckybox te vinden.</em><br />\r\n<br />\r\n<br />\r\n<u>Professioneel</u> zijn in je beroep houd in dat je je beroep nog beter kan uitvoeren hieronder zie je hoe.<br />\r\n<br />\r\n<em>- Autodief: Wachttijden nog korter en steel kans groter.<br />\r\n- Uitbreker: Uitbraak kansen groter + vergoeding per uitbraak.<br />\r\n- Dief: Wachttijden nog korter en steel kans groter.<br />\r\n- Bankier: Je krijgt 7% rente per dag.<br />\r\n- Pimp: Je pimpt 30% meer hoeren dan normaal.<br />\r\n- Smokkelaar: Nog minder snel betrapt worden en nog sneller kunnen reizen.</em> </span>', 'On this page you can see all the requirements you need to become a certain rank. You will also see a number of extra options that you get when you have reached a certain rank.', 85, 1, 0);
INSERT INTO `helpsystem` VALUES (111, 'weapon-training', '<strong>WapenTraining/Logboeken</strong><br />\r\nBij logboeken kun je de laatste 10 aanvallen zien. Bij wapen training zie je hoe hoog je wapen ervaring en wapen training is. Wapen ervaring gaat omhoog door spelers aan te vallen. Wapen training kan je om de 5* minuten trainen.<br />\r\n<br />\r\n* Voor eigenaren van een Fastpass (te koop in de <em>Donatieshop</em>) is de wachttijd 2,5 minuten.', '<strong>WapenTraining/Logboeken</strong><br />\r\nIn logs you can see the last 10 attacks. With weapon training you can see how high your weapon experience and weapon training is. Weapon experience increases by attacking players. Weapon training can train you every 5* minutes.<br />\r\n<br />\r\n* For owners of a Fastpass (for sale in the <em>Donation Shop</em>) the waiting time is 2.5 minutes.', 1, 0, 1);
INSERT INTO `helpsystem` VALUES (112, '...', '', '', 2, 1, 1);

-- ----------------------------
-- Table structure for hitlist
-- ----------------------------
DROP TABLE IF EXISTS `hitlist`;
CREATE TABLE `hitlist`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `ordererID` bigint NULL DEFAULT NULL,
  `userID` bigint NULL DEFAULT NULL,
  `prize` bigint NOT NULL DEFAULT 0,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of hitlist
-- ----------------------------

-- ----------------------------
-- Table structure for honorpoint_log
-- ----------------------------
DROP TABLE IF EXISTS `honorpoint_log`;
CREATE TABLE `honorpoint_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `senderID` bigint NULL DEFAULT NULL,
  `receiverID` bigint NULL DEFAULT NULL,
  `amount` int NULL DEFAULT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of honorpoint_log
-- ----------------------------

-- ----------------------------
-- Table structure for ip_ban
-- ----------------------------
DROP TABLE IF EXISTS `ip_ban`;
CREATE TABLE `ip_ban`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ip_ban
-- ----------------------------

-- ----------------------------
-- Table structure for login
-- ----------------------------
DROP TABLE IF EXISTS `login`;
CREATE TABLE `login`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type=disabled',
  `date` datetime NOT NULL COMMENT 'type=disabled',
  `time` bigint NOT NULL COMMENT 'type=disabled',
  `tries` int NOT NULL DEFAULT 1 COMMENT 'type=disabled',
  `cookieLogin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id`(`userID`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of login
-- ----------------------------

-- ----------------------------
-- Table structure for login_fail
-- ----------------------------
DROP TABLE IF EXISTS `login_fail`;
CREATE TABLE `login_fail`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `username` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'type=disabled',
  `ip` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'type=disabled',
  `date` datetime NULL DEFAULT NULL COMMENT 'type=disabled',
  `time` bigint NOT NULL DEFAULT 0 COMMENT 'type=disabled',
  `type` smallint NOT NULL DEFAULT 0 COMMENT 'select=Inloggegevens,Overtreding,Waarschuwing,Tijdelijke ban,Permanente ban',
  `cookieLogin` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Records of login_fail
-- ----------------------------

-- ----------------------------
-- Table structure for lottery
-- ----------------------------
DROP TABLE IF EXISTS `lottery`;
CREATE TABLE `lottery`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Dag,Week',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of lottery
-- ----------------------------

-- ----------------------------
-- Table structure for lottery_winner
-- ----------------------------
DROP TABLE IF EXISTS `lottery_winner`;
CREATE TABLE `lottery_winner`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` tinyint(1) NULL DEFAULT 0 COMMENT 'select=Dag,Week',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `prize` bigint NULL DEFAULT NULL,
  `place` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of lottery_winner
-- ----------------------------

-- ----------------------------
-- Table structure for market
-- ----------------------------
DROP TABLE IF EXISTS `market`;
CREATE TABLE `market`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `requested` smallint NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `amount` int NOT NULL DEFAULT 0,
  `price` bigint NOT NULL DEFAULT 0,
  `anonymous` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `date` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of market
-- ----------------------------

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=disabled',
  `status` int NOT NULL COMMENT 'couple=status&factor=id&show=status_nl',
  `naam` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `voornaam` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `adres` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `gemeente` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `postcode` int NULL DEFAULT NULL,
  `position` tinyint(1) NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of member
-- ----------------------------

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `cID` bigint NULL DEFAULT NULL COMMENT 'type=disabled',
  `senderID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `receiverID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `date` datetime NULL DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `inSenderInbox` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'type=yesno',
  `inReceiverInbox` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'type=yesno',
  `position` bigint NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of message
-- ----------------------------

-- ----------------------------
-- Table structure for murder_log
-- ----------------------------
DROP TABLE IF EXISTS `murder_log`;
CREATE TABLE `murder_log`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `attackerID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `victimID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `time` bigint NOT NULL DEFAULT 0,
  `result` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '00',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of murder_log
-- ----------------------------

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` enum('news','update') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'news',
  `title_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `article_nl` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `article_en` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `date` datetime NULL DEFAULT NULL,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of news
-- ----------------------------

-- ----------------------------
-- Table structure for notification
-- ----------------------------
DROP TABLE IF EXISTS `notification`;
CREATE TABLE `notification`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NULL DEFAULT NULL,
  `notification` varchar(75) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `params` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `inInbox` tinyint(1) NOT NULL DEFAULT 1,
  `date` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of notification
-- ----------------------------

-- ----------------------------
-- Table structure for poll_answer
-- ----------------------------
DROP TABLE IF EXISTS `poll_answer`;
CREATE TABLE `poll_answer`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `questionID` int NULL DEFAULT NULL COMMENT 'couple=poll_question&factor=id&show=question_nl',
  `answer_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `answer_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of poll_answer
-- ----------------------------
INSERT INTO `poll_answer` VALUES (1, 1, 'Ja', 'Yes', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (2, 1, 'Nee, een bank donaties reset knop voorzien daarintegen', 'No, make a bank donation reset button available instead', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (3, 1, 'Nee, dagelijks automatisch donaties resetten', 'No, automatically reset donations daily', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (4, 1, 'Geen mening', 'No opinion', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (5, 2, 'Ja', 'Yes', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (6, 2, 'Nee', 'No', NULL, 0, 0);
INSERT INTO `poll_answer` VALUES (7, 2, 'Geen mening', 'No opinion', NULL, 0, 0);

-- ----------------------------
-- Table structure for poll_question
-- ----------------------------
DROP TABLE IF EXISTS `poll_question`;
CREATE TABLE `poll_question`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `question_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `question_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `startDate` datetime NULL DEFAULT NULL,
  `endDate` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of poll_question
-- ----------------------------
INSERT INTO `poll_question` VALUES (1, 'Familie bank verzenden aftrekken van donaties?', 'Substact family bank sending from donations?', 'Het verzonden bedrag naar deze speler wordt ook van zijn donatielog afgetrokken of laten zoals het is en donaties reset voorzien.', 'The amount sent to this player will also be deducted from his donation log or left as is and provide donation reset.', '2020-07-07 23:00:49', '2020-12-26 22:43:47', NULL, 0, 0);
INSERT INTO `poll_question` VALUES (2, 'Legerdary Don\'s enkel elkaar kunnen aanvallen en rankeisen toevoegen?', 'Legendary Don\'s  can only attack eachother and add rank requirements?', 'Eisen zoals een aantal hoeren, eerpunten en kills per rank Don, Respectable Don en Legendary Don', 'Requirements such as a number of whores, honor points and kills by rank Don, Respectable Don and Legendary Don', '2020-07-09 18:23:38', '2020-12-26 22:43:50', NULL, 0, 0);

-- ----------------------------
-- Table structure for poll_vote
-- ----------------------------
DROP TABLE IF EXISTS `poll_vote`;
CREATE TABLE `poll_vote`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `questionID` int NULL DEFAULT NULL COMMENT 'couple=poll_question&factor=id&show=question_nl',
  `answerID` int NULL DEFAULT NULL COMMENT 'couple=poll_answer&factor=id&show=answer_nl',
  `date` datetime NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of poll_vote
-- ----------------------------

-- ----------------------------
-- Table structure for possess
-- ----------------------------
DROP TABLE IF EXISTS `possess`;
CREATE TABLE `possess`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `pID` int NULL DEFAULT NULL COMMENT 'couple=possession&factor=id&show=name_nl',
  `stateID` smallint NULL DEFAULT 0 COMMENT 'couple=state&factor=id&show=name',
  `cityID` smallint NULL DEFAULT 0 COMMENT 'couple=city&factor=id&show=name',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `profit` bigint NOT NULL DEFAULT 0,
  `profit_hour` bigint NOT NULL DEFAULT 0,
  `stake` int NOT NULL DEFAULT 50000,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 214 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of possess
-- ----------------------------
INSERT INTO `possess` VALUES (1, 1, 1, 0, 0, 0, 0, 50000, 1, 1, 0);
INSERT INTO `possess` VALUES (2, 1, 2, 0, 0, 0, 0, 50000, 2, 1, 0);
INSERT INTO `possess` VALUES (3, 1, 3, 0, 0, 0, 0, 50000, 3, 1, 0);
INSERT INTO `possess` VALUES (4, 1, 4, 0, 0, 0, 0, 50000, 4, 1, 0);
INSERT INTO `possess` VALUES (5, 1, 5, 0, 0, 0, 0, 50000, 5, 1, 0);
INSERT INTO `possess` VALUES (6, 1, 6, 0, 0, 0, 0, 50000, 6, 1, 0);
INSERT INTO `possess` VALUES (7, 2, 1, 0, 0, 0, 0, 50000, 7, 1, 0);
INSERT INTO `possess` VALUES (8, 2, 2, 0, 0, 0, 0, 50000, 8, 1, 0);
INSERT INTO `possess` VALUES (9, 2, 3, 0, 0, 0, 0, 50000, 9, 1, 0);
INSERT INTO `possess` VALUES (10, 2, 4, 0, 0, 0, 0, 50000, 10, 1, 0);
INSERT INTO `possess` VALUES (11, 2, 5, 0, 0, 0, 0, 50000, 11, 1, 0);
INSERT INTO `possess` VALUES (12, 2, 6, 0, 0, 0, 0, 50000, 12, 1, 0);
INSERT INTO `possess` VALUES (13, 3, 1, 0, 0, 0, 0, 50000, 13, 1, 0);
INSERT INTO `possess` VALUES (14, 3, 2, 0, 0, 0, 0, 50000, 14, 1, 0);
INSERT INTO `possess` VALUES (15, 3, 3, 0, 0, 0, 0, 50000, 15, 1, 0);
INSERT INTO `possess` VALUES (16, 3, 4, 0, 0, 0, 0, 50000, 16, 1, 0);
INSERT INTO `possess` VALUES (17, 3, 5, 0, 0, 0, 0, 50000, 17, 1, 0);
INSERT INTO `possess` VALUES (18, 3, 6, 0, 0, 0, 0, 50000, 18, 1, 0);
INSERT INTO `possess` VALUES (19, 4, 1, 1, 0, 0, 0, 50000, 19, 1, 0);
INSERT INTO `possess` VALUES (20, 4, 1, 2, 0, 0, 0, 50000, 20, 1, 0);
INSERT INTO `possess` VALUES (21, 4, 1, 3, 0, 0, 0, 50000, 21, 1, 0);
INSERT INTO `possess` VALUES (22, 4, 2, 4, 0, 0, 0, 50000, 22, 1, 0);
INSERT INTO `possess` VALUES (23, 4, 2, 5, 0, 0, 0, 50000, 23, 1, 0);
INSERT INTO `possess` VALUES (24, 4, 2, 6, 0, 0, 0, 50000, 24, 1, 0);
INSERT INTO `possess` VALUES (25, 4, 3, 7, 0, 0, 0, 50000, 25, 1, 0);
INSERT INTO `possess` VALUES (26, 4, 3, 8, 0, 0, 0, 50000, 26, 1, 0);
INSERT INTO `possess` VALUES (27, 4, 3, 9, 0, 0, 0, 50000, 27, 1, 0);
INSERT INTO `possess` VALUES (28, 4, 4, 10, 0, 0, 0, 50000, 28, 1, 0);
INSERT INTO `possess` VALUES (29, 4, 4, 11, 0, 0, 0, 50000, 29, 1, 0);
INSERT INTO `possess` VALUES (30, 4, 4, 12, 0, 0, 0, 50000, 30, 1, 0);
INSERT INTO `possess` VALUES (31, 4, 5, 13, 0, 0, 0, 50000, 31, 1, 0);
INSERT INTO `possess` VALUES (32, 4, 5, 14, 0, 0, 0, 50000, 32, 1, 0);
INSERT INTO `possess` VALUES (33, 4, 5, 15, 0, 0, 0, 50000, 33, 1, 0);
INSERT INTO `possess` VALUES (34, 4, 6, 16, 0, 0, 0, 50000, 34, 1, 0);
INSERT INTO `possess` VALUES (35, 4, 6, 17, 0, 0, 0, 50000, 35, 1, 0);
INSERT INTO `possess` VALUES (36, 4, 6, 18, 0, 0, 0, 50000, 36, 1, 0);
INSERT INTO `possess` VALUES (37, 5, 1, 1, 0, 0, 0, 50000, 37, 1, 0);
INSERT INTO `possess` VALUES (38, 5, 1, 2, 0, 0, 0, 50000, 38, 1, 0);
INSERT INTO `possess` VALUES (39, 5, 1, 3, 0, 0, 0, 50000, 39, 1, 0);
INSERT INTO `possess` VALUES (40, 5, 2, 4, 0, 0, 0, 50000, 40, 1, 0);
INSERT INTO `possess` VALUES (41, 5, 2, 5, 0, 0, 0, 50000, 41, 1, 0);
INSERT INTO `possess` VALUES (42, 5, 2, 6, 0, 0, 0, 50000, 42, 1, 0);
INSERT INTO `possess` VALUES (43, 5, 3, 7, 0, 0, 0, 50000, 43, 1, 0);
INSERT INTO `possess` VALUES (44, 5, 3, 8, 0, 0, 0, 50000, 44, 1, 0);
INSERT INTO `possess` VALUES (45, 5, 3, 9, 0, 0, 0, 50000, 45, 1, 0);
INSERT INTO `possess` VALUES (46, 5, 4, 10, 0, 0, 0, 50000, 46, 1, 0);
INSERT INTO `possess` VALUES (47, 5, 4, 11, 0, 0, 0, 50000, 47, 1, 0);
INSERT INTO `possess` VALUES (48, 5, 4, 12, 0, 0, 0, 50000, 48, 1, 0);
INSERT INTO `possess` VALUES (49, 5, 5, 13, 0, 0, 0, 50000, 49, 1, 0);
INSERT INTO `possess` VALUES (50, 5, 5, 14, 0, 0, 0, 50000, 50, 1, 0);
INSERT INTO `possess` VALUES (51, 5, 5, 15, 0, 0, 0, 50000, 51, 1, 0);
INSERT INTO `possess` VALUES (52, 5, 6, 16, 0, 0, 0, 50000, 52, 1, 0);
INSERT INTO `possess` VALUES (53, 5, 6, 17, 0, 0, 0, 50000, 53, 1, 0);
INSERT INTO `possess` VALUES (54, 5, 6, 18, 0, 0, 0, 50000, 54, 1, 0);
INSERT INTO `possess` VALUES (55, 6, 1, 0, 0, 0, 0, 50000, 55, 1, 0);
INSERT INTO `possess` VALUES (56, 6, 2, 0, 0, 0, 0, 50000, 56, 1, 0);
INSERT INTO `possess` VALUES (57, 6, 3, 0, 0, 0, 0, 50000, 57, 1, 0);
INSERT INTO `possess` VALUES (58, 6, 4, 0, 0, 0, 0, 50000, 58, 1, 0);
INSERT INTO `possess` VALUES (59, 6, 5, 0, 0, 0, 0, 50000, 59, 1, 0);
INSERT INTO `possess` VALUES (60, 6, 6, 0, 0, 0, 0, 50000, 60, 1, 0);
INSERT INTO `possess` VALUES (61, 7, 1, 1, 0, 0, 0, 50000, 61, 1, 0);
INSERT INTO `possess` VALUES (62, 7, 1, 2, 0, 0, 0, 50000, 62, 1, 0);
INSERT INTO `possess` VALUES (63, 7, 1, 3, 0, 0, 0, 50000, 63, 1, 0);
INSERT INTO `possess` VALUES (64, 7, 2, 4, 0, 0, 0, 50000, 64, 1, 0);
INSERT INTO `possess` VALUES (65, 7, 2, 5, 0, 0, 0, 50000, 65, 1, 0);
INSERT INTO `possess` VALUES (66, 7, 2, 6, 0, 0, 0, 50000, 66, 1, 0);
INSERT INTO `possess` VALUES (67, 7, 3, 7, 0, 0, 0, 50000, 67, 1, 0);
INSERT INTO `possess` VALUES (68, 7, 3, 8, 0, 0, 0, 50000, 68, 1, 0);
INSERT INTO `possess` VALUES (69, 7, 3, 9, 0, 0, 0, 50000, 69, 1, 0);
INSERT INTO `possess` VALUES (70, 7, 4, 10, 0, 0, 0, 50000, 70, 1, 0);
INSERT INTO `possess` VALUES (71, 7, 4, 11, 0, 0, 0, 50000, 71, 1, 0);
INSERT INTO `possess` VALUES (72, 7, 4, 12, 0, 0, 0, 50000, 72, 1, 0);
INSERT INTO `possess` VALUES (73, 7, 5, 13, 0, 0, 0, 50000, 73, 1, 0);
INSERT INTO `possess` VALUES (74, 7, 5, 14, 0, 0, 0, 50000, 74, 1, 0);
INSERT INTO `possess` VALUES (75, 7, 5, 15, 0, 0, 0, 50000, 75, 1, 0);
INSERT INTO `possess` VALUES (76, 7, 6, 16, 0, 0, 0, 50000, 76, 1, 0);
INSERT INTO `possess` VALUES (77, 7, 6, 17, 0, 0, 0, 50000, 77, 1, 0);
INSERT INTO `possess` VALUES (78, 7, 6, 18, 0, 0, 0, 50000, 78, 1, 0);
INSERT INTO `possess` VALUES (79, 8, 1, 0, 0, 0, 0, 50000, 79, 1, 0);
INSERT INTO `possess` VALUES (80, 8, 2, 0, 0, 0, 0, 50000, 80, 1, 0);
INSERT INTO `possess` VALUES (81, 8, 3, 0, 0, 0, 0, 50000, 81, 1, 0);
INSERT INTO `possess` VALUES (82, 8, 4, 0, 0, 0, 0, 50000, 82, 1, 0);
INSERT INTO `possess` VALUES (83, 8, 5, 0, 0, 0, 0, 50000, 83, 1, 0);
INSERT INTO `possess` VALUES (84, 8, 6, 0, 0, 0, 0, 50000, 84, 1, 0);
INSERT INTO `possess` VALUES (85, 9, 1, 1, 0, 0, 0, 50000, 85, 1, 0);
INSERT INTO `possess` VALUES (86, 9, 1, 2, 0, 0, 0, 50000, 86, 1, 0);
INSERT INTO `possess` VALUES (87, 9, 1, 3, 0, 0, 0, 50000, 87, 1, 0);
INSERT INTO `possess` VALUES (88, 9, 2, 4, 0, 0, 0, 50000, 88, 1, 0);
INSERT INTO `possess` VALUES (89, 9, 2, 5, 0, 0, 0, 50000, 89, 1, 0);
INSERT INTO `possess` VALUES (90, 9, 2, 6, 0, 0, 0, 50000, 90, 1, 0);
INSERT INTO `possess` VALUES (91, 9, 3, 7, 0, 0, 0, 50000, 91, 1, 0);
INSERT INTO `possess` VALUES (92, 9, 3, 8, 0, 0, 0, 50000, 92, 1, 0);
INSERT INTO `possess` VALUES (93, 9, 3, 9, 0, 0, 0, 50000, 93, 1, 0);
INSERT INTO `possess` VALUES (94, 9, 4, 10, 0, 0, 0, 50000, 94, 1, 0);
INSERT INTO `possess` VALUES (95, 9, 4, 11, 0, 0, 0, 50000, 95, 1, 0);
INSERT INTO `possess` VALUES (96, 9, 4, 12, 0, 0, 0, 50000, 96, 1, 0);
INSERT INTO `possess` VALUES (97, 9, 5, 13, 0, 0, 0, 50000, 97, 1, 0);
INSERT INTO `possess` VALUES (98, 9, 5, 14, 0, 0, 0, 50000, 98, 1, 0);
INSERT INTO `possess` VALUES (99, 9, 5, 15, 0, 0, 0, 50000, 99, 1, 0);
INSERT INTO `possess` VALUES (100, 9, 6, 16, 0, 0, 0, 50000, 100, 1, 0);
INSERT INTO `possess` VALUES (101, 9, 6, 17, 0, 0, 0, 50000, 101, 1, 0);
INSERT INTO `possess` VALUES (102, 9, 6, 18, 0, 0, 0, 50000, 102, 1, 0);
INSERT INTO `possess` VALUES (103, 10, 0, 0, 0, 0, 0, 50000, 103, 1, 0);
INSERT INTO `possess` VALUES (104, 11, 1, 1, 0, 0, 0, 50000, 104, 1, 0);
INSERT INTO `possess` VALUES (105, 11, 1, 2, 0, 0, 0, 50000, 105, 1, 0);
INSERT INTO `possess` VALUES (106, 11, 1, 3, 0, 0, 0, 50000, 106, 1, 0);
INSERT INTO `possess` VALUES (107, 11, 2, 4, 0, 0, 0, 50000, 107, 1, 0);
INSERT INTO `possess` VALUES (108, 11, 2, 5, 0, 0, 0, 50000, 108, 1, 0);
INSERT INTO `possess` VALUES (109, 11, 2, 6, 0, 0, 0, 50000, 109, 1, 0);
INSERT INTO `possess` VALUES (110, 11, 3, 7, 0, 0, 0, 50000, 110, 1, 0);
INSERT INTO `possess` VALUES (111, 11, 3, 8, 0, 0, 0, 50000, 111, 1, 0);
INSERT INTO `possess` VALUES (112, 11, 3, 9, 0, 0, 0, 50000, 112, 1, 0);
INSERT INTO `possess` VALUES (113, 11, 4, 10, 0, 0, 0, 50000, 113, 1, 0);
INSERT INTO `possess` VALUES (114, 11, 4, 11, 0, 0, 0, 50000, 114, 1, 0);
INSERT INTO `possess` VALUES (115, 11, 4, 12, 0, 0, 0, 50000, 115, 1, 0);
INSERT INTO `possess` VALUES (116, 11, 5, 13, 0, 0, 0, 50000, 116, 1, 0);
INSERT INTO `possess` VALUES (117, 11, 5, 14, 0, 0, 0, 50000, 117, 1, 0);
INSERT INTO `possess` VALUES (118, 11, 5, 15, 0, 0, 0, 50000, 118, 1, 0);
INSERT INTO `possess` VALUES (119, 11, 6, 16, 0, 0, 0, 50000, 119, 1, 0);
INSERT INTO `possess` VALUES (120, 11, 6, 17, 0, 0, 0, 50000, 120, 1, 0);
INSERT INTO `possess` VALUES (121, 11, 6, 18, 0, 0, 0, 50000, 121, 1, 0);
INSERT INTO `possess` VALUES (122, 12, 0, 0, 0, 0, 0, 50000, 122, 1, 0);
INSERT INTO `possess` VALUES (123, 13, 1, 1, 0, 0, 0, 50000, 123, 1, 0);
INSERT INTO `possess` VALUES (124, 13, 1, 2, 0, 0, 0, 50000, 124, 1, 0);
INSERT INTO `possess` VALUES (125, 13, 1, 3, 0, 0, 0, 50000, 125, 1, 0);
INSERT INTO `possess` VALUES (126, 13, 2, 4, 0, 0, 0, 50000, 126, 1, 0);
INSERT INTO `possess` VALUES (127, 13, 2, 5, 0, 0, 0, 50000, 127, 1, 0);
INSERT INTO `possess` VALUES (128, 13, 2, 6, 0, 0, 0, 50000, 128, 1, 0);
INSERT INTO `possess` VALUES (129, 13, 3, 7, 0, 0, 0, 50000, 129, 1, 0);
INSERT INTO `possess` VALUES (130, 13, 3, 8, 0, 0, 0, 50000, 130, 1, 0);
INSERT INTO `possess` VALUES (131, 13, 3, 9, 0, 0, 0, 50000, 131, 1, 0);
INSERT INTO `possess` VALUES (132, 13, 4, 10, 0, 0, 0, 50000, 132, 1, 0);
INSERT INTO `possess` VALUES (133, 13, 4, 11, 0, 0, 0, 50000, 133, 1, 0);
INSERT INTO `possess` VALUES (134, 13, 4, 12, 0, 0, 0, 50000, 134, 1, 0);
INSERT INTO `possess` VALUES (135, 13, 5, 13, 0, 0, 0, 50000, 135, 1, 0);
INSERT INTO `possess` VALUES (136, 13, 5, 14, 0, 0, 0, 50000, 136, 1, 0);
INSERT INTO `possess` VALUES (137, 13, 5, 15, 0, 0, 0, 50000, 137, 1, 0);
INSERT INTO `possess` VALUES (138, 13, 6, 16, 0, 0, 0, 50000, 138, 1, 0);
INSERT INTO `possess` VALUES (139, 13, 6, 17, 0, 0, 0, 50000, 139, 1, 0);
INSERT INTO `possess` VALUES (140, 13, 6, 18, 0, 0, 0, 50000, 140, 1, 0);
INSERT INTO `possess` VALUES (141, 14, 1, 1, 0, 0, 0, 50000, 141, 1, 0);
INSERT INTO `possess` VALUES (142, 14, 1, 2, 0, 0, 0, 50000, 142, 1, 0);
INSERT INTO `possess` VALUES (143, 14, 1, 3, 0, 0, 0, 50000, 143, 1, 0);
INSERT INTO `possess` VALUES (144, 14, 2, 4, 0, 0, 0, 50000, 144, 1, 0);
INSERT INTO `possess` VALUES (145, 14, 2, 5, 0, 0, 0, 50000, 145, 1, 0);
INSERT INTO `possess` VALUES (146, 14, 2, 6, 0, 0, 0, 50000, 146, 1, 0);
INSERT INTO `possess` VALUES (147, 14, 3, 7, 0, 0, 0, 50000, 147, 1, 0);
INSERT INTO `possess` VALUES (148, 14, 3, 8, 0, 0, 0, 50000, 148, 1, 0);
INSERT INTO `possess` VALUES (149, 14, 3, 9, 0, 0, 0, 50000, 149, 1, 0);
INSERT INTO `possess` VALUES (150, 14, 4, 10, 0, 0, 0, 50000, 150, 1, 0);
INSERT INTO `possess` VALUES (151, 14, 4, 11, 0, 0, 0, 50000, 151, 1, 0);
INSERT INTO `possess` VALUES (152, 14, 4, 12, 0, 0, 0, 50000, 152, 1, 0);
INSERT INTO `possess` VALUES (153, 14, 5, 13, 0, 0, 0, 50000, 153, 1, 0);
INSERT INTO `possess` VALUES (154, 14, 5, 14, 0, 0, 0, 50000, 154, 1, 0);
INSERT INTO `possess` VALUES (155, 14, 5, 15, 0, 0, 0, 50000, 155, 1, 0);
INSERT INTO `possess` VALUES (156, 14, 6, 16, 0, 0, 0, 50000, 156, 1, 0);
INSERT INTO `possess` VALUES (157, 14, 6, 17, 0, 0, 0, 50000, 157, 1, 0);
INSERT INTO `possess` VALUES (158, 14, 6, 18, 0, 0, 0, 50000, 158, 1, 0);
INSERT INTO `possess` VALUES (159, 15, 1, 1, 0, 0, 0, 50000, 159, 1, 0);
INSERT INTO `possess` VALUES (160, 15, 1, 2, 0, 0, 0, 50000, 160, 1, 0);
INSERT INTO `possess` VALUES (161, 15, 1, 3, 0, 0, 0, 50000, 161, 1, 0);
INSERT INTO `possess` VALUES (162, 15, 2, 4, 0, 0, 0, 50000, 162, 1, 0);
INSERT INTO `possess` VALUES (163, 15, 2, 5, 0, 0, 0, 50000, 163, 1, 0);
INSERT INTO `possess` VALUES (164, 15, 2, 6, 0, 0, 0, 50000, 164, 1, 0);
INSERT INTO `possess` VALUES (165, 15, 3, 7, 0, 0, 0, 50000, 165, 1, 0);
INSERT INTO `possess` VALUES (166, 15, 3, 8, 0, 0, 0, 50000, 166, 1, 0);
INSERT INTO `possess` VALUES (167, 15, 3, 9, 0, 0, 0, 50000, 167, 1, 0);
INSERT INTO `possess` VALUES (168, 15, 4, 10, 0, 0, 0, 50000, 168, 1, 0);
INSERT INTO `possess` VALUES (169, 15, 4, 11, 0, 0, 0, 50000, 169, 1, 0);
INSERT INTO `possess` VALUES (170, 15, 4, 12, 0, 0, 0, 50000, 170, 1, 0);
INSERT INTO `possess` VALUES (171, 15, 5, 13, 0, 0, 0, 50000, 171, 1, 0);
INSERT INTO `possess` VALUES (172, 15, 5, 14, 0, 0, 0, 50000, 172, 1, 0);
INSERT INTO `possess` VALUES (173, 15, 5, 15, 0, 0, 0, 50000, 173, 1, 0);
INSERT INTO `possess` VALUES (174, 15, 6, 16, 0, 0, 0, 50000, 174, 1, 0);
INSERT INTO `possess` VALUES (175, 15, 6, 17, 0, 0, 0, 50000, 175, 1, 0);
INSERT INTO `possess` VALUES (176, 15, 6, 18, 0, 0, 0, 50000, 176, 1, 0);
INSERT INTO `possess` VALUES (177, 16, 1, 1, 0, 0, 0, 50000, 177, 1, 0);
INSERT INTO `possess` VALUES (178, 16, 1, 2, 0, 0, 0, 50000, 178, 1, 0);
INSERT INTO `possess` VALUES (179, 16, 1, 3, 0, 0, 0, 50000, 179, 1, 0);
INSERT INTO `possess` VALUES (180, 16, 2, 4, 0, 0, 0, 50000, 180, 1, 0);
INSERT INTO `possess` VALUES (181, 16, 2, 5, 0, 0, 0, 50000, 181, 1, 0);
INSERT INTO `possess` VALUES (182, 16, 2, 6, 0, 0, 0, 50000, 182, 1, 0);
INSERT INTO `possess` VALUES (183, 16, 3, 7, 0, 0, 0, 50000, 183, 1, 0);
INSERT INTO `possess` VALUES (184, 16, 3, 8, 0, 0, 0, 50000, 184, 1, 0);
INSERT INTO `possess` VALUES (185, 16, 3, 9, 0, 0, 0, 50000, 185, 1, 0);
INSERT INTO `possess` VALUES (186, 16, 4, 10, 0, 0, 0, 50000, 186, 1, 0);
INSERT INTO `possess` VALUES (187, 16, 4, 11, 0, 0, 0, 50000, 187, 1, 0);
INSERT INTO `possess` VALUES (188, 16, 4, 12, 0, 0, 0, 50000, 188, 1, 0);
INSERT INTO `possess` VALUES (189, 16, 5, 13, 0, 0, 0, 50000, 189, 1, 0);
INSERT INTO `possess` VALUES (190, 16, 5, 14, 0, 0, 0, 50000, 190, 1, 0);
INSERT INTO `possess` VALUES (191, 16, 5, 15, 0, 0, 0, 50000, 191, 1, 0);
INSERT INTO `possess` VALUES (192, 16, 6, 16, 0, 0, 0, 50000, 192, 1, 0);
INSERT INTO `possess` VALUES (193, 16, 6, 17, 0, 0, 0, 50000, 193, 1, 0);
INSERT INTO `possess` VALUES (194, 16, 6, 18, 0, 0, 0, 50000, 194, 1, 0);
INSERT INTO `possess` VALUES (195, 17, 1, 1, 0, 0, 0, 50000, 195, 1, 0);
INSERT INTO `possess` VALUES (196, 17, 1, 2, 0, 0, 0, 50000, 196, 1, 0);
INSERT INTO `possess` VALUES (197, 17, 1, 3, 0, 0, 0, 50000, 197, 1, 0);
INSERT INTO `possess` VALUES (198, 17, 2, 4, 0, 0, 0, 50000, 198, 1, 0);
INSERT INTO `possess` VALUES (199, 17, 2, 5, 0, 0, 0, 50000, 199, 1, 0);
INSERT INTO `possess` VALUES (200, 17, 2, 6, 0, 0, 0, 50000, 200, 1, 0);
INSERT INTO `possess` VALUES (201, 17, 3, 7, 0, 0, 0, 50000, 201, 1, 0);
INSERT INTO `possess` VALUES (202, 17, 3, 8, 0, 0, 0, 50000, 202, 1, 0);
INSERT INTO `possess` VALUES (203, 17, 3, 9, 0, 0, 0, 50000, 203, 1, 0);
INSERT INTO `possess` VALUES (204, 17, 4, 10, 0, 0, 0, 50000, 204, 1, 0);
INSERT INTO `possess` VALUES (205, 17, 4, 11, 0, 0, 0, 50000, 205, 1, 0);
INSERT INTO `possess` VALUES (206, 17, 4, 12, 0, 0, 0, 50000, 206, 1, 0);
INSERT INTO `possess` VALUES (207, 17, 5, 13, 0, 0, 0, 50000, 207, 1, 0);
INSERT INTO `possess` VALUES (208, 17, 5, 14, 0, 0, 0, 50000, 208, 1, 0);
INSERT INTO `possess` VALUES (209, 17, 5, 15, 0, 0, 0, 50000, 209, 1, 0);
INSERT INTO `possess` VALUES (210, 17, 6, 16, 0, 0, 0, 50000, 210, 1, 0);
INSERT INTO `possess` VALUES (211, 17, 6, 17, 0, 0, 0, 50000, 211, 1, 0);
INSERT INTO `possess` VALUES (212, 17, 6, 18, 0, 0, 0, 50000, 212, 1, 0);
INSERT INTO `possess` VALUES (213, 18, 0, 0, 0, 0, 0, 50000, 213, 1, 0);

-- ----------------------------
-- Table structure for possess_transfer
-- ----------------------------
DROP TABLE IF EXISTS `possess_transfer`;
CREATE TABLE `possess_transfer`  (
  `possessID` int NOT NULL DEFAULT 0 COMMENT 'couple=possess&factor=id&show=id',
  `senderID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `receiverID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  PRIMARY KEY (`possessID`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of possess_transfer
-- ----------------------------

-- ----------------------------
-- Table structure for possession
-- ----------------------------
DROP TABLE IF EXISTS `possession`;
CREATE TABLE `possession`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_en` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload&width=1280',
  `price` int NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of possession
-- ----------------------------
INSERT INTO `possession` VALUES (1, 'Kogelfabriek', 'Bullet Factory', 'c66b1b80.jpg', 2500000, 1, 1, 0);
INSERT INTO `possession` VALUES (2, 'Red Light District', 'Red Light District', '3b55903a.jpg', 2000000, 2, 1, 0);
INSERT INTO `possession` VALUES (3, 'Mafiasource Bank', 'Mafiasource Bank', '895965c6.png', 1500000, 3, 1, 0);
INSERT INTO `possession` VALUES (4, 'Reisbureau', 'Travel Agency', '036751dd.jpg', 1500000, 4, 1, 0);
INSERT INTO `possession` VALUES (5, 'Uitrusting Winkels', 'Equipment Stores', 'c2c455f2.jpg', 1000000, 5, 1, 0);
INSERT INTO `possession` VALUES (6, 'Makelaardij', 'Estate Agency', '1b31ce9c.png', 1000000, 6, 1, 0);
INSERT INTO `possession` VALUES (7, 'Ziekenhuis', 'Hospital', '1fc1f9af.jpg', 1000000, 7, 1, 0);
INSERT INTO `possession` VALUES (8, 'Garage Makelaardij', 'Garage Agency', '263ab64b.jpg', 1000000, 8, 1, 0);
INSERT INTO `possession` VALUES (9, 'Voertuig Handelszaak', 'Vehicle Business', '2ac48900.png', 1500000, 9, 1, 0);
INSERT INTO `possession` VALUES (10, 'Gevangenis', 'Prison', 'af5ff8fb.jpg', 1000000, 10, 1, 0);
INSERT INTO `possession` VALUES (11, 'Detective Bureau', 'Detective Desk', 'cf1a53b8.png', 1500000, 11, 1, 0);
INSERT INTO `possession` VALUES (12, 'Telecombedrijf', 'Telecom Company', '7b7d2b4d.jpg', 500000, 12, 1, 0);
INSERT INTO `possession` VALUES (13, 'Dobbel Tafel', 'Dobbling Table', '31a67813.jpg', 750000, 13, 1, 0);
INSERT INTO `possession` VALUES (14, 'Race Track', 'Race Track', 'e3011f35.jpg', 750000, 14, 1, 0);
INSERT INTO `possession` VALUES (15, 'Roulette', 'Roulette', '05e03961.jpg', 750000, 15, 1, 0);
INSERT INTO `possession` VALUES (16, 'Fruitmachine', 'Slot Machine', '8e11084f.png', 750000, 16, 1, 0);
INSERT INTO `possession` VALUES (17, 'Blackjack', 'Blackjack', 'f574eec5.png', 750000, 17, 1, 0);
INSERT INTO `possession` VALUES (18, 'Loterij', 'Lottery', '126faca6.jpg', 2500000, 18, 1, 0);
INSERT INTO `possession` VALUES (19, '', '', '', 0, 19, 0, 0);

-- ----------------------------
-- Table structure for prison
-- ----------------------------
DROP TABLE IF EXISTS `prison`;
CREATE TABLE `prison`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NULL DEFAULT NULL,
  `time` bigint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of prison
-- ----------------------------

-- ----------------------------
-- Table structure for profession
-- ----------------------------
DROP TABLE IF EXISTS `profession`;
CREATE TABLE `profession`  (
  `id` tinyint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `profession_nl` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `profession_en` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description_en` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of profession
-- ----------------------------
INSERT INTO `profession` VALUES (1, 'Autodief', 'Carjacker', 'Steel makkelijker voertuigen.', 'Steal vehicles more easily.', 1, 1, 0);
INSERT INTO `profession` VALUES (2, 'Uitbreker', 'Prison-breaker', 'Breek mensen makkelijker uit de gevangenis.', 'Easier to break people from prison.', 2, 1, 0);
INSERT INTO `profession` VALUES (3, 'Dief', 'Thief', 'Pleeg makkelijker misdaden.', 'Commit crimes easier.', 3, 1, 0);
INSERT INTO `profession` VALUES (4, 'Pimp', 'Pimp', 'Je pimpt tot 15% meer hoeren dan normaal.', 'You pimp up to 15% more hoes than usual.', 4, 1, 0);
INSERT INTO `profession` VALUES (5, 'Bankier', 'Banker', 'Krijg 3% dagelijkse rente op je bank i.p.v. 1%', 'Get 3% daily interests on your bank instead of 1%', 5, 1, 0);
INSERT INTO `profession` VALUES (6, 'Smokkelaar', 'Smuggler', 'Je wordt minder snel betrapt door de douane.', 'You get caught less by the border-patrol.', 6, 1, 0);

-- ----------------------------
-- Table structure for protection
-- ----------------------------
DROP TABLE IF EXISTS `protection`;
CREATE TABLE `protection`  (
  `id` int NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NOT NULL DEFAULT 0,
  `protection` smallint NOT NULL DEFAULT 10,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of protection
-- ----------------------------
INSERT INTO `protection` VALUES (0, 'Geen', NULL, 0, 0, 4522, 1, 0);
INSERT INTO `protection` VALUES (1, 'Pitbull', '242e6ebf.jpg', 10000, 9, 1, 1, 0);
INSERT INTO `protection` VALUES (2, 'Bullet-free Vest', '050c1b3c.jpg', 100000, 17, 2, 1, 0);
INSERT INTO `protection` VALUES (3, 'Body Guard', 'bc3bcca1.jpg', 500000, 29, 3, 1, 0);
INSERT INTO `protection` VALUES (4, 'Armored Viper GTS', '08e6b911.jpg', 1000000, 47, 4, 1, 0);
INSERT INTO `protection` VALUES (5, 'Top Camere Security', 'aeaf7b21.jpg', 2500000, 69, 5, 1, 0);
INSERT INTO `protection` VALUES (6, 'Armored Helicopter', '06d4a5e5.jpg', 4000000, 77, 6, 1, 0);

-- ----------------------------
-- Table structure for public_mission
-- ----------------------------
DROP TABLE IF EXISTS `public_mission`;
CREATE TABLE `public_mission`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `missionID` tinyint(1) NOT NULL DEFAULT 0,
  `minAmount` int NOT NULL DEFAULT 0,
  `rewardType` tinyint(1) NOT NULL DEFAULT 0,
  `rewardAmount` int NOT NULL DEFAULT 0,
  `reward2Type` tinyint(1) NOT NULL DEFAULT 0,
  `reward2Amount` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of public_mission
-- ----------------------------
INSERT INTO `public_mission` VALUES (1, 16, 3, 2, 55, 1, 5);

-- ----------------------------
-- Table structure for recover_password
-- ----------------------------
DROP TABLE IF EXISTS `recover_password`;
CREATE TABLE `recover_password`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NOT NULL,
  `key` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of recover_password
-- ----------------------------

-- ----------------------------
-- Table structure for residence
-- ----------------------------
DROP TABLE IF EXISTS `residence`;
CREATE TABLE `residence`  (
  `id` smallint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_en` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NOT NULL DEFAULT 0,
  `defence` smallint NOT NULL DEFAULT 0,
  `rankpoints` int NULL DEFAULT 2,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of residence
-- ----------------------------
INSERT INTO `residence` VALUES (1, 'Caravan', 'Caravan', '', '', 'dcdc15f6.jpg', 10000, 3, 2, 1, 1, 0);
INSERT INTO `residence` VALUES (2, 'Houten Huis', 'Wooden House', '', '', 'c8097e40.jpg', 70000, 6, 5, 2, 1, 0);
INSERT INTO `residence` VALUES (3, 'Luxe Bergvilla', ' Luxury Mountain Villa', '', '', 'fbd6622f.jpg', 250000, 9, 10, 3, 1, 0);
INSERT INTO `residence` VALUES (4, 'Villa', 'Villa', '', '', '8eaa5c0e.jpg', 490000, 13, 20, 4, 1, 0);
INSERT INTO `residence` VALUES (5, 'Boshuis', 'Forest House', '', '', '12e1521d.jpg', 1100000, 17, 35, 5, 1, 0);
INSERT INTO `residence` VALUES (6, 'Luxe Bunker', 'Luxury Bunker', '', '', 'b68edff6.jpg', 1700000, 22, 50, 6, 1, 0);
INSERT INTO `residence` VALUES (7, 'Luxe Strandvilla', 'Luxury Beach Villa', '', '', '517f0e00.jpg', 2250000, 26, 70, 7, 1, 0);
INSERT INTO `residence` VALUES (8, 'Gangster Paradise', 'Gangster Paradise', '', '', 'e86e3552.jpg', 4300000, 31, 95, 8, 1, 0);
INSERT INTO `residence` VALUES (9, 'Japanse Villa', 'Japanese Villa', '', '', '94eca8f3.jpg', 12000000, 36, 130, 9, 1, 0);
INSERT INTO `residence` VALUES (10, 'Mediteraanse Villa', 'Mediterranean Villa', '', '', '7e98076f.jpg', 23000000, 42, 180, 10, 1, 0);
INSERT INTO `residence` VALUES (11, 'Pool Paradise', 'Pool Paradise', '', '', '84770252.jpg', 51000000, 49, 240, 11, 1, 0);
INSERT INTO `residence` VALUES (12, 'Landgoed', 'Estate', '', '', 'd02704f7.jpg', 107000000, 57, 320, 12, 1, 0);
INSERT INTO `residence` VALUES (13, 'Tropische Villa', 'Tropical Villa', '', '', '29c700cd.jpg', 176000000, 66, 420, 13, 1, 0);
INSERT INTO `residence` VALUES (14, 'Fantasy Home', 'Fantasy Home', '', '', 'b5fc8d85.jpg', 235000000, 76, 530, 14, 1, 0);

-- ----------------------------
-- Table structure for rld
-- ----------------------------
DROP TABLE IF EXISTS `rld`;
CREATE TABLE `rld`  (
  `id` tinyint(1) NOT NULL AUTO_INCREMENT,
  `possessID` int NOT NULL DEFAULT 0,
  `windows` int NOT NULL DEFAULT 1,
  `priceEachWindow` smallint NOT NULL DEFAULT 150,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of rld
-- ----------------------------
INSERT INTO `rld` VALUES (1, 7, 1, 150);
INSERT INTO `rld` VALUES (2, 8, 1, 150);
INSERT INTO `rld` VALUES (3, 9, 1, 150);
INSERT INTO `rld` VALUES (4, 10, 1, 150);
INSERT INTO `rld` VALUES (5, 11, 1, 150);
INSERT INTO `rld` VALUES (6, 12, 1, 150);

-- ----------------------------
-- Table structure for rld_whore
-- ----------------------------
DROP TABLE IF EXISTS `rld_whore`;
CREATE TABLE `rld_whore`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `stateID` smallint NULL DEFAULT NULL COMMENT 'couple=state&factor=id&show=name',
  `userID` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `whores` int NOT NULL DEFAULT 0,
  `position` bigint NULL DEFAULT NULL,
  `active` smallint NOT NULL DEFAULT 1,
  `deleted` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of rld_whore
-- ----------------------------

-- ----------------------------
-- Table structure for round
-- ----------------------------
DROP TABLE IF EXISTS `round`;
CREATE TABLE `round`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `round` smallint NOT NULL DEFAULT 1,
  `startDate` datetime NULL DEFAULT NULL,
  `endDate` datetime NULL DEFAULT NULL,
  `hofJson` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `dbBackup` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of round
-- ----------------------------
INSERT INTO `round` VALUES (1, 0, '2016-01-01 14:46:38', '2020-12-21 22:47:01', '{\"members\":[{\"getScorePosition\":1,\"getId\":18,\"getUsername\":\"Nexive\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"Nexive_avatar_8.png\",\"getScore\":677461888,\"getFamilyID\":17,\"getFamily\":\"TheSystem\"},{\"getScorePosition\":2,\"getId\":20,\"getUsername\":\"the-killer-king\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":355069500,\"getFamilyID\":8,\"getFamily\":\"kwakje\"},{\"getScorePosition\":3,\"getId\":20,\"getUsername\":\"Bones\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":308605695,\"getFamilyID\":17,\"getFamily\":\"TheSystem\"},{\"getScorePosition\":4,\"getId\":4,\"getUsername\":\"xHelix\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":258088456,\"getFamilyID\":17,\"getFamily\":\"TheSystem\"},{\"getScorePosition\":5,\"getId\":29,\"getUsername\":\"NoSexTonight\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"NoSexTonight_avatar_7.png\",\"getScore\":156819283,\"getFamilyID\":3,\"getFamily\":\"Revolution\"},{\"getScorePosition\":6,\"getId\":114,\"getUsername\":\"pThreads\",\"getDonatorID\":10,\"getUsernameClassName\":\"moderator\",\"getAvatar\":\"pThreads_avatar_4.jpg\",\"getScore\":118369110,\"getFamilyID\":17,\"getFamily\":\"TheSystem\"},{\"getScorePosition\":7,\"getId\":97,\"getUsername\":\"MrStatisfied\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":97913831,\"getFamilyID\":9,\"getFamily\":\"SoldSouls\"},{\"getScorePosition\":8,\"getId\":102,\"getUsername\":\"Salahh\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":88934072,\"getFamilyID\":11,\"getFamily\":\"Fearless\"},{\"getScorePosition\":9,\"getId\":82,\"getUsername\":\"Jasper\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"Jasper_avatar_5.jpg\",\"getScore\":76702512,\"getFamilyID\":9,\"getFamily\":\"SoldSouls\"},{\"getScorePosition\":10,\"getId\":56,\"getUsername\":\"lasertje\",\"getDonatorID\":10,\"getUsernameClassName\":\"gold-member\",\"getAvatar\":\"\",\"getScore\":74443013,\"getFamilyID\":3,\"getFamily\":\"Revolution\"}],\"families\":[{\"getName\":\"TheSystem\",\"getVip\":true,\"getMoney\":19675553839,\"getTotalScore\":1368854733},{\"getName\":\"kwakje\",\"getVip\":true,\"getMoney\":5353981676,\"getTotalScore\":388464162},{\"getName\":\"Revolution\",\"getVip\":true,\"getMoney\":17779443498,\"getTotalScore\":350507757},{\"getName\":\"SoldSouls\",\"getVip\":true,\"getMoney\":40212187670,\"getTotalScore\":188011867},{\"getName\":\"Fearless\",\"getVip\":true,\"getMoney\":4043396679,\"getTotalScore\":168853068}],\"game\":{\"getTotalMembers\":133,\"getTotalCash\":391176969,\"getTotalBank\":3730186703,\"getTotalMoney\":4121363672,\"getAverageMoney\":30987697,\"getTotalFamilies\":9,\"getTotalBullets\":3819839,\"getAverageBullets\":28721,\"getTotalDeathNow\":0,\"getTotalBanned\":6},\"richest\":[{\"getKey\":\"pThreads\",\"getValue\":1021723008},{\"getKey\":\"Salahh\",\"getValue\":665247456},{\"getKey\":\"Jojo98\",\"getValue\":500272386},{\"getKey\":\"Nexive\",\"getValue\":464618195},{\"getKey\":\"xHelix\",\"getValue\":305050508},{\"getKey\":\"CookieMonster\",\"getValue\":269125591},{\"getKey\":\"tuur-dierckx\",\"getValue\":152296984},{\"getKey\":\"Jasper\",\"getValue\":152181255},{\"getKey\":\"spliner\",\"getValue\":122151195},{\"getKey\":\"Bones\",\"getValue\":100532542}],\"mostHonored\":[{\"getKey\":\"Nexive\",\"getValue\":1661171},{\"getKey\":\"hoofdsmurf\",\"getValue\":8019},{\"getKey\":\"NoSexTonight\",\"getValue\":3780},{\"getKey\":\"xHelix\",\"getValue\":3728},{\"getKey\":\"Bones\",\"getValue\":3689},{\"getKey\":\"BALR\",\"getValue\":1937},{\"getKey\":\"MrStatisfied\",\"getValue\":1475},{\"getKey\":\"tuur-dierckx\",\"getValue\":1247},{\"getKey\":\"the-killer-king\",\"getValue\":1230},{\"getKey\":\"Alone-Green\",\"getValue\":1123}],\"killerking\":[{\"getKey\":\"Salahh\",\"getValue\":36},{\"getKey\":\"Jojo98\",\"getValue\":34},{\"getKey\":\"the-killer-king\",\"getValue\":31},{\"getKey\":\"Alone-Green\",\"getValue\":16},{\"getKey\":\"BigR\",\"getValue\":10},{\"getKey\":\"Macjunior\",\"getValue\":7},{\"getKey\":\"Jasper\",\"getValue\":7},{\"getKey\":\"JackTheRipper\",\"getValue\":6},{\"getKey\":\"NoSexTonight\",\"getValue\":6},{\"getKey\":\"xHelix\",\"getValue\":3}],\"prisonBreaking\":[{\"getKey\":\"Jojo98\",\"getValue\":508},{\"getKey\":\"Nexive\",\"getValue\":204},{\"getKey\":\"Salahh\",\"getValue\":148},{\"getKey\":\"BigR\",\"getValue\":103},{\"getKey\":\"the-killer-king\",\"getValue\":102},{\"getKey\":\"Bones\",\"getValue\":94},{\"getKey\":\"Aimanejr\",\"getValue\":85},{\"getKey\":\"Macjunior\",\"getValue\":74},{\"getKey\":\"xHelix\",\"getValue\":63},{\"getKey\":\"iFear\",\"getValue\":41}],\"carjacking\":[{\"getKey\":\"xHelix\",\"getValue\":2486},{\"getKey\":\"Jojo98\",\"getValue\":2059},{\"getKey\":\"Nexive\",\"getValue\":1955},{\"getKey\":\"Bones\",\"getValue\":1828},{\"getKey\":\"NoSexTonight\",\"getValue\":1601},{\"getKey\":\"Salahh\",\"getValue\":1137},{\"getKey\":\"the-killer-king\",\"getValue\":1016},{\"getKey\":\"MrStatisfied\",\"getValue\":930},{\"getKey\":\"Alone-Green\",\"getValue\":810},{\"getKey\":\"Macjunior\",\"getValue\":545}],\"crimes\":[{\"getKey\":\"xHelix\",\"getValue\":2079},{\"getKey\":\"Jojo98\",\"getValue\":1983},{\"getKey\":\"Nexive\",\"getValue\":1917},{\"getKey\":\"Bones\",\"getValue\":1824},{\"getKey\":\"NoSexTonight\",\"getValue\":1420},{\"getKey\":\"the-killer-king\",\"getValue\":1042},{\"getKey\":\"MrStatisfied\",\"getValue\":1031},{\"getKey\":\"Salahh\",\"getValue\":1017},{\"getKey\":\"Alone-Green\",\"getValue\":867},{\"getKey\":\"pThreads\",\"getValue\":847}],\"pimping\":[{\"getKey\":\"xHelix\",\"getValue\":281481},{\"getKey\":\"Bones\",\"getValue\":156244},{\"getKey\":\"Nexive\",\"getValue\":122701},{\"getKey\":\"NoSexTonight\",\"getValue\":76872},{\"getKey\":\"Jojo98\",\"getValue\":56805},{\"getKey\":\"Alone-Green\",\"getValue\":55587},{\"getKey\":\"pThreads\",\"getValue\":53333},{\"getKey\":\"the-killer-king\",\"getValue\":51794},{\"getKey\":\"MrStatisfied\",\"getValue\":39121},{\"getKey\":\"Salahh\",\"getValue\":27958}],\"smuggling\":[{\"getKey\":\"Nexive\",\"getValue\":7046600},{\"getKey\":\"MrStatisfied\",\"getValue\":5912596},{\"getKey\":\"Bones\",\"getValue\":2467673},{\"getKey\":\"Alone-Green\",\"getValue\":1208900},{\"getKey\":\"the-killer-king\",\"getValue\":961100},{\"getKey\":\"NoSexTonight\",\"getValue\":923752},{\"getKey\":\"Jojo98\",\"getValue\":724800},{\"getKey\":\"Salahh\",\"getValue\":657267},{\"getKey\":\"iFear\",\"getValue\":579378},{\"getKey\":\"Frikandel\",\"getValue\":569999}],\"referral\":[{\"getKey\":\"Jojo98\",\"getValue\":3000000},{\"getKey\":\"Yassirboz\",\"getValue\":1000000},{\"getKey\":\"Sweeter\",\"getValue\":0},{\"getKey\":\"RobertGoria\",\"getValue\":0},{\"getKey\":\"Jor\",\"getValue\":0},{\"getKey\":\"BALR\",\"getValue\":0},{\"getKey\":\"YZET\",\"getValue\":0},{\"getKey\":\"Alcazar\",\"getValue\":0},{\"getKey\":\"AlvaroTex\",\"getValue\":0},{\"getKey\":\"iFear\",\"getValue\":0}]}', NULL, 1, 1, 0);

-- ----------------------------
-- Table structure for seo
-- ----------------------------
DROP TABLE IF EXISTS `seo`;
CREATE TABLE `seo`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `routename` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `title_nl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `title_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subject_nl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `subject_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `image` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description_nl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `keywords_nl` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `keywords_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of seo
-- ----------------------------
INSERT INTO `seo` VALUES (1, 'home', 'Online Maffia RPG - Mafiasource', 'Online Mafia RPG - Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/', 'Mafiasource is een gratis tekst gebaseerd online maffia RPG ge&euml;volueerd uit crimeclub, verover staten en steden in de V.S. samen met vrienden en familie, bouw een imperium en baan je weg naar de rijkste maffia families. Word jij de nieuwste beste gangster?', 'Mafiasource is a free text based online mafia RPG evolved from crimeclub, conquer states and cities in the U.S. build an empire with your friends and family and make your way to the wealthiest Mafia families. Will you become the newest best gangster?', 'mafiasource,maffia,mafiaway,crimeclub,gangster,online,rpg,gratis,ge&euml;volueerd,mafia families,crimineel,Verenigde Staten,misdaden,moord', 'mafiasource,mafia,mafiaway,crimeclub,gangster,online,rpg,free,evolved,mafia families,criminal,United States,crimes,murder', NULL, 1, 0);
INSERT INTO `seo` VALUES (2, 'login', 'Inloggen in een wereld vol criminaliteit en corruptie - Mafiasource', 'Login to a world full of crime and corruption - Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/login', 'Zet je avontuur voort, domineer de straten van Mafiasource en baan je weg naar de top!', '\r\nContinue your adventure, dominate the streets of Mafiasource and make your way to the top!', 'inloggen,login,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer,avontuur', 'login,sign in,signn-in,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer,adventure', NULL, 1, 0);
INSERT INTO `seo` VALUES (3, 'recover-password', 'Wachtwoord herstellen voor Mafiasource', 'Recover your password for Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/recover-password', 'Je wachtwoord herstellen voor Mafiasource doe je hier, controleer zeker je e-mail spambox wanneer je onze e-mail niet onmiddelijk ontvangt.', 'You can reset your password for Mafiasource here, be sure to check your e-mail spam box if you do not receive our e-mail immediately.', 'wachtwoord herstellen,herstel wachtwoord,mafiasource,gratis online mafia rpg,online mafia,mafia rpg,rpg', 'recover password,password recovery,mafiasource,free online mafia rpg,online mafia,mafia rpg,rpg', NULL, 1, 0);
INSERT INTO `seo` VALUES (4, 'change-password', 'Wachtwoord aanpassen op Mafiasource', 'Change your password on Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/recover-password', 'Je wachtwoord bijwerken voor Mafiasource kan vanaf nu, probeer deze op een veilige plaats te bewaren om vergeten te vermeiden.', 'You can update your password for Mafiasource from now on, try to keep it in a safe place to avoid forgetting.', 'wachtwoord aanpassen,pas wachtwoord aan,mafiasource,gratis online mafia rpg,online mafia,mafia rpg,rpg', 'change password,password change,mafiasource,free online mafia rpg,online mafia,mafia rpg,rpg', NULL, 1, 0);
INSERT INTO `seo` VALUES (5, 'change-email', 'E-mailadres aanpassen op Mafiasource', 'Change your e-mail address on Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/', 'Je staat op het punt je e-mail adres aan te passen voor Mafiasource, neem onderstaande gegevens goed door om ongewenste aanpassingen te vermeiden.', 'You are about to change your e-mail address for Mafiasource, please read the details below thoroughly to avoid unwanted changes.', 'e-mailadres aanpassen,pas e-mailadres aan,mafiasource,gratis online mafia rpg,online mafia,mafia rpg,rpg', 'change email address,email address change,mafiasource,free online mafia rpg,online mafia,mafia rpg,rpg', NULL, 1, 0);
INSERT INTO `seo` VALUES (6, 'register', 'Registreer gratis je maffiabaas karakter - Mafiasource', 'Register your free mafiaboss character - Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/register', 'Jouw gratis Mafiasource account aanmaken en meteen aan de slag gaan met je criminele carri&egrave;re.', 'Create your free Mafiasource account and start immediately with your criminal career.', 'registreer,registreren,aanmelden,crimiele carri&egrave;re,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer', 'register,sign up,sign-up,criminal career,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer', NULL, 1, 0);
INSERT INTO `seo` VALUES (7, 'screenshots', 'Gameplay screenshots ontdekken - Mafiasource', 'Discover gameplay screenshots - Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/screenshots', 'Ontdek hoe Mafiasource eruit ziet binnenin aan de hand van enkele screenshots, laat je inspireren om eventueel zelf aan de slag te gaan als Maffiabaas.', 'Discover what Mafiasource looks like on the basis of a few screenshots, get inspired to possibly start working as a Mafia boss yourself.', 'screenshots,media,afbeeldingen,maffiabaas,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer', 'screenshots,media,images,mafiabosss,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer', NULL, 1, 0);
INSERT INTO `seo` VALUES (8, 'privacy-policy', 'Privacy beleid van Mafiasource', 'Privacy policy of Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/privacy-policy', 'Raadpleeg ons privacybeleid, bij vragen of opmerkingen aarzel zeker niet om ons te contacteren.', 'Consult our privacy policy, if you have any questions or comments do not hesitate to contact us.', 'privacybeleid,privacy beleid,maffiabaas,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer', 'privacypolicy,privacy policy,mafiabosss,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer', NULL, 1, 0);
INSERT INTO `seo` VALUES (9, 'terms-and-conditions', 'Algemene voorwaarden voor Mafiasource', 'Terms and conditions for Mafiasource', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/terms-and-conditions', 'Algemene voorwaarden met onze alsook jouw rechten en plichten. Wanneer je onze website gebruikt ga je automatich akkoord met onze voorwaarden.', 'Terms and conditions with our as well as your rights and obligations. When you use our website you automatically agree with our conditions.', 'algemene voorwaarden,algemene,voorwaarden,maffiabaas,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer', 'terms and conditions,terms,conditions,mafiabosss,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer', NULL, 1, 0);
INSERT INTO `seo` VALUES (10, 'not_found', '404 - Pagina werd niet (meer) gevonden op de Mafiasource', '404 - Page couldn\'t be found on Mafiasource (anymore)', 'Gratis online Maffia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/not-found', 'Awh, we hebben de gevraagde pagina nergens kunnen terug vinden op Mafiasource, onze welgemeende excuses.', 'Awh, we could not find the requested page anywhere on Mafiasource, our sincere apologies.', 'niet gevonden pagina,pagina niet gevonden,niet gevonden,maffiabaas,mafiasource,mafia,crimeclub,crime,criminals,crimineel,drugs,smokkelen,racen,casino,misdaad,misdaden,rpg,game,online,gratis,multiplayer', 'not found page,page not found,not found,mafiabosss,mafiasource,mafia,crimeclub,crime,criminals,criminal,drugs,smuggling,racing,casino,crime,crimes,rpg,game,online,free,multiplayer', NULL, 1, 0);
INSERT INTO `seo` VALUES (11, 'get-the-app', 'Download de Mafiasource beta app nu beschikbaar enkel voor android', 'Download the Mafiasource beta app now availbe for android only', 'Gratis online Mafia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/get-the-app', 'Probeer nu onze beta app uit momenteel enkel te downloaden voor android gebruikers gedurende onze beta ronde.', 'Try out our beta app currently only downloadable for android users during our beta round.', 'download app,verkrijg de app,mafiasource,maffia app', 'download app,get the app,mafiasource app,mafia app', NULL, 1, 0);
INSERT INTO `seo` VALUES (12, 'link-partners', 'Onze linkpartners - Mafiasource', 'Our link partners - Mafiasource', 'Gratis online Mafia RPG', 'Free online Mafia RPG', 'https://static.mafiasource.nl/web/public/images/mafiasource.jpg', 'mafiasource.nl/link-partners', 'Linkpartners die ons helpen groeien en andersom, jouw website erbij plaatsen? Contacteer ons!', '\r\nLink partners who help us grow and vice versa, add your website? Contact us!', 'linkpartners,links;link,partners,partner,seo,link-building', 'linkpartners,links;link,partners,partner,seo,link-building', NULL, 1, 0);
INSERT INTO `seo` VALUES (13, '', '', '', '', '', '', '', '', '', '', '664,613,997,892,457,936,451,903,530,140,172,287', NULL, 0, 0);

-- ----------------------------
-- Table structure for shoutbox_en
-- ----------------------------
DROP TABLE IF EXISTS `shoutbox_en`;
CREATE TABLE `shoutbox_en`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL COMMENT 'couple=user&factor=id&show=username',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `message` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of shoutbox_en
-- ----------------------------

-- ----------------------------
-- Table structure for shoutbox_nl
-- ----------------------------
DROP TABLE IF EXISTS `shoutbox_nl`;
CREATE TABLE `shoutbox_nl`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL COMMENT 'couple=user&factor=id&show=username',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of shoutbox_nl
-- ----------------------------

-- ----------------------------
-- Table structure for smuggle
-- ----------------------------
DROP TABLE IF EXISTS `smuggle`;
CREATE TABLE `smuggle`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `type` tinyint(1) NULL DEFAULT 1 COMMENT 'select=Drugs,Drank,Vuurwerk,Wapens,Dieren',
  `name_nl` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_en` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `level` smallint NOT NULL DEFAULT 1,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 55 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of smuggle
-- ----------------------------
INSERT INTO `smuggle` VALUES (1, 1, 'Marihuana', 'Heftige geur dit goedje', 'Marijuana', 'God this smells great', '', 1, 1, 1, 0);
INSERT INTO `smuggle` VALUES (2, 2, 'Bier', 'Waarschijnlijk het beste bier ter wereld.', 'Beer', 'Probably the best beer in the world.', '', 1, 2, 1, 0);
INSERT INTO `smuggle` VALUES (3, 3, 'Strijkers', 'Daarnee blaas je wel wat op.', 'Strings', 'You\'ll blow something with that.', '', 1, 3, 1, 0);
INSERT INTO `smuggle` VALUES (4, 4, 'Mes', 'Gevaarlijk scherp.', 'Knife', 'Dangerously sharp.', '', 1, 4, 1, 0);
INSERT INTO `smuggle` VALUES (5, 5, 'Zeepaardje', 'Gefokt door professionals.', 'Sea horse', ' Bred by professionals.', '', 1, 5, 1, 0);
INSERT INTO `smuggle` VALUES (6, 1, 'Hasj', '', 'Hash', '', '', 3, 6, 1, 0);
INSERT INTO `smuggle` VALUES (7, 2, 'Wijn', '', 'Wine', '', '', 5, 7, 1, 0);
INSERT INTO `smuggle` VALUES (8, 3, 'Window-makers', '', 'Window-makers', '', '', 7, 8, 1, 0);
INSERT INTO `smuggle` VALUES (9, 4, 'Desert Eagle', '', 'Desert Eagle', '', '', 9, 9, 1, 0);
INSERT INTO `smuggle` VALUES (10, 5, 'Papegaai', '', 'Parrot', '', '', 11, 10, 1, 0);
INSERT INTO `smuggle` VALUES (11, 1, 'Tabak', '', 'Tobacco', '', '', 13, 11, 1, 0);
INSERT INTO `smuggle` VALUES (12, 2, 'Bacardi', '', 'Bacardi', '', '', 15, 12, 1, 0);
INSERT INTO `smuggle` VALUES (13, 3, 'Knalmatten', '', 'Pop Mats', '', '', 17, 13, 1, 0);
INSERT INTO `smuggle` VALUES (14, 4, 'Samurai Zwaard', '', 'Samurai Sword', '', '', 19, 14, 1, 0);
INSERT INTO `smuggle` VALUES (15, 5, 'Slang', '', 'Snake', '', '', 21, 15, 1, 0);
INSERT INTO `smuggle` VALUES (16, 1, 'Paddo\'s', '', 'Mushrooms', '', '', 23, 16, 1, 0);
INSERT INTO `smuggle` VALUES (17, 2, 'Rum', '', 'Rum', '', '', 25, 17, 1, 0);
INSERT INTO `smuggle` VALUES (18, 3, 'Vlinders', '', 'Butterflies', '', '', 27, 18, 1, 0);
INSERT INTO `smuggle` VALUES (19, 4, 'Aka-Beta', '', 'Aka-Beta', '', '', 29, 19, 1, 0);
INSERT INTO `smuggle` VALUES (20, 5, 'Toekan', '', 'Toucan', '', '', 31, 20, 1, 0);
INSERT INTO `smuggle` VALUES (21, 1, 'Lachgas', '', 'Nitrous Oxide', '', '', 33, 21, 1, 0);
INSERT INTO `smuggle` VALUES (22, 2, 'Sambuca', '', 'Sambuca', '', '', 35, 22, 1, 0);
INSERT INTO `smuggle` VALUES (23, 3, '10.000 Chinese Rol', '', '10.000 Chinese Role', '', '', 37, 23, 1, 0);
INSERT INTO `smuggle` VALUES (24, 4, 'Thompson', '', 'Thompson', '', '', 39, 24, 1, 0);
INSERT INTO `smuggle` VALUES (25, 5, 'Flamingo', '', 'Flamingo', '', '', 41, 25, 1, 0);
INSERT INTO `smuggle` VALUES (26, 1, 'Opium', '', 'Opium', '', '', 43, 26, 1, 0);
INSERT INTO `smuggle` VALUES (27, 2, 'Whisky', '', 'Whiskey', '', '', 45, 27, 1, 0);
INSERT INTO `smuggle` VALUES (28, 3, 'Nitraten', '', 'Nitrates', '', '', 47, 28, 1, 0);
INSERT INTO `smuggle` VALUES (29, 4, 'Colt MX 17', '', 'Colt MX 17', '', '', 49, 29, 1, 0);
INSERT INTO `smuggle` VALUES (30, 5, 'Orang-oetan', '', 'orang-utan', '', '', 51, 30, 1, 0);
INSERT INTO `smuggle` VALUES (31, 1, 'GHB', '', 'GHB', '', '', 53, 31, 1, 0);
INSERT INTO `smuggle` VALUES (32, 2, 'Port', '', 'Postage', '', '', 55, 32, 1, 0);
INSERT INTO `smuggle` VALUES (33, 3, '100.000 Chinese Rol', '', '100.000 Chinese Role', '', '', 57, 33, 1, 0);
INSERT INTO `smuggle` VALUES (34, 4, 'Shotgun', '', 'Shotgun', '', '', 59, 34, 1, 0);
INSERT INTO `smuggle` VALUES (35, 5, 'Krokodil', '', 'Crocodile', '', '', 61, 35, 1, 0);
INSERT INTO `smuggle` VALUES (36, 1, 'XTC', '', 'XTC', '', '', 63, 36, 1, 0);
INSERT INTO `smuggle` VALUES (37, 2, 'Malibu', '', 'Malibu', '', '', 65, 37, 1, 0);
INSERT INTO `smuggle` VALUES (38, 3, 'Italiaanse Bommen', '', 'Italian Bombs', '', '', 67, 38, 1, 0);
INSERT INTO `smuggle` VALUES (39, 4, 'M-16', '', 'M-16', '', '', 69, 39, 1, 0);
INSERT INTO `smuggle` VALUES (40, 5, 'Tijger', '', 'Tiger', '', '', 71, 40, 1, 0);
INSERT INTO `smuggle` VALUES (41, 1, 'LSD', '', 'LSD', '', '', 73, 41, 1, 0);
INSERT INTO `smuggle` VALUES (42, 2, 'Ouzo', '', 'Ouzo', '', '', 75, 42, 1, 0);
INSERT INTO `smuggle` VALUES (43, 3, 'Mortieren', '', 'Mortars', '', '', 77, 43, 1, 0);
INSERT INTO `smuggle` VALUES (44, 4, 'RPG-7', '', 'RPG-7', '', '', 79, 44, 1, 0);
INSERT INTO `smuggle` VALUES (45, 5, 'Neushoorn', '', 'Rhino', '', '', 81, 45, 1, 0);
INSERT INTO `smuggle` VALUES (46, 1, 'HeroÃ¯ne', '', 'Heroin', '', '', 83, 46, 1, 0);
INSERT INTO `smuggle` VALUES (47, 2, 'Absint', '', 'Absinthe', '', '', 85, 47, 1, 0);
INSERT INTO `smuggle` VALUES (48, 3, '1.000.000 Chinese Rol', '', '1.000.000 Chinese Role', '', '', 87, 48, 1, 0);
INSERT INTO `smuggle` VALUES (49, 4, 'Atoombom', '', 'Nuclear Bomb', '', '', 89, 49, 1, 0);
INSERT INTO `smuggle` VALUES (50, 5, 'Olifant', '', 'Elephant', '', '', 91, 50, 1, 0);
INSERT INTO `smuggle` VALUES (51, 1, 'Cocaine', '', 'Cocaine', '', '', 93, 51, 1, 0);
INSERT INTO `smuggle` VALUES (52, 2, 'Tequila', '', 'Tequila', '', '', 95, 52, 1, 0);
INSERT INTO `smuggle` VALUES (53, 3, 'Lawinepijlen', '', 'Avalanche Arrows', '', '', 97, 53, 1, 0);
INSERT INTO `smuggle` VALUES (54, 4, 'Waterstofbom', '', 'Hydrogen Bomb', '', '', 99, 54, 1, 0);

-- ----------------------------
-- Table structure for smuggle_unit
-- ----------------------------
DROP TABLE IF EXISTS `smuggle_unit`;
CREATE TABLE `smuggle_unit`  (
  `userID` int NULL DEFAULT NULL,
  `typeNr` tinyint(1) NULL DEFAULT NULL,
  `unitNr` tinyint(1) NULL DEFAULT NULL,
  `amount` int NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of smuggle_unit
-- ----------------------------

-- ----------------------------
-- Table structure for state
-- ----------------------------
DROP TABLE IF EXISTS `state`;
CREATE TABLE `state`  (
  `id` tinyint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` smallint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of state
-- ----------------------------
INSERT INTO `state` VALUES (1, 'Hawaii', 0, 1, 0);
INSERT INTO `state` VALUES (2, 'California', 1, 1, 0);
INSERT INTO `state` VALUES (3, 'New York', 2, 1, 0);
INSERT INTO `state` VALUES (4, 'Colorado', 3, 1, 0);
INSERT INTO `state` VALUES (5, 'Texas', 4, 1, 0);
INSERT INTO `state` VALUES (6, 'Florida', 5, 1, 0);
INSERT INTO `state` VALUES (7, 'New Jersey', 6, 0, 0);
INSERT INTO `state` VALUES (8, 'Illinois', 7, 0, 0);
INSERT INTO `state` VALUES (9, 'Michigan', 8, 0, 0);
INSERT INTO `state` VALUES (10, 'Ohio', 9, 0, 0);
INSERT INTO `state` VALUES (11, 'Nevada', 10, 0, 0);
INSERT INTO `state` VALUES (12, 'Louisiana', 11, 0, 0);
INSERT INTO `state` VALUES (13, 'Pennsylvania', 12, 0, 0);

-- ----------------------------
-- Table structure for status
-- ----------------------------
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `status_nl` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status_en` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `colorCode` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of status
-- ----------------------------
INSERT INTO `status` VALUES (1, 'Webmaster', 'Webmaster', '', '', '#509000', 1, 1, 1);
INSERT INTO `status` VALUES (2, 'Administrator', 'Administrator', 'De admins beheren het spel en zijn leden, ontwikkelen nieuwe updates en lossen fouten op.', 'The admins manage the game and it\'s members, develop new updates and fix bugs.', '#FF0000', 2, 1, -1);
INSERT INTO `status` VALUES (3, 'Moderator', 'Moderator', 'De moderators letten op het spel, bannen en waarschuwen waar het noodzakelijk is.', 'The moderators pay attention to the game, ban and warn where it is necessary.', '#9b94f9', 3, 1, -1);
INSERT INTO `status` VALUES (4, 'Game Moderator', 'Game Moderator', NULL, NULL, '#FFA502', 4, 0, 0);
INSERT INTO `status` VALUES (5, 'Forum Moderator', 'Forum Moderator', NULL, NULL, '#ADADAD', 5, 0, 0);
INSERT INTO `status` VALUES (6, 'Helpdesk', 'Helpdesk', 'Helpdesks beantwoorden alle vragen van alle spelers over het spel en maken onze help pagina\'s op.', '\r\nHelpdesks answer all questions from all players about the game and create our help pages.', '#39DE00', 7, 1, 0);
INSERT INTO `status` VALUES (7, 'Lid', 'Member', NULL, NULL, '#FFFFFF', 8, 1, 0);
INSERT INTO `status` VALUES (8, 'Verbannen', 'Banned', NULL, NULL, '#000000', 9, 1, 0);

-- ----------------------------
-- Table structure for steal_vehicle
-- ----------------------------
DROP TABLE IF EXISTS `steal_vehicle`;
CREATE TABLE `steal_vehicle`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name_nl` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_nl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_en` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `description_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `level` smallint NOT NULL DEFAULT 1,
  `difficulty` smallint NOT NULL DEFAULT 5,
  `maxRankPoints` tinyint(1) NOT NULL DEFAULT 1,
  `donatorID` tinyint(1) NULL DEFAULT 0 COMMENT 'couple=donator&factor=id&show=donator_nl',
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 56 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of steal_vehicle
-- ----------------------------
INSERT INTO `steal_vehicle` VALUES (1, 'Breek in een auto', 'Gebruikt een zachte handdoek om het bestuurders raam te breken.', 'Break into a car', 'Use a soft towel to break the drivers windows.', '4daa94fc.png', 1, 1, 1, 0, 0, 1, 0);
INSERT INTO `steal_vehicle` VALUES (2, 'Steel autosleutels', 'Grijp naar een handtas om de autosleutels te jatten!', 'Steal car keys', 'Grab a purse to steal some car keys!', '3b492d07.jpg', 3, 4, 2, 0, 1, 1, 0);
INSERT INTO `steal_vehicle` VALUES (3, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 2, 1, 1);
INSERT INTO `steal_vehicle` VALUES (4, 'steel een oldtimer', 'Steel een oud voertuig zonder alarm', '', '', '59775155.jpg', 6, 5, 1, 0, 3, 1, 0);
INSERT INTO `steal_vehicle` VALUES (5, 'Steel uit een parkeergarage.', 'Steel een voertuig uit een onbewaakte parkeergarage.', '', '', '3c11e013.jpg', 8, 5, 1, 0, 4, 1, 0);
INSERT INTO `steal_vehicle` VALUES (6, 'steel van een schroothoop', 'Steel een voertuig van de schroothoop.', '', '', '81f49588.jpg', 11, 5, 1, 0, 5, 1, 0);
INSERT INTO `steal_vehicle` VALUES (7, 'Steel bij een camping', 'Steel een voertuig bij een camping.', '', '', 'a62b4bde.jpg', 14, 5, 1, 0, 6, 1, 0);
INSERT INTO `steal_vehicle` VALUES (8, 'Steel bij een discotheek', 'Steel een voertuig bij een onbewaakt feestje.', '', '', '6a4e809c.php', 16, 5, 1, 0, 7, 1, 0);
INSERT INTO `steal_vehicle` VALUES (9, 'Breek in een auto', 'Gebruik een schroevendraaier om de autodeur open te maken.', '', '', '73061e3e.png', 19, 5, 1, 0, 8, 1, 0);
INSERT INTO `steal_vehicle` VALUES (10, 'Steel een bedijfswagen', 'Steel een auto van een zakenman', '', '', 'd6b00123.jpg', 22, 5, 1, 0, 9, 1, 0);
INSERT INTO `steal_vehicle` VALUES (11, 'Steel autosleutels', 'Steel de autosleutels uit een druk cafe.', '', '', '021ec791.jpg', 24, 5, 1, 0, 10, 1, 0);
INSERT INTO `steal_vehicle` VALUES (12, 'steel uit een klein dorp', 'Steel een voertuig in een klein dorp in Belgie', '', '', '6513b6b5.jpg', 27, 5, 1, 0, 11, 1, 0);
INSERT INTO `steal_vehicle` VALUES (13, 'steel van een voetballer', 'Steel de auto van Neymar Junior', '', '', '9b099877.jpg', 29, 5, 1, 0, 12, 1, 0);
INSERT INTO `steal_vehicle` VALUES (14, 'Steel van een vliegveld', 'Steel een auto uit de parkeergarage van het vliegveld', '', '', 'f6b455ce.jpg', 32, 5, 1, 0, 13, 1, 0);
INSERT INTO `steal_vehicle` VALUES (15, 'Beroof een teamlid', 'Steel een voertuig van Spilner', '', '', 'c8afa5a0.jpg', 34, 5, 1, 0, 14, 1, 0);
INSERT INTO `steal_vehicle` VALUES (16, 'Breek in een auto', 'Slaag de ruit in met een voorwerp', '', '', '0d33633e.png', 36, 5, 1, 0, 15, 1, 0);
INSERT INTO `steal_vehicle` VALUES (17, 'Steel autosleutels', 'Steel de autosleutels bij een sportschool', '', '', '20e98258.png', 39, 5, 1, 0, 16, 1, 0);
INSERT INTO `steal_vehicle` VALUES (18, 'Steel een bedrijfswagen', 'Steel de auto van een politieker', '', '', '3a659298.jpg', 41, 5, 1, 0, 17, 1, 0);
INSERT INTO `steal_vehicle` VALUES (19, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 18, 1, 1);
INSERT INTO `steal_vehicle` VALUES (20, 'Steel van een voetbalploeg', 'Steel de supporters bus van Royal Antwerp FC', '', '', '1e164ed8.jpg', 43, 5, 1, 0, 19, 1, 0);
INSERT INTO `steal_vehicle` VALUES (21, 'Steel uit een gemeente', 'Steel een auto uit de gemeente Wilrijk', '', '', 'b91769fb.jpg', 46, 5, 1, 0, 20, 1, 0);
INSERT INTO `steal_vehicle` VALUES (22, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 21, 1, 1);
INSERT INTO `steal_vehicle` VALUES (23, 'steel van een voetballer', 'Steel de club auto van Tuur Dierckx ', '', '', 'd3b35d55.jpg', 49, 5, 1, 0, 22, 1, 0);
INSERT INTO `steal_vehicle` VALUES (24, 'Steel van een agent', 'Steel een politie auto bij het politieburaeau', '', '', 'b5794194.jpg', 51, 5, 1, 0, 23, 1, 0);
INSERT INTO `steal_vehicle` VALUES (25, 'Steel van een teamlid', 'Steel de auto van Alcazar', '', '', 'fec0ecbe.png', 53, 5, 1, 0, 24, 1, 0);
INSERT INTO `steal_vehicle` VALUES (26, 'Steel een auto bij de gevangenis', 'steel de auto van een bezoeker', 'Steal a car at the prison', 'steal a visitor\'s car', '23acbd41.jpg', 55, 5, 1, 0, 25, 1, 0);
INSERT INTO `steal_vehicle` VALUES (27, 'Breek in bij een huis en steel', '', 'Break in at a house and steal ', '', '25eb5bb0.jpg', 57, 5, 1, 0, 26, 1, 0);
INSERT INTO `steal_vehicle` VALUES (28, 'Steel een voertuig bij een bos', '', 'Steal a vehicle at a forest ho', '', '', 58, 5, 1, 0, 27, 1, 0);
INSERT INTO `steal_vehicle` VALUES (29, 'steel een auto op straat', 'Sleur iemand uit een voertuig die staat te wachten voor het stoplicht.', 'steal a car on the street', 'Drag someone from a vehicle that is waiting for the traffic light.', '', 60, 5, 1, 0, 28, 1, 0);
INSERT INTO `steal_vehicle` VALUES (30, '', 'Steel een voertuig bij een Japanse Villa.', '', '', '', 61, 5, 1, 0, 29, 1, 0);
INSERT INTO `steal_vehicle` VALUES (31, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 30, 1, 1);
INSERT INTO `steal_vehicle` VALUES (32, '', 'Steel een voertuig van een advocaat.', '', 'Steal a vehicle from a lawyer.', '', 64, 5, 1, 0, 31, 1, 0);
INSERT INTO `steal_vehicle` VALUES (33, '', 'Steel de sleutels uit een koffertje van een zakenman.', '', 'Steal the keys from a suitcase of a businessman.', '', 65, 5, 1, 0, 32, 1, 0);
INSERT INTO `steal_vehicle` VALUES (34, '', 'Steel een voertuig bij een Mediteraanse Villa.', '', 'Steal a vehicle at a Mediterranean Villa.', '', 67, 5, 1, 0, 33, 1, 0);
INSERT INTO `steal_vehicle` VALUES (35, '', 'Steel een voertuig van een drugsbende.', '', 'Steal a vehicle from a drugs gang.', '', 68, 5, 1, 0, 34, 1, 0);
INSERT INTO `steal_vehicle` VALUES (36, '', 'Steel een voertuig bij een groot bedrijf.', '', 'Steal a vehicle at a large company.', '', 70, 5, 1, 0, 35, 1, 0);
INSERT INTO `steal_vehicle` VALUES (37, '', 'Steel een voertuig uit een zwaar bewaakte garage.', '', 'Steal a vehicle from a heavily guarded garage.', '', 71, 5, 1, 0, 36, 1, 0);
INSERT INTO `steal_vehicle` VALUES (38, '', 'Steel een voertuig bij een Pool Paradise.', '', 'Steal a vehicle at a Pool Paradise.', '', 73, 5, 1, 0, 37, 1, 0);
INSERT INTO `steal_vehicle` VALUES (39, '', 'Steel een voertuig uit een showroom.', '', 'Steal a vehicle from a showroom.', '', 75, 5, 1, 0, 38, 1, 0);
INSERT INTO `steal_vehicle` VALUES (40, '', 'Steel een voertuig van een politicus.', '', 'Steal a vehicle from a politician.', '', 77, 5, 1, 0, 39, 1, 0);
INSERT INTO `steal_vehicle` VALUES (41, '', 'Steel een voertuig bij een Landgoed.', '', 'Steal a vehicle at a Estate.', '', 78, 5, 1, 0, 40, 1, 0);
INSERT INTO `steal_vehicle` VALUES (42, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 41, 1, 1);
INSERT INTO `steal_vehicle` VALUES (43, '', 'Steel een voertuig van een lid van de koninklijke familie.', '', 'Steal a vehicle from a member of the royal family.', '', 80, 5, 1, 0, 42, 1, 0);
INSERT INTO `steal_vehicle` VALUES (44, '', 'Steel een voertuig uit een zwaarbewaakt transportvliegtuig.', '', 'Steal a vehicle from a heavily guarded transport plane.', '', 81, 5, 1, 0, 43, 1, 0);
INSERT INTO `steal_vehicle` VALUES (45, '', 'Steel een voertuig bij de miljonair fair.', '', 'Steal a vehicle at the millionaire fair.', '', 84, 5, 1, 0, 44, 1, 0);
INSERT INTO `steal_vehicle` VALUES (46, '', 'Steel een voertuig van een filmster.', '', 'Steal a vehicle from a movie star.', '', 85, 5, 1, 0, 45, 1, 0);
INSERT INTO `steal_vehicle` VALUES (47, '', 'Steel een voertuig van een rijke sjeik.', '', 'Steal a vehicle from a rich sheik.', '', 87, 5, 1, 0, 46, 1, 0);
INSERT INTO `steal_vehicle` VALUES (48, '', 'Steel een voertuig van de vice-president.', '', 'Steal a vehicle from the vice president.', '', 88, 5, 1, 0, 47, 1, 0);
INSERT INTO `steal_vehicle` VALUES (49, NULL, NULL, NULL, NULL, NULL, 1, 5, 1, 0, 48, 1, 1);
INSERT INTO `steal_vehicle` VALUES (50, '', 'Steel de voertuig van een Oost-Indische prins.', '', 'Steal the vehicle of an East Indian prince.', '', 90, 5, 1, 0, 49, 1, 0);
INSERT INTO `steal_vehicle` VALUES (51, '', 'Steel een voertuig bij een Tropische Villa.', '', 'Steal a vehicle at a Tropical Villa.', '', 91, 5, 1, 0, 50, 1, 0);
INSERT INTO `steal_vehicle` VALUES (52, '', 'Steel een voertuig uit het museum.', '', 'Steal a vehicle from the museum.', '', 94, 5, 1, 0, 51, 1, 0);
INSERT INTO `steal_vehicle` VALUES (53, '', 'Steel de sleutels van de koning.', '', 'Steal the keys of the king.', '', 95, 5, 1, 0, 52, 1, 0);
INSERT INTO `steal_vehicle` VALUES (54, '', 'Steel een voertuig van de president.', '', 'Steal a vehicle from the president.', '', 97, 5, 1, 0, 53, 1, 0);
INSERT INTO `steal_vehicle` VALUES (55, '', 'Steel een van de keizer', '', 'Steal one of the emperor', '', 100, 5, 1, 0, 54, 1, 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type=disabled',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'type=disabled',
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registerDate` datetime NOT NULL,
  `restartDate` datetime NOT NULL,
  `isProtected` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'type=yesno',
  `lastclick` bigint NULL DEFAULT NULL,
  `activeTime` bigint NOT NULL DEFAULT 0,
  `lang` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `charType` tinyint NOT NULL DEFAULT 1 COMMENT 'couple=profession&factor=id&show=profession_nl',
  `referralOf` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `referrals` int NOT NULL DEFAULT 0,
  `referralProfits` bigint NOT NULL DEFAULT 0,
  `warns` smallint NOT NULL DEFAULT 0,
  `testamentHolder` bigint NOT NULL DEFAULT 0 COMMENT 'couple=user&factor=id&show=username',
  `avatar` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `profile` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `privateID` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=disabled',
  `forumPosts` int NOT NULL DEFAULT 0,
  `statusID` smallint NOT NULL DEFAULT 7 COMMENT 'couple=status&factor=id&show=status_nl',
  `donatorID` smallint NOT NULL DEFAULT 0 COMMENT 'couple=donator&factor=id&show=donator_nl',
  `stateID` smallint NOT NULL DEFAULT 1 COMMENT 'couple=state&factor=id&show=name',
  `cityID` smallint NOT NULL DEFAULT 1 COMMENT 'couple=city&factor=id&show=name',
  `familyID` int NOT NULL DEFAULT 0 COMMENT 'couple=family&factor=id&show=name',
  `rankpoints` float(15, 1) NOT NULL DEFAULT 0.5,
  `health` smallint NOT NULL DEFAULT 100,
  `score` bigint NOT NULL DEFAULT 0,
  `cash` bigint NOT NULL DEFAULT 2500,
  `bank` bigint NOT NULL DEFAULT 10000,
  `swissBank` bigint NOT NULL DEFAULT 0,
  `swissBankMax` bigint NOT NULL DEFAULT 100000000,
  `prisonBusts` int NOT NULL DEFAULT 0,
  `honorPoints` bigint NOT NULL DEFAULT 0,
  `whoresStreet` bigint NOT NULL DEFAULT 0,
  `kills` int NOT NULL DEFAULT 0,
  `deaths` int NOT NULL DEFAULT 0,
  `headshots` int NOT NULL DEFAULT 0,
  `bullets` bigint NOT NULL DEFAULT 10,
  `weapon` smallint NOT NULL DEFAULT 0 COMMENT 'couple=weapon&factor=id&show=name',
  `protection` smallint NOT NULL DEFAULT 0 COMMENT 'couple=protection&factor=id&show=name',
  `airplane` smallint NOT NULL DEFAULT 0 COMMENT 'couple=airplane&factor=id&show=name',
  `weaponExperience` smallint NOT NULL DEFAULT 0,
  `weaponTraining` smallint NOT NULL DEFAULT 0,
  `backfireType` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'select=Percentage,Nummer,Alles,Dubbel,Even,Helft',
  `backfireNumber` bigint NOT NULL DEFAULT 0,
  `residence` smallint NOT NULL DEFAULT 0 COMMENT 'couple=residence&factor=id&show=name_nl',
  `residenceHistory` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'type=disabled',
  `gymFastAction` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'type=Opdrukken,Fietsen,Bankdrukken,Hardlopen',
  `power` int NOT NULL DEFAULT 0,
  `cardio` int NOT NULL DEFAULT 0,
  `gymCompetitionWin` int NOT NULL DEFAULT 0,
  `gymCompetitionLoss` int NOT NULL DEFAULT 0,
  `gymProfit` bigint NOT NULL DEFAULT 0,
  `gymScorePointsEarned` int NOT NULL DEFAULT 0,
  `daily1Amount` mediumint NOT NULL DEFAULT 0,
  `daily2Amount` mediumint NOT NULL DEFAULT 0,
  `daily3Amount` mediumint NOT NULL DEFAULT 0,
  `dailyCompletedDays` int NOT NULL DEFAULT 1,
  `luckybox` int NOT NULL DEFAULT 0,
  `credits` bigint NOT NULL DEFAULT 0,
  `creditsWon` int NOT NULL DEFAULT 0,
  `crimesLv` smallint NOT NULL DEFAULT 1,
  `crimesXp` float(5, 2) NOT NULL DEFAULT 0.00,
  `crimesProfit` bigint NOT NULL DEFAULT 0,
  `crimesSuccess` int NOT NULL DEFAULT 0,
  `crimesFail` int NOT NULL DEFAULT 0,
  `crimesRankpoints` int NOT NULL DEFAULT 0,
  `vehiclesLv` smallint NOT NULL DEFAULT 1,
  `vehiclesXp` float(5, 2) NOT NULL DEFAULT 0.00,
  `vehiclesProfit` bigint NOT NULL DEFAULT 0,
  `vehiclesSuccess` int NOT NULL DEFAULT 0,
  `vehiclesFail` int NOT NULL DEFAULT 0,
  `vehiclesRankpoints` int NOT NULL DEFAULT 0,
  `pimpLv` smallint NOT NULL DEFAULT 1,
  `pimpXp` float(5, 2) NOT NULL DEFAULT 0.00,
  `pimpProfit` bigint NOT NULL DEFAULT 0,
  `pimpAttempts` int NOT NULL DEFAULT 0,
  `pimpAmount` int NOT NULL DEFAULT 0,
  `smugglingLv` smallint NOT NULL DEFAULT 1,
  `smugglingXp` float(5, 2) NOT NULL DEFAULT 0.00,
  `smugglingProfit` bigint NOT NULL DEFAULT 0,
  `smugglingTrips` int NOT NULL DEFAULT 0,
  `smugglingUnits` int NOT NULL DEFAULT 0,
  `smugglingBusts` int NOT NULL DEFAULT 0,
  `m5c` mediumint NOT NULL DEFAULT 0,
  `m8c` mediumint NOT NULL DEFAULT 0,
  `publicMission` mediumint NOT NULL DEFAULT 0,
  `lrsID_nl` int NOT NULL DEFAULT 0 COMMENT 'type=disabled',
  `lrsID_en` int NOT NULL DEFAULT 0 COMMENT 'type=disabled',
  `lrfsID_nl` int NOT NULL DEFAULT 0 COMMENT 'type=disabled',
  `lrfsID_en` int NOT NULL DEFAULT 0 COMMENT 'type=disabled',
  `ground` tinyint(1) NOT NULL DEFAULT 0,
  `smugglingCapacity` smallint NOT NULL DEFAULT 0,
  `cHalvingTimes` bigint NOT NULL DEFAULT 0,
  `cBribingPolice` bigint NOT NULL DEFAULT 0,
  `cCrimes` bigint NOT NULL DEFAULT 0,
  `cWeaponTraining` bigint NOT NULL DEFAULT 0,
  `cGymTraining` bigint NOT NULL DEFAULT 0,
  `cStealVehicles` bigint NOT NULL DEFAULT 0,
  `cPimpWhores` bigint NOT NULL DEFAULT 0,
  `cFamilyRaid` bigint NOT NULL DEFAULT 0,
  `cFamilyCrimes` bigint NOT NULL DEFAULT 0,
  `cBombardement` bigint NOT NULL DEFAULT 0,
  `cTravelTime` bigint NOT NULL DEFAULT 0,
  `cPimpWhoresFor` bigint NOT NULL DEFAULT 0,
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `by_username`(`username`) USING BTREE,
  INDEX `by_score`(`score`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user
-- ----------------------------

-- ----------------------------
-- Table structure for user_friend_block
-- ----------------------------
DROP TABLE IF EXISTS `user_friend_block`;
CREATE TABLE `user_friend_block`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `inviterID` bigint NOT NULL COMMENT 'couple=user&factor=id&show=username',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `friendID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `type` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user_friend_block
-- ----------------------------

-- ----------------------------
-- Table structure for user_garage
-- ----------------------------
DROP TABLE IF EXISTS `user_garage`;
CREATE TABLE `user_garage`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `stateID` smallint NULL DEFAULT NULL COMMENT 'couple=state&factor=id&show=name',
  `userID` bigint NULL DEFAULT NULL COMMENT 'couple=user&factor=id&show=username',
  `size` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'small',
  `position` bigint NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user_garage
-- ----------------------------

-- ----------------------------
-- Table structure for user_mission_carjacker
-- ----------------------------
DROP TABLE IF EXISTS `user_mission_carjacker`;
CREATE TABLE `user_mission_carjacker`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `userID` bigint NOT NULL DEFAULT 0,
  `vehicleID` int NOT NULL DEFAULT 0,
  `stolenAmount` mediumint NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user_mission_carjacker
-- ----------------------------

-- ----------------------------
-- Table structure for user_residence
-- ----------------------------
DROP TABLE IF EXISTS `user_residence`;
CREATE TABLE `user_residence`  (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `userID` bigint NULL DEFAULT NULL,
  `residenceID` smallint NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of user_residence
-- ----------------------------

-- ----------------------------
-- Table structure for vehicle
-- ----------------------------
DROP TABLE IF EXISTS `vehicle`;
CREATE TABLE `vehicle`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'type=disabled',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'type=cms',
  `picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NOT NULL DEFAULT 0,
  `horsepower` smallint NULL DEFAULT 0,
  `topspeed` smallint NULL DEFAULT NULL,
  `acceleration` smallint NULL DEFAULT NULL,
  `control` smallint NULL DEFAULT NULL,
  `breaking` smallint NULL DEFAULT NULL,
  `stealLv` smallint NULL DEFAULT 1,
  `position` int NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 119 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of vehicle
-- ----------------------------
INSERT INTO `vehicle` VALUES (1, 'Dodge Viper SRT', '', '884c7939.jpg', 90000, 650, 332, 75, 3, 85, 34, 0, 1, 0);
INSERT INTO `vehicle` VALUES (2, 'AC Cobra 427', '', 'ae853880.jpg', 625000, 485, 257, 68, 18, 47, 83, 1, 1, 0);
INSERT INTO `vehicle` VALUES (3, 'Alfa Romeo 8C', '', 'c4bdcf84.jpg', 196000, 444, 305, 69, 55, 38, 59, 2, 1, 0);
INSERT INTO `vehicle` VALUES (4, 'Ascari KZ-1', '', 'b7d25c99.jpg', 550000, 500, 321, 71, 16, 24, 81, 3, 1, 0);
INSERT INTO `vehicle` VALUES (5, 'Aston Martin DB9 ', '', '11a3efa3.jpg', 98000, 450, 304, 61, 35, 75, 36, 4, 1, 0);
INSERT INTO `vehicle` VALUES (6, 'Aston Martin V12 Vantage ', '', '2b9fe5f6.jpg', 115000, 510, 305, 68, 42, 86, 43, 5, 1, 0);
INSERT INTO `vehicle` VALUES (7, 'Aston Martin DBS ', '', '5987ecd9.jpg', 113000, 510, 313, 67, 38, 72, 42, 6, 1, 0);
INSERT INTO `vehicle` VALUES (8, 'Aston Martin One-77 ', '', '66f7e83a.jpg', 1900000, 750, 354, 75, 0, 0, 104, 7, 1, 0);
INSERT INTO `vehicle` VALUES (9, 'Aston Martin Vanquish ', '', '69f9295c.jpg', 181000, 568, 323, 74, 19, 45, 56, 8, 1, 0);
INSERT INTO `vehicle` VALUES (10, 'Aston Martin DB5 ', '', 'c41f394a.jpg', 890000, 282, 233, 31, 51, 50, 89, 9, 1, 0);
INSERT INTO `vehicle` VALUES (11, 'Aston Martin DB11 ', '', '5898039a.jpg', 184000, 600, 321, 70, 53, 66, 57, 10, 1, 0);
INSERT INTO `vehicle` VALUES (12, 'Audi R8 V10', '', 'e2f33c0e.jpg', 190000, 518, 317, 72, 24, 90, 58, 11, 1, 0);
INSERT INTO `vehicle` VALUES (13, 'Audi RS5 ', '', '7d84982e.jpg', 69000, 444, 280, 70, 46, 6, 28, 12, 1, 0);
INSERT INTO `vehicle` VALUES (14, 'Audi RS6 ', '', '72f0bcee.jpg', 70000, 572, 250, 66, 54, 33, 29, 13, 1, 0);
INSERT INTO `vehicle` VALUES (15, 'Audi Q7 ', '', '5f4d093b.jpg', 50000, 500, 250, 57, 43, 56, 21, 14, 1, 0);
INSERT INTO `vehicle` VALUES (16, 'BMW i8 ', '', '8b40c174.jpg', 125000, 357, 250, 69, 22, 45, 46, 15, 1, 0);
INSERT INTO `vehicle` VALUES (17, 'BMW M3 ', '', 'ac391198.jpg', 40000, 414, 250, 64, 89, 21, 16, 16, 1, 0);
INSERT INTO `vehicle` VALUES (18, 'BMW M5 ', '', 'aec850a0.jpg', 50000, 507, 250, 67, 72, 53, 22, 17, 1, 0);
INSERT INTO `vehicle` VALUES (19, 'BMW M4 ', '', 'ed8a2488.jpg', 65000, 425, 250, 71, 69, 74, 26, 18, 1, 0);
INSERT INTO `vehicle` VALUES (20, 'BMW M6 ', '', 'af032942.jpg', 110000, 552, 250, 70, 62, 67, 40, 19, 1, 0);
INSERT INTO `vehicle` VALUES (21, 'BMW Z4 M Coupe ', '', '2a49174d.jpg', 30000, 338, 250, 61, 32, 11, 12, 20, 1, 0);
INSERT INTO `vehicle` VALUES (22, 'BMW Z8 ', '', '0864736f.jpg', 205000, 400, 257, 63, 42, 33, 61, 21, 1, 0);
INSERT INTO `vehicle` VALUES (23, 'BMW 850 Csi ', '', '04ae5521.jpg', 35000, 380, 270, 46, 4, 41, 14, 22, 1, 0);
INSERT INTO `vehicle` VALUES (24, 'Bentley Continental GT ', '', 'bb2698ef.jpg', 166000, 567, 320, 66, 54, 51, 53, 23, 1, 0);
INSERT INTO `vehicle` VALUES (25, 'Bentley Mulsanne ', '', '7450e434.jpg', 270000, 530, 305, 62, 35, 75, 67, 24, 1, 0);
INSERT INTO `vehicle` VALUES (26, 'Bugatti EB110 Supersport ', '', 'f5150c30.jpg', 530000, 611, 350, 71, 91, 19, 79, 25, 1, 0);
INSERT INTO `vehicle` VALUES (27, 'Bugatti Chiron ', '', '24f8a997.jpg', 2200000, 1479, 420, 87, 0, 0, 108, 26, 1, 0);
INSERT INTO `vehicle` VALUES (28, 'Bugatti Veyron ', '', '1484f62d.jpg', 1000000, 1001, 408, 85, 40, 40, 94, 27, 1, 0);
INSERT INTO `vehicle` VALUES (29, 'Chevrolet Corvette ZR1 ', '', '668d70f1.jpg', 90000, 638, 330, 75, 30, 82, 35, 28, 1, 0);
INSERT INTO `vehicle` VALUES (30, 'Chevrolet Camaro SS ', '', '8f56e0f3.jpg', 53000, 426, 255, 63, 6, 32, 23, 29, 1, 0);
INSERT INTO `vehicle` VALUES (31, 'Chevrolet Corvette C7 Stingray ', '', 'c7937797.jpg', 72000, 460, 290, 72, 49, 61, 31, 30, 1, 0);
INSERT INTO `vehicle` VALUES (32, 'Dodge Viper SRT-10 ', '', '9c168ba3.jpg', 70000, 600, 325, 75, 56, 76, 30, 31, 1, 0);
INSERT INTO `vehicle` VALUES (33, 'Dodge Challenger SRT8 ', '', '29ff9d27.jpg', 45000, 470, 273, 66, 53, 91, 19, 32, 1, 0);
INSERT INTO `vehicle` VALUES (34, 'Dodge Charger SRT8', '', '4fe8dfd5.jpg', 43000, 470, 282, 66, 26, 63, 18, 33, 1, 0);
INSERT INTO `vehicle` VALUES (35, 'Ferrari F12 ', '', 'bb778480.jpg', 287000, 730, 350, 81, 47, 50, 71, 34, 1, 0);
INSERT INTO `vehicle` VALUES (36, 'Ferrari 575M Maranello ', '', '35e38ca5.jpg', 170000, 510, 325, 68, 62, 25, 54, 35, 1, 0);
INSERT INTO `vehicle` VALUES (37, 'Ferrari F40 ', '', 'd63440ce.jpg', 985000, 478, 323, 71, 5, 57, 93, 36, 1, 0);
INSERT INTO `vehicle` VALUES (38, 'Ferrari F50 ', '', 'e143c154.jpg', 835000, 513, 325, 73, 21, 88, 87, 37, 1, 0);
INSERT INTO `vehicle` VALUES (39, 'Ferrari Enzo ', '', 'dceaaa52.jpg', 2000000, 650, 350, 76, 0, 0, 105, 38, 1, 0);
INSERT INTO `vehicle` VALUES (40, 'Ferrari LaFerrari ', '', '1426bae4.jpg', 1500000, 950, 365, 85, 0, 0, 101, 39, 1, 0);
INSERT INTO `vehicle` VALUES (41, 'Ferrari 599 GTO ', '', 'a346617b.jpg', 536000, 661, 336, 78, 59, 60, 80, 40, 1, 0);
INSERT INTO `vehicle` VALUES (42, 'Ferrari 458 Italia ', '', '1c524852.jpg', 205000, 562, 325, 77, 52, 18, 62, 41, 1, 0);
INSERT INTO `vehicle` VALUES (43, 'Ford GT ', '', 'fbf52448.jpg', 300000, 550, 337, 74, 41, 37, 72, 42, 1, 0);
INSERT INTO `vehicle` VALUES (44, 'Ford Mustang ', '', '7c134abb.jpg', 45000, 300, 240, 58, 36, 93, 20, 43, 1, 0);
INSERT INTO `vehicle` VALUES (45, 'Hennessey Venom GT ', '', '346da17f.jpg', 800000, 1244, 434, 83, 58, 30, 85, 44, 1, 0);
INSERT INTO `vehicle` VALUES (46, 'Honda Civic Type-R ', '', '87f6e3e6.jpg', 35000, 306, 265, 56, 25, 22, 15, 45, 1, 0);
INSERT INTO `vehicle` VALUES (47, 'Honda NSX ', '', '86ae7f20.jpg', 150000, 573, 305, 82, 7, 78, 50, 46, 1, 0);
INSERT INTO `vehicle` VALUES (48, 'Jaguar F-Type R ', '', '0dc03726.jpg', 110000, 542, 300, 71, 29, 18, 41, 47, 1, 0);
INSERT INTO `vehicle` VALUES (49, 'Jaguar XKR ', '', 'b7c19839.jpg', 66000, 503, 250, 60, 77, 27, 27, 48, 1, 0);
INSERT INTO `vehicle` VALUES (50, 'Jaguar E-Type ', '', '03a3d73a.jpg', 125000, 265, 240, 39, 20, 35, 47, 49, 1, 0);
INSERT INTO `vehicle` VALUES (51, 'Jaguar XJ220 ', '', '814dbbff.jpg', 390000, 542, 350, 74, 32, 80, 75, 50, 1, 0);
INSERT INTO `vehicle` VALUES (52, 'Koenigsegg Agera R ', '', 'b6b193a7.jpg', 1400000, 1115, 429, 82, 82, 20, 99, 51, 1, 0);
INSERT INTO `vehicle` VALUES (53, 'Koenigsegg CCX ', '', '00bd6ab4.jpg', 830000, 806, 390, 79, 63, 87, 86, 52, 1, 0);
INSERT INTO `vehicle` VALUES (54, 'Koenigsegg Regera ', '', '1d42a847.jpg', 1400000, 1479, 410, 83, 68, 14, 100, 53, 1, 0);
INSERT INTO `vehicle` VALUES (55, 'Lamborghini Aventador LP700 ', '', '9eee163c.jpg', 310000, 700, 350, 82, 36, 28, 73, 54, 1, 0);
INSERT INTO `vehicle` VALUES (56, 'Lamborghini Murcielago ', '', 'bf335b23.jpg', 250000, 571, 330, 73, 58, 35, 66, 55, 1, 0);
INSERT INTO `vehicle` VALUES (57, 'Lamborghini Gallardo ', '', '761f54bd.jpg', 175000, 493, 310, 69, 54, 36, 55, 56, 1, 0);
INSERT INTO `vehicle` VALUES (58, 'Lamborghini Huracan LP610 ', '', 'd5f1b5ce.jpg', 215000, 602, 325, 83, 20, 60, 63, 57, 1, 0);
INSERT INTO `vehicle` VALUES (59, 'Lamborghini Miura S ', '', 'fac7f181.jpg', 1300000, 360, 285, 45, 79, 77, 97, 58, 1, 0);
INSERT INTO `vehicle` VALUES (60, 'Maserati MC12 ', '', '4dc4a926.jpg', 1340000, 622, 330, 73, 67, 92, 98, 59, 1, 0);
INSERT INTO `vehicle` VALUES (61, 'Maserati Granturismo ', '', '07614b55.jpg', 60000, 340, 280, 62, 15, 46, 24, 60, 1, 0);
INSERT INTO `vehicle` VALUES (62, 'McLaren F1 ', '', '1aebd984.jpg', 1000000, 627, 385, 79, 80, 65, 95, 61, 1, 0);
INSERT INTO `vehicle` VALUES (63, 'McLaren P1 ', '', '34a824b2.jpg', 1600000, 903, 350, 84, 0, 0, 102, 62, 1, 0);
INSERT INTO `vehicle` VALUES (64, 'McLaren 650S ', '', '96258a47.jpg', 230000, 641, 335, 81, 37, 12, 64, 63, 1, 0);
INSERT INTO `vehicle` VALUES (65, 'McLaren MP4-12C ', '', '58540562.jpg', 150000, 592, 325, 79, 66, 66, 51, 64, 1, 0);
INSERT INTO `vehicle` VALUES (66, 'Mercedes SLS AMG ', '', '1cc47d1a.jpg', 160000, 563, 317, 74, 64, 51, 52, 65, 1, 0);
INSERT INTO `vehicle` VALUES (67, 'Mercedes SLR McLaren ', '', '31d80c1e.jpg', 250000, 626, 334, 73, 62, 91, 67, 66, 1, 0);
INSERT INTO `vehicle` VALUES (68, 'Mercedes AMG GT ', '', '014e45da.jpg', 130000, 503, 310, 73, 32, 19, 48, 67, 1, 0);
INSERT INTO `vehicle` VALUES (69, 'Mitsubishi Lancer Evo VIII ', '', '665d0b8a.jpg', 25000, 276, 240, 61, 42, 15, 9, 68, 1, 0);
INSERT INTO `vehicle` VALUES (70, 'Nissan Skyline GTR R34', '', 'f59e77c2.jpg', 40000, 276, 255, 63, 9, 62, 17, 69, 1, 0);
INSERT INTO `vehicle` VALUES (71, 'Nissan GT-R R35', '', '1c325a36.jpg', 75000, 542, 315, 82, 42, 7, 32, 70, 1, 0);
INSERT INTO `vehicle` VALUES (72, 'Noble M600 ', '', '75542590.jpg', 200000, 650, 360, 81, 17, 13, 60, 71, 1, 0);
INSERT INTO `vehicle` VALUES (73, 'Pagani Zonda F ', '', '8b8642b5.jpg', 900000, 650, 335, 75, 74, 41, 90, 72, 1, 0);
INSERT INTO `vehicle` VALUES (74, 'Pagani Huayra ', '', '96574e02.jpg', 950000, 730, 360, 79, 66, 89, 91, 73, 1, 0);
INSERT INTO `vehicle` VALUES (75, 'Porsche 911 GT3 RS ', '', 'd6e73bbb.jpg', 130000, 500, 310, 79, 22, 9, 49, 74, 1, 0);
INSERT INTO `vehicle` VALUES (76, 'Porsche 918 ', '', '38273fdc.jpg', 850000, 887, 345, 88, 84, 73, 88, 75, 1, 0);
INSERT INTO `vehicle` VALUES (77, 'Rolls Royce Wraith ', '', '486a9b5a.jpg', 235000, 623, 250, 66, 14, 44, 65, 76, 1, 0);
INSERT INTO `vehicle` VALUES (78, 'Rolls Royce Phantom ', '', '6d22d262.jpg', 285000, 453, 240, 51, 23, 42, 70, 77, 1, 0);
INSERT INTO `vehicle` VALUES (79, 'SSC Ultimate Aero TT ', '', 'b241bc74.jpg', 400000, 1183, 411, 82, 86, 4, 76, 78, 1, 0);
INSERT INTO `vehicle` VALUES (80, 'Saleen S7 Turbo ', '', '2def4a58.jpg', 475000, 750, 386, 80, 32, 22, 77, 79, 1, 0);
INSERT INTO `vehicle` VALUES (81, 'Tesla Model S ', '', 'ba70313c.jpg', 80000, 532, 250, 82, 35, 12, 33, 80, 1, 0);
INSERT INTO `vehicle` VALUES (82, 'Toyota GT86 ', '', '86ae71b5.jpg', 22000, 200, 235, 37, 52, 63, 8, 81, 1, 0);
INSERT INTO `vehicle` VALUES (83, 'Toyota Supra Turbo ', '', 'b73ee27f.jpg', 25000, 326, 255, 59, 75, 8, 10, 82, 1, 0);
INSERT INTO `vehicle` VALUES (84, 'Lykan Hypersport ', '', '99d6214e.jpg', 2500000, 780, 386, 83, 0, 0, 109, 83, 1, 0);
INSERT INTO `vehicle` VALUES (85, 'Subaru Impreza WRX STI', '', 'aaee943c.jpg', 20000, 261, 240, 61, 42, 93, 5, 84, 1, 0);
INSERT INTO `vehicle` VALUES (86, 'Wiesmann GT ', '', '99aebd32.jpg', 100000, 362, 280, 65, 10, 10, 37, 85, 1, 0);
INSERT INTO `vehicle` VALUES (87, 'Spyker C8 Aileron ', '', '6b2c6580.jpg', 120000, 400, 300, 66, 13, 54, 44, 86, 1, 0);
INSERT INTO `vehicle` VALUES (88, 'Volkswagen Golf 5 R32 ', '', '2bdf2272.jpg', 20000, 250, 245, 48, 23, 52, 6, 87, 1, 0);
INSERT INTO `vehicle` VALUES (89, 'Volkswagen Scirocco R', '', '48910224.jpg', 25000, 268, 245, 52, 11, 42, 11, 88, 1, 0);
INSERT INTO `vehicle` VALUES (90, 'Toyota Avensis 2.0 D4D', '', '6f7f516b.jpg', 3000, 110, 195, 49, 55, 63, 1, 89, 1, 0);
INSERT INTO `vehicle` VALUES (91, 'Toyota FT-1', '', 'ad01a5f4.jpg', 60000, 335, 260, 63, 21, 3, 25, 90, 1, 0);
INSERT INTO `vehicle` VALUES (92, 'Lexus LFA', '', '59d44b9a.jpg', 325000, 560, 326, 74, 27, 49, 74, 91, 1, 0);
INSERT INTO `vehicle` VALUES (93, 'Ferrari 488', '', '7a81fe78.jpg', 280000, 670, 330, 80, 57, 55, 69, 92, 1, 0);
INSERT INTO `vehicle` VALUES (94, 'Lamborghini Sesto Elemento ', '', '435edde2.jpg', 2000000, 570, 354, 85, 0, 0, 106, 93, 1, 0);
INSERT INTO `vehicle` VALUES (95, 'Porsche 911 GT2 (996)', '', 'e59b9b76.jpg', 120000, 489, 319, 71, 90, 45, 45, 94, 1, 0);
INSERT INTO `vehicle` VALUES (96, 'Porsche Carrera GT', '', '32261941.jpg', 580000, 604, 331, 73, 51, 68, 82, 95, 1, 0);
INSERT INTO `vehicle` VALUES (97, 'Porsche 959 ', '', 'f278b80f.jpg', 105000, 450, 317, 71, 55, 81, 38, 96, 1, 0);
INSERT INTO `vehicle` VALUES (98, 'Ducati 1098 S', '', '2e015737.jpg', 20000, 160, 279, 80, 8, 58, 7, 97, 1, 0);
INSERT INTO `vehicle` VALUES (99, 'Ducati 996 R', '', 'a4df460e.jpg', 10000, 136, 281, 78, 24, 43, 2, 98, 1, 0);
INSERT INTO `vehicle` VALUES (100, 'Yamaha YZF R1', '', '10cd7e1b.jpg', 15000, 150, 277, 81, 33, 5, 3, 99, 1, 0);
INSERT INTO `vehicle` VALUES (101, 'Honda CBR 1000RR Repsol', '', '6475c087.jpg', 15000, 172, 277, 80, 12, 32, 4, 100, 1, 0);
INSERT INTO `vehicle` VALUES (102, 'Dodge Challenger SRT Hellcat', '', 'b72f9825.jpg', 105000, 707, 325, 71, 25, 31, 39, 101, 1, 0);
INSERT INTO `vehicle` VALUES (103, 'F-16 Fighting Falcon', '', '061ffd96.jpg', 15000000, 15000, 2414, 89, 0, 0, 118, 102, 1, 0);
INSERT INTO `vehicle` VALUES (104, 'Mercedes CLK GTR ', '', '9758d37c.jpg', 1250000, 612, 320, 74, 50, 83, 96, 103, 1, 0);
INSERT INTO `vehicle` VALUES (105, 'Zenvo ST1', '', '8bf6ecc6.jpg', 780000, 1104, 375, 83, 88, 16, 84, 104, 1, 0);
INSERT INTO `vehicle` VALUES (106, 'Saab Aero X ', '', '32ed37f1.jpg', 1800000, 400, 250, 61, 0, 0, 103, 105, 1, 0);
INSERT INTO `vehicle` VALUES (107, 'Volkswagen W12 ', '', '9940f10c.jpg', 2000000, 600, 350, 77, 0, 0, 107, 106, 1, 0);
INSERT INTO `vehicle` VALUES (108, 'Maybach Exelero ', '', '5160d60e.jpg', 7000000, 700, 350, 67, 0, 0, 117, 107, 1, 0);
INSERT INTO `vehicle` VALUES (109, 'Jaguar C-X75 ', '', 'f2f32213.jpg', 950000, 850, 330, 77, 87, 17, 92, 108, 1, 0);
INSERT INTO `vehicle` VALUES (110, 'Cadillac Sixteen ', '', '04f3804e.jpg', 3000000, 1000, 320, 70, 0, 0, 113, 109, 1, 0);
INSERT INTO `vehicle` VALUES (111, 'Cadillac Cien ', '', '5367d508.jpg', 2800000, 750, 350, 75, 0, 0, 111, 110, 1, 0);
INSERT INTO `vehicle` VALUES (112, 'Cadillac Ciel', '', '84adb8ee.jpg', 2900000, 430, 280, 60, 0, 0, 112, 111, 1, 0);
INSERT INTO `vehicle` VALUES (113, 'Chrysler ME Four-Twelve ', '', '2504ac21.jpg', 2500000, 850, 400, 81, 0, 0, 110, 112, 1, 0);
INSERT INTO `vehicle` VALUES (114, 'Lamborghini Veneno', '', '4770d2f2.jpg', 4000000, 750, 355, 82, 0, 0, 114, 113, 1, 0);
INSERT INTO `vehicle` VALUES (115, 'Mercedes-Benz G63 AMG 6x6', '', 'f212b768.jpg', 500000, 536, 200, 32, 41, 35, 78, 114, 1, 0);
INSERT INTO `vehicle` VALUES (116, 'Opel Astra OPC', '', 'f851ec4e.jpeg', 31000, 280, 250, 50, 35, 60, 13, 115, 1, 0);
INSERT INTO `vehicle` VALUES (117, 'Leopard 2', '', '3d03a072.jpg', 5400000, 1500, 72, 0, 0, 0, 115, 116, 1, 0);
INSERT INTO `vehicle` VALUES (118, 'M1 Abrams', '', '6433c194.jpeg', 5800000, 1500, 72, 0, 0, 0, 116, 117, 1, 0);

-- ----------------------------
-- Table structure for weapon
-- ----------------------------
DROP TABLE IF EXISTS `weapon`;
CREATE TABLE `weapon`  (
  `id` int NOT NULL,
  `name` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `picture` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'type=upload',
  `price` int NOT NULL DEFAULT 0,
  `wpnExpTrain` smallint NOT NULL DEFAULT 10,
  `damage` smallint NULL DEFAULT 10,
  `position` tinyint(1) NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of weapon
-- ----------------------------
INSERT INTO `weapon` VALUES (0, 'Mes', '3ef2d4be.jpg', 0, 0, 1, 127, 1, 0);
INSERT INTO `weapon` VALUES (1, 'Desert Eagle', 'e6e2a35c.jpg', 25000, 10, 3, 1, 1, 0);
INSERT INTO `weapon` VALUES (2, 'Aka-Beta', '4936ab35.jpg', 100000, 20, 7, 2, 1, 0);
INSERT INTO `weapon` VALUES (3, 'Thompson', 'f74a5194.jpg', 400000, 30, 14, 3, 1, 0);
INSERT INTO `weapon` VALUES (4, 'Colt MX 17', '7f794524.jpg', 750000, 45, 25, 4, 1, 0);
INSERT INTO `weapon` VALUES (5, 'Shotgun', '6760211d.jpg', 1100000, 60, 37, 5, 1, 0);
INSERT INTO `weapon` VALUES (6, 'M-16', 'da2d9f7c.jpg', 1600000, 80, 49, 6, 1, 0);
INSERT INTO `weapon` VALUES (7, 'RPG-7', 'a4f8723e.jpg', 2500000, 100, 62, 7, 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
