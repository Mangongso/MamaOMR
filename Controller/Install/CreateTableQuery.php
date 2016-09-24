<?
$arrTable['book_info'] = "
CREATE TABLE book_info (
  seq INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  isbn_code VARCHAR(100) DEFAULT NULL,
  writer_seq BIGINT(20) NOT NULL,
  sub_writer_seq BIGINT(20) DEFAULT NULL,
  title VARCHAR(100) NOT NULL,
  pub_name VARCHAR(20) DEFAULT NULL,
  pub_year VARCHAR(4) DEFAULT NULL,
  cover_url VARCHAR(100) DEFAULT NULL,
  category_seq INT(10) DEFAULT '0',
  create_date DATETIME DEFAULT NULL,
  modify_date DATETIME DEFAULT NULL,
  delete_flg TINYINT(4) DEFAULT '0',
  pub_date VARCHAR(10) DEFAULT NULL,
  author VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY (seq)
) ENGINE=INNODB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8
";
$arrTable['category'] = "
CREATE TABLE category (
  seq INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  writer_seq BIGINT(20) NOT NULL,
  title VARCHAR(45) NOT NULL,
  category_type INT(11) NOT NULL,
  create_date DATETIME DEFAULT NULL,
  modify_date DATETIME DEFAULT NULL,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq)
) ENGINE=INNODB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8
";
$arrTable['member_basic_info'] = "
CREATE TABLE member_basic_info (
  member_seq BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  member_id VARCHAR(25) NOT NULL,
  email VARCHAR(100) NOT NULL,
  email_display ENUM('0','1') NOT NULL DEFAULT '0',
  NAME VARCHAR(50) DEFAULT NULL,
  name_display ENUM('0','1') NOT NULL DEFAULT '0',
  nickname VARCHAR(50) DEFAULT NULL,
  reg_date DATETIME DEFAULT NULL,
  modifydate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  login_num SMALLINT(6) DEFAULT NULL,
  pwd CHAR(41) DEFAULT NULL,
  del_flg ENUM('0','1') NOT NULL DEFAULT '0',
  ip_address VARCHAR(20) NOT NULL,
  auth_key CHAR(41) DEFAULT NULL,
  auth_flg TINYINT(1) NOT NULL DEFAULT '0',
  LEVEL SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
  admin_level SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
  n_keyword VARCHAR(30) DEFAULT NULL,
  EXP DOUBLE NOT NULL DEFAULT '0',
  team_seq INT(10) UNSIGNED ZEROFILL NOT NULL,
  parent_mb_seq INT(10) UNSIGNED ZEROFILL DEFAULT NULL,
  opensocial_flg TINYINT(1) NOT NULL DEFAULT '0',
  confirm_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  join_type VARCHAR(45) DEFAULT NULL,
  member_grade TINYINT(1) DEFAULT '0',
  login_guide_flg TINYINT(4) DEFAULT '0',
  PRIMARY KEY (member_seq,email)
) ENGINE=MYISAM AUTO_INCREMENT=38314458 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='InnoDB free: 10240 kB; InnoDB free: 10240 kB'
";
$arrTable['member_extend_info'] = "
CREATE TABLE member_extend_info (
  tel VARCHAR(20) DEFAULT NULL,
  cphone VARCHAR(20) DEFAULT NULL,
  sex ENUM('0','1','2') DEFAULT '0',
  address1 VARCHAR(100) DEFAULT NULL,
  address2 VARCHAR(100) DEFAULT NULL,
  zcode1 VARCHAR(4) DEFAULT NULL,
  zcode2 VARCHAR(4) DEFAULT NULL,
  idno1 CHAR(6) DEFAULT NULL,
  idno2 CHAR(7) DEFAULT NULL,
  messenger VARCHAR(100) DEFAULT NULL,
  messenger_display ENUM('0','1') DEFAULT '0',
  homepage VARCHAR(100) DEFAULT NULL,
  homepage_display ENUM('0','1') DEFAULT '0',
  birth_day_y CHAR(4) DEFAULT NULL,
  birth_day_m CHAR(2) DEFAULT NULL,
  birth_day_d CHAR(2) DEFAULT NULL,
  member_seq VARCHAR(25) NOT NULL,
  member_type VARCHAR(10) DEFAULT NULL,
  test_seq BIGINT(20) DEFAULT NULL,
  test_flg INT(11) DEFAULT '0',
  academy VARCHAR(100) DEFAULT NULL,
  school VARCHAR(100) DEFAULT NULL,
  SUBJECT VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (member_seq),
  KEY FKMEMBER_EXT8209 (member_seq),
  KEY fk_member_extend_info_member_basic_info1 (member_seq)
) ENGINE=MYISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='InnoDB free: 10240 kB';
";
$arrTable['post_comment'] = "
CREATE TABLE post_comment (
  cmt_id INT(11) NOT NULL AUTO_INCREMENT,
  cmt_name VARCHAR(50) DEFAULT NULL,
  reg_id VARCHAR(20) DEFAULT NULL,
  pwd CHAR(41) DEFAULT NULL,
  COMMENT TEXT NOT NULL,
  reg_ip VARCHAR(20) NOT NULL,
  reg_date DATETIME NOT NULL,
  post_seq BIGINT(20) UNSIGNED NOT NULL,
  bbs_seq VARCHAR(20) NOT NULL,
  PRIMARY KEY (cmt_id,post_seq,bbs_seq)
) ENGINE=INNODB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8
";
$arrTable['question'] = "
CREATE TABLE question (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  writer_seq BIGINT(20) NOT NULL,
  contents TEXT NOT NULL,
  question_type TINYINT(4) NOT NULL DEFAULT '0' COMMENT '질문 종류 - 주관식,객관식 등',
  example_type TINYINT(4) NOT NULL DEFAULT '0' COMMENT '중복 답변수 - 객관식',
  create_date DATETIME NOT NULL,
  modify_date DATETIME DEFAULT NULL,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  hint TEXT,
  commentary TEXT,
  tags VARCHAR(30) DEFAULT NULL,
  file_name VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq)
) ENGINE=INNODB AUTO_INCREMENT=19357 DEFAULT CHARSET=utf8	
";
$arrTable['question_example'] = "
CREATE TABLE question_example (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  question_seq BIGINT(19) UNSIGNED NOT NULL,
  example_type TINYINT(4) DEFAULT NULL,
  contents VARCHAR(100) NOT NULL DEFAULT '',
  create_date DATETIME NOT NULL,
  modify_date DATETIME DEFAULT NULL,
  subjective_answer VARCHAR(255) DEFAULT NULL,
  answer_flg TINYINT(4) NOT NULL DEFAULT '0',
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  example_number INT(11) DEFAULT NULL,
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq),
  KEY fk_example_question1_idx (question_seq),
  CONSTRAINT fk_example_question11 FOREIGN KEY (question_seq) REFERENCES question (seq) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB AUTO_INCREMENT=96983 DEFAULT CHARSET=utf8
";
$arrTable['question_history'] = "
CREATE TABLE question_history (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  question_seq BIGINT(19) DEFAULT NULL,
  writer_seq BIGINT(20) NOT NULL,
  contents TEXT NOT NULL,
  question_type TINYINT(4) NOT NULL DEFAULT '0' COMMENT '질문 종류 - 주관식,객관식 등',
  example_type TINYINT(4) NOT NULL DEFAULT '0' COMMENT '중복 답변수 - 객관식',
  create_date DATETIME NOT NULL,
  modify_date DATETIME DEFAULT NULL,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  hint TEXT,
  commentary TEXT,
  tags VARCHAR(30) DEFAULT NULL,
  file_name VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq)
) ENGINE=INNODB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8
";
$arrTable['question_jimoon'] = "
CREATE TABLE question_jimoon (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  contents TEXT,
  create_date DATETIME DEFAULT NULL,
  modify_date DATETIME DEFAULT NULL,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq)
) ENGINE=INNODB DEFAULT CHARSET=utf8
";
$arrTable['question_tag'] = "
CREATE TABLE question_tag (
  question_seq BIGINT(20) NOT NULL,
  tag VARCHAR(20) NOT NULL,
  create_date DATETIME DEFAULT NULL,
  PRIMARY KEY (question_seq,tag)
) ENGINE=INNODB DEFAULT CHARSET=utf8
";
$arrTable['record'] = "
CREATE TABLE record (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  revision SMALLINT(5) UNSIGNED NOT NULL,
  user_seq BIGINT(20) UNSIGNED NOT NULL,
  user_name VARCHAR(45) NOT NULL,
  sex ENUM('0','1','2') NOT NULL DEFAULT '0',
  user_score INT(3) NOT NULL,
  total_score INT(3) NOT NULL,
  create_date DATETIME DEFAULT NULL,
  modify_date DATETIME DEFAULT NULL,
  right_count INT(3) DEFAULT '0',
  wrong_count INT(3) DEFAULT '0',
  testing_time TIME DEFAULT NULL,
  start_date DATETIME DEFAULT NULL,
  end_date DATETIME DEFAULT NULL,
  PRIMARY KEY (seq)
) ENGINE=INNODB AUTO_INCREMENT=13967 DEFAULT CHARSET=utf8
";
$arrTable['student_manager'] = "
CREATE TABLE student_manager (
  seq INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  manager_seq BIGINT(20) DEFAULT NULL,
  student_seq BIGINT(20) DEFAULT NULL,
  create_date DATETIME DEFAULT NULL,
  STATUS TINYINT(4) DEFAULT NULL,
  delete_flg TINYINT(1) DEFAULT '0',
  modify_date DATETIME DEFAULT NULL,
  auth_key VARCHAR(100) DEFAULT NULL,
  PRIMARY KEY (seq),
  KEY seq (seq)
) ENGINE=INNODB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8
";
$arrTable['tag'] = "
CREATE TABLE tag (
  tag VARCHAR(20) NOT NULL,
  TYPE TINYINT(4) NOT NULL COMMENT '1 : 테스트\n2 : 문제',
  member_seq BIGINT(20) NOT NULL,
  create_date DATETIME DEFAULT NULL,
  PRIMARY KEY (tag,TYPE,member_seq)
) ENGINE=INNODB DEFAULT CHARSET=utf8
		
";
$arrTable['teacher_student_list'] = "
CREATE TABLE teacher_student_list (
  seq BIGINT(20) NOT NULL AUTO_INCREMENT,
  teacher_seq BIGINT(20) NOT NULL,
  student_seq BIGINT(20) NOT NULL,
  delete_flg TINYINT(4) DEFAULT '0',
  approve_flg TINYINT(1) DEFAULT '0',
  apply_date DATETIME DEFAULT NULL,
  approve_date DATETIME DEFAULT NULL,
  modify_date DATETIME DEFAULT NULL,
  PRIMARY KEY (seq)
) ENGINE=INNODB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8
";
$arrTable['test'] = "
CREATE TABLE test (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  writer_seq BIGINT(20) NOT NULL,
  sub_master BIGINT(20) NOT NULL,
  TYPE INT(11) NOT NULL DEFAULT '0',
  SUBJECT VARCHAR(100) DEFAULT NULL,
  contents TEXT,
  create_date DATETIME NOT NULL,
  modify_date DATETIME DEFAULT NULL,
  example_numbering_style TINYINT(4) UNSIGNED NOT NULL DEFAULT '0',
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  tags VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq)
) ENGINE=INNODB AUTO_INCREMENT=1699 DEFAULT CHARSET=utf8
";
$arrTable['test_join_user'] = "
CREATE TABLE test_join_user (
  seq INT(11) NOT NULL AUTO_INCREMENT,
  user_group_seq INT(10) UNSIGNED DEFAULT NULL,
  user_seq BIGINT(20) UNSIGNED NOT NULL,
  join_date DATETIME DEFAULT NULL,
  create_date DATETIME DEFAULT NULL,
  start_date DATETIME DEFAULT NULL,
  end_date DATETIME DEFAULT NULL,
  test_status_flg TINYINT(4) NOT NULL DEFAULT '0',
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  test_published_seq BIGINT(19) UNSIGNED NOT NULL,
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  read_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  modify_date DATETIME DEFAULT NULL,
  PRIMARY KEY (seq,test_published_seq,test_seq),
  KEY fk_survey_join_user_survey_published1_idx (test_published_seq,test_seq)
) ENGINE=INNODB AUTO_INCREMENT=9115 DEFAULT CHARSET=utf8
";
$arrTable['test_published'] = "
CREATE TABLE test_published (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  published_date DATETIME DEFAULT NULL,
  start_date DATETIME DEFAULT NULL,
  finish_date DATETIME DEFAULT NULL,
  state TINYINT(4) DEFAULT '0',
  delete_flg TINYINT(4) DEFAULT '0',
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  TIME TIME DEFAULT NULL,
  category_seq INT(11) DEFAULT NULL,
  group_list_seq INT(11) DEFAULT '0',
  total_score INT(3) NOT NULL DEFAULT '100',
  test_prog_flg TINYINT(1) NOT NULL DEFAULT '1',
  published_type TINYINT(3) UNSIGNED NOT NULL,
  paper_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '2' COMMENT '0:omr,1:paper,2:auto',
  record_view_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  repeat_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  test_view_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1:single_view, 2:all_view',
  deadline_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  display_flg TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '0:none, 1:block',
  book_seq INT(11) DEFAULT NULL,
  PRIMARY KEY (seq,test_seq),
  KEY fk_survey_published_survey1_idx (test_seq)
) ENGINE=INNODB AUTO_INCREMENT=1680 DEFAULT CHARSET=utf8
";
$arrTable['test_question_list'] = "
CREATE TABLE test_question_list (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  question_seq BIGINT(19) UNSIGNED NOT NULL,
  question_number INT(11) DEFAULT NULL,
  question_score FLOAT DEFAULT '0',
  order_number SMALLINT(5) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq),
  KEY fk_survey_question_list_survey1_idx (test_seq),
  KEY fk_survey_question_list_question1_idx (question_seq)
) ENGINE=INNODB AUTO_INCREMENT=19351 DEFAULT CHARSET=utf8
";
$arrTable['test_tag'] = "
CREATE TABLE test_tag (
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  tag VARCHAR(20) NOT NULL,
  create_date DATETIME NOT NULL,
  PRIMARY KEY (test_seq,tag)
) ENGINE=INNODB DEFAULT CHARSET=utf8
";
$arrTable['user_answer'] = "
CREATE TABLE user_answer (
  seq BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_seq BIGINT(20) NOT NULL,
  test_seq BIGINT(19) UNSIGNED NOT NULL,
  record_seq BIGINT(20) UNSIGNED DEFAULT NULL,
  question_seq BIGINT(19) UNSIGNED NOT NULL,
  question_answer VARCHAR(255) NOT NULL DEFAULT '',
  user_answer VARCHAR(255) NOT NULL DEFAULT '',
  result_flg TINYINT(4) NOT NULL DEFAULT '0',
  create_date DATETIME NOT NULL,
  user_name VARCHAR(50) DEFAULT NULL,
  sex ENUM('0','1','2') DEFAULT '0',
  score FLOAT DEFAULT '0',
  delete_flg TINYINT(1) UNSIGNED DEFAULT '0',
  PRIMARY KEY (seq),
  UNIQUE KEY seq_UNIQUE (seq),
  KEY fk_user_answer_question1_idx (question_seq),
  KEY fk_user_answer_survey_published1_idx (test_seq),
  CONSTRAINT fk_user_answer_question1 FOREIGN KEY (question_seq) REFERENCES question (seq) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB AUTO_INCREMENT=100618 DEFAULT CHARSET=utf8
";
$arrTable['user_answer_discus'] = "
CREATE TABLE user_answer_discus (
  seq BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_answer_seq BIGINT(20) DEFAULT NULL,
  survey_seq BIGINT(20) DEFAULT NULL,
  record_seq BIGINT(20) DEFAULT NULL,
  question_seq BIGINT(20) DEFAULT NULL,
  user_seq BIGINT(20) DEFAULT NULL,
  discus_answer TEXT,
  answer_comment TEXT,
  PRIMARY KEY (seq)
) ENGINE=INNODB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8
";
$arrTable['wrong_note'] = "
CREATE TABLE wrong_note (
  seq INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  user_seq BIGINT(20) NOT NULL,
  note_title VARCHAR(100) DEFAULT NULL,
  create_date DATETIME NOT NULL,
  last_update_date DATETIME NOT NULL,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (seq)
) ENGINE=INNODB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8
";
$arrTable['wrong_note_list'] = "
CREATE TABLE wrong_note_list (
  seq BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  wrong_note_seq INT(10) UNSIGNED NOT NULL,
  user_seq BIGINT(20) NOT NULL,
  test_seq BIGINT(20) NOT NULL,
  record_seq BIGINT(20) NOT NULL,
  question_seq BIGINT(20) NOT NULL,
  question_order_no SMALLINT(5) UNSIGNED DEFAULT NULL,
  user_answer VARCHAR(255) DEFAULT NULL,
  create_date DATETIME NOT NULL,
  test_date DATETIME NOT NULL,
  note TEXT,
  delete_flg TINYINT(4) NOT NULL DEFAULT '0',
  file_name VARCHAR(255) DEFAULT NULL,
  question TEXT,
  PRIMARY KEY (seq,wrong_note_seq),
  KEY fk_wrong_note_list_wrong_note1 (wrong_note_seq),
  CONSTRAINT fk_wrong_note_list_wrong_note1 FOREIGN KEY (wrong_note_seq) REFERENCES wrong_note (seq) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=INNODB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8
";
?>