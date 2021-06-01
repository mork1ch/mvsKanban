<?php
class Controller_Kanban extends Controller
{
    public function __construct()
    {
        $this->model = new Model_User();
        $this->view = new View();
    }
    function action_desks(){
        $this->view->generate('desks_view.php', 'template_view.php');
        $this->model->desks_kanban();
    }

    function action_newdesk(){
        $this->view->generate('newdesk_view.php', 'template_view.php');
    }

    function action_createDesk(){

        $success = $this->model->newdesk_kanban();

        if ($success == "ok") {
            header("Location: /kanban/desks");
        }
        else {
            $this->view->generate('newdesk_view.php', 'template_view.php');
        }
    }

    function action_deldesk(){
        $this->view->generate('deldesk_view.php', 'template_view.php');
    }

    function action_deliteDesk(){

        $success = $this->model->deldesk_kanban();
        if ($success == "ok") {
            header("Location: /kanban/desks");
        }
        else {
            $this->view->generate('deldesk_view.php', 'template_view.php');
        }
    }

    function action_delete_this_desk(){
        $success = $this->model->delete_this_desk_kanban();

        if ($success == "ok") {
            header("Location: /kanban/desks");
        }
        else {
            $this->view->generate('deldesk_view.php', 'template_view.php');
        }
    }

    function action_desks_info(){
        $this->view->generate('desks_info.php', 'template_view.php');
        $this->model->desks_info_kanban();
    }

    function action_Create_new_tiket(){
        $this->view->generate('Create_new_tiket.php', 'template_view.php');
    }

    function action_Create_new_tiket_do(){
        $deskid = $this->model->Create_new_tiket();
        if (isset($deskid)) {
            header("Location: /kanban/desks_info/?id=$deskid");
        }
        else {
            $this->view->generate('Create_new_tiket.php', 'template_view.php');
        }
    }
}
?>