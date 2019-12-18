-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: 18-Dez-2019 às 11:32
-- Versão do servidor: 5.7.26
-- versão do PHP: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdponto`
--
CREATE DATABASE IF NOT EXISTS `bdponto` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bdponto`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE IF NOT EXISTS `empresas` (
  `EMPRESA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_EMPRESA` varchar(80) NOT NULL,
  `EMPRESA_ATIVA` int(1) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`EMPRESA_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `empresas`
--

INSERT INTO `empresas` (`EMPRESA_ID`, `NM_EMPRESA`, `EMPRESA_ATIVA`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'EMPRESA', 1, '2020-01-05 02:05:18', '2019-03-04 21:05:56');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcionario`
--

DROP TABLE IF EXISTS `funcionario`;
CREATE TABLE IF NOT EXISTS `funcionario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` int(10) NOT NULL,
  `QTD_ALT_COD` int(1) NOT NULL DEFAULT '0',
  `NOME` varchar(100) NOT NULL,
  `FUNCAO` int(11) DEFAULT NULL,
  `EMPRESA` int(11) NOT NULL,
  `TIPO_FUNCIONARIO` int(11) NOT NULL,
  `TURNO` varchar(1) DEFAULT NULL,
  `FUNC_ATIVO` int(1) DEFAULT '1',
  `DT_CADASTRO` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`) USING BTREE,
  UNIQUE KEY `CODIGO` (`CODIGO`),
  KEY `FUNCAO` (`FUNCAO`),
  KEY `EMPRESA` (`EMPRESA`),
  KEY `TIPO_FUNCIONARIO` (`TIPO_FUNCIONARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `funcionario`
--

INSERT INTO `funcionario` (`ID`, `CODIGO`, `QTD_ALT_COD`, `NOME`, `FUNCAO`, `EMPRESA`, `TIPO_FUNCIONARIO`, `TURNO`, `FUNC_ATIVO`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 1, 0, 'funcionario', 1, 1, 1, 'I', 1, '2020-01-05 16:03:48', '2019-03-05 18:02:05');

-- --------------------------------------------------------

--
-- Estrutura da tabela `funcoes`
--

DROP TABLE IF EXISTS `funcoes`;
CREATE TABLE IF NOT EXISTS `funcoes` (
  `FUNCAO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_FUNCAO` varchar(80) NOT NULL,
  `FUNCAO_ATIVA` int(1) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`FUNCAO_ID`),
  UNIQUE KEY `DESCRICAO` (`NM_FUNCAO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `funcoes`
--

INSERT INTO `funcoes` (`FUNCAO_ID`, `NM_FUNCAO`, `FUNCAO_ATIVA`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'Administrador', 1, '2020-01-05 17:59:42', '2019-03-04 17:06:20');

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupoacesso`
--

DROP TABLE IF EXISTS `grupoacesso`;
CREATE TABLE IF NOT EXISTS `grupoacesso` (
  `GRUPOACESSO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_GRUPOACESSO` varchar(30) NOT NULL,
  `GRUPOACESSO_ATIVO` int(1) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`GRUPOACESSO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `grupoacesso`
--

INSERT INTO `grupoacesso` (`GRUPOACESSO_ID`, `NM_GRUPOACESSO`, `GRUPOACESSO_ATIVO`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'ADMINISTRADOR MASTER', 1, '2020-01-05 01:55:31', '2019-04-17 11:30:19'),
(2, 'ADMINISTRADOR', 1, '2020-01-05 02:20:56', '2019-11-10 22:55:57'),
(3, 'PADRAO', 1, '2020-01-05 14:41:31', '2019-08-17 21:21:46');

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `MENU_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_MENU` varchar(30) NOT NULL,
  `MENU_ATIVO` int(1) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`MENU_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `menu`
--

INSERT INTO `menu` (`MENU_ID`, `NM_MENU`, `MENU_ATIVO`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'Relatorio de ponto', 1, '2019-08-12 12:23:56', NULL),
(2, 'Pausa/Intervalo Ativo', 1, '2019-08-12 12:23:56', NULL),
(3, 'Cadastro de Funcionarios', 1, '2019-08-12 12:23:56', NULL),
(4, 'Cadastro de Usuarios', 1, '2019-08-12 12:23:56', NULL),
(5, 'Cadastro de Funcoes', 1, '2019-08-12 12:23:56', NULL),
(6, 'Cadastro de Empresas', 1, '2019-08-12 12:29:02', NULL),
(7, 'Tipos de Contrato', 1, '2019-08-12 12:29:02', NULL),
(8, 'Grupo de Acesso', 1, '2019-08-12 12:29:02', NULL),
(9, 'Permissoes', 1, '2019-08-12 12:29:02', NULL),
(10, 'Parametros', 1, '2019-08-12 12:29:02', NULL),
(11, 'Relatorio Detalhado', 1, '2019-11-02 23:30:56', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `parametros`
--

DROP TABLE IF EXISTS `parametros`;
CREATE TABLE IF NOT EXISTS `parametros` (
  `PARAMETRO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `QTD_MAX_PAUSA` int(11) DEFAULT '2',
  `MINUTOS` int(11) DEFAULT '10',
  `TMP_MIN_PAUSE` smallint(6) NOT NULL DEFAULT '60',
  `EMPPADRAO` int(5) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`PARAMETRO_ID`),
  KEY `EMPPADRAO` (`EMPPADRAO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `parametros`
--

INSERT INTO `parametros` (`PARAMETRO_ID`, `QTD_MAX_PAUSA`, `MINUTOS`, `TMP_MIN_PAUSE`, `EMPPADRAO`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 20, 50, 0, 1, '2020-01-05 08:13:39', '2020-01-05 21:18:50');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes`
--

DROP TABLE IF EXISTS `permissoes`;
CREATE TABLE IF NOT EXISTS `permissoes` (
  `PERMISSOES_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_PERMISSOES` varchar(30) NOT NULL,
  `PERMISSOES_ATIVA` int(1) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`PERMISSOES_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `permissoes`
--

INSERT INTO `permissoes` (`PERMISSOES_ID`, `NM_PERMISSOES`, `PERMISSOES_ATIVA`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'Pausas Ativas', 1, '2019-08-12 12:30:13', NULL),
(2, 'Cadastros', 1, '2019-08-12 12:30:13', NULL),
(3, 'RelatÃ³rio de Ponto', 1, '2019-08-12 12:30:49', '2019-08-12 10:23:30'),
(4, 'Parametros', 1, '2019-08-12 12:30:49', NULL),
(5, 'Permissoes do usuario', 1, '2019-08-12 12:31:09', NULL),
(6, 'Tipos de Contrato', 1, '2019-08-12 12:59:34', NULL),
(7, 'Grupos de Acesso', 1, '2019-08-12 13:00:13', NULL),
(8, 'RelatÃ³rio Detalhado', 1, '2019-11-02 23:31:34', '2019-11-02 20:31:49'),
(9, 'ADMINISTRADOR2', 1, '2019-11-11 01:49:50', '2019-11-10 23:06:00');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes_grupos`
--

DROP TABLE IF EXISTS `permissoes_grupos`;
CREATE TABLE IF NOT EXISTS `permissoes_grupos` (
  `GRUPO_ID` int(11) NOT NULL,
  `PERMISSOES_ID` int(11) NOT NULL,
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`GRUPO_ID`,`PERMISSOES_ID`),
  KEY `PG_PERMISSOES_GRUPOS_FK2` (`PERMISSOES_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `permissoes_grupos`
--

INSERT INTO `permissoes_grupos` (`GRUPO_ID`, `PERMISSOES_ID`, `DT_CADASTRO`) VALUES
(1, 1, '2019-08-12 13:17:30'),
(1, 2, '2019-08-12 13:17:30'),
(1, 3, '2019-08-12 13:17:30'),
(1, 4, '2019-08-12 13:17:30'),
(1, 5, '2019-08-12 13:17:30'),
(1, 6, '2019-08-12 13:17:47'),
(1, 7, '2019-08-12 13:17:47'),
(1, 8, '2019-08-12 13:17:47'),
(2, 1, '2019-08-12 13:26:27'),
(2, 2, '2019-08-18 00:21:38'),
(2, 3, '2019-08-18 00:21:38'),
(2, 4, '2019-10-23 13:33:11'),
(2, 5, '2019-08-12 13:26:27'),
(2, 6, '2019-08-18 00:21:38'),
(2, 7, '2019-08-18 00:21:38'),
(2, 8, '2019-11-02 23:32:12'),
(2, 9, '2019-11-11 01:55:57'),
(3, 1, '2019-08-18 00:21:46');

-- --------------------------------------------------------

--
-- Estrutura da tabela `permissoes_menu`
--

DROP TABLE IF EXISTS `permissoes_menu`;
CREATE TABLE IF NOT EXISTS `permissoes_menu` (
  `PERMISSOES_ID` int(11) NOT NULL,
  `MENU_ID` int(11) NOT NULL,
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY `PERMISSOES_MENU_FK` (`PERMISSOES_ID`),
  KEY `PERMISSOES_MENU_FK2` (`MENU_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `permissoes_menu`
--

INSERT INTO `permissoes_menu` (`PERMISSOES_ID`, `MENU_ID`, `DT_CADASTRO`) VALUES
(1, 2, '2019-08-12 13:21:05'),
(2, 3, '2019-08-12 13:21:05'),
(2, 4, '2019-08-12 13:21:05'),
(2, 5, '2019-08-12 13:21:05'),
(2, 6, '2019-08-12 13:21:05'),
(3, 1, '2019-08-12 13:21:05'),
(4, 10, '2019-08-12 13:21:05'),
(5, 9, '2019-08-12 13:21:05'),
(6, 7, '2019-08-12 13:21:05'),
(7, 8, '2019-08-12 13:21:05'),
(8, 11, '2019-11-02 23:31:49'),
(9, 6, '2019-11-11 01:50:06'),
(9, 3, '2019-11-11 01:50:06'),
(9, 5, '2019-11-11 01:50:06'),
(9, 4, '2019-11-11 01:50:06'),
(9, 8, '2019-11-11 01:50:06'),
(9, 10, '2019-11-11 01:50:06'),
(9, 2, '2019-11-11 01:50:06'),
(9, 9, '2019-11-11 01:50:06'),
(9, 1, '2019-11-11 01:50:06'),
(9, 11, '2019-11-11 01:50:06'),
(9, 7, '2019-11-11 01:50:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `registros`
--

DROP TABLE IF EXISTS `registros`;
CREATE TABLE IF NOT EXISTS `registros` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` int(10) NOT NULL,
  `DATA` date NOT NULL,
  `HORA_ENTRADA` varchar(8) DEFAULT NULL,
  `HORA_SAIDA_INTERVALO` varchar(8) DEFAULT NULL,
  `HORA_RETORNO_INTERVALO` varchar(8) DEFAULT NULL,
  `HORA_SAIDA` varchar(8) DEFAULT NULL,
  `HORA_SAIDA_PAUSA` varchar(8) DEFAULT NULL,
  `HORA_VOLTA_PAUSA` varchar(8) DEFAULT NULL,
  `PAUSA_ATIVA` int(1) NOT NULL DEFAULT '0',
  `HORA_PAUSA_ATIVA` varchar(8) DEFAULT NULL,
  `HORA_PAUSA_INATIVA` varchar(8) DEFAULT NULL,
  `DESCRICAO_PAUSA_ATIVA` varchar(80) DEFAULT NULL,
  `ALTERADO` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `CODIGO` (`CODIGO`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `registros`
--

INSERT INTO `registros` (`ID`, `CODIGO`, `DATA`, `HORA_ENTRADA`, `HORA_SAIDA_INTERVALO`, `HORA_RETORNO_INTERVALO`, `HORA_SAIDA`, `HORA_SAIDA_PAUSA`, `HORA_VOLTA_PAUSA`, `PAUSA_ATIVA`, `HORA_PAUSA_ATIVA`, `HORA_PAUSA_INATIVA`, `DESCRICAO_PAUSA_ATIVA`, `ALTERADO`) VALUES
(1, 1, '2018-11-15', '07:52', '08:00', '11:00', '11:15', '21:00', '21:35', 0, NULL, '', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipocontrato`
--

DROP TABLE IF EXISTS `tipocontrato`;
CREATE TABLE IF NOT EXISTS `tipocontrato` (
  `TIPOCONTRATO_ID` int(11) NOT NULL AUTO_INCREMENT,
  `NM_TIPOCONTRATO` varchar(80) NOT NULL,
  `TIPOCONTRATO_ATIVO` int(1) NOT NULL DEFAULT '1',
  `QTDREGISTROS` smallint(2) NOT NULL,
  `RELATORIOPAUSAATIVA` int(1) NOT NULL,
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`TIPOCONTRATO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tipocontrato`
--

INSERT INTO `tipocontrato` (`TIPOCONTRATO_ID`, `NM_TIPOCONTRATO`, `TIPOCONTRATO_ATIVO`, `QTDREGISTROS`, `RELATORIOPAUSAATIVA`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'CONTRATADO', 1, 6, 1, '2019-03-05 04:08:49', '2019-11-10 21:19:53');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(100) NOT NULL,
  `PASSWORD` varchar(100) NOT NULL,
  `NOME` varchar(100) NOT NULL,
  `USER_ATIVO` tinyint(1) NOT NULL DEFAULT '1',
  `GRUPOACESSO` int(11) NOT NULL,
  `PARAMETRO_ID` int(11) NOT NULL DEFAULT '1',
  `DT_CADASTRO` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DT_ALTERACAO` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `GRUPOACESSO_FK` (`GRUPOACESSO`),
  KEY `USERPARAMETROID_FK` (`PARAMETRO_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`ID`, `LOGIN`, `PASSWORD`, `NOME`, `USER_ATIVO`, `GRUPOACESSO`, `PARAMETRO_ID`, `DT_CADASTRO`, `DT_ALTERACAO`) VALUES
(1, 'admin', '$2y$10$bnkw/xk7ak3IAyRxd2OC3umy9Fdd4BuAH53JkZfBdWsi47xK9MN0C', 'Administrador', 1, 1, 1, '2018-12-14 17:32:18', '2018-12-23 17:58:21');
/* Senha: 12345 */
-- --------------------------------------------------------

--
-- Stand-in structure for view `vwrelatorio_detalhado`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `vwrelatorio_detalhado`;
CREATE TABLE IF NOT EXISTS `vwrelatorio_detalhado` (
`NOME` varchar(100)
,`CODIGO` int(10)
,`QTDREGISTROS` smallint(2)
,`TIPOCONTRATO_ID` int(11)
,`NM_TIPOCONTRATO` varchar(80)
,`EMPRESA_ID` int(11)
,`NM_EMPRESA` varchar(80)
,`FUNCAO_ID` int(11)
,`NM_FUNCAO` varchar(80)
,`DATA` date
,`ALTERADO` int(4)
,`SAIDA_1PAUSA` varchar(8)
,`VOLTA_1PAUSA` varchar(8)
,`DURACAO_1PAUSA` varchar(10)
,`SAIDA_INTERVALOT6` varchar(8)
,`VOLTA_INTERVALOT6` varchar(8)
,`DURACAO_INTERVALOT6` varchar(10)
,`SAIDA_2PAUSA` varchar(8)
,`VOLTA_2PAUSA` varchar(8)
,`DURACAO_2PAUSA` varchar(10)
,`SAIDA_INTERVALOT2` varchar(8)
,`VOLTA_INTERVALOT2` varchar(8)
,`DURACAO_INTERVALOT2` varchar(10)
,`SAIDA_ALMOCO` varchar(8)
,`VOLTA_ALMOCO` varchar(8)
,`DURACAO_ALMOCO` varchar(10)
);

-- --------------------------------------------------------

--
-- Structure for view `vwrelatorio_detalhado`
--
DROP TABLE IF EXISTS `vwrelatorio_detalhado`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vwrelatorio_detalhado`  AS  select `f`.`NOME` AS `NOME`,`f`.`CODIGO` AS `CODIGO`,`tc`.`QTDREGISTROS` AS `QTDREGISTROS`,`tc`.`TIPOCONTRATO_ID` AS `TIPOCONTRATO_ID`,`tc`.`NM_TIPOCONTRATO` AS `NM_TIPOCONTRATO`,`e`.`EMPRESA_ID` AS `EMPRESA_ID`,`e`.`NM_EMPRESA` AS `NM_EMPRESA`,`ff`.`FUNCAO_ID` AS `FUNCAO_ID`,`ff`.`NM_FUNCAO` AS `NM_FUNCAO`,`r`.`DATA` AS `DATA`,`r`.`ALTERADO` AS `ALTERADO`,`r`.`HORA_ENTRADA` AS `SAIDA_1PAUSA`,`r`.`HORA_SAIDA_INTERVALO` AS `VOLTA_1PAUSA`,time_format(timediff(`r`.`HORA_SAIDA_INTERVALO`,`r`.`HORA_ENTRADA`),'%H:%i') AS `DURACAO_1PAUSA`,`r`.`HORA_RETORNO_INTERVALO` AS `SAIDA_INTERVALOT6`,`r`.`HORA_SAIDA` AS `VOLTA_INTERVALOT6`,time_format(timediff(`r`.`HORA_SAIDA`,`r`.`HORA_RETORNO_INTERVALO`),'%H:%i') AS `DURACAO_INTERVALOT6`,`r`.`HORA_SAIDA_PAUSA` AS `SAIDA_2PAUSA`,`r`.`HORA_VOLTA_PAUSA` AS `VOLTA_2PAUSA`,time_format(timediff(`r`.`HORA_VOLTA_PAUSA`,`r`.`HORA_SAIDA_PAUSA`),'%H:%i') AS `DURACAO_2PAUSA`,`r`.`HORA_ENTRADA` AS `SAIDA_INTERVALOT2`,`r`.`HORA_SAIDA_INTERVALO` AS `VOLTA_INTERVALOT2`,time_format(timediff(`r`.`HORA_SAIDA_INTERVALO`,`r`.`HORA_ENTRADA`),'%H:%i') AS `DURACAO_INTERVALOT2`,`r`.`HORA_SAIDA_INTERVALO` AS `SAIDA_ALMOCO`,`r`.`HORA_RETORNO_INTERVALO` AS `VOLTA_ALMOCO`,time_format(timediff(`r`.`HORA_RETORNO_INTERVALO`,`r`.`HORA_SAIDA_INTERVALO`),'%H:%i') AS `DURACAO_ALMOCO` from ((((`registros` `r` join `funcionario` `f` on((`r`.`CODIGO` = `f`.`CODIGO`))) join `tipocontrato` `tc` on((`f`.`TIPO_FUNCIONARIO` = `tc`.`TIPOCONTRATO_ID`))) join `empresas` `e` on((`f`.`EMPRESA` = `e`.`EMPRESA_ID`))) join `funcoes` `ff` on((`f`.`FUNCAO` = `ff`.`FUNCAO_ID`))) ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `EMPRESA_FUNCIONARIO` FOREIGN KEY (`EMPRESA`) REFERENCES `empresas` (`EMPRESA_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FUNCAO_FUNCIONARIO_FK` FOREIGN KEY (`FUNCAO`) REFERENCES `funcoes` (`FUNCAO_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `TIPOCONTRATO_FUNCIONARIO_FK` FOREIGN KEY (`TIPO_FUNCIONARIO`) REFERENCES `tipocontrato` (`TIPOCONTRATO_ID`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `parametros`
--
ALTER TABLE `parametros`
  ADD CONSTRAINT `PARAMETRO_EMPRESA` FOREIGN KEY (`EMPPADRAO`) REFERENCES `empresas` (`EMPRESA_ID`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `permissoes_grupos`
--
ALTER TABLE `permissoes_grupos`
  ADD CONSTRAINT `PG_PERMISSOES_GRUPOS_FK` FOREIGN KEY (`GRUPO_ID`) REFERENCES `grupoacesso` (`GRUPOACESSO_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PG_PERMISSOES_GRUPOS_FK2` FOREIGN KEY (`PERMISSOES_ID`) REFERENCES `permissoes` (`PERMISSOES_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `permissoes_menu`
--
ALTER TABLE `permissoes_menu`
  ADD CONSTRAINT `PERMISSOES_MENU_FK` FOREIGN KEY (`PERMISSOES_ID`) REFERENCES `permissoes` (`PERMISSOES_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `PERMISSOES_MENU_FK2` FOREIGN KEY (`MENU_ID`) REFERENCES `menu` (`MENU_ID`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `FUNC_REGISTR_FK` FOREIGN KEY (`CODIGO`) REFERENCES `funcionario` (`CODIGO`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `USERGRUPOACESSO_FK` FOREIGN KEY (`GRUPOACESSO`) REFERENCES `grupoacesso` (`GRUPOACESSO_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `USERPARAMETROID_FK` FOREIGN KEY (`PARAMETRO_ID`) REFERENCES `parametros` (`PARAMETRO_ID`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
