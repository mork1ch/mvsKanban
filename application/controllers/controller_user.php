<?php
class Controller_User extends Controller
{
    function __construct()
    {
        $this->model = new Model_User();
        $this->view = new View();
    }

	function action_login()
    {
			$this->view->generate('login_view.php', 'template_view.php');
    }

	function action_reg()
    {
			$this->view->generate('reg_view.php', 'template_view.php');
    }

	function action_run()
    {
        $success = $this->model->run_user();

        if ($success == "ok") {
            $this->view->generate('welcome_view.php', 'template_view.php');
        }
        else {
            $this->view->generate('login_view.php', 'template_view.php');
        }
    }

	function action_logout()
    {
        $this->model->logout_user();
        $this->view->generate('main_view.php', 'template_view.php');
    }

    function action_registration(){
        $success = $this->model->registration_user();

        if ($success == "ok") {
            $this->view->generate('login_view.php', 'template_view.php');
        }else 
            $this->view->generate('reg_view.php', 'template_view.php');
        }

}
?>