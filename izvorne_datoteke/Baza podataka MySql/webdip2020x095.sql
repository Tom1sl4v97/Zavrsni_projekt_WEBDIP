-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2021 at 06:22 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webdip2020x095`
--

DELIMITER $$
--
-- Functions
--
CREATE FUNCTION `getStatusIzlozbe` (`izlozbaId` INT, `virtualniDatum` DATE) RETURNS VARCHAR(45) CHARSET utf8 COLLATE utf8_bin BEGIN
	DECLARE result VARCHAR(45);
	SET @danasnjiDatum = virtualniDatum;
    SET @pocetakIzlozbe = (SELECT DATE(datum_pocetka) FROM Izlozba WHERE Izlozba.id = izlozbaId);
	SET  @pocetakGlasovanja = (SELECT DATE(vazi_od) FROM Glasovanje WHERE izlozba_id = izlozbaId);
	SET  @krajGlasovanja = (SELECT DATE(vazi_do) FROM Glasovanje WHERE izlozba_id = izlozbaId);
	 
     IF (@pocetakIzlozbe) IS NULL THEN
		SIGNAL SQLSTATE '77000' SET MESSAGE_TEXT = 'Zadana izložba nije definirana.';
	 END IF;
	 
     IF (@danasnjiDatum < @pocetakIzlozbe) THEN
		SET result = (SELECT status FROM StatusIzlozbe WHERE id =1);
	 ELSEIF (@danasnjiDatum>= @pocetakIzlozbe) AND @danasnjiDatum<@pocetakGlasovanja THEN
		SET result = (SELECT status FROM StatusIzlozbe WHERE id =2);
	 ELSEIF (@danasnjiDatum>= @pocetakGlasovanja) AND @danasnjiDatum<@krajGlasovanja THEN
		SET result = (SELECT status FROM StatusIzlozbe WHERE id =3);
	 ELSE 
	   SET result = (SELECT status FROM StatusIzlozbe WHERE id =4);
	  END IF;
      
      RETURN result;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `dnevnik`
--

CREATE TABLE `dnevnik` (
  `id` int(11) NOT NULL,
  `stranica` text COLLATE latin2_croatian_ci NOT NULL,
  `upit` text COLLATE latin2_croatian_ci DEFAULT NULL,
  `datum_pristupa` datetime NOT NULL,
  `tip_dnevnika_id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `dnevnik`
--

INSERT INTO `dnevnik` (`id`, `stranica`, `upit`, `datum_pristupa`, `tip_dnevnika_id`, `korisnik_id`) VALUES
(8, 'C:xampphtdocsProjektni_zadatak/obrasci/prijava.php', NULL, '2021-06-20 09:09:55', 1, 1),
(11, 'C:xampphtdocsProjektni_zadatak/logout.php', NULL, '2021-06-17 09:16:55', 1, 1),
(12, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 09:23:28', 1, 1),
(13, '/Projektni_zadatak/administrator/dodijeliTematiku.php', 'INSERT INTO tematika (naziv, opis, kreirao_korisnik_id, datum_kreiranja) VALUES (\'Nekaj\',\'nekaj je nekaj\',\'1\',\'2021-06-17 09:44:56\')', '2021-06-17 09:44:56', 2, 1),
(14, '/Projektni_zadatak/administrator/dodijeliTematiku.php', 'UPDATE tematika SET naziv = \'Najveći vlak\', opis = \'Veliki vlakovi sa mnogo broja sjedala ali je \', azurirao_korisnik_id = \'1\', datum_azuriranja = \'2021-06-17 09:45:59\' WHERE id = \'5\';', '2021-06-17 09:45:59', 2, 1),
(15, '/Projektni_zadatak/administrator/dodijeliTematiku.php', 'UPDATE tematika SET naziv = \'Najveći vlak\', opis = \'Veliki vlakovi sa mnogo broja sjedala\', azurirao_korisnik_id = \'1\', datum_azuriranja = \'2021-06-17 09:46:09\' WHERE id = \'5\';', '2021-06-17 09:46:09', 2, 1),
(16, '/Projektni_zadatak/administrator/dodijeliTematiku.php', 'INSERT INTO tematika (naziv, opis, kreirao_korisnik_id, datum_kreiranja) VALUES (\'asdasd\',\'nekaj je nekaj\',\'1\',\'2021-06-17 09:49:06\')', '2021-06-17 09:49:06', 2, 1),
(17, '/Projektni_zadatak/administrator/tematikaVlakova.php?obrisiTematiku=61', 'DELETE FROM tematika WHERE id = 61', '2021-06-17 09:49:48', 2, 1),
(18, '/Projektni_zadatak/administrator/tematikaVlakova.php?obrisiModeratora=48', 'DELETE FROM moderatori WHERE id = 48', '2021-06-17 09:50:56', 2, 1),
(19, '/Projektni_zadatak/administrator/dodjeliModeratoraTematici.php', 'INSERT INTO moderatori (administrator_id, moderator_id, tematika_id, vazi_od, vazi_do) VALUES (1,7,4,\'2021-06-16\', \'2021-06-25\')', '2021-06-15 09:58:54', 2, 1),
(20, '/Projektni_zadatak/administrator/tematikaVlakova.php?obrisiModeratora=59', 'DELETE FROM moderatori WHERE id = 59', '2021-06-15 09:59:12', 2, 1),
(24, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:41:52', 1, 3),
(25, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:42:01', 1, 1),
(26, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:42:03', 1, 1),
(27, 'dada', 'DELETE FROM prijavavlaka WHERE id = 187', '2021-06-17 21:46:19', 2, 3),
(28, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:47:23', 1, 3),
(29, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:47:37', 1, 1),
(30, '/Projektni_zadatak/JQueryPHPObrisiVlakKorisnika.php', 'DELETE FROM prijavavlaka WHERE id = 188', '2021-06-17 21:47:45', 2, 3),
(31, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:53:49', 1, 3),
(32, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 21:54:10', 1, 1),
(33, '/Projektni_zadatak/JQueryPHPOcijeniPrijavuVlaka.php', 'UPDATE `ocjena` SET  ocjena_korisnika=8,      komentar=\'Jako mi se sviđa vlak :D\' WHERE prijava_vlaka_id = 170      AND ocjena.id = 49     AND korisnik_id IN (SELECT id FROM korisnik WHERE korisnicko_ime = \'ttomiek\')', '2021-06-17 21:56:34', 2, 1),
(34, '/Projektni_zadatak/JQueryPHPOcijeniPrijavuVlaka.php', 'INSERT INTO ocjena (prijava_vlaka_id,komentar,ocjena_korisnika, korisnik_id) VALUES (172, \'Dobar vlak\',  6, (SELECT id FROM korisnik WHERE korisnicko_ime = \'ttomiek\' LIMIT 1))', '2021-06-17 21:56:58', 2, 1),
(35, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'Loki\', 160, 310, \'Loki je spori vlak sa mnogo broja sjedala\', 2, 1)', '2021-06-17 22:04:38', 2, 1),
(36, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'UPDATE vlak SET naziv = \'Loki\', max_brzina = 160, broj_sjedala = 310, opis = \'Loki je spori vlak sa mnogo broja sjedala\', vrsta_pogona_id = 9, vlasnik_id = 1 WHERE id = 26', '2021-06-17 22:07:21', 2, 1),
(37, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vrstapogona (naziv_pogona, opis) VALUES (\'Elektro dizel\', \'Elektro dizel je stariji pogon koji se pokreće pomoću nafte i posjeduje električni pogon kod nižih brzina, najčešće kod pokretanja vlaka.\')', '2021-06-17 22:09:07', 2, 1),
(38, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'Kravica\', 80, 150, \'Maleni i šareni vlakić\', 16, 1)', '2021-06-17 22:09:07', 2, 1),
(39, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vrstapogona (naziv_pogona, opis) VALUES (\'qweqweq\', \'wqewqeqweq\')', '2021-06-17 22:11:55', 2, 1),
(40, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'asdfdasfdsa\', 123, 123, \'qweqwewqewq\', 17, 1)', '2021-06-17 22:11:55', 2, 1),
(41, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'asdfasf\', 123, 123, \'dfasdssadsa\', 1, 1)', '2021-06-17 22:13:26', 2, 1),
(42, '/Projektni_zadatak/korisnik/prikazVlakova.php?obrisiVlakKorisnika=29', 'DELETE FROM vlak WHERE id = 29', '2021-06-17 22:17:48', 2, 1),
(43, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'asdsad\', 123, 123, \'132\', 2, 1)', '2021-06-17 22:18:27', 2, 1),
(44, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'UPDATE vlak SET naziv = \'asdsad\', max_brzina = 123, broj_sjedala = 123, opis = \'132\', vrsta_pogona_id = 6, vlasnik_id = 1 WHERE id = 30', '2021-06-17 22:18:42', 2, 1),
(45, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vrstapogona (naziv_pogona, opis) VALUES (\'123\', \'123123\')', '2021-06-17 22:18:54', 2, 1),
(46, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'UPDATE vlak SET naziv = \'asdsad\', max_brzina = 123, broj_sjedala = 123, opis = \'132\', vrsta_pogona_id = 18, vlasnik_id = 1 WHERE id = 30', '2021-06-17 22:18:54', 2, 1),
(47, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vrstapogona (naziv_pogona, opis) VALUES (\'123\', \'123123123\')', '2021-06-17 22:19:14', 2, 1),
(48, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'INSERT INTO vlak (naziv, max_brzina, broj_sjedala, opis, vrsta_pogona_id, vlasnik_id) VALUES (\'123\', 123, 123, \'123\', 19, 1)', '2021-06-17 22:19:14', 2, 1),
(49, '/Projektni_zadatak/korisnik/prikazVlakova.php?obrisiVlakKorisnika=28', 'DELETE FROM vlak WHERE id = 28', '2021-06-17 22:19:24', 2, 1),
(50, '/Projektni_zadatak/korisnik/prikazVlakova.php?obrisiVlakKorisnika=30', 'DELETE FROM vlak WHERE id = 30', '2021-06-17 22:19:25', 2, 1),
(51, '/Projektni_zadatak/korisnik/prikazVlakova.php?obrisiVlakKorisnika=31', 'DELETE FROM vlak WHERE id = 31', '2021-06-17 22:19:26', 2, 1),
(52, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 22:20:24', 1, 1),
(53, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'UPDATE vlak SET naziv = \'Resetka\', max_brzina = 32, broj_sjedala = 323, opis = \'Veliki i moderni vlak. Vrsta pogona naše kokice je parni pogon. \', vrsta_pogona_id = 3, vlasnik_id = 3 WHERE id = 23', '2021-06-17 22:20:33', 2, 3),
(54, '/Projektni_zadatak/korisnik/dodavanjeNovogVlaka.php', 'UPDATE vlak SET naziv = \'Magnetska strijela\', max_brzina = 320, broj_sjedala = 350, opis = \'Veliki i moderni vlak. Vrsta pogona naše kokice je parni pogon. \', vrsta_pogona_id = 3, vlasnik_id = 3 WHERE id = 25', '2021-06-17 22:20:50', 2, 3),
(55, '/Projektni_zadatak/logout.php', NULL, '2021-06-17 22:41:35', 1, 3),
(56, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO izlozba (datum_pocetka, broj_korisnika, tematika_id, moderator_id, datum_kreiranja) VALUES (\'2021-06-19T13:00\',\'21\',\'1\',\'1\',\'2021-06-17 22:42:02\')', '2021-06-17 22:42:02', 2, 1),
(57, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO glasovanje (vazi_od, vazi_do, izlozba_id) VALUES (\'2021-06-21\', \'2021-06-27\', 68)', '2021-06-17 22:42:02', 2, 1),
(58, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'UPDATE izlozba SET datum_pocetka = \'2021-06-19T13:00:00\', broj_korisnika = \'21\', tematika_id = \'1\', datum_azuriranja = \'2021-06-17 22:43:30\' WHERE id = \'68\'', '2021-06-17 22:43:30', 2, 1),
(59, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'UPDATE glasovanje SET vazi_od = \'2021-06-21\', vazi_do = \'2021-07-04\' WHERE izlozba_id = 68', '2021-06-17 22:43:30', 2, 1),
(60, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO izlozba (datum_pocetka, broj_korisnika, tematika_id, moderator_id, datum_kreiranja) VALUES (\'2021-06-17T11:13\',\'1\',\'1\',\'1\',\'2021-06-17 23:13:45\')', '2021-06-17 23:13:45', 2, 1),
(61, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO glasovanje (vazi_od, vazi_do, izlozba_id) VALUES (\'2021-06-18\', \'2021-06-19\', 69)', '2021-06-17 23:13:45', 2, 1),
(62, '/Projektni_zadatak/izlozbaVlakova.php?izbrisiIzlozbu=69', 'DELETE FROM izlozba WHERE id = 69', '2021-06-17 23:14:04', 2, 1),
(63, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO izlozba (datum_pocetka, broj_korisnika, tematika_id, moderator_id, datum_kreiranja) VALUES (\'2021-06-17T11:14\',\'1\',\'1\',\'1\',\'2021-06-17 23:14:29\')', '2021-06-17 23:14:29', 2, 1),
(64, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'INSERT INTO glasovanje (vazi_od, vazi_do, izlozba_id) VALUES (\'2021-06-18\', \'2021-06-19\', 70)', '2021-06-17 23:14:29', 2, 1),
(65, '/Projektni_zadatak/izlozbaVlakova.php?izbrisiIzlozbu=70', 'DELETE FROM izlozba WHERE id = 70', '2021-06-17 23:14:34', 2, 1),
(66, '/Projektni_zadatak/prikazPrijaveVlakova.php?odbij=177', 'UPDATE prijavavlaka SET status_id = 2 WHERE id = \'177\';', '2021-06-17 23:19:00', 2, 1),
(67, '/Projektni_zadatak/prikazPrijaveVlakova.php?prihvati=177', 'UPDATE prijavavlaka SET status_id = 1 WHERE id = \'177\';', '2021-06-17 23:19:30', 2, 1),
(68, '/Projektni_zadatak/prikazPrijaveVlakova.php?odbij=177', 'UPDATE prijavavlaka SET status_id = 2 WHERE id = \'177\';', '2021-06-17 23:19:37', 2, 1),
(69, '/Projektni_zadatak/prikazPrijaveVlakova.php?odbij=178', 'UPDATE prijavavlaka SET status_id = 2 WHERE id = \'178\';', '2021-06-17 23:19:38', 2, 1),
(70, '/Projektni_zadatak/prikazPrijaveVlakova.php?prihvati=177', 'UPDATE prijavavlaka SET status_id = 1 WHERE id = \'177\';', '2021-06-17 23:19:46', 2, 1),
(71, '/Projektni_zadatak/prikazPrijaveVlakova.php?prihvati=178', 'UPDATE prijavavlaka SET status_id = 1 WHERE id = \'178\';', '2021-06-17 23:19:47', 2, 1),
(72, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 03:03:32', 1, 1),
(73, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 03:04:48', 1, 1),
(74, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 04:49:38', 1, 1),
(75, '/Projektni_zadatak/obrasci/prijava.php?korime=dtokic&lozinka=danijel123&prijava_korisnika=Prijavi+se', NULL, '2021-06-18 04:56:38', 1, 4),
(76, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 04:56:44', 1, 4),
(77, '/Projektni_zadatak/obrasci/prijava.php?korime=ttomiek&lozinka=lozinka123&prijava_korisnika=Prijavi+se', NULL, '2021-06-18 04:56:59', 1, 1),
(78, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 04:57:01', 1, 1),
(79, '/Projektni_zadatak/obrasci/prijava.php?korime=ttomiek&lozinka=lozinka123&zapamtiMe=da&prijava_korisnika=Prijavi+se', NULL, '2021-06-18 04:57:31', 1, 1),
(80, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 04:57:33', 1, 1),
(81, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 04:57:44', 1, 1),
(82, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 05:10:29', 1, 1),
(83, '/Projektni_zadatak/logout.php', NULL, '2021-06-18 05:48:34', 1, 1),
(84, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'UPDATE izlozba SET datum_pocetka = \'2021-06-14T15:30:00\', broj_korisnika = \'3\', tematika_id = \'6\', datum_azuriranja = \'2021-06-18 05:49:10\' WHERE id = \'64\'', '2021-06-18 05:49:10', 2, 1),
(85, '/Projektni_zadatak/dodavanjeNoveIzlozbe.php', 'UPDATE glasovanje SET vazi_od = \'2021-06-15\', vazi_do = \'2021-06-22\' WHERE izlozba_id = 64', '2021-06-18 05:49:10', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `glasovanje`
--

CREATE TABLE `glasovanje` (
  `id` int(11) NOT NULL,
  `vazi_od` datetime NOT NULL,
  `vazi_do` datetime NOT NULL,
  `izlozba_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `glasovanje`
--

INSERT INTO `glasovanje` (`id`, `vazi_od`, `vazi_do`, `izlozba_id`) VALUES
(35, '2021-04-26 00:00:00', '2021-04-27 00:00:00', 2),
(37, '2021-06-11 00:00:00', '2021-06-12 00:00:00', 41),
(38, '2021-06-09 00:00:00', '2021-06-11 00:00:00', 60),
(39, '2021-06-17 00:00:00', '2021-06-28 00:00:00', 63),
(40, '2021-06-15 00:00:00', '2021-06-22 00:00:00', 64),
(43, '2021-06-20 00:00:00', '2021-06-27 00:00:00', 67),
(44, '2021-06-21 00:00:00', '2021-07-04 00:00:00', 68);

-- --------------------------------------------------------

--
-- Table structure for table `izlozba`
--

CREATE TABLE `izlozba` (
  `id` int(11) NOT NULL,
  `datum_pocetka` datetime NOT NULL,
  `broj_korisnika` int(11) NOT NULL,
  `tematika_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `datum_kreiranja` datetime NOT NULL,
  `datum_azuriranja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `izlozba`
--

INSERT INTO `izlozba` (`id`, `datum_pocetka`, `broj_korisnika`, `tematika_id`, `moderator_id`, `datum_kreiranja`, `datum_azuriranja`) VALUES
(2, '2021-04-26 06:00:00', 15, 3, 2, '2021-04-12 22:03:35', NULL),
(41, '2021-06-11 10:00:00', 9, 1, 4, '2021-06-03 10:29:04', '2021-06-11 12:27:41'),
(60, '2021-06-09 08:44:00', 5, 4, 4, '2021-06-10 06:42:52', NULL),
(63, '2021-06-16 10:00:00', 6, 5, 1, '2021-06-10 14:26:22', '2021-06-17 21:54:06'),
(64, '2021-06-14 15:30:00', 3, 6, 1, '2021-06-10 14:29:02', '2021-06-18 05:49:10'),
(67, '2021-06-20 09:33:00', 15, 2, 1, '2021-06-15 11:32:30', NULL),
(68, '2021-06-19 13:00:00', 21, 1, 1, '2021-06-17 22:42:02', '2021-06-17 22:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `ime` varchar(20) COLLATE latin2_croatian_ci DEFAULT NULL,
  `prezime` varchar(40) COLLATE latin2_croatian_ci DEFAULT NULL,
  `korisnicko_ime` varchar(45) COLLATE latin2_croatian_ci NOT NULL,
  `lozinka_sha1` char(64) COLLATE latin2_croatian_ci NOT NULL,
  `email` varchar(45) COLLATE latin2_croatian_ci NOT NULL,
  `uvjeti_koristenja` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `tip_korisnika_id` int(11) NOT NULL,
  `lozinka` varchar(45) COLLATE latin2_croatian_ci NOT NULL,
  `broj_neuspijesnih_prijava` int(11) NOT NULL DEFAULT 0,
  `salt` text COLLATE latin2_croatian_ci NOT NULL,
  `datum_kreiranja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `ime`, `prezime`, `korisnicko_ime`, `lozinka_sha1`, `email`, `uvjeti_koristenja`, `status`, `tip_korisnika_id`, `lozinka`, `broj_neuspijesnih_prijava`, `salt`, `datum_kreiranja`) VALUES
(1, 'Tomislav', 'Tomiek', 'ttomiek', 'bf55b1ec4082c9b45c7f7f6c6182b8e5db7fecca06e95486c0c21c54ab258e1f', 'ttomiek@foi.hr', '2021-06-18 04:57:41', 1, 1, 'lozinka123', 0, '7c3', '2021-04-04 00:00:00'),
(2, 'Viktor', 'Horvatić', 'viki007', 'b6535457f3fb52ce71d9a45a03c9b6d2a8a36abfb041d6428054c8a8d8a799de', 'viktor.horvatic@foi.hr', '2021-04-04 06:18:04', 0, 2, 'viktor123', 0, 'hj5', '2021-04-04 06:18:04'),
(3, 'Marko', 'Marulic', 'mmarulic', 'f6663e666f44e46d244d1a0c090995ab75151f35b3ab1d4c4bdd9e3d9adcbd4d', 'mmarulic@foi.hr', '2021-03-23 06:46:47', 1, 3, 'marko123', 0, 'nfe', '2021-03-23 06:46:47'),
(4, 'Danijel', 'Tokić', 'dtokic', 'a18f4de263ac2643d0f6eb1687e51439f30dbbcc1655f8e38e863fcfc329c508', 'dtokic@foi.hr', '2021-06-18 04:56:41', 1, 2, 'danijel123', 0, 'op4', '2021-04-01 21:15:11'),
(5, 'Martina', 'Štriga', 'MartinaS', 'a0dec5a6d8914f4805c3dca08bdd1f6e0c93a40f3eb5cda0b2fb15576382eb35', 'mstriga@foi.hr', '2021-03-31 20:10:11', 0, 2, 'martina123', 0, 'lo4', '2021-03-31 20:10:11'),
(6, 'Dominik', 'Tomsic', 'dtomsic', '1dde0e798fffbc4b9df9cb14952c81e23e5faec9b624cb0ae97828b94fb87343', 'dtomsic@foi.hr', '2021-04-09 13:12:02', 0, 3, 'dominik123', 0, 'hj1', '2021-04-09 13:12:02'),
(7, 'Ivana', 'Peric', 'iperic', 'b93db0c8b749503d6f6eb65800957d5657118b9a9e6614cce7aa7646c233f75c', 'iperic@foi.hr', '2021-02-10 10:13:00', 0, 2, 'ivana123', 0, '19d', '2021-02-10 10:13:00'),
(9, 'Antonio', 'Ciglar', 'tonc05', '6e263e803dfe74bdb83f967f1f4ea1774f2f0d4cc609ba0e969f3a9574200892', 'aciglar@foi.hr', '2021-04-04 08:09:08', 0, 3, 'antonio123', 0, 'jke', '2021-04-04 08:09:08'),
(10, 'Antonia', 'Vuk', 'avuk', 'a6a05188d52625561cce11c25d98be4508e8bec7dc6ae6aaa28a681e772430fd', 'avuk@foi.hr', '2021-04-07 08:30:39', 1, 1, 'antonia123', 0, 'j42', '2021-04-07 08:30:39'),
(16, 'Mirko', 'Žugec', 'mirkomirkovic', '21c1c0b7a724f69fe33ae6095aee7ecc8da315c975f1ebd0c3d02904c7615848', 'mzugec@foi.hr', '2021-04-05 11:12:00', 0, 3, 'mirko123', 0, 'pc8', '2021-04-05 11:12:00'),
(18, 'Kruno', 'Tomiek', 'ktomiek', '3602f8926b34e56d1af14cc9766ae9191af60a605bf5f39305be8765cc6a5d6f', 'ktomiek@foi.hr', '2021-06-04 22:33:53', 0, 3, 'kruno123', 3, 'che', '2021-06-04 22:33:53'),
(20, 'krunek', 'Krunoslav', 'kkrunek', 'c91b5fa58bb2fe109bea11f4dc45d2cd1897b815cc444fea5309a66dff15b660', 'kkrunek@foi.hr', NULL, 0, 3, 'krunek123', 3, 'cd5', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `materijal`
--

CREATE TABLE `materijal` (
  `id` int(11) NOT NULL,
  `url` text COLLATE latin2_croatian_ci NOT NULL,
  `vrsta_materijala_id` int(11) NOT NULL,
  `prijava_vlaka_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `moderatori`
--

CREATE TABLE `moderatori` (
  `id` int(11) NOT NULL,
  `administrator_id` int(11) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `tematika_id` int(11) NOT NULL,
  `vazi_od` datetime NOT NULL,
  `vazi_do` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `moderatori`
--

INSERT INTO `moderatori` (`id`, `administrator_id`, `moderator_id`, `tematika_id`, `vazi_od`, `vazi_do`) VALUES
(1, 1, 2, 1, '2021-04-12 00:00:00', '2021-12-31 00:00:00'),
(2, 1, 2, 3, '2021-04-12 00:00:00', '2021-06-30 00:00:00'),
(3, 1, 4, 2, '2021-07-01 00:00:00', '2021-06-26 00:00:00'),
(5, 1, 5, 4, '2021-04-12 00:00:00', '2021-06-26 00:00:00'),
(35, 1, 5, 1, '2021-06-02 12:22:57', '2021-06-30 00:00:00'),
(36, 1, 4, 5, '2021-06-02 00:00:00', '2021-06-26 00:00:00'),
(40, 1, 4, 3, '2021-06-02 16:55:55', NULL),
(44, 1, 7, 3, '2021-06-03 00:00:00', '2021-06-26 00:00:00'),
(53, 1, 2, 6, '2021-06-02 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ocjena`
--

CREATE TABLE `ocjena` (
  `id` int(11) NOT NULL,
  `ocjena_korisnika` int(11) NOT NULL,
  `komentar` text COLLATE latin2_croatian_ci DEFAULT NULL,
  `prijava_vlaka_id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prijavavlaka`
--

CREATE TABLE `prijavavlaka` (
  `id` int(11) NOT NULL,
  `vlak_id` int(11) NOT NULL,
  `izlozba_id` int(11) NOT NULL,
  `azurirao_moderator_id` int(11) DEFAULT NULL,
  `datum_azuriranja` datetime DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `prijavavlaka`
--

INSERT INTO `prijavavlaka` (`id`, `vlak_id`, `izlozba_id`, `azurirao_moderator_id`, `datum_azuriranja`, `status_id`) VALUES
(196, 12, 41, 1, '2021-06-17 18:04:06', 1),
(197, 22, 60, NULL, NULL, 1),
(198, 22, 63, NULL, NULL, 1),
(199, 22, 67, NULL, NULL, 1),
(200, 27, 63, NULL, NULL, 1),
(201, 27, 67, NULL, NULL, 1),
(202, 26, 64, NULL, NULL, 1),
(203, 25, 64, NULL, NULL, 1),
(204, 6, 67, 1, '2021-06-17 18:03:11', 1),
(205, 23, 68, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `statusizlozbe`
--

CREATE TABLE `statusizlozbe` (
  `id` int(11) NOT NULL,
  `status` varchar(45) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `statusizlozbe`
--

INSERT INTO `statusizlozbe` (`id`, `status`) VALUES
(1, 'Otvorene prijave'),
(2, 'Izložba u tijeku'),
(3, 'Otvoreno glasovanje'),
(4, 'Zatvoreno glasovanje');

-- --------------------------------------------------------

--
-- Table structure for table `statusprijave`
--

CREATE TABLE `statusprijave` (
  `id` int(11) NOT NULL,
  `status` varchar(45) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `statusprijave`
--

INSERT INTO `statusprijave` (`id`, `status`) VALUES
(1, 'Potvrđena'),
(2, 'Odbijena'),
(3, 'Na čekanju');

-- --------------------------------------------------------

--
-- Table structure for table `tematika`
--

CREATE TABLE `tematika` (
  `id` int(11) NOT NULL,
  `naziv` varchar(100) COLLATE latin2_croatian_ci NOT NULL,
  `opis` text COLLATE latin2_croatian_ci NOT NULL,
  `kreirao_korisnik_id` int(11) NOT NULL,
  `datum_kreiranja` datetime NOT NULL,
  `azurirao_korisnik_id` int(11) DEFAULT NULL,
  `datum_azuriranja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `tematika`
--

INSERT INTO `tematika` (`id`, `naziv`, `opis`, `kreirao_korisnik_id`, `datum_kreiranja`, `azurirao_korisnik_id`, `datum_azuriranja`) VALUES
(1, 'Moderni vlak', 'U ovu tematiku spadaju vlakovi koji su modernog dizajna (vanjskog i/ili unutarnjeg dizajna)', 1, '2021-04-12 21:49:25', NULL, NULL),
(2, 'Najbrži vlak', 'Ova vrsta tematike spadaju samo najbrži vlakovi, pobjednik je korisnik sa najbržim vlakom.', 1, '2021-04-12 21:47:02', 2, '2021-05-29 12:21:56'),
(3, 'Motorni vlak', 'Vlakovi koji imaju motorni pogon.', 1, '2021-04-12 21:52:15', 1, '2021-04-12 21:53:59'),
(4, 'Lokomotive', 'Ova kategorija spadaju samo parne lokomotive.', 1, '2021-04-12 21:55:04', NULL, NULL),
(5, 'Najveći vlak', 'Veliki vlakovi sa mnogo broja sjedala', 1, '2021-04-12 21:55:52', 1, '2021-06-17 09:46:09'),
(6, 'Najmanji vlak', 'Vlakovi koji imaju maleni broj vagona i / ili broj sjedala', 1, '2021-04-12 21:57:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tipdnevnika`
--

CREATE TABLE `tipdnevnika` (
  `id` int(11) NOT NULL,
  `opis` varchar(45) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `tipdnevnika`
--

INSERT INTO `tipdnevnika` (`id`, `opis`) VALUES
(1, 'Prijava / odjava'),
(2, 'Rad s bazom'),
(3, 'Ostale radnje');

-- --------------------------------------------------------

--
-- Table structure for table `tipkorisnika`
--

CREATE TABLE `tipkorisnika` (
  `id` int(11) NOT NULL,
  `naziv` varchar(45) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `tipkorisnika`
--

INSERT INTO `tipkorisnika` (`id`, `naziv`) VALUES
(1, 'Administrator'),
(2, 'Moderator'),
(3, 'Registrirati korisnik'),
(4, 'Neregistrirani korisnik');

-- --------------------------------------------------------

--
-- Table structure for table `vlak`
--

CREATE TABLE `vlak` (
  `id` int(11) NOT NULL,
  `naziv` varchar(100) COLLATE latin2_croatian_ci NOT NULL,
  `max_brzina` decimal(10,0) NOT NULL,
  `broj_sjedala` int(11) NOT NULL,
  `opis` text COLLATE latin2_croatian_ci NOT NULL,
  `vrsta_pogona_id` int(11) NOT NULL,
  `vlasnik_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `vlak`
--

INSERT INTO `vlak` (`id`, `naziv`, `max_brzina`, `broj_sjedala`, `opis`, `vrsta_pogona_id`, `vlasnik_id`) VALUES
(2, 'Vruća stvar', '80', 185, 'Stari vlak, male brzine, jer je parni vlak.', 3, 6),
(5, 'Eko-Elektro', '130', 300, 'Eko-Elektro je vlak koji se brine za okoliš, jer je električni vlak modernog dizajna sa mnogo sjedala.', 2, 9),
(6, 'Mrak', '105', 150, 'Mrak je prosječan vlak sa pogonom na benzin', 4, 6),
(8, 'Mjesec', '148', 192, 'Mjesec je vlak na benzin, modernog dizajna i modernim uslugama (poput wifi, mini tv, WC, usluge bara i slično).', 4, 9),
(12, 'Elektro vlak', '160', 300, 'Električni vlak', 2, 4),
(22, 'Gromp', '160', 103, 'Gromp je srednji vlak, modernog izgleda. Najpoznatiji vlak u sjevernom sijelu rumunjske. ', 9, 3),
(23, 'Resetka', '32', 323, 'Veliki i moderni vlak. Vrsta pogona naše kokice je parni pogon. ', 3, 3),
(25, 'Magnetska strijela', '320', 350, 'Veliki i moderni vlak. Vrsta pogona naše kokice je parni pogon. ', 3, 3),
(26, 'Loki', '160', 310, 'Loki je spori vlak sa mnogo broja sjedala', 9, 1),
(27, 'Kravica', '80', 150, 'Maleni i šareni vlakić', 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `vrstamaterijala`
--

CREATE TABLE `vrstamaterijala` (
  `id` int(11) NOT NULL,
  `format` varchar(45) COLLATE latin2_croatian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `vrstamaterijala`
--

INSERT INTO `vrstamaterijala` (`id`, `format`) VALUES
(1, 'Slike'),
(2, 'Audio'),
(3, 'Video'),
(4, 'Giff');

-- --------------------------------------------------------

--
-- Table structure for table `vrstapogona`
--

CREATE TABLE `vrstapogona` (
  `id` int(11) NOT NULL,
  `naziv_pogona` varchar(45) COLLATE latin2_croatian_ci NOT NULL,
  `opis` varchar(200) COLLATE latin2_croatian_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin2 COLLATE=latin2_croatian_ci;

--
-- Dumping data for table `vrstapogona`
--

INSERT INTO `vrstapogona` (`id`, `naziv_pogona`, `opis`) VALUES
(1, 'dizel', 'Ova vrsta pogona je dizel. Pokreće se na gorivo dizel.'),
(2, 'električni', 'Ova vrsta pogona je električni pogon. Koristi se električna struja za pogon.'),
(3, 'parni', 'Ova vrsta pogona je na paru. Glavni izvor energije je para.'),
(4, 'benzin', 'Ova vrsta pogona je benzin. Pokreće se na gorivo benzin'),
(5, 'Hibridni ele-dizel vlakovi', 'Pogon je električni i na dizel'),
(6, 'Hibridni ele-benzin vlakovi', 'Pogon je električni i benzin'),
(9, 'Parni pogon', 'Ovo je starinski pogon kojega su ljudi zaboravili da uopće i postoji.'),
(16, 'Elektro dizel', 'Elektro dizel je stariji pogon koji se pokreće pomoću nafte i posjeduje električni pogon kod nižih brzina, najčešće kod pokretanja vlaka.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dnevnik`
--
ALTER TABLE `dnevnik`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Dnevnik_TipDnevnika1_idx` (`tip_dnevnika_id`),
  ADD KEY `fk_Dnevnik_Korisnik1_idx` (`korisnik_id`);

--
-- Indexes for table `glasovanje`
--
ALTER TABLE `glasovanje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Glasovanje_Izlozba1_idx` (`izlozba_id`);

--
-- Indexes for table `izlozba`
--
ALTER TABLE `izlozba`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Izlozba_Tematika1_idx` (`tematika_id`),
  ADD KEY `fk_Izlozba_Korisnik2_idx` (`moderator_id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `korisnicko_ime_UNIQUE` (`korisnicko_ime`),
  ADD KEY `fk_Korisnik_TipKorisnika_idx` (`tip_korisnika_id`);

--
-- Indexes for table `materijal`
--
ALTER TABLE `materijal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Materijal_VrstaMaterijala1_idx` (`vrsta_materijala_id`),
  ADD KEY `fk_Materijal_PrijavaVlaka1` (`prijava_vlaka_id`);

--
-- Indexes for table `moderatori`
--
ALTER TABLE `moderatori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tematika_moderator` (`moderator_id`,`tematika_id`),
  ADD KEY `fk_Moderatori_Korisnik1_idx` (`administrator_id`),
  ADD KEY `fk_Moderatori_Korisnik2_idx` (`moderator_id`),
  ADD KEY `fk_Moderatori_Tematika1_idx` (`tematika_id`);

--
-- Indexes for table `ocjena`
--
ALTER TABLE `ocjena`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_glasovanje_korisnik` (`korisnik_id`,`prijava_vlaka_id`),
  ADD KEY `fk_Ocjena_Korisnik1_idx` (`korisnik_id`),
  ADD KEY `fk_Ocjena_PrijavaVlaka` (`prijava_vlaka_id`);

--
-- Indexes for table `prijavavlaka`
--
ALTER TABLE `prijavavlaka`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_PrijavaVlaka_Vlak1_idx` (`vlak_id`),
  ADD KEY `fk_PrijavaVlaka_Izlozba1_idx` (`izlozba_id`),
  ADD KEY `fk_PrijavaVlaka_Korisnik1_idx` (`azurirao_moderator_id`),
  ADD KEY `fk_PrijavaVlaka_StatusPrijave1_idx` (`status_id`);

--
-- Indexes for table `statusizlozbe`
--
ALTER TABLE `statusizlozbe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statusprijave`
--
ALTER TABLE `statusprijave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tematika`
--
ALTER TABLE `tematika`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Tematika_Korisnik1_idx` (`kreirao_korisnik_id`),
  ADD KEY `fk_Tematika_Korisnik2_idx` (`azurirao_korisnik_id`);

--
-- Indexes for table `tipdnevnika`
--
ALTER TABLE `tipdnevnika`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipkorisnika`
--
ALTER TABLE `tipkorisnika`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vlak`
--
ALTER TABLE `vlak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_Vlak_VrstaPogona1_idx` (`vrsta_pogona_id`),
  ADD KEY `fk_Vlak_Korisnik1_idx` (`vlasnik_id`);

--
-- Indexes for table `vrstamaterijala`
--
ALTER TABLE `vrstamaterijala`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vrstapogona`
--
ALTER TABLE `vrstapogona`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dnevnik`
--
ALTER TABLE `dnevnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `glasovanje`
--
ALTER TABLE `glasovanje`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `izlozba`
--
ALTER TABLE `izlozba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `materijal`
--
ALTER TABLE `materijal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `moderatori`
--
ALTER TABLE `moderatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `ocjena`
--
ALTER TABLE `ocjena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `prijavavlaka`
--
ALTER TABLE `prijavavlaka`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- AUTO_INCREMENT for table `statusizlozbe`
--
ALTER TABLE `statusizlozbe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `statusprijave`
--
ALTER TABLE `statusprijave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tematika`
--
ALTER TABLE `tematika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `tipdnevnika`
--
ALTER TABLE `tipdnevnika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tipkorisnika`
--
ALTER TABLE `tipkorisnika`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `vlak`
--
ALTER TABLE `vlak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `vrstamaterijala`
--
ALTER TABLE `vrstamaterijala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vrstapogona`
--
ALTER TABLE `vrstapogona`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dnevnik`
--
ALTER TABLE `dnevnik`
  ADD CONSTRAINT `fk_Dnevnik_Korisnik1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Dnevnik_TipDnevnika1` FOREIGN KEY (`tip_dnevnika_id`) REFERENCES `tipdnevnika` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `glasovanje`
--
ALTER TABLE `glasovanje`
  ADD CONSTRAINT `fk_Glasovanje_Izlozba1` FOREIGN KEY (`izlozba_id`) REFERENCES `izlozba` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `izlozba`
--
ALTER TABLE `izlozba`
  ADD CONSTRAINT `fk_Izlozba_Korisnik2` FOREIGN KEY (`moderator_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Izlozba_Tematika1` FOREIGN KEY (`tematika_id`) REFERENCES `tematika` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD CONSTRAINT `fk_Korisnik_TipKorisnika` FOREIGN KEY (`tip_korisnika_id`) REFERENCES `tipkorisnika` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `materijal`
--
ALTER TABLE `materijal`
  ADD CONSTRAINT `fk_Materijal_PrijavaVlaka1` FOREIGN KEY (`prijava_vlaka_id`) REFERENCES `prijavavlaka` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Materijal_VrstaMaterijala1` FOREIGN KEY (`vrsta_materijala_id`) REFERENCES `vrstamaterijala` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `moderatori`
--
ALTER TABLE `moderatori`
  ADD CONSTRAINT `fk_Moderatori_Korisnik1` FOREIGN KEY (`administrator_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Moderatori_Korisnik2` FOREIGN KEY (`moderator_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Moderatori_Tematika1` FOREIGN KEY (`tematika_id`) REFERENCES `tematika` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `ocjena`
--
ALTER TABLE `ocjena`
  ADD CONSTRAINT `fk_Ocjena_Korisnik` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Ocjena_PrijavaVlaka` FOREIGN KEY (`prijava_vlaka_id`) REFERENCES `prijavavlaka` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prijavavlaka`
--
ALTER TABLE `prijavavlaka`
  ADD CONSTRAINT `fk_PrijavaVlaka_Izlozba1` FOREIGN KEY (`izlozba_id`) REFERENCES `izlozba` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PrijavaVlaka_Korisnik1` FOREIGN KEY (`azurirao_moderator_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PrijavaVlaka_StatusPrijave1` FOREIGN KEY (`status_id`) REFERENCES `statusprijave` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_PrijavaVlaka_Vlak1` FOREIGN KEY (`vlak_id`) REFERENCES `vlak` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tematika`
--
ALTER TABLE `tematika`
  ADD CONSTRAINT `fk_Tematika_Korisnik1` FOREIGN KEY (`kreirao_korisnik_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Tematika_Korisnik2` FOREIGN KEY (`azurirao_korisnik_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `vlak`
--
ALTER TABLE `vlak`
  ADD CONSTRAINT `fk_Vlak_Korisnik1` FOREIGN KEY (`vlasnik_id`) REFERENCES `korisnik` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_Vlak_VrstaPogona1` FOREIGN KEY (`vrsta_pogona_id`) REFERENCES `vrstapogona` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
