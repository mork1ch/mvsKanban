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
        $id_user = $user['id']; //получаем id пользователя

        $select= mysqli_query($mysqli, "SELECT * FROM `boards` WHERE `id_user` = '$id_user' ORDER BY `id` DESC LIMIT $page, $count");

        $xposp = $select->num_rows;
        if($select->num_rows): while($xposp>=0):
                echo "<ul>";
                while ($row = mysqli_fetch_array($select)) {

                    echo "<form action=\"/kanban/DeleteDesk\" method=\"post\">";
                        echo "<li>";
                            echo "<a href=\"/kanban/deldesk\" class=\"del\" style=\"float:left;border:0;background-color: #fff;\"></a>";
                            echo "<a href=\"/kanban/desk_info?id=".$row['id']."\" style=\"margin-left: 20px;\">"; // сделать преход на доску
                                echo "<span class=\"data\">" . $row['date'] . " " . "</span>";
                                echo "<span class=\"title\">". $row['title'] . " " . "</span>";
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
                    echo "<a style='color:red;' href=kanban/desks?page=".$i."> ".$i." </a>";
                }else{
                    echo "<a href=main.php?page=".$i."> ".$i." </a>";
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

            $query = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title`='".mysqli_real_escape_string($mysqli, $title)."'"); //Сверка с бд
            if(mysqli_num_rows($query) > 0 || empty($_POST['deskname']))
            {
                //defaul znach
                $maxid = mysqli_query($mysqli, "Select max(`id`) as `maxid` from `boards`")->fetch_assoc();
                $maxid = max($maxid) + 1;
                $title = "Новая_доска_".$maxid;
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
        $id_desk = $_POST["id"]; //получаем айди доски

        if(isset($_POST["id"]) || isset($_POST["deskname"])){
            $login_from = $_SESSION['login'];
            $user = mysqli_query($mysqli, "SELECT * FROM `users` WHERE `login` = '$login_from'")->fetch_assoc();    //получаем инфу пользователя (не стринг)
            $id_user = $user['id']; //получаем id пользователя
            
            $TitleDesk = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title` = '$deskname' AND `id_user` = '$id_user'")->fetch_assoc();
            $title_desk = $TitleDesk['id'];
            if(isset($title_desk)){
                mysqli_query($mysqli,"DELETE FROM `boards` WHERE `boards`.`id` = '$title_desk'");
                return "ok";
            }else{
                echo "ошибка";
            }
        }
    }
}
?>