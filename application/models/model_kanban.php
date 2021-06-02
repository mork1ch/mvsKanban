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

                            echo "</form>";
                            echo "<form action=\"/kanban/desks_info/?id=".$row['id']."\" method=\"post\">";
                                echo "<input type=\"submit\" value=\"открыть\" style=\"margin-left: 20px;\">";
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
            
            $id_board = mysqli_query($mysqli, "Select max(`id`) as `maxid` from `boards`")->fetch_assoc();
            $id_board = max($id_board);

            //добавление колонок в новую доску
            mysqli_query($mysqli,"INSERT INTO `cell` (`id_board`, `title`, `date`) VALUES ('$id_board', 'ToDo', '$date')");
            mysqli_query($mysqli,"INSERT INTO `cell` (`id_board`, `title`, `date`) VALUES ('$id_board', 'InProgress', '$date')");
            mysqli_query($mysqli,"INSERT INTO `cell` (`id_board`, `title`, `date`) VALUES ('$id_board', 'Done', '$date')");

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
                $TitleDesk = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title` = '$deskname'")->fetch_assoc();
            }else{
                $id_user = $user['id']; //получаем id пользователя
                
                $TitleDesk = mysqli_query($mysqli, "SELECT `id` FROM `boards` WHERE `title` = '$deskname' AND `id_user` = '$id_user'")->fetch_assoc();
            }

            $title_desk = $TitleDesk['id'];

            if(isset($title_desk)){

                //удаление тикетов добавить
                mysqli_query($mysqli,"DELETE FROM `cell` WHERE `id_board` = '$title_desk'");
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

        //удаление тикетов добавить
        $id_bord = $_POST['id_bord'];
        $id_cell = mysqli_query($mysqli,"SELECT `id` FROM `cell` WHERE `id_board` = '$id_bord'")->fetch_assoc();
        $id_cell = $id_cell['id'];
        mysqli_query($mysqli,"DELETE FROM `tikets` WHERE `id_cell` = '$id_cell'");
        mysqli_query($mysqli,"DELETE FROM `cell` WHERE `id_board` = '$id_bord'");
        mysqli_query($mysqli,"DELETE FROM `boards` WHERE `boards`.`id` = '$id_bord'");  

        return "ok";
    }

    function Desks_info_kanban(){
        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        $mysqli->set_charset('utf8');

        $id = $_GET['id'];
        $deskid = $id;

        //Название доски
        $deskname = mysqli_query($mysqli,"SELECT `title` FROM `boards` WHERE `id` = '$id'")->fetch_assoc();
        $deskname = $deskname['title'];
        // не могу без коментов
        // получаем айдишки колонок для проверки ниже, еть ли в колонках инфа

        $info_ToDo = mysqli_query($mysqli,"SELECT `id` FROM `cell` WHERE `title` = 'TODO' AND `id_board` = '$id'");
        $strukt_ToDo = $info_ToDo;
        $strukt_ToDo = $strukt_ToDo->fetch_assoc();
        $id_todo = $strukt_ToDo['id'];

        $info_InProgress = mysqli_query($mysqli,"SELECT `id` FROM `cell` WHERE `title` = 'InProgress' AND `id_board` = '$id'");
        $strukt_InProgress = $info_InProgress;
        $strukt_InProgress = $strukt_InProgress->fetch_assoc();
        $id_InProgress = $strukt_InProgress['id'];

        $info_Done = mysqli_query($mysqli,"SELECT `id` FROM `cell` WHERE `title` = 'Done' AND `id_board` = '$id'");
        $strukt_Done = $info_Done;
        $strukt_Done = $strukt_Done->fetch_assoc();
        $id_Done = $strukt_Done['id'];

        

        //html verstka

        echo "<div class=\"content\">";

            echo " <div class=\"vert ToDo\">";
                echo "<h3>To Do</h3>";
                echo "<div class=\"info\">";
                    $tikets_TODO = mysqli_query($mysqli,"SELECT * FROM `tikets` WHERE `id_cell` = '$id_todo'");
                    $tiket_array_TODO = $tikets_TODO;
                    $tiket_array_TODO = $tiket_array_TODO->fetch_assoc();
                    $tiket_id_TODO = $tiket_array_TODO['id'];
                    $num_rowsa_strukt_tikets_TODO = mysqli_query($mysqli,"SELECT COUNT(*) FROM `tikets` WHERE `id_cell` = '$id_todo'")->fetch_assoc();
                    $num_rows_strukt_tikets_TODO = mysqli_query($mysqli,"SELECT * FROM `tikets` WHERE `id_cell` = '$id_todo'");
                    $num_rowsa_strukt_tikets_TODO = $num_rowsa_strukt_tikets_TODO['COUNT(*)'];

                    

                    if($num_rowsa_strukt_tikets_TODO >= 1) {
                        if ($num_rows_strukt_tikets_TODO->num_rows) : while($num_rows_strukt_tikets_TODO > 0):
                                //вывод тикетов
                                echo "<div class=\"tikets\">";
                                    echo "<form action=\"/kanban/Create_new_tiket/".$deskname."/?deskid=".$deskid."&id=".$id_todo."\" method=\"post\">";
                                    echo "<button class=\"Create_tik\" >Добавить тикет</button>";
                                    echo "</form>";
                                    while ($row = mysqli_fetch_array($num_rows_strukt_tikets_TODO)){   
                                        echo "<div class=\"tiket\">";
                                            echo "<a href=\"\"><span class=\"left\"></span></a>";
                                            echo "<p>". $row['title'] . " " . "</p>";
                                            echo "<a href=\"\"><span class=\"right\"></span></a>";
                                            //опять костыли для передачи id

                                            echo "<form action=\"/kanban/delete_this_tiket\" method=\"post\" style=\"display:inline-block;float:right;\">";
                                                
                                                $id_tiket = $row['id'];//передаем id, короче долбанные костыли
                                                echo "<input style=\"display:none\" value = \"$id_tiket\" id=\"id_tiket\" name=\"id_tiket\">";

                                                echo "<input type=\"submit\" class=\"del\" value=\"\"></input>";
                                            echo "</form>";

                                        echo "</div>";
                                    }
                                    echo "";


                                echo "</div>";
                                $num_rows_strukt_tikets_TODO = $num_rows_strukt_tikets_TODO - 1;
                            endwhile;
                        endif;
                    }
                echo "</div>";
            echo "</div>";

            //проверка есть ли тикеты
            

            //проверка есть ли тикеты
            

        echo "</div>";
    }

    function Create_new_tiket(){
        if (empty($_SESSION['login'])) 
        {
            die("<p>Создание тем доступно только для авторизованых пользователей!</p>");
        }
        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        
        $mysqli->set_charset('utf8');

        $deskid = $_GET['deskid'];
        $id = $_GET['id'];
        $tiket_title = $_POST['title'];

        if(!empty($tiket_title)){
            mysqli_query($mysqli,"INSERT INTO `tikets` (`id_cell`, `title`) VALUES ('$id', '$tiket_title')");

            return $deskid;
        }else{
            echo "ошибка- не указанно название";
        }
    }
    function delete_this_tiket(){
        $mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
            die('Error');
        }
        $mysqli->set_charset('utf8');

        $id_tiket = $_POST['id_tiket'];

        //получаем айди доски
        $id_cell = mysqli_query($mysqli,"SELECT * FROM `tikets` WHERE `id` = '$id_tiket'")->fetch_assoc();
        $id_cell = $id_cell['id_cell']; 

        $deskid = mysqli_query($mysqli,"SELECT * FROM `cell` WHERE `id` = '$id_cell'")->fetch_assoc();
        $deskid = $deskid['id_board'];
        
        mysqli_query($mysqli,"DELETE FROM `tikets` WHERE `id` = '$id_tiket'");
        
        return $deskid;
    }
}
?>