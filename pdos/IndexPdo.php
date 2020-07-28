<?php


function getGenreList()
{
    $pdo = pdoSqlConnect();
    $query = "select genreId,genreName from genre;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getMusicList($page)
{
    $pdo = pdoSqlConnect();
    $query = "select music.musicId, musicName, albumImage, artist.artistName,album.albumId,artist.artistId
from music,
     (select musicId, count(musicId) as b
      from musicHistory
     where musicHistory.isDeleted='N'
      group by musicId) as a,
     album,
     artist
where a.musicId = music.musicId and music.isDeleted='N'
  and album.musicId = music.musicId
  and artist.artistId = music.artistId
order by b desc";
    $query = $query." limit " .(($page-1)*10).",10;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getMusicFile($musicId)
{
    $pdo = pdoSqlConnect();
    $query = "select album.albumImage,albumId,a.musicId,a.musicName,a.artistName,a.musicFile,a.artistId
from album,(select musicId,musicName,artistName,musicFile,artist.artistId
from music,artist
where music.artistId=artist.artistId) as a
where album.musicId=a.musicId and a.musicId=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$musicId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getGenreNewAlbum($genreId,$page)
{
    $pdo = pdoSqlConnect();
    $query = "select b.genreId,b.musicId,b.albumImage,b.artistId,b.artistName,b.albumId,
       case when isFeaturing='Y' then concat(b.musicName,'(','Feat.',b.featuringArtist,')')
       else b.musicName
       end musicName
from genre,(select artist.artistName, artist.artistId,a.albumImage,a.albumId,a.musicId,a.musicName,a.genreId,a.createdAt,a.featuringArtist,a.isFeaturing
from artist,(select music.musicName,music.musicId,album.albumId,album.albumImage,music.artistId,music.genreId,album.createdAt,music.isFeaturing,music.featuringArtist

from album,music
where album.musicId=music.musicId and music.isDeleted='N') as a
where a.artistId=artist.artistId and artist.isDeleted='N') as b
where genre.genreId=b.genreId and genre.genreId=? and genre.isDeleted='N'
order by b.createdAt desc";
    $query = $query." limit " .(($page-1)*4).",4;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$genreId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewAlbum($page)
{
    $pdo = pdoSqlConnect();
    $query = "select artist.artistId,artist.artistName,a.musicId,a.musicName,a.albumImage,a.albumId
from artist,(select album.albumId,music.musicId,albumImage,music.artistId,album.createdAt,music.isForeign,
       case when music.isFeaturing='Y' then concat(music.musicName,'(','Feat.',music.featuringArtist,')')
       else music.musicName
       end musicName
from album,music
where album.createdAt> DATE_SUB(NOW(), INTERVAL 2 WEEK) and album.isDeleted='N'
  and music.isDeleted='N'
  and album.isDeleted='N'
and music.musicId=album.musicId) as a
where artist.artistId=a.artistId
      and artist.isDeleted='N'
order by a.createdAt desc";
    $query = $query." limit " .(($page-1)*4).",4;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewAlbumPlus($page,$isForeign)
{
    $pdo = pdoSqlConnect();
    $query = "select artist.artistId,artist.artistName,a.musicId,a.musicName,a.albumImage,a.albumId
from artist,(select album.albumId,music.musicId,albumImage,music.artistId,album.createdAt,music.isForeign,
       case when music.isFeaturing='Y' then concat(music.musicName,'(','Feat.',music.featuringArtist,')')
       else music.musicName
       end musicName
from album,music
where album.createdAt> DATE_SUB(NOW(), INTERVAL 2 WEEK) and album.isDeleted='N'
  and music.isDeleted='N'
and music.musicId=album.musicId) as a
where artist.artistId=a.artistId
      and a.isForeign like concat ('%', ?, '%')
      and artist.isDeleted='N'
order by a.createdAt desc";
    $query = $query." limit " .(($page-1)*8).",8;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$isForeign]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}




function getBillboarHotMusicList($page)
{
    $pdo = pdoSqlConnect();
    $query = "select music.musicId, musicName, albumImage, artist.artistName,album.albumId,artist.artistId
from music,
     (select musicId, count(musicId) as b
      from musicHistory
     where musicHistory.isDeleted='N'
      group by musicId) as a,
     album,
     artist
where a.musicId = music.musicId and music.isDeleted='N'
  and album.musicId = music.musicId
  and artist.artistId = music.artistId
  and music.isForeign='Y'
order by b desc";
    $query = $query." limit " .(($page-1)*10).",10;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function getAlbumDetail($genreId)
{
    $pdo = pdoSqlConnect();
    $query = "select album.albumId,album.albumName,album.albumImage,artist.artistName,concat(DATE_FORMAT(NOW(),'%Y.%m.%d'),'∙',album.albumGenre) as albumData
from music,
     album,
     artist
where music.isDeleted='N'
  and album.musicId = music.musicId
  and artist.artistId = music.artistId
  and album.albumId=?
  group by album.albumName ;";
    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$genreId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getTop100Date()
{
    $pdo = pdoSqlConnect();
    $query = "select CASE
    WHEN DATE_FORMAT(NOW(),'%p') ='PM' and DATE_FORMAT(NOW(),'%H') >=7  then DATE_FORMAT(NOW(),'%m월 %d일 오전 %7시 업데이트')
    WHEN DATE_FORMAT(NOW(),'%p') ='AM' and DATE_FORMAT(NOW(),'%H') <7 then DATE_FORMAT((SELECT DATE_SUB(NOW(), INTERVAL 1 DAY)),'%m월 %d일 오전 %7시 업데이트')
    end as data;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

function getMusicVideoTop50ate()
{
    $pdo = pdoSqlConnect();
    $query = "select CASE
    WHEN DATE_FORMAT(NOW(),'%H') >= 13 then DATE_FORMAT( DATE_SUB(NOW(), INTERVAL 13 HOUR),'%l시간 전 업데이트')
    WHEN DATE_FORMAT(NOW(),'%H') <13  then DATE_FORMAT((SELECT DATE_SUB(NOW(), INTERVAL  13 HOUR)),'%H시간 전 업데이트')
     WHEN DATE_FORMAT(NOW(),'%H') = 13 and DATE_FORMAT(NOW(),'%i') <=59 then DATE_FORMAT(NOW(),'%i 분 전 업데이트')
     end as data;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}



function getBillboardHotDate()
{
    $pdo = pdoSqlConnect();
    $query = " select CASE
WHEN DATE_FORMAT(NOW(),'%H') <=23  and DATE_FORMAT(NOW(),'%i') <=59 then DATE_FORMAT((SELECT DATE_SUB(NOW(), INTERVAL 1 DAY)),'%m월 %d일 업데이트')
    end as data;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
function getArtistList()
{
    $pdo = pdoSqlConnect();
    $query = "select artistId,artistName,artistImage
from artist
where artist.isDeleted='N'
order by rand() limit 15;";

    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getRecentMusicList($page,$userId)
{
    $pdo = pdoSqlConnect();
    $query = "select album.albumId,album.albumImage,a.musicId,a.musicName,a.artistId,a.artistName
from album,(select musicHistory.userId,music.musicId,music.musicName,musicHistory.createdAt,artist.artistId,artist.artistName
from musicHistory,music,artist
where musicHistory.musicId =music.musicId and userId=? and artist.artistId=music.artistId
and music.isDeleted='N'
group by musicHistory.userId,music.musicId) as a
where album.musicId= a.musicId
order by a.createdAt desc ";
    $query = $query." limit " .(($page-1)*10).",10;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([$userId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


function getMusicVideoTopList($page)
{
    $pdo = pdoSqlConnect();
    $query = "select b.musicId,b.musicName,b.musicVideoId,b.musicVideoUrl,b.artistId,b.artistName
from musicVideoHistory,(select a.musicId,a.musicName,a.musicVideoId,a.musicVideoUrl,a.artistId,artist.artistName
from artist,(select music.musicId,music.musicName,musicVideo.musicVideoId,musicVideo.musicVideoUrl,music.artistId
from musicVideo,music
where music.musicId=musicVideo.musicId
    and music.isDeleted='N' and musicVideo.isDeleted='N') as a
where a.artistId=artist.artistId and artist.isDeleted='N') as b
where musicVideoHistory.musicVideoId =b.musicVideoId
group by b.musicId
order by count(musicId) desc";
    $query = $query." limit " .(($page-1)*4).",4;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getAlbumMusicList($albumId)
{
    $pdo = pdoSqlConnect();
    $query = "select (music.musicId),(musicName),album.albumId,
        case when music.isTitle='Y' then 1
        else 2
        end as isTitle
from music,
     album,
     artist
where music.isDeleted='N'
  and album.musicId = music.musicId
  and artist.artistId = music.artistId
  and artist.isDeleted='N'
  and album.isDeleted='N'

  and album.albumId=?
  group by music.musicName
  order by isTitle ;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([$albumId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewsList($page)
{
    $pdo = pdoSqlConnect();
    $query = "select a.newsId,a.newsImage,a.newText
from (select newsId,newsImage,news.newText
from news
where news.isDeleted='N'
order by news.createdAt desc
limit 8) as a";
    $query = $query." limit " .(($page-1)*3).",3;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getNewsUrl($newsId)
{
    $pdo = pdoSqlConnect();
    $query = "select newsId,newsUrl
from news
where news.newsId=?;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([$newsId]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

function getInteriorCountryGrowth($page)
{
    $pdo = pdoSqlConnect();
    $query = "select music.musicId, musicName, albumImage, artist.artistName,album.albumId
from music,
     (select musicId, count(musicId) as b
      from musicHistory
     where musicHistory.isDeleted='N'
     and musicHistory.createdAt <= DATE_SUB(NOW(), INTERVAL 1 HOUR)
      group by musicId) as a,(select musicId, count(musicId) as d
      from musicHistory
     where musicHistory.isDeleted='N'
     and musicHistory.createdAt <= (NOW())
      group by musicId) as c,
     album,
     artist
where a.musicId = music.musicId and music.isDeleted='N'
  and album.isDeleted='N'
  and artist.isDeleted='N'
  and album.musicId = music.musicId
  and artist.artistId = music.artistId
  and music.isForeign='N'
  and a.musicId=c.musicId
order by d-b desc";
    $query = $query." limit " .(($page-1)*10).",10;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

#검색 가수이름일때->관련가수 노래가 있는지
function gerSearchingArtistName($searchingName)
{
    $pdo = pdoSqlConnect();
    $query = "select music.musicName,music.musicId,album.albumId,album.albumImage,a.artistId,a.artistName
from music,album,(select artistId,artistName
from artist
where artist.artistName like concat('%',?,'%'))as a
where music.artistId = a.artistId and
      music.musicId=album.musicId
order by music.createdAt;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([$searchingName]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function gerSearchingMusicName($searchingName)
{
    $pdo = pdoSqlConnect();
    $query = " select album.albumId,album.albumImage,a.artistId,a.artistName,a.musicId,a.musicName

    from album, (select musicId,artist.artistName,artist.artistId,music.createdAt,music.musicName
    from music,artist
    where music.musicName like concat('%',?,'%')
    and music.artistId = artist.artistId) as a
    where album.musicId =a.musicId
    order by a.createdAt ;";
    $st = $pdo->prepare($query);
    //    $st->execute([]);
    $st->execute([$searchingName]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
function isVaildMusicId($musicId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select *from music where musicId = ?)  as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$musicId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}





function isVaildGenreId($genreId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select *from genre where genreId = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$genreId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isVaildAlbumId($albumId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select *from album where albumId = ?) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$albumId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

//가수이름기준 존재하는 음악있는지
function isvaildArtistName($searchingName)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS
    (select music.musicName,music.musicId,album.albumId,album.albumImage,a.artistId,a.artistName
from music,album,(select artistId,artistName
from artist
where artist.artistName like concat('%',?,'%')) as a
where music.artistId = a.artistId and
      music.musicId=album.musicId
order by music.createdAt) as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$searchingName]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}

function isvaildMusicName($searchingName)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(
   select album.albumId,album.albumImage,a.artistId,a.artistName,a.musicId,a.musicName

    from album, (select musicId,artist.artistName,artist.artistId,music.createdAt,music.musicName
    from music,artist
    where music.musicName like concat('%',?,'%')
    and music.artistId = artist.artistId) as a
    where album.musicId =a.musicId
    order by a.createdAt)as exist;";

    $st = $pdo->prepare($query);
    $st->execute([$searchingName]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return intval($res[0]['exist']);
}


//READ
//function getUsers()
//{
//    $pdo = pdoSqlConnect();
//    $query = "select  customerId,age,gender,contactInformation,customerEmail,gender,interestedClothesType from 회원정보테이블;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
////READ
//
//
//
//function getUserDetail($customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select  customerId,age,gender,contactInformation,customerEmail,interestedClothesType from 회원정보테이블 where customerEmail=?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$customerEmail]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res[0];
//}
//
//function getUserId($customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select  customerId from 회원정보테이블 where customerEmail=?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$customerEmail]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res);
//}
//
//function getloginuser($getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select  customerId,age,gender,contactInformation,customerEmail,interestedClothesType from 회원정보테이블 where customerEmail=?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$getuser]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res[0];
//}
//
//function getShopMainTargetList($keyword)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
//       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
//       group_concat(해시테그테이블.hashtagName) as hashtagName,
//       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
//       group_concat(distinct 쇼핑몰목록테이블.shopId) as shopId
//from 쇼핑몰목록테이블,해시테그테이블
//where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
//        쇼핑몰목록테이블.mainTarget like concat('%',?,'%')
//group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
//order by 쇼핑몰목록테이블.popularity desc;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$keyword]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//function isvaildno($customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select * from 회원정보테이블 where customerEmail=?) as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$customerEmail]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//function isvailMainTarget($keyword)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where mainTarget=?) as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$keyword]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//function isvailCustomerId($customerId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS (select * from 회원정보테이블  where 회원정보테이블.customerId=?) as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$customerId]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//function isvaildSubTarget($subTarget)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select *from 쇼핑몰목록테이블 where 쇼핑몰목록테이블.subTarget=?) as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$subTarget]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//function isvailHashTag($hashTag)
//{
//    $pdo = pdoSqlConnect();
//    $query ="select EXISTS(select * from 해시테그테이블 where hashtagName=?)as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$hashTag]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//
//
//function getshopIist()
//{
//    $pdo = pdoSqlConnect();
//    $query = "select shopName,
//       shopImageUrl,
//       group_concat(해시테그테이블.hashtagName) as hashtagName,
//       group_concat(distinct  concat(mainTarget,'대',subTarget,'대')) as target,
//       쇼핑몰목록테이블.shopId as shopId
//from 쇼핑몰목록테이블,해시테그테이블
//where 쇼핑몰목록테이블.shopId=해시테그테이블.shopId
//group by 쇼핑몰목록테이블.shopName;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//
//function deleteShopList($shopId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "delete from 쇼핑몰목록테이블
//            where 쇼핑몰목록테이블.shopId=?;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$shopId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//   // $res[1]='';
//    return $res;
//}
//
//function deleteUser($customerId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "delete from 회원정보테이블
//where 회원정보테이블.customerId=?;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    //$res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    // $res[1]='';
//    // return $res[1];
//}
//
/////////////////jwt 사용 회원 delete 문
//function loginDeleteUser($customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "delete from 회원정보테이블
//where 회원정보테이블.customerEmail=?;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerEmail]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    //$res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    // $res[1]='';
//    // return $res[1];
//}
////////jwt 사용 keep -item 목록
//
//
//
//function getUserKeepItem($getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select #group_concat(제품마스터테이블.sale),
//       group_concat(distinct  쇼핑몰목록테이블.shopName) as shopName,
//       group_concat(distinct a.clothesId) as clothesId,
//       a.customerId as customerId,
//      # group_concat(a.customerEmail),
//       #group_concat(a.shopName)
//      GROUP_CONCAT( distinct 제품마스터테이블.shopId) as shopId,
//       GROUP_CONCAT( 제품마스터테이블.clothesName) as clothesName,
//       GROUP_CONCAT( 제품마스터테이블.clothesImageUrl) as clothesImageUrl ,
//       GROUP_CONCAT( concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
//         GROUP_CONCAT( ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
//        GROUP_CONCAT( 제품마스터테이블.FreeShipping)as FreeShipping,
//      GROUP_CONCAT( 제품마스터테이블.isNew)as isNew,
//        GROUP_CONCAT( 제품마스터테이블.isHandMade)as isHandMade
//
//from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
//       회원정보테이블.customerId as customerId,
//      회원정보테이블.customerEmail as customerEmail
//       #찜목록테이블.shopId
//       #group_concat(제품마스터테이블.sale)
//
//from 찜목록테이블,회원정보테이블
//where 찜목록테이블.customerId=회원정보테이블.customerId)as a
//#group by 찜목록테이블.clothesId) as a
//where 제품마스터테이블.clothesId =a.clothesId and
//      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
//      and a.customerEmail like concat('%',?,'%')
//group by a.customerId ;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res[0];
//}
//
//function getUsersRecommendation($getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select #group_concat(제품마스터테이블.sale),
//       group_concat(쇼핑몰목록테이블.shopName) as shopName,
//       group_concat(a.clothesId) as clothesId,
//       a.customerId as customerId,
//      # group_concat(a.customerEmail),
//       #group_concat(a.shopName)
//      GROUP_CONCAT(제품마스터테이블.shopId) as shopId,
//       제품마스터테이블.clothesName,
//       제품마스터테이블.clothesImageUrl,
//       GROUP_CONCAT(concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
//         GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
//        GROUP_CONCAT(제품마스터테이블.FreeShipping)as FreeShipping
//from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
//       회원정보테이블.customerId as customerId,
//      회원정보테이블.customerEmail as customerEmail
//       #찜목록테이블.shopId
//       #group_concat(제품마스터테이블.sale)
//
//from 찜목록테이블,회원정보테이블
//where 찜목록테이블.customerId=회원정보테이블.customerId)as a
//#group by 찜목록테이블.clothesId) as a
//where 제품마스터테이블.clothesId =a.clothesId and
//      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
//      and a.customerEmail like concat('%',?,'%')
//group by a.customerId ;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res;
//}
//
//function getMyKeepItem($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select #group_concat(제품마스터테이블.sale),
//       group_concat(쇼핑몰목록테이블.shopName) as shopName,
//       group_concat(a.clothesId) as clothesId,
//       a.customerId as customerId,
//      # group_concat(a.customerEmail),
//       #group_concat(a.shopName)
//      GROUP_CONCAT(제품마스터테이블.shopId) as shopId,
//       제품마스터테이블.clothesName,
//       제품마스터테이블.clothesImageUrl,
//       GROUP_CONCAT(concat(제품마스터테이블.sale,'%') SEPARATOR ',')as salePercent,
//         GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
//        GROUP_CONCAT(제품마스터테이블.FreeShipping)as FreeShipping
//from 제품마스터테이블,쇼핑몰목록테이블,(select 찜목록테이블.clothesId as clothesId,
//       회원정보테이블.customerId as customerId,
//      회원정보테이블.customerEmail as customerEmail
//       #찜목록테이블.shopId
//       #group_concat(제품마스터테이블.sale)
//
//from 찜목록테이블,회원정보테이블
//where 찜목록테이블.customerId=회원정보테이블.customerId)as a
//#group by 찜목록테이블.clothesId) as a
//where 제품마스터테이블.clothesId =a.clothesId and
//      쇼핑몰목록테이블.shopId=제품마스터테이블.shopId
//      and a.customerEmail like concat('%',?,'%')
//group by a.customerId ;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$clothesId,$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res;
//}
//
//function isvaildKeepItem($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS( select *
//from (select *
//from 찜목록테이블
//where 찜목록테이블.clothesId=?) as a
//where a.customerId=?) as exist";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$clothesId,$getuser]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//function isvaildKeepItemYes($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select 찜목록테이블.isHeart
//from 찜목록테이블
//where 찜목록테이블.clothesId =? and 찜목록테이블.customerId =? and 찜목록테이블.isHeart like ('%Y%'))as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$clothesId,$getuser]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//
//
//
//function updateKeepItemNO($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "UPDATE 찜목록테이블
//SET 찜목록테이블.isHeart='N'
//where  찜목록테이블.clothesId=? and 찜목록테이블.customerId =?";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$clothesId,$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//   // $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
// //   return $res;
//}
//
//function updateKeepItemYes($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "UPDATE 찜목록테이블
//SET 찜목록테이블.isHeart='Y'
//where  찜목록테이블.clothesId=? and 찜목록테이블.customerId =?";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$clothesId,$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//   // $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
// //   return $res;
//}
//
//
//function insertKeepItem($clothesId,$getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO 찜목록테이블(clothesId,customerId) VALUES (?,?);";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$clothesId,$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//   // $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    //return $res;
//}
//
//
//function insertHistory($customerId,$clothesId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO 히스토리테이블(customerId,clothesId) VALUES (?,?);";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerId,$clothesId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    // $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    //return $res;
//}
//
//
//function isvaildHistory($clothesId,$costomerId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS( select *
//    from (select *
//        from 히스토리테이블
//where 히스토리테이블.clothesId=?) as a
//where a.customerId=?) as exist";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$clothesId,$costomerId]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//function updateHistoryNo($clothesId,$costomerId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "UPDATE 히스토리테이블
//SET 히스토리테이블.isDleted='Y'
//where  히스토리테이블.clothesId=? and 히스토리테이블.costomerId =?";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$clothesId,$costomerId]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//function getPopularityShopList($popularity)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
//       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
//       group_concat(해시테그테이블.hashtagName) as hashtagName,
//       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
//       쇼핑몰목록테이블.shopId as shopId
//from 쇼핑몰목록테이블,해시테그테이블
//where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and 쇼핑몰목록테이블.popularity =?
//group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
//order by 쇼핑몰목록테이블.popularity desc;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$popularity]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res;
//}
//
//function isvaildPopularity($popularity)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where 쇼핑몰목록테이블.popularity =?) as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$popularity]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//function isvaildShopId($shopId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS(select * from 쇼핑몰목록테이블 where shopId=?)as exist;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$shopId]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return intval($res[0]['exist']);
//}
//
//
//
//
//function getShopDetail($shopId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
//       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
//       group_concat(해시테그테이블.hashtagName) as hashtagName,
//       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
//       쇼핑몰목록테이블.shopId as shopId
//from 쇼핑몰목록테이블,해시테그테이블
//where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
//        해시테그테이블.hashtagName not like '%#%'
//        and 쇼핑몰목록테이블.shopId=?
//group by  쇼핑몰목록테이블.shopName;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$shopId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res[0];
//}
//
//function getShopClothesList($shopId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select group_concat(distinct 제품마스터테이블.clothesPrice) as clothesPrice,
//       group_concat(distinct 제품마스터테이블.clothesId) as clothesId,
//       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
//       group_concat(제품마스터테이블.clothesImageUrl) as cothesImageUrl,
//     #  (DATE_FORMAT(제품마스터테이블.updatedAt,'금주의 신상품 - %m.%d %H:%i')) as clothesUpdateAt,
//      group_concat(distinct ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100), 0) ) as 최종가격,
//      제품마스터테이블.isNew as isNew,
//      제품마스터테이블.isHandMade as isHandMakd
//
//
//    from 쇼핑몰목록테이블,제품마스터테이블,해시테그테이블
//    where 쇼핑몰목록테이블.shopId =제품마스터테이블.shopId and 쇼핑몰목록테이블.shopId=? and
//          제품마스터테이블.updatedAt >date_add(now(),interval -7 day)
//    group by 쇼핑몰목록테이블.shopName,제품마스터테이블.updatedAt
//    order by 제품마스터테이블.updatedAt DESC;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$shopId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res;
//}
//function getShopClothesUpdate($shopId)
//{
//  $pdo = pdoSqlConnect();
//    $query = "select (DATE_FORMAT(제품마스터테이블.updatedAt,'금주의 신상품 - %m.%d %H:%i')) as clothesUpdateAt
//    from 제품마스터테이블
//    where 제품마스터테이블.shopId=?
//    order by 제품마스터테이블.updatedAt DESC limit 1;";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$shopId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return [$res][0];
//}
//
//
//function createUser($customerEmail,$contactInformation,$pw,$customerId)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO 회원정보테이블 (customerEmail,contactInformation,pw,customerId) VALUES (?,?,?,?);";
//    $st = $pdo->prepare($query);
//    $st->execute([$customerEmail,$contactInformation,$pw,$customerId]);
//  //  $st->setFetchMode(PDO::FETCH_ASSOC);
// //   $res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//   // return $res;
//}
//
//
//
//function registerClothes($shopId,$clothesPrice,$clothesType,$clothesName,$clothesImageUrl,$sale,$freeShipping,$shopName,$isHeart,$isNew,$isHandMade)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO 제품마스터테이블 (shopId,clothesPrice,clothesType,clothesName,clothesImageUrl,sale,
//freeShipping,shopName,isHeart,isNew,isHandMade) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
//    $st = $pdo->prepare($query);
//    $st->execute([$shopId,$clothesPrice,$clothesType,$clothesName,$clothesImageUrl,$sale,$freeShipping,$shopName,$isHeart,$isNew,$isHandMade]);
//    //  $st->setFetchMode(PDO::FETCH_ASSOC);
//    //   $res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//    // return $res;
//}
//
//
//function patchChangeContactinformation($contactInformation,$customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "UPDATE 회원정보테이블
//
//SET 회원정보테이블.contactInformation = ?
//
//WHERE customerEmail = ?;";
//    $st = $pdo->prepare($query);
//    $st->execute([$contactInformation,$customerEmail]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    #$res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//    #return $res;
//}
//
//function patchChangeCustomerName($patchChangeCustomerName,$customerEmail)
//{
//    $pdo = pdoSqlConnect();
//    $query = "UPDATE 회원정보테이블
//SET 회원정보테이블.customerName=?
//where 회원정보테이블.customerEmail=?";
//    $st = $pdo->prepare($query);
//    $st->execute([$patchChangeCustomerName,$customerEmail]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    #$res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//    #return $res;
//}
//
//
//function getShopSubTargetList($subTarget)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select group_concat(distinct  쇼핑몰목록테이블.shopName ) as shopName,
//       group_concat(distinct 쇼핑몰목록테이블.mainTarget,'대',쇼핑몰목록테이블.subTarget,'대') as shopTarget,
//       group_concat(해시테그테이블.hashtagName) as hashtagName,
//       group_concat(distinct 쇼핑몰목록테이블.shopImageUrl) as shopImageUrl,
//       group_concat(distinct 쇼핑몰목록테이블.shopId) as shopId
//from 쇼핑몰목록테이블,해시테그테이블
//where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
//        쇼핑몰목록테이블.subTarget like concat('%',?,'%')
//group by  쇼핑몰목록테이블.shopName,쇼핑몰목록테이블.popularity
//order by 쇼핑몰목록테이블.popularity desc;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$subTarget]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//}
//
//
//function createShop($shopName,$shopImageUrl,$mainTarget,$subTarget)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO 쇼핑몰목록테이블 (shopName,shopImageUrl,mainTarget,subTarget) VALUES (?,?,?,?);";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$shopName,$shopImageUrl,$mainTarget,$subTarget]);
//    //  $st->setFetchMode(PDO::FETCH_ASSOC);
//    //   $res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//    // return $res;
//}
//
//
//
//function getShopListAdvertise()
//{
//    $pdo = pdoSqlConnect();
//    $query = "SELECT 제품마스터테이블.shopName,
//        GROUP_CONCAT(제품마스터테이블.clothesId) as clothesId,
//        GROUP_CONCAT(제품마스터테이블.clothesName)as colthesName,
//        GROUP_CONCAT(제품마스터테이블.clothesImageUrl)as colthesUrl,
//        GROUP_CONCAT(제품마스터테이블.sale)as sale,
//        GROUP_CONCAT(ROUND(제품마스터테이블.clothesPrice - (제품마스터테이블.clothesPrice * 제품마스터테이블.sale / 100),-2))as price,
//        GROUP_CONCAT(제품마스터테이블.FreeShipping)as freeShipping,
//        제품마스터테이블.shopId as shopId
//FROM 제품마스터테이블 ,광고테이블
//where 제품마스터테이블.shopName=광고테이블.shopName
//GROUP BY 제품마스터테이블.shopName;";
//
//    $st = $pdo->prepare($query);
//    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//    $st = null;
//    $pdo = null;
//
//     return $res;
//}
//
//function getShopHashtag($hashTag)
//{
//    $pdo = pdoSqlConnect();
//    $query = "SELECT
//         #a.popularity,
//        a.shopName  as shopName,
//        GROUP_CONCAT(distinct a.shopId) as shopId,
//        GROUP_CONCAT(a.shopImageUrl) as shopImageUrl
//       #a.hashtagId,
//       #a.shopName
//FROM (select
//       해시테그테이블.hashtagName,
//       해시테그테이블.hashtagId,
//       쇼핑몰목록테이블.shopName,
//       쇼핑몰목록테이블.shopId as shop,
//       해시테그테이블.shopId,
//       쇼핑몰목록테이블.popularity,
//       쇼핑몰목록테이블.shopImageUrl
//       from 해시테그테이블,쇼핑몰목록테이블
//    where 해시테그테이블.shopId=쇼핑몰목록테이블.shopId and
//        해시테그테이블.hashtagName like  concat('%',?,'%')
//        and 해시테그테이블.hashtagName not like '%심플베이직%'
//        and 해시테그테이블.hashtagName not like '%러블리%'
//        and 해시테그테이블.hashtagName not like '%오피스룩%'
//        and 해시테그테이블.hashtagName not like '%캠퍼스룩%'
//       order by 쇼핑몰목록테이블.popularity desc) as a
//group by shopId;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$hashTag]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res;
//
//
//
//}
//
//
//
//function isValidUserId($customerId)
//{
//
//    // return ($id=="softsqared"&&$pw="1234");
//
//
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS (select * from 회원정보테이블  where 회원정보테이블.customerId=?) as exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;
//    $pdo = null;
//
//    return intval($res[0]["exist"]);
//
//}
//
//
//
//
//function isValidClothesNo($clothesId)
//{
//
//    // return ($id=="softsqared"&&$pw="1234");
//
//
//    $pdo = pdoSqlConnect();
//    $query = "select EXISTS( select *  from 제품마스터테이블 where  제품마스터테이블.clothesId=?) as exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$clothesId]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;
//    $pdo = null;
//
//    return intval($res[0]["exist"]);
//
//}
//
//
//
//
//function isValidUserCustomerEmail($customerEmail)
//{
//
//    // return ($id=="softsqared"&&$pw="1234");
//
//
//    $pdo = pdoSqlConnect();
//    $query = "select  EXISTS(select * from 회원정보테이블 where customerEmail=?) AS exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerEmail]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;
//    $pdo = null;
//
//    return intval($res[0]["exist"]);
//
//}
//
//
//function isValidUser($customerEmail, $pw)
//{
//
//   // return ($id=="softsqared"&&$pw="1234");
//
//
//    $pdo = pdoSqlConnect();
//    $query = "select  EXISTS(select * from 회원정보테이블 where customerEmail=? and pw=?) AS exist;";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerEmail, $pw]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;
//    $pdo = null;
//
//    return intval($res[0]["exist"]);
//
//}
//
//
//function user($customerEmail, $pw)
//{
//
//    // return ($id=="softsqared"&&$pw="1234");
//
//
//    $pdo = pdoSqlConnect();
//    $query = "select * from 회원정보테이블 where customerEmail=? and pw=?";
//
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$customerEmail, $pw]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st=null;
//    $pdo = null;
//
//    return res;
//
//}
//
//
//function getMyInformation($getuser)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select 회원정보테이블.customerName,
//       회원정보테이블.customerEmail
//from 회원정보테이블
//where 회원정보테이블.customerEmail=?";
//
//    $st = $pdo->prepare($query);
//    //    $st->execute([$param,$param]);
//    $st->execute([$getuser]);
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//    //$res[2]='';
//    return $res;
//}


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
