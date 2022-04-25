<?php
require_once(ROOT_PATH .'Models/Contact.php');

class ContactController {
    private $request;   // リクエストパラメータ(GET,POST)
    private $Contact;    // Contactモデル

    public function __construct() {
        // リクエストパラメータの取得
        $this->request['get'] = $_GET;
        $this->request['post'] = $_POST;

        // モデルオブジェクトの生成
        $this->Contact = new Contact();
        // 別モデルと連携
        $dbh = $this->Contact->get_db_handler();
    }

    public function index() {
      $contacts = $this->Contact -> findAll();
      return $contacts;
    }

    public function create() {
      $contact = $this->Contact -> insert();
      return $contact;
    }

    public function show() {
      $contact = $this->Contact -> getData();
      return $contact;
    }

    public function update() {
      $contact = $this->Contact -> update();
      return $contact;
    }

    public function destroy() {
      $contact = $this->Contact -> delete();
      return $contact;
    }

}
