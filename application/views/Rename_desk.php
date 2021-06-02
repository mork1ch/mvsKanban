<?php 
    $id = $_GET['id'];
?>
<div class="back">
    <?php 
        echo "<a href=\"/kanban/desks\">Назад</a>"; 
    ?>
</div>
<style>
    .content p{
        width: 500px;
        margin: 0 auto;
        text-align: left;
    }
    input{
        float: right;
    }
</style>
<div class="content">
    <h2>Изменение названия</h2>
    <?php 
        echo "<form action=\"/kanban/rename_desk_do/?id=". $id ."\" method=\"post\">" 
    ?>

        <p>Название<input type="text" id="title" name="title"></p> 
        <!-- <p>Комментарий<input type="text" id="comment" name="comment"></p> 
        <p>Файлы<button>выбрать</button></p>  -->

        <div class="RegLog">
            <input type="submit" value="Изменить">
        </div>

    </form>