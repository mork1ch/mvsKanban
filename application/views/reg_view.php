<div class="content">
    <h2>Регистрация нового пользователя:</h2>
    <form action="/user/registration" method="post">

        <p>Логин <input type="text" id="login" name="login"></p>

        <p>Имя <input type="text" id="name" name="name"></p>

        <p>Почта <input type="text" id="email" name="email"></p>

        <p>Номер телефона <input type="number" id="telephone" name="telephone"></p>

        <p>Пароль <input type="password" id="password_f" name="password_f"></p>

        <p>Пароль еще раз <input type="password" id="password_s" name="password_s"></p>

        <div class="RegLog">
            <input type="submit" value="Отправить">
        </div>

    </form>

</div>