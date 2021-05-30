<?
session_start();


class Model_User extends Model
{

	function run_user() { 

		$mysqli = $this->sql_connect();
        if ($mysqli->connect_error){
			die('Error');
		}
        $mysqli->set_charset('utf8');

        if(isset($_POST['login']))
        {
            $query = mysqli_query($mysqli,"SELECT `id`, `password` FROM `users` WHERE login='".mysqli_real_escape_string($mysqli, $_POST['login'])."' LIMIT 1");  //Вытаскиваем запись с нашим логином
                
            $data = mysqli_fetch_assoc($query); //Записываем данные в ассоциативный массив
    
            if($data['password'] === md5(($_POST['password_f'])))
            {

                $_SESSION['login'] = $_POST['login'];
                $_SESSION['id'] = $data['id'];
    
                return "ok";
            }
            else
            {
                echo "Неверный логин или пароль! Повторите попытку!";
            }
        }
    
	}

    function logout_user(){
    session_destroy();
    }

    function registration_user(){

        if($_POST['login']){

            $errors = [];  //Массив для ошибок

            $mysqli = $this->sql_connect();

            //Проверка логина

            if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
            {
                $errors[] = "Логин может состоять только из букв английского алфавита и цифр";
            }

            if((strlen($_POST['login']) < 3) or (strlen($_POST['login']) > 18))
            {
                $errors[] = "Логин должен быть от 3 до 18 символов";
            }

            $query = mysqli_query($mysqli, "SELECT `id` FROM `users` WHERE `login`='".mysqli_real_escape_string($mysqli, $_POST['login'])."'"); //Сверка с бд

            if(mysqli_num_rows($query) > 0)
            {
                $errors[] = "Пользователь с таким логином уже существует в базе данных!";
            }

            //Проверка почты

            if (preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $_POST['email'])) {
            }else{
                $errors[] = "Некорректный адрес электронной почты";
            }

            //Проверка пароля

            if (empty($_POST['password_f']) && $_POST['password_f'] = ' ')
            { 
                $errors[] = "Ошибка: поле 'Пароль' не заполнено!";
            }

            if (empty($_POST['password_s']) && $_POST['password_s'] = ' ')
            { 
                $errors[] = "Ошибка: повторите пароль!";
            }
                    
            if ($_POST['password_f'] != $_POST['password_s'])
            {
                $errors[] = "Ошибка: Введённые пароли не совпадают!";
            }

            if(count($errors) == 0)
            {
                $login = $_POST['login'];
                $password = md5(trim($_POST['password_f']));
                $name = $_POST['name'];
                $email = $_POST['email'];
                $tel = $_POST['telephone'];

                mysqli_query($mysqli,"INSERT INTO `users` (`login`, `name`, `password`, `mail`, `telephone`) VALUES ('$login', '$name', '$password', '$email', '$tel')");
                return "ok";
            }
            else
            {
                echo "<div>Регистрация не удалась!</div>";
                foreach($errors AS $error)
                {
                    echo "<div style=\"\" class=\"err\">" . $error ."</div><br>";
                }
            }
        }

    }
    
}
?>