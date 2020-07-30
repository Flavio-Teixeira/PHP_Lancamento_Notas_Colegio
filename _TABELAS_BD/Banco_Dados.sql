CREATE TABLE `bimestres` (
  `bimestre_sequencial` int(11) NOT NULL AUTO_INCREMENT,
  `bimestre_descricao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bimestre_sequencial`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE `usuarios` (
  `usuario_loguin` varchar(80) NOT NULL,
  `usuario_senha` varchar(40) DEFAULT NULL,
  `usuario_nome` varchar(255) DEFAULT NULL,
  `usuario_pasta` varchar(255) DEFAULT NULL,
  `usuario_tipo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`usuario_loguin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `planilhas` (
  `planilha_ano` int(4) NOT NULL,
  `planilha_bimestre_sequencial` int(11) NOT NULL,
  `planilha_usuario_loguin` varchar(80) NOT NULL,
  `planilha_disciplina` varchar(255) NOT NULL,
  `planilha_linha` int(11) NOT NULL,
  `planilha_bimestre_descricao` varchar(255) DEFAULT NULL,
  `planilha_nome_professor` varchar(255) DEFAULT NULL,
  `planilha_coluna_a_escola` varchar(255) DEFAULT NULL,
  `planilha_coluna_b_ano` varchar(255) DEFAULT NULL,
  `planilha_coluna_c_mes` varchar(255) DEFAULT NULL,
  `planilha_coluna_d_epoca` varchar(255) DEFAULT NULL,
  `planilha_coluna_e_turma` varchar(255) DEFAULT NULL,
  `planilha_coluna_f_disciplina` varchar(255) DEFAULT NULL,
  `planilha_coluna_g_coddisciplina` varchar(255) DEFAULT NULL,
  `planilha_coluna_h_matricula` varchar(255) DEFAULT NULL,
  `planilha_coluna_i_aluno` varchar(255) DEFAULT NULL,
  `planilha_coluna_j_numero` varchar(255) DEFAULT NULL,
  `planilha_coluna_k_nota` double(5,2) DEFAULT NULL,
  `planilha_coluna_l_falta` int(3) DEFAULT NULL,
  `planilha_coluna_m_dispensa` varchar(255) DEFAULT NULL,
  `planilha_coluna_n_aulas_dadas` int(3) DEFAULT NULL,
  `planilha_coluna_o_vazia` varchar(255) DEFAULT NULL,
  `planilha_coluna_p_dispensa_codigo` varchar(255) DEFAULT NULL,
  `planilha_coluna_q_dispensa_descricao` varchar(255) DEFAULT NULL,
  `planilha_status` varchar(15) DEFAULT NULL,
  `planilha_data_alteracao` date DEFAULT '0000-00-00',
  `planilha_hora_alteracao` time DEFAULT NULL,
  PRIMARY KEY (`planilha_ano`,`planilha_bimestre_sequencial`,`planilha_usuario_loguin`,`planilha_disciplina`,`planilha_linha`),
  CONSTRAINT planilha_bimestre_sequencial FOREIGN KEY (planilha_bimestre_sequencial) REFERENCES bimestres(bimestre_sequencial) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT planilha_usuario_loguin FOREIGN KEY (planilha_usuario_loguin) REFERENCES usuarios(usuario_loguin) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


