<?php

namespace App\Http\Controllers;
use Config\Database;
use PDO;

class UserController
{

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function show($id = null)
    {

        try {
            $pdo = $this->pdo;
            $query = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $query->bindParam(':id', $id, PDO::PARAM_INT);
            $query->execute();
            $user = $query->fetch(PDO::FETCH_OBJ);


            if ($user === false) {
                 http_response_code(404);
                 $this->view('errors/404.php');
                 return;
            }

             $this->view('user/show.php', [
                'user' => $user
            ]);
            return;
        }catch (\Exception $e){
             http_response_code(500);
             $this->view('errors/500.php');
            return;
        }
    }

    public function showMultiple($id, $language_id)
    {
        try {
            $pdo = $this->pdo;
            $query_user = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $query_user->bindParam(':id', $id, PDO::PARAM_INT);
            $query_user->execute();

            $query_language = $pdo->prepare("SELECT * FROM languages WHERE id = :id");
            $query_language->bindParam(':id', $language_id, PDO::PARAM_INT);
            $query_language->execute();


            $user = $query_user->fetch(PDO::FETCH_OBJ);
            $language = $query_language->fetch(PDO::FETCH_OBJ);

            if ($user === false || $language === false) {
                http_response_code(404);
                $this->view('errors/404.php');
                return;
            }


            $this->view('user/show.php', [
                'user' => $user,
                'language' => $language,
            ]);
            return;
        }catch (\Exception $e){
            http_response_code(500);
            $this->view('errors/500.php');
            return;
        }
    }



    // Templete engine
    protected function view($template, $data = [])
    {
        extract($data);
        $templatePath = __DIR__ . '/../../../resources/views/' . $template;
        if (!file_exists($templatePath)) {
            throw new \Exception("View not found: $templatePath");
        }
        require $templatePath;
    }

}