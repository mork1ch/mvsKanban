<?php 
    $id = $_GET['id'];
    $deskid = $_GET['deskid'];
?>
<div class="back">
    <?php 
        echo "<a href=\"/kanban/desks_info/?id=".$deskid."\">Назад</a>"; 
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
    <h2>Новый тикет</h2>
    <?php 
        echo "<form action=\"/kanban/Create_new_tiket_do/?deskid=".$deskid."&id=". $id ."\" method=\"post\">" 
    ?>

        <p>Название<input type="text" id="title" name="title"></p> 
        <!-- <p>Комментарий<input type="text" id="comment" name="comment"></p> 
        <p>Файлы<button>выбрать</button></p>  -->

        <div class="RegLog">
            <input type="submit" value="Отправить">
        </div>

    </form>