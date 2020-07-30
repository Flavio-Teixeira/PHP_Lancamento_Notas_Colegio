CREATE TABLE `usuarios` (
  `usuario_loguin` varchar(80) NOT NULL,
  `usuario_senha` varchar(40) DEFAULT NULL,
  `usuario_nome` varchar(255) DEFAULT NULL,
  `usuario_pasta` varchar(255) DEFAULT NULL,
  `usuario_tipo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`usuario_loguin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
