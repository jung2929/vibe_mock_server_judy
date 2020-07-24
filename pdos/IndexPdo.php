<?php

//READ
function getUsers()
{
    $pdo = pdoSqlConnect();
    $query = "select  customerId,age,gender,contactInformation,customerEmail,gender,interestedClothesType from 회원정보테이블;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//READ



function getUserDetail($customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "select  customerId,age,gender,contactInformation,customerEmail,interestedClothesType from 회원정보테이블 where customerEmail=?;";

    $st = $pdo->prepare($query);
    $st->execute([$customerEmail]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getUserId($customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "select  customerId from 회원정보테이블 where customerEmail=?;";

    $st = $pdo->prepare($query);
    $st->execute([$customerEmail]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res);
}

function getloginuser($getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select  customerId,age,gender,contactInformation,customerEmail,interestedClothesType from 회원정보테이블 where customerEmail=?;";

    $st = $pdo->prepare($query);
    $st->execute([$getuser]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getShopMainTargetList($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
       group_concat(해시테그테이블.hashtagName) as hashtagName,
       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
       group_concat(distinct 쇼핑몰목록테이블.shopId) as shopId
from 쇼핑몰목록테이블,해시테그테이블
where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
        쇼핑몰목록테이블.mainTarget like concat('%',?,'%')
group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
order by 쇼핑몰목록테이블.popularity desc;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function isvaildno($customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from 회원정보테이블 where customerEmail=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$customerEmail]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isvailMainTarget($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where mainTarget=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}


function isvailCustomerId($customerId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS (select * from 회원정보테이블  where 회원정보테이블.customerId=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$customerId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isvaildSubTarget($subTarget)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select *from 쇼핑몰목록테이블 where 쇼핑몰목록테이블.subTarget=?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$subTarget]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}


function isvailHashTag($hashTag)
{
    $pdo = pdoSqlConnect();
    $query ="select EXISTS(select * from 해시테그테이블 where hashtagName=?)as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$hashTag]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}




function getshopIist()
{
    $pdo = pdoSqlConnect();
    $query = "select shopName,
       shopImageUrl,
       group_concat(해시테그테이블.hashtagName) as hashtagName,
       group_concat(distinct  concat(mainTarget,'대',subTarget,'대')) as target,
       쇼핑몰목록테이블.shopId as shopId
from 쇼핑몰목록테이블,해시테그테이블
where 쇼핑몰목록테이블.shopId=해시테그테이블.shopId
group by 쇼핑몰목록테이블.shopName;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function deleteShopList($shopId)
{
    $pdo = pdoSqlConnect();
    $query = "delete from 쇼핑몰목록테이블
            where 쇼핑몰목록테이블.shopId=?;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$shopId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
   // $res[1]='';
    return $res;
}

function deleteUser($customerId)
{
    $pdo = pdoSqlConnect();
    $query = "delete from 회원정보테이블
where 회원정보테이블.customerId=?;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    //$res = $st->fetchAll();

    $st = null;
    $pdo = null;
    // $res[1]='';
    // return $res[1];
}

///////////////jwt 사용 회원 delete 문
function loginDeleteUser($customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "delete from 회원정보테이블
where 회원정보테이블.customerEmail=?;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    //$res = $st->fetchAll();

    $st = null;
    $pdo = null;
    // $res[1]='';
    // return $res[1];
}
//////jwt 사용 keep -item 목록



function getUserKeepItem($getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select #group_concat(제품마스터테이블.sale),
       group_concat(distinct  쇼핑몰목록테이블.shopName) as shopName,
       group_concat(distinct a.clothesId) as clothesId,
       a.customerId as customerId,
      # group_concat(a.customerEmail),
       #group_concat(a.shopName)
      GROUP_CONCAT( distinct 제품마스터테이블.shopId) as shopId,
       GROUP_CONCAT( 제품마스터테이블.clothesName) as clothesName,
       GROUP_CONCAT( 제품마스터테이블.clothesImageUrl) as clothesImageUrl ,
       GROUP_CONCAT( concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
         GROUP_CONCAT( ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
        GROUP_CONCAT( 제품마스터테이블.FreeShipping)as FreeShipping,
      GROUP_CONCAT( 제품마스터테이블.isNew)as isNew,
        GROUP_CONCAT( 제품마스터테이블.isHandMade)as isHandMade

from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
       회원정보테이블.customerId as customerId,
      회원정보테이블.customerEmail as customerEmail
       #찜목록테이블.shopId
       #group_concat(제품마스터테이블.sale)

from 찜목록테이블,회원정보테이블
where 찜목록테이블.customerId=회원정보테이블.customerId)as a
#group by 찜목록테이블.clothesId) as a
where 제품마스터테이블.clothesId =a.clothesId and
      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
      and a.customerEmail like concat('%',?,'%')
group by a.customerId ;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res[0];
}

function getUsersRecommendation($getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select #group_concat(제품마스터테이블.sale),
       group_concat(쇼핑몰목록테이블.shopName) as shopName,
       group_concat(a.clothesId) as clothesId,
       a.customerId as customerId,
      # group_concat(a.customerEmail),
       #group_concat(a.shopName)
      GROUP_CONCAT(제품마스터테이블.shopId) as shopId,
       제품마스터테이블.clothesName,
       제품마스터테이블.clothesImageUrl,
       GROUP_CONCAT(concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
         GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
        GROUP_CONCAT(제품마스터테이블.FreeShipping)as FreeShipping
from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
       회원정보테이블.customerId as customerId,
      회원정보테이블.customerEmail as customerEmail
       #찜목록테이블.shopId
       #group_concat(제품마스터테이블.sale)

from 찜목록테이블,회원정보테이블
where 찜목록테이블.customerId=회원정보테이블.customerId)as a
#group by 찜목록테이블.clothesId) as a
where 제품마스터테이블.clothesId =a.clothesId and
      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
      and a.customerEmail like concat('%',?,'%')
group by a.customerId ;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res;
}

function getMyKeepItem($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select #group_concat(제품마스터테이블.sale),
       group_concat(쇼핑몰목록테이블.shopName) as shopName,
       group_concat(a.clothesId) as clothesId,
       a.customerId as customerId,
      # group_concat(a.customerEmail),
       #group_concat(a.shopName)
      GROUP_CONCAT(제품마스터테이블.shopId) as shopId,
       제품마스터테이블.clothesName,
       제품마스터테이블.clothesImageUrl,
       GROUP_CONCAT(concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
         GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
        GROUP_CONCAT(제품마스터테이블.FreeShipping)as FreeShipping
from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
       회원정보테이블.customerId as customerId,
      회원정보테이블.customerEmail as customerEmail
       #찜목록테이블.shopId
       #group_concat(제품마스터테이블.sale)

from 찜목록테이블,회원정보테이블
where 찜목록테이블.customerId=회원정보테이블.customerId)as a
#group by 찜목록테이블.clothesId) as a
where 제품마스터테이블.clothesId =a.clothesId and
      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
      and a.customerEmail like concat('%',?,'%')
group by a.customerId ;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$clothesId,$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res;
}

function isvaildKeepItem($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS( select *
from (select *
from 찜목록테이블
where 찜목록테이블.clothesId=?) as a
where a.customerId=?) as exist";

    $st = $pdo->prepare($query);
    $st->execute([$clothesId,$getuser]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isvaildKeepItemYes($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select 찜목록테이블.isHeart
from 찜목록테이블
where 찜목록테이블.clothesId =? and 찜목록테이블.customerId =? and 찜목록테이블.isHeart like ('%Y%'))as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$clothesId,$getuser]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}





function updateKeepItemNO($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE 찜목록테이블
SET 찜목록테이블.isHeart='N'
where  찜목록테이블.clothesId=? and 찜목록테이블.customerId =?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$clothesId,$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
   // $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
 //   return $res;
}

function updateKeepItemYes($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE 찜목록테이블
SET 찜목록테이블.isHeart='Y'
where  찜목록테이블.clothesId=? and 찜목록테이블.customerId =?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$clothesId,$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
   // $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
 //   return $res;
}


function insertKeepItem($clothesId,$getuser)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO 찜목록테이블(clothesId,customerId) VALUES (?,?);";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$clothesId,$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
   // $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    //return $res;
}


function insertHistory($customerId,$clothesId)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO 히스토리테이블(customerId,clothesId) VALUES (?,?);";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerId,$clothesId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    // $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    //return $res;
}


function isvaildHistory($clothesId,$costomerId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS( select *
    from (select *
        from 히스토리테이블
where 히스토리테이블.clothesId=?) as a
where a.customerId=?) as exist";

    $st = $pdo->prepare($query);
    $st->execute([$clothesId,$costomerId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}
function updateHistoryNo($clothesId,$costomerId)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE 히스토리테이블
SET 히스토리테이블.isDleted='Y'
where  히스토리테이블.clothesId=? and 히스토리테이블.costomerId =?";

    $st = $pdo->prepare($query);
    $st->execute([$clothesId,$costomerId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}


function getPopularityShopList($popularity)
{
    $pdo = pdoSqlConnect();
    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
       group_concat(해시테그테이블.hashtagName) as hashtagName,
       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
       쇼핑몰목록테이블.shopId as shopId
from 쇼핑몰목록테이블,해시테그테이블
where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and 쇼핑몰목록테이블.popularity =?
group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
order by 쇼핑몰목록테이블.popularity desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$popularity]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res;
}

function isvaildPopularity($popularity)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where 쇼핑몰목록테이블.popularity =?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$popularity]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isvaildShopId($shopId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where shopId=?)as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$shopId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}




function getShopDetail($shopId)
{
    $pdo = pdoSqlConnect();
    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
       group_concat(해시테그테이블.hashtagName) as hashtagName,
       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
       쇼핑몰목록테이블.shopId as shopId
from 쇼핑몰목록테이블,해시테그테이블
where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
        해시테그테이블.hashtagName not like '%#%'
        and 쇼핑몰목록테이블.shopId=?
group by  쇼핑몰목록테이블.shopName;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$shopId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res[0];
}

function getShopClothesList($shopId)
{
    $pdo = pdoSqlConnect();
    $query = "select group_concat(distinct 제품마스터테이블.clothesPrice) as clothesPrice,
       group_concat(distinct 제품마스터테이블.clothesId) as clothesId,
       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
       group_concat(제품마스터테이블.clothesImageUrl) as cothesImageUrl,
     #  (DATE_FORMAT(제품마스터테이블.updatedAt,'금주의 신상품 - %m.%d %H:%i')) as clothesUpdateAt,
      group_concat(distinct ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100), 0) ) as 최종가격,
      제품마스터테이블.isNew as isNew,
      제품마스터테이블.isHandMade as isHandMakd
      
      
    from 쇼핑몰목록테이블,제품마스터테이블,해시테그테이블
    where 쇼핑몰목록테이블.shopId =제품마스터테이블.shopId and 쇼핑몰목록테이블.shopId=? and
          제품마스터테이블.updatedAt >date_add(now(),interval -7 day)
    group by 쇼핑몰목록테이블.shopName,제품마스터테이블.updatedAt
    order by 제품마스터테이블.updatedAt DESC;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$shopId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res;
}
function getShopClothesUpdate($shopId)
{
  $pdo = pdoSqlConnect();
    $query = "select (DATE_FORMAT(제품마스터테이블.updatedAt,'금주의 신상품 - %m.%d %H:%i')) as clothesUpdateAt
    from 제품마스터테이블
    where 제품마스터테이블.shopId=?
    order by 제품마스터테이블.updatedAt DESC limit 1;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$shopId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return [$res][0];
}


function createUser($customerEmail,$contactInformation,$pw,$customerId)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO 회원정보테이블 (customerEmail,contactInformation,pw,customerId) VALUES (?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$customerEmail,$contactInformation,$pw,$customerId]);
  //  $st->setFetchMode(PDO::FETCH_ASSOC);
 //   $res = $st->fetchAll();
    $st = null;
    $pdo = null;

   // return $res;
}



function registerClothes($shopId,$clothesPrice,$clothesType,$clothesName,$clothesImageUrl,$sale,$freeShipping,$shopName,$isHeart,$isNew,$isHandMade)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO 제품마스터테이블 (shopId,clothesPrice,clothesType,clothesName,clothesImageUrl,sale,
freeShipping,shopName,isHeart,isNew,isHandMade) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
    $st = $pdo->prepare($query);
    $st->execute([$shopId,$clothesPrice,$clothesType,$clothesName,$clothesImageUrl,$sale,$freeShipping,$shopName,$isHeart,$isNew,$isHandMade]);
    //  $st->setFetchMode(PDO::FETCH_ASSOC);
    //   $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    // return $res;
}


function patchChangeContactinformation($contactInformation,$customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE 회원정보테이블

SET 회원정보테이블.contactInformation = ?

WHERE customerEmail = ?;";
    $st = $pdo->prepare($query);
    $st->execute([$contactInformation,$customerEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    #$res = $st->fetchAll();
    $st = null;
    $pdo = null;

    #return $res;
}

function patchChangeCustomerName($patchChangeCustomerName,$customerEmail)
{
    $pdo = pdoSqlConnect();
    $query = "UPDATE 회원정보테이블
SET 회원정보테이블.customerName=?
where 회원정보테이블.customerEmail=?";
    $st = $pdo->prepare($query);
    $st->execute([$patchChangeCustomerName,$customerEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    #$res = $st->fetchAll();
    $st = null;
    $pdo = null;

    #return $res;
}


function getShopSubTargetList($subTarget)
{
    $pdo = pdoSqlConnect();
    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
       group_concat(해시테그테이블.hashtagName) as hashtagName,
       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
       group_concat(distinct 쇼핑몰목록테이블.shopId) as shopId
from 쇼핑몰목록테이블,해시테그테이블
where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
        쇼핑몰목록테이블.subTarget like concat('%',?,'%')
group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
order by 쇼핑몰목록테이블.popularity desc;";

    $st = $pdo->prepare($query);
    $st->execute([$subTarget]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function createShop($shopName,$shopImageUrl,$mainTarget,$subTarget)
{
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO 쇼핑몰목록테이블 (shopName,shopImageUrl,mainTarget,subTarget) VALUES (?,?,?,?);";

    $st = $pdo->prepare($query);
    $st->execute([$shopName,$shopImageUrl,$mainTarget,$subTarget]);
    //  $st->setFetchMode(PDO::FETCH_ASSOC);
    //   $res = $st->fetchAll();
    $st = null;
    $pdo = null;

    // return $res;
}



function getShopListAdvertise()
{
    $pdo = pdoSqlConnect();
    $query = "SELECT 제품마스터테이블.shopName,
        GROUP_CONCAT(제품마스터테이블.clothesId) as clothesId,
        GROUP_CONCAT(제품마스터테이블.clothesName)as colthesName,
        GROUP_CONCAT(제품마스터테이블.clothesImageUrl)as colthesUrl,
        GROUP_CONCAT(제품마스터테이블.sale)as sale,
        GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
        GROUP_CONCAT(제품마스터테이블.FreeShipping)as freeShipping,
        제품마스터테이블.shopId as shopId
FROM 제품마스터테이블 ,광고테이블
where 제품마스터테이블.shopName=광고테이블.shopName
GROUP BY 제품마스터테이블.shopName;";

    $st = $pdo->prepare($query);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;

     return $res;
}

function getShopHashtag($hashTag)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT
         #a.popularity,
        a.shopName  as shopName,
        GROUP_CONCAT(distinct a.shopId) as shopId,
        GROUP_CONCAT(a.shopImageUrl) as shopImageUrl
       #a.hashtagId,
       #a.shopName
FROM (select
       해시테그테이블.hashtagName,
       해시테그테이블.hashtagId,
       쇼핑몰목록테이블.shopName,
       쇼핑몰목록테이블.shopId as shop,
       해시테그테이블.shopId,
       쇼핑몰목록테이블.popularity,
       쇼핑몰목록테이블.shopImageUrl
       from 해시테그테이블,쇼핑몰목록테이블
    where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
        해시테그테이블.hashtagName like  concat('%',?,'%')
        and 해시테그테이블.hashtagName not like '%심플베이직%'
        and 해시테그테이블.hashtagName not like '%러블리%'
        and 해시테그테이블.hashtagName not like '%오피스룩%'
        and 해시테그테이블.hashtagName not like '%캠퍼스룩%'
       order by 쇼핑몰목록테이블.popularity desc) as a
group by shopId;";

    $st = $pdo->prepare($query);
    $st->execute([$hashTag]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;



}



function isValidUserId($customerId)
{

    // return ($id=="softsqared"&&$pw="1234");


    $pdo = pdoSqlConnect();
    $query = "select EXISTS (select * from 회원정보테이블  where 회원정보테이블.customerId=?) as exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return intval($res[0]["exist"]);

}




function isValidClothesNo($clothesId)
{

    // return ($id=="softsqared"&&$pw="1234");


    $pdo = pdoSqlConnect();
    $query = "select EXISTS( select *  from 제품마스터테이블 where  제품마스터테이블.clothesId=?) as exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$clothesId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return intval($res[0]["exist"]);

}




function isValidUserCustomerEmail($customerEmail)
{

    // return ($id=="softsqared"&&$pw="1234");


    $pdo = pdoSqlConnect();
    $query = "select  EXISTS(select * from 회원정보테이블 where customerEmail=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerEmail]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return intval($res[0]["exist"]);

}


function isValidUser($customerEmail, $pw)
{

   // return ($id=="softsqared"&&$pw="1234");


    $pdo = pdoSqlConnect();
    $query = "select  EXISTS(select * from 회원정보테이블 where customerEmail=? and pw=?) AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerEmail, $pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return intval($res[0]["exist"]);

}


function user($customerEmail, $pw)
{

    // return ($id=="softsqared"&&$pw="1234");


    $pdo = pdoSqlConnect();
    $query = "select * from 회원정보테이블 where customerEmail=? and pw=?";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$customerEmail, $pw]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;
    $pdo = null;

    return res;

}


function getMyInformation($getuser)
{
    $pdo = pdoSqlConnect();
    $query = "select 회원정보테이블.customerName,
       회원정보테이블.customerEmail
from 회원정보테이블
where 회원정보테이블.customerEmail=?";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$getuser]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;
    //$res[2]='';
    return $res;
}


// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }


// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }

// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
