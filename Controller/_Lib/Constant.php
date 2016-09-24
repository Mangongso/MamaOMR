<?php
/*
 * 기본상수
 */
define("DR",$_SERVER["DOCUMENT_ROOT"]);

/**
 * 로그인 확인 상수
 */
define("AUTH_FALSE", 0);
define("AUTH_TRUE", 1);

/**
 * 문제 형식 상수
 */
define("QUESTION_TYPE_1_EXAMPLE_COUNT",2);
define("QUESTION_TYPE_2_EXAMPLE_COUNT",3);
define("QUESTION_TYPE_3_EXAMPLE_COUNT",4);
define("QUESTION_TYPE_4_EXAMPLE_COUNT",5);
define("QUESTION_TYPE_5_EXAMPLE_COUNT",1);
define("QUESTION_TYPE_6_EXAMPLE_COUNT",2);
define("QUESTION_TYPE_7_EXAMPLE_COUNT",3);
define("QUESTION_TYPE_8_EXAMPLE_COUNT",4);
define("QUESTION_TYPE_9_EXAMPLE_COUNT",5);
define("QUESTION_TYPE_10_EXAMPLE_COUNT",1);
define("QUESTION_TYPE_11_EXAMPLE_COUNT",2);//2지선다
define("QUESTION_TYPE_20_EXAMPLE_COUNT",1);

/**
 * 테스트 상수
 */
define("TEST_MAKE_COMPLETE",0);//테스트 만들기 완료
define("TEST_APPLICAION_WATING",1);//테스트 신청 대기중
define("TEST_PROGRESS",2);//테스트 진행중
define("TEST_FINISH",4);//테스트 종료
define("TEST_USER_FIRST",0);//테스트 유저 생성
define("TEST_USER_APPLICAION_COMPLETE",1);//테스트 신청 완료
define("TEST_USER_TEST_PROGESS",2);//테스트 진행중
define("TEST_USER_TEST_FINISH",3);//테스트 완료

/**
 * 정답 결과 상수
 */
define("ANSWER_RESULT_FLG_INCORRECT",0);
define("ANSWER_RESULT_FLG_CORRECT",1);
define("ANSWER_RESULT_FLG_MARKING",2);
define("ANSWER_RESULT_FLG_MARKED",3);

define("SMART_OMR_TEACHER_SEQ",38314443);//smart_omr teacher
define("SMART_OMR_TEACHER_LEVEL",200);//사용불가
?>