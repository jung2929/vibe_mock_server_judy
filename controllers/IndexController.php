<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        /*
         * API No. 0
         * API Name : 테스트 API
         * 마지막 수정 날짜 : 19.04.29
         */

        case "getUsers":
            http_response_code(200);
            $res->result = getUsers();
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원정보조회성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
         * API No. 0
         * API Name : 테스트 Path Variable API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "getUserDetail":
            http_response_code(200);

            $customerEmail=$vars["customerEmail"];

            if(!(isvaildno($customerEmail)))
            {

                $res->isSuccess = FALSE;
                $res->code = 200;
                $res->message = "유효하지 않은 회원Email입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

            $res->result = getUserDetail($vars["customerEmail"]);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "개인회원정보 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


   case "getShopMainTargetList":
       http_response_code(200);

     //  $vars;
       $keyword=$_GET['mainTarget'];

       if(!(isvailMainTarget($keyword)))
       {

           $res->isSuccess = FALSE;
           $res->code = 200;
           $res->message = "유효하지 않은 main Target 쇼핑몰 입니다";
           echo json_encode($res, JSON_NUMERIC_CHECK);
           break;

       }

       $res->result = getShopMainTargetList($keyword);
       $res->isSuccess = TRUE;
       $res->code = 100;
       $res->message = "Main Target별 쇼핑몰 목록조회 성공";
       echo json_encode($res, JSON_NUMERIC_CHECK);
       break;


        case "getshopIist":
            http_response_code(200);
            $hashTag= $_GET['hashTag'];
            if($_GET['hashTag'])
            {
            if(!(isvailHashTag($hashTag)))
            {
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "유효하지 않은 hashTag입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
            #$res=res();

            $res->result['hashTag']='#'.$_GET['hashTag'];
            $res->result['shop']=getShopHashtag($hashTag);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "쇼핑몰 해시테그별 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


            }
            else{

                $res->result = getshopIist();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "쇼핑몰목록조회성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }





             case "deleteShopList":
                 http_response_code(200);
                // $vars="";
                 $shopId= $vars["shopId"];
                 if(!(isvaildShopId($shopId)))
                 {
                     $res->isSuccess = FALSE;
                     $res->code = 210;
                     $res->message = "존재하지 않는 쇼핑몰 입니다.";
                     echo json_encode($res, JSON_NUMERIC_CHECK);
                     break;


                 }

                 $res->result = deleteShopList($shopId);
                 $res->result = "완료";
                 $res->result =$req->shopId;
                 $res->isSuccess = TRUE;
                 $res->code = 100;
                 $res->message = "쇼핑몰목록삭제성공";
                 echo json_encode($res, JSON_NUMERIC_CHECK);
                 break;


               case "getUsersRecommendation":
                   http_response_code(200);
                   $getuser=$vars['getuser'];
                   $res->result = getUsersRecommendation($getuser);
                   $res->isSuccess = TRUE;
                   $res->code = 100;
                   $res->message = "추천리스트조회성공";
                   echo json_encode($res, JSON_NUMERIC_CHECK);
                   break;
/*
        case "getShopHashtag":
            http_response_code(200);
            $hashTag= $_GET['hashTag'];
            if(!(isvailHashTag($hashTag)))
            {

                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "유효하지 않은 hashTag입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }

            $res->hashTag=$_GET['hashTag'];
            $res->result = getShopHashtag($hashTag);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "쇼핑몰 해시테그별 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

*/
        case "getPopularityShopList":
            $popularity=$_GET['popularity'];
            if(!(isvaildPopularity($popularity)))
            {
                $res->isSuccess = FALSE;
                $res->code = 280;
                $res->message = "유효하지 않은 인기순위 입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;


            }
            http_response_code(200);
            $res->result = getPopularityShopList($popularity);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "쇼핑몰 인기도순 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



        case "getShopSubTargetList":
            $subTarget=$_GET['subTarget'];
            if(!(isvaildSubTarget($subTarget)))
            {
                $res->isSuccess = FALSE;
                $res->code = 280;
                $res->message = "존재하지 않는 쇼핑몰 subTarget입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;


            }
            http_response_code(200);
            $res->result = getShopSubTargetList($subTarget);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "쇼핑몰 subTarget 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



         case "getShopDetail":
             http_response_code(200);
             $shopId= $vars["shopId"];
             if(!(isvaildShopId($shopId)))
             {
                 $res->isSuccess = FALSE;
                 $res->code = 210;
                 $res->message = "존재하지 않는 쇼핑몰 입니다.";
                 echo json_encode($res, JSON_NUMERIC_CHECK);
                 break;


             }
             $res->result = getShopDetail($shopId);
             $res->isSuccess = TRUE;
             $res->code = 100;
             $res->message = "쇼핑몰 조회 성공";
             echo json_encode($res, JSON_NUMERIC_CHECK);
             break;



                case "getShopClothesList":
                    http_response_code(200);


                    $res->result["today"]=getShopClothesUpdate($vars["shopId"]);
                    $res->result["shop"] = getShopClothesList($vars["shopId"]);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "쇼핑몰 옷 종류 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;

   case "deleteUser":
       http_response_code(200);
       // $vars="";

       $res->result = deleteUser($vars["customerId"]);
       //$res->result = "완료";
       //  $res->result =$req->shopId;
       $res->isSuccess = TRUE;
       $res->code = 100;
       $res->message = "회원정보삭제완료";
       echo json_encode($res, JSON_NUMERIC_CHECK);
       break;

/*
            case "createUser":
                http_response_code(200);
                $res->result =  createUser($req->name);
                $res->result =  "user생성성공";
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "user생성성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
*/
/*
        case "createUser":
            http_response_code(200);
            $res->result =  createUser($req->customerId,$req->customerEmail,$req->contactInformation,$req->id);
            $res->result =  "user생성성공";
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "user생성성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

*/
               case "createShop":
                   http_response_code(200);
                   $res->result =  createShop($req->shopName,$req->shopImageUrl,$req->mainTarget,$req->subTarget);
                 //  $res->result =  createShop($req->shopName,$req->shopImageUrl);
                   $res->result = $req->shopName;//가게이름 반환
                   $res->isSuccess = TRUE;
                   $res->code = 100;
                   $res->message = "shop생성성공";
                   echo json_encode($res, JSON_NUMERIC_CHECK);
                   break;

        case "registerClothes":
            http_response_code(200);
            $shopId=$req->shopId;
            if(!isvaildShopId($shopId))
            {
                $res->isSuccess = FALSE;
                $res->code = 210;
                $res->message = "존재하지 않는 쇼핑몰 입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;

            }
            $res->result =  registerClothes($req->shopId,$req->clothesPrice,
                $req->clothesType,$req->clothesName,$req->clothesImageUrl,$req->sale,
                $req->freeShipping,$req->shopName,$req->isHeart,
            $req->isNew,$req->isHandMade);
            $res->result = $req->shopName;//가게이름 반환
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "옷 추가 완료";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;




                 case "getShopListAdvertise":
                     http_response_code(200);
                     $res->result =  getShopListAdvertise();
                     $res->isSuccess = TRUE;
                     $res->code = 100;
                     $res->message = "광고 금액순 쇼핑몰 옷 정보 조회 성공";
                     echo json_encode($res, JSON_NUMERIC_CHECK);
                     break;


 case "putUser":
     http_response_code(200);
     $res->result =  putUser($req->customerId,$req->customerEmail,$req->contactInformation,$req->id,$req->pw);
     //  $res->result =  createShop($req->shopName,$req->shopImageUrl);
    // $res->result = $req->shopName;//가게이름 반환
     $res->isSuccess = TRUE;
     $res->code = 100;
     $res->message = "shop생성성공";
     echo json_encode($res, JSON_NUMERIC_CHECK);
     break;

        /*
         * API No. 0
         * API Name : 테스트 Body & Insert API
         * 마지막 수정 날짜 : 19.04.29
         */
        case "testPost":
            http_response_code(200);
            $res->result = testPost($req->name);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "테스트 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
