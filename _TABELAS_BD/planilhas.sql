CREATE TABLE planilhas(
planilha_ano INT(4) NOT NULL,
planilha_bimestre_sequencial INT(11) NOT NULL,
planilha_usuario_loguin VARCHAR(80) NOT NULL,
planilha_disciplina VARCHAR(255) NOT NULL,
planilha_linha INT(11) NOT NULL,

planilha_coluna_a_escola VARCHAR(255),
planilha_coluna_b_ano VARCHAR(255),
planilha_coluna_c_mes VARCHAR(255),
planilha_coluna_d_epoca VARCHAR(255),
planilha_coluna_e_turma VARCHAR(255),
planilha_coluna_f_disciplina VARCHAR(255),
planilha_coluna_g_coddisciplina VARCHAR(255),
planilha_coluna_h_matricula VARCHAR(255),
planilha_coluna_i_aluno VARCHAR(255),
planilha_coluna_j_numero VARCHAR(255),
planilha_coluna_k_nota DOUBLE(5,2),
planilha_coluna_l_falta INT(3),
planilha_coluna_m_dispensa VARCHAR(255),
planilha_coluna_n_aulas_dadas INT(3),
planilha_coluna_o_vazia VARCHAR(255),
planilha_coluna_p_dispensa_codigo VARCHAR(255),
planilha_coluna_q_dispensa_descricao VARCHAR(255),

planilha_status VARCHAR(15),
planilha_data_alteracao DATE default '0000-00-00',
planilha_hota_alteracao TIME,

PRIMARY KEY(planilha_ano, planilha_bimestre_sequencial, planilha_usuario_loguin, planilha_disciplina, planilha_linha),

CONSTRAINT planilha_bimestre_sequencial FOREIGN KEY (planilha_bimestre_sequencial) REFERENCES bimestres(bimestre_sequencial) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT planilha_usuario_loguin FOREIGN KEY (planilha_usuario_loguin) REFERENCES usuarios(usuario_loguin) ON DELETE CASCADE ON UPDATE CASCADE
);