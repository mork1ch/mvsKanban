<div class="back">
    <a href="/kanban/desks">Назад</a>
</div>
<style>
        
.content{
    min-width: 800px;
}

.content .vert{
    width: 300px;
    display: inline-block;
}
.content h3{
    margin-bottom: 25px;
    font-size: 2em;
}
.info {
    background-color: #3EB489;
    padding: 10px 0;
    border-radius: 5px;
}
.Create_tik{
    width: 200px;
    margin: 5px auto;
    display: block;
    background-color: #fff;
    border-radius: 5px;
    padding: 5px 0;
    border: none;
    font-size: 1.3em;
}
.tiket{
    background-color: #fff;
    border-radius: 5px;
    padding: 5px 0;
    margin: 10px 8px;
}

.del{
    float: right;
    margin-top: 5px;
    width: 15px;
    height: 15px;
    background-image: url(../images/krest.png);
    background-size: 100%;    
    background-position: center;
    background-repeat: no-repeat;
    display: inline-block;
}
.content .tikets .left{
    background-image: url(../images/left.png);
    background-position: center;
    width: 10px;
    height: 10px;
    background-repeat: no-repeat;
    background-size: 100%;
    margin-left: 8px;
    margin-top: 7px;
}
.content .tikets p{
    display: inline;
}
.content .tikets .right{
    background-image: url(../images/right.png);
    background-position: center;
    width: 10px;
    height: 10px;
    background-repeat: no-repeat;
    background-size: 100%;
    margin-right: 8px;
    margin-top: 7px;
}

.content .New{
    display: block;
    width: 300px;
    background-color: #3EB489;
    border-radius: 5px;
    margin: 50px auto 0;
}

.New a{
    width: 300px;
    margin: 0 auto;
    color: #fff;
    font-size: 1.4em;
}
    </style>

<!-- <div class="content">
        <div class="vert ToDo">
            <h3>To Do</h3>
            <div class="info">
            
    <form action="/kanban/Create_new_tiket" method="post">
                <button class="Create_tik" >Добавить тикет</button>
    </form>

                <div class="tikets">
                    <div class="tiket">
                        <a href=""><span class="left"></span></a>
                            <p>To do smth</p>
                        <a href=""><span class="right"></span></a>
                        <div class="del"></div>
                    </div>

                </div>

            </div>
        </div>
        
        <div class="vert InProgress">
            <h3>InProgress</h3>
            <div class="info">

            <form action="/kanban/desks" method="post">
                <button class="Create_tik" >Добавить тикет</button>
            </form>

                <div class="tikets">
                    <div class="tiket">
                        <a href=""><span class="left"></span></a>
                            <p>To do smth</p>
                        <a href=""><span class="right"></span></a>
                        <div class="del"></div>
                    </div>
                    
                </div>

            </div>
        </div>
        <div class="vert Done">
            <h3>Done</h3>
            <div class="info">

            <form action="/kanban/desks" method="post">
                <button class="Create_tik" >Добавить тикет</button>
            </form>

                <div class="tikets">
                    <div class="tiket">
                        <a href=""><span class="left"></span></a>
                            <p>To do smth</p>
                        <a href=""><span class="right"></span></a>
                        <div class="del"></div>
                    </div>
                    
                </div>

            </div>
        </div>
        <div class="New vert"><a href="">+</a></div> -->