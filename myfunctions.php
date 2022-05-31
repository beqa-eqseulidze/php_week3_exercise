<?php
$user_name = '';
$img_url = '';
$repo_quantity = '';
$follower_quantity = '';
$not_user = '';

?>
<?php
// this function conects git Hub server and get data ebout wanted user 
// also dounloads user profile photo creat folders and save the photo;
//also this function returns assoc array and this data will save into db; 
function get_data_from_api()
{
    global $header, $found_user_from_api, $status_code,$new_loc;
    if ($_POST) {
        /// ===================== curl Authorization ===================== 
        $header = [
            "User-Agent: Example REST API Client",
            "Authorization: token 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"
        ];
        $url = 'https://api.github.com/users/' . $_POST['find_user'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($status_code == 200) {
            $result = json_decode($result, true);
            $img_url= $result['avatar_url'];
            dounload_image($img_url);
            $found_user_from_api = ['user' => $result['login'], 'img'=>$new_loc, 'repo' => $result['public_repos'], 'follower' => $result['followers']];
            return $found_user_from_api;
        }
    }
}

?>

<?php
//this function conects to the db, searchs the wanted user and then 
// if fond it will return this user if not found will return 0;

function get_data_from_db()
{
    global $found_user_db, $pdo;
    $pdo = new PDO('mysql:host=localhost;port=3306; dbname=week3_exercise;', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $statment = $pdo->prepare('SELECT * FROM data');
    $statment->execute();
    $mydata = $statment->fetchAll(PDO::FETCH_ASSOC);
    foreach ($mydata as $found_user_db) {
        if ($found_user_db['user'] == $_POST['find_user']) {
            break;
        }
        $found_user_db = 0;
    }
    return $found_user_db;
}

?>
<?php
// this function writes the new user's datas into db;
function upload_data_into_db()
{
    global $found_user_from_api, $pdo;
    get_data_from_api();
       if (!empty($found_user_from_api)) {
        $user_api = $found_user_from_api['user'];
        $img_api = $found_user_from_api['img'];
        $repo_api = $found_user_from_api['repo'];
        $follower_api = $found_user_from_api['follower'];
        $pdo->exec("INSERT INTO data(user,img,repo,follower) VALUES('$user_api',' $img_api','$repo_api','$follower_api')");
    }
}
?>

<?php
// this function searchs the wanted user first in the bd if it's not found there then in the git hub sever
//if user not found returns eror mesage 'user not found' 
function search_user() {
global $found_user_from_api,$found_user_db,$user_name,$img_url, $repo_quantity,$follower_quantity,$not_user;
    get_data_from_db();
    if ($found_user_db != 0) {
        $user_name = $found_user_db['user'];
        $img_url = $found_user_db['img'];
        $repo_quantity = $found_user_db['repo'];
        $follower_quantity = $found_user_db['follower'];
    } else {
        upload_data_into_db();
       
        if (!empty($found_user_from_api)) {
            get_data_from_db();
            $user_name = $found_user_db['user'];
            $img_url = $found_user_db['img'];
            $repo_quantity = $found_user_db['repo'];
            $follower_quantity = $found_user_db['follower'];
        } else {
            $not_user = 'user not found';
        }
    }
}

?>

<?php 
// this function dounloads user's photo from git hub server and seves in the image folder with randon name;
function dounload_image($img_url){
    global $new_loc;
    if(!is_dir('image')){
        mkdir('image');
    } 
    $my_dir='./image/';
    $filename=random_int(0,10000000).'.png';
    $new_loc=$my_dir.$filename;
    file_put_contents($new_loc,file_get_contents($img_url));
    return $new_loc;
}
?>

<?php

if($_POST){
 search_user();
}

?>
