<?php
//check Auth
define("AUTH_TRUE", 1);
define("AUTH_MEMBER_DUPLICATION", 2);
define("AUTH_TOKEN_EMPTY", 3);
define("AUTH_MEMBER_EMPTY", 4);
define("AUTH_EMAIL_FAIL", 5);
define("AUTH_FAIL", 6);
define("AUTH_PASSWORD_FALSE", 7);
define("AUTH_CPHONE_FALSE", 8);

//REPORT STATUS
define("REPORT_MAKE_COMPLETE",0);//과제 만들기 완료
define("REPORT_PROGRESS",1);//과제 진행중
define("REPORT_FINISH",2);//과제 종료
//REPORT user ststus flg
define("REPORT_USER_FIRST",0);//과제 유저 생성
define("REPORT_USER_TEST_PROGESS",1);//과제 진행중
define("REPORT_USER_TEST_FINISH",2);//과제 완료

//TEST STATUS
define("TEST_MAKE_COMPLETE",0);//테스트 만들기 완료
define("TEST_APPLICAION_WATING",1);//테스트 신청 대기중
define("TEST_PROGRESS",2);//테스트 진행중
//define("TEST_STOP",3);//테스트 종료 (아직 사용안함)
define("TEST_FINISH",4);//테스트 종료

//test user ststus flg
define("TEST_USER_FIRST",0);//테스트 유저 생성
define("TEST_USER_APPLICAION_COMPLETE",1);//테스트 신청 완료
define("TEST_USER_TEST_PROGESS",2);//테스트 진행중
define("TEST_USER_TEST_FINISH",3);//테스트 완료

define("LOGIN_AUTH_FAILE",0);
define("LOGIN_AUTH_SUCCESS",1);
define("SESSION_STORAGE_FALSE",2);

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

define("ANSWER_RESULT_FLG_INCORRECT",0);
define("ANSWER_RESULT_FLG_CORRECT",1);
define("ANSWER_RESULT_FLG_MARKING",2);
define("ANSWER_RESULT_FLG_MARKED",3);

define("FILE_SIZE_OVER",1);
define("FILE_EXTENSION_NOT_ARROWED",2);

// publish state : 0-생성, 1-시작대기, 2-진행중, 3-정지, 4-종료

//RECORD STATUS
define("RECORD_ALL_RIGHT",1);//만점
define("RECORD_TEST_COMPLETE",2);//테트스 완료
define("RECORD_TEST_PROGRESS",3);//테스트 진행중
define("RECORD_TEST_NOT_JOIN",4);//미참여

//WITHDRAW REASON
define("WITHDRAW_REASON_TYPE_1",1);//회원탈퇴

//NEW_COMPARE_TIME
define("COMMON_NEW_BOARD_COMPARE_TIME",date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")." -1 day")));

//TICKET
define("TICKET_PRICE",5000);
//TICKET TYPE
define("TICKET_VOUCHER",1);//MONTH 1
//VOUCHER

//ticket status
define("TICKET_USE_ABLE",1);//사용간한 티켓
define("TICKET_EMPTY",2);//미적용 
define("TICKET_STOP",3);//정지
define("TICKET_PERIOD_OVER",4);//기간이 만료
define("TICKET_DISABLED",5);//사용불가

define("NOT_MY_TEST",1000);//사용불가
define("NOT_MY_REPORT",2000);//사용불가

//applycation division
/* GLOBAL */
$GLOBAL_APP_NAME;

define("SMART_OMR_TEACHER_SEQ",38314443);//smart_omr teacher


/*
//CHECK TRIAL MEMBER
define("MEMBER_POLICY_COMPLETE",1);//trail check complete
define("MEMBER_POLICY_FAIL",2);//t	rail check complete
define("PERIOD_OVER",3);//trail period over
define("TEST_COUNT_OVER",4);//테스트 제한 갯수 초과 10개
define("REPORT_COUNT_OVER",5);//레포트 제한 갯수 초과 10개
define("MAX_UPLOAD_FILE_SIZE_OVER",6);//레포트 제한 갯수 초과 10개
define("MAX_STUDENT_COUNT_OVER",7);//레포트 제한 갯수 초과 10개

define("PERIOD_OVER_MSG",'기간이 만료 되었습니다');//trail period over
define("TEST_COUNT_OVER_MSG",'');//테스트 제한 갯수 초과 10개
define("REPORT_COUNT_OVER_MSG",'');//레포트 제한 갯수 초과 10개
define("MAX_UPLOAD_FILE_SIZE_OVER_MSG",'');//레포트 제한 갯수 초과 10개

//SET MEMBER GRADE POLICY
define("TRIAL_MEMBER_POLICY",serialize(array('member_grade'=>0,'period'=>30,'max_student_count'=>30,'max_upload_size'=>104857600)));//TRIAL MEMBER 30일 100MB 
define("TUTOR_MEMBER_POLICY",serialize(array('member_grade'=>2,'period'=>0,'max_student_count'=>20,'max_upload_size'=>524288000)));//MEMBER 30일 500MB
define("PROFESSIONAL_MEMBER_POLICY",serialize(array('member_grade'=>3,'period'=>0,'max_student_count'=>50,'max_upload_size'=>1073741824)));//MEMBER 30일 500MB

define("STUDENT_MEMBER_POLICY",serialize(array('member_grade'=>2000,'period'=>0,'max_student_count'=>0,'max_upload_size'=>0)));//MEMBER 30일 500MB
define("TEACHER_MEMBER_POLICY",serialize(array('member_grade'=>1000,'period'=>0,'max_student_count'=>0,'max_upload_size'=>0)));//MEMBER 30일 500MB
*/
define("SMART_OMR_TEACHER_LEVEL",200);//사용불가
?>