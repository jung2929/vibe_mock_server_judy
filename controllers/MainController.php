<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        /*
         * API No. 0
         * API Name : JWT 유효성 검사 테스트 API
         * 마지막 수정 날짜 : 19.04.25
         */
        case "getLoginUser":
            // jwt 유효성 검사

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            http_response_code(200);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $getuser=$data->customerEmail;
            $res->result=getloginuser($getuser);
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원정보 조회 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "loginDeleteUser":
            // jwt 유효성 검사

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            http_response_code(200);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $getuser=$data->customerEmail;
            $res->result=loginDeleteUser($getuser);
            $res->result=$data->customerEmail;
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원 탈퇴 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getUserKeepItem":
            // jwt 유효성 검사

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            http_response_code(200);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $getuser=$data->customerEmail;
            $res->result=getUserKeepItem($getuser);
          #  $res->result=$data->customerEmail;
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "개인 회원 찜 목록 조회 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getMyInformation":
            // jwt 유효성 검사

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            http_response_code(200);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $getuser=$data->customerEmail;
            $res->result=getMyInformation($getuser);
            #  $res->result=$data->customerEmail;
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "개인 회원 정보 조회 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        case "getMyKeepItem":
            // jwt 유효성 검사

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            http_response_code(200);
            $getuser=$req->customerId;
            $clothesId=$req->clothesId;

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }
            if(isvaildKeepItem($clothesId,$getuser))
            {
                if(isvaildKeepItemYes($clothesId,$getuser))//Y 즉 찜 되어있음

                {
                    updateKeepItemNO($clothesId,$getuser);
                    $res->message ="N변경성공";


                }
                else if(!(isvaildKeepItemYes($clothesId,$getuser)))
                {

                    updateKeepItemYes($clothesId,$getuser);
                    $res->message ="Y변경성공";
                }


            }

            else if(!(isvaildKeepItem($clothesId,$getuser)))
            {
                insertKeepItem($clothesId,$getuser);
                $res->message ="찜목록추가완료";

            }


            $res->confirm=(isvaildKeepItem($clothesId,$getuser));
            #  $res->result=$data->customerEmail;
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
           // $res->message = "찜 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



            case "getClothesDetail":
            // jwt 유효성 검사

            echo "tdsadasd";
            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];
            http_response_code(200);
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $costomerId=$data->customerId;
            $clothesId=$req->clothesId;

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 330;
                $res->message = "회원정보 조회 실패";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

           if(!(isValidClothesNo($clothesId)))
           {
               $res->isSuccess = FALSE;
               $res->code = 290;
               $res->message = "존재하지 않는 옷인덱스입니다.";
               echo json_encode($res, JSON_NUMERIC_CHECK);
               addErrorLogs($errorLogs, $res, $req);
               return;


           }

            $res->result=insertHistory($costomerId,$clothesId);

            $res->result=$costomerId;
            #  $res->result=$data->customerEmail;
            #$res->result=$data->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "히스 토리 추가 성공";

            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "createUser":
            http_response_code(200);

            if (isValidUserCustomerEmail($req->customerEmail))
            {
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "이미 있는 회원  아이디 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }
            if(strpos($req->customerEmail,'@')&&(strpos($req->customerEmail,'.com'))&&(strpos($req->contactInformation,'-')))
            {
                $jwt = getUserToken($req->customerEmail, $req->pw,$req->contactInformation,$req->customerId ,JWT_SECRET_KEY);
                $res->result->user=  createUser($req->customerEmail,$req->contactInformation,$req->pw,$req->customerId);
                $res->result->jwt = $jwt;

                $res->result->user = $req->customerEmail;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "user생성성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;


            }
            else if(!(strpos($req->customerEmail,'@')))
            {
                $res->isSuccess = FALSE;
                $res->code = 261;
                $res->message = "틀린형식의 회원  아이디 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;


            }
            else if(!(strpos($req->customerEmail,'.com')))
            {

                $res->isSuccess = FALSE;
                $res->code = 261;
                $res->message = "틀린형식의 회원  아이디 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;

            }
            else if(!(strpos($req->contactInformation,'-')))
            {
                $res->isSuccess = FALSE;
                $res->code = 262;
                $res->message = "틀린형식의 휴대폰 번호 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;


            }







      case "patchChangeContactInformation":
          // jwt 유효성 검사

          $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

          if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
              $res->isSuccess = FALSE;
              $res->code = 330;
              $res->message = "회원정보 조회 실패";
              echo json_encode($res, JSON_NUMERIC_CHECK);
              addErrorLogs($errorLogs, $res, $req);
              return;
          }


          http_response_code(200);
          $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
          $getUser=$data->customerEmail;
          $contactInformation=$req->contactInformation;
        if(!(strpos($req->contactInformation,'-')))
          {
              $res->isSuccess = FALSE;
              $res->code = 262;
              $res->message = "틀린형식의 휴대폰 번호 입니다";
              echo json_encode($res, JSON_NUMERIC_CHECK);
              return;


          }

          $res->code = 100;
          $res->message = "회원 휴대폰 번호 변경성공";
          $res->result=patchChangeContactInformation($contactInformation,$getUser);
          $res->result->contactInformation=$contactInformation;
          $res->isSuccess = TRUE;

          echo json_encode($res, JSON_NUMERIC_CHECK);
          break;










             case "patchChangeCustomerName":
                 // jwt 유효성 검사

                 $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

                 if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                     $res->isSuccess = FALSE;
                     $res->code = 330;
                     $res->message = "회원정보 조회 실패";
                     echo json_encode($res, JSON_NUMERIC_CHECK);
                     addErrorLogs($errorLogs, $res, $req);
                     return;
                 }


                 http_response_code(200);
                 $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
                 $getUser=$data->customerEmail;
                 $customerName=$req->customerName;
                 $res->code = 100;
                 $res->message = "회원 이름 변경성공";
                 $res->result=patchChangeCustomerName($customerName,$getUser);
                 $res->result->customerName=$customerName;
                 $res->isSuccess = TRUE;

                 echo json_encode($res, JSON_NUMERIC_CHECK);
                 break;

        /*
         * API No. 1
         * API Name : JWT 생성 테스트 API (로그인)
         * 마지막 수정 날짜 : 19.04.25
         */
        case "createUserJwt":
            // jwt 유효성 검사
            http_response_code(200);
            // select  customerId,age,gender,contactInformation,customerEmail,interestedClothesType from 회원정보테이블 where customerEmail=? and pw=?;
            if (!isValidUser($req->customerEmail, $req->pw))
            {
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "유효하지 않은 회원  아이디 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }

            //페이로드에 맞게 다시 설정 요함
            $jwt = getJWToken($req->customerEmail, $req->pw,$req->userId ,JWT_SECRET_KEY);
            $res->result->jwt = $jwt;
            $res->result->customerEmail=$req->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원 로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        //
//            * API No. 2
//        * API Name : 회원용 데이터 리스트 조회 API
//        * 마지막 수정 날짜 : 19.04.25
//        */
        case "getDataList":
            // jwt 유효성 검사
            http_response_code(200);

            $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

            if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                $res->isSuccess = FALSE;
                $res->code = 201;
                $res->message = "유효하지 않은 토큰입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);

                addErrorLogs($errorLogs, $res, $req);
                return;
            }


            //페이로드에 맞게 다시 설정 요함
            $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
            $res->result = $data;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        case "getHistoryUser":
            // jwt 유효성 검사
            http_response_code(200);

            if (!isValidUser($req->customerEmail, $req->pw))
            {
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "유효하지 않은 회원  아이디 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                return;
            }


            //페이로드에 맞게 다시 설정 요함
            $jwt = getJWToken($req->customerEmail, $req->pw,$req->userId ,JWT_SECRET_KEY);
            $res->result->jwt = $jwt;
            $res->result->customerEmail=$req->customerEmail;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원 로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

////////////////////////////////새로 만들 회원용 api 획득//////////////////////////
///
///
///
///
///    case "getUsers":
//            http_response_code(200);
//            $res->result = getUsers();
//            $res->isSuccess = TRUE;
//            $res->code = 100;
//            $res->message = "회원정보조회성공";
//            echo json_encode($res, JSON_NUMERIC_CHECK);
//            break;
        /*
            case "getDataList":
                // jwt 유효성 검사
                http_response_code(200);

                $jwt = $_SERVER["HTTP_X_ACCESS_TOKEN"];

                if (!isValidHeader($jwt, JWT_SECRET_KEY)) {
                    $res->isSuccess = FALSE;
                    $res->code = 201;
                    $res->message = "유효하지 않은 토큰입니다";
                    echo json_encode($res, JSON_NUMERIC_CHECK);

                    addErrorLogs($errorLogs, $res, $req);
                    return;
                }


                //페이로드에 맞게 다시 설정 요함
                $data = getDataByJWToken($jwt, JWT_SECRET_KEY);
                $res->result=$data;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "테스트 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
    */

        }
    }
catch
    (\Exception $e) {
        return getSQLErrorException($errorLogs, $e, $req);
    }
