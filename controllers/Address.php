<?php
    class Address extends ApiController{

        private $userRepository;

        public function __construct() {
            parent::__construct();
            $this->userRepository = new Example\Models\UserRepository(new Example\Models\FileStorage(ROOT_PARENT_DIRECTORY . "example.csv"));
        }

        public function get($id) {
            if ($this->method === "GET") {
                try {
                    $this->response($this->userRepository->getUserById($id));
                } catch (Exception $e) {
                    $this->response($e->getMessage(), "500");
                }
            } else {
                $this->response("Method not applicable.");
            }
        }

        public function delete($id) {
            $id = intval($id);
            if ($this->method === "DELETE") {
                try {
                    $this->response($this->userRepository->deleteUserById($id));
                } catch (Exception $e) {
                    $this->response($e->getMessage(), "500");
                }
            } else {
                $this->response("Method not applicable.");
            }
        }
        
        public function update($id, $data) {
            if ($this->method === "PUT") {
                try {
                    $this->response($this->userRepository->updateUserById($id, $data));
                } catch (Exception $e) {
                    $this->response($e->getMessage(), "500");
                }
            } else {
                $this->response("Method not applicable.");
            }
        }
                
    }
?>