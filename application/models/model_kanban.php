<?
session_start();

class Model_User extends Model
{
    
    function desks_kanban(){

        echo "<div class=\"content\" style=\"text-align:left\">";
        
	    $mysqli = $this->sql_connect();

        $count = 5; //Количество страниц для пагинации

        if (isset($_GET['page'])){ //Задать вопрос на паре!
            $page=($_GET['page']-1);
            $current = $page+1;
        }else{
            $page=0;
        }

        $page *= $count; //Задать вопрос на паре!

        $str_query_pg = "SELECT * FROM `boards`;";
        $res_pg = $mysqli->query($str_query_pg);
        $num = $res_pg->num_rows; //Получение количества строк
        $str_pag = ceil($num / $count); //Количество страниц


        $login_from = $_SESSION['login'];    //получаем логин пользователя
        $user = mysqli_query($mysqli, "SELECT * FROM `users` WHERE `login` = '$login_from'")->fetch_assoc();    //получаем инфу пользователя (не стринг)

        // вывод всех тем если админ
        if($user["id_role"] == 1){
            $select = mysqli_query($mysqli, "SELECT * FROM `boards` ORDER BY `id` DESC LIMIT $page, $count");

        }else{
            $id_user = $user['id']; //получаем id пользователя
            $select= mysqli_query($mysqli, "SELECT * FROM `boards` WHERE `id_user` = '$id_user' ORDER BY `id` DESC LIMIT $page, $count");
        }
        $xposp = $select->num_rows;
        if($select->num_rows): while($xposp>=0):
                echo "<ul>";
                while ($row = mysqli_fetch_array($select)) {

                    echo "<form action=\"/kanban/DeleteDesk\" method=\"post\">";
                        echo "<a href=\"/kanban/deldesk\" class=\"del\" style=\"float:left;border:0;background-color: #fff;\"></a>";
                    echo "</form>";
                        echo "<li>";
                    
                        echo "<form action=\"/kanban/delete_this_desk\" method=\"post\">";

                            $id_bord = $row['id'];//передаем id, короче долбанные костыли
                            echo "<input style=\"display:none\" value = \"$id_bord\" id=\"id_bord\" name=\"id_bord\">";
                            echo "<input type=\"submit\" value=\"Удалить эту таблицу\">";

                            echo "<a href=\"/kanban/desk_info/?page=".$row['id']."\" style=\"margin-left: 20px;\">"; // сделать преход на доску
                                echo "<span class=\"data\">" . $row['date'] . " " . "</span>";
                                echo "<span class=\"title\">". $row['title'] . " " . "</span>";
                                    if($user["id_role"] == 1){
                                        // вывод логина если админ
                                        $user_id = $row['id_user'];
                                        $user_name = mysqli_query($mysqli, "SELECT `login` FROM `users` WHERE `id` = '$user_id'")->fetch_assoc();
                                        echo "<span class=\"title\">". $user_name['login'] . " " . "</span>";
                                    }
                            echo "</a>";
                        echo "</li>";
                    echo "</form>";
                }
                $xposp = $xposp - 1;

                endwhile;
            endif;

        echo "</ul>";

        echo "<nav>";
            for ($i = 1; $i <= $str_pag; $i++){
                if ($i == $current){
                    echo "<a style='color:red;' href=/kanban/desks/?page=".$i."> ".$i." </a>";
                }else{
                    echo "<a href=/kanban/desks/?page=".$i."> ".$i." </a>";
                }
            }
        echo "</nav>";
        echo "</div>";
    }

    function newdesk_kanban(){

        if (empty($_SESSION['login'])) 
        {
            die("<p>Создание тем доступно только для авторизованых пользователей!</p>");
        }

        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        $mysqli->set_charset('utf8');

        if (isset($_POST['deskname'])){

            $id = $_SESSION['id'];
            $title = $_POST['deskname'];
            $date = date('Y-m-d');

            
            if(empty($_POST['deskname']))
            {
                //defaul znach
                
                // $maxid = mysqli_query($mysqli, "Select max(`id`) as `maxid` from `boards`")->fetch_assoc();
                // $maxid = max($maxid) + 1;
                $maxid = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE 'id_user' = '$id'");
                $maxid = $maxid->num_rows;
                $maxid = $maxid + 1;
                $title = "Новая_доска_".$maxid;
                $query = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title`='".mysqli_real_escape_string($mysqli, $title)."'"); //Сверка с бд
                while(mysqli_num_rows($query) > 0 ){
                    $maxid = $maxid + 1;
                    $title = "Новая_доска_".$maxid;
                    $query = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title`='".mysqli_real_escape_string($mysqli, $title)."'"); //Сверка с бд
                }
            }

            

            mysqli_query($mysqli,"INSERT INTO `boards` (`id_user`, `title`, `date`) VALUES ('$id', '$title', '$date')");
                return "ok";
        }else{
            echo "ошибка";
        }
    }

    function deldesk_kanban (){

        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        $mysqli->set_charset('utf8');

        $deskname = $_POST["deskname"]; //получаем титул доски

        if(isset($_POST["deskname"])){
            $login_from = $_SESSION['login'];
            $user = mysqli_query($mysqli, "SELECT * FROM `users` WHERE `login` = '$login_from'")->fetch_assoc();    //получаем инфу пользователя (не стринг)
            
            if($user['id_role'] == 1){
                $TitleDesk = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title` = '$deskname'");
            }else{
                $id_user = $user['id']; //получаем id пользователя
                
                $TitleDesk = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title` = '$deskname' AND `id_user` = '$id_user'")->fetch_assoc();
            }
            $title_desk = $TitleDesk['id'];
            if(isset($title_desk)){
                mysqli_query($mysqli,"DELETE FROM `boards` WHERE `boards`.`id` = '$title_desk'");
                return "ok";
            }else{
                echo "ошибка";
            }
        }
    }
    function delete_this_desk_kanban(){
        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        $mysqli->set_charset('utf8');

        $id_bord = $_POST['id_bord'];
            mysqli_query($mysqli,"DELETE FROM `boards` WHERE `boards`.`id` = '$id_bord'");
            return "ok";
    }
}
?>